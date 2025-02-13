/**
 * @file
 * JCC Case lookup integrations & behaviors.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Behavior description.
   */
  Drupal.behaviors.jccCaseLookup = {
    attach: function (context) {
      $(document).ready(function() {
        // BEGIN Load hidden ACCMS lookup form onto page if global search field exists.
        if ($('.usa-search input').length && $('#searchFormNumber').length == 0) {
          const searchForm = '<form class="sr-only" aria-hidden="true" name="searchFormNumber" id="searchFormNumber" method="post" action="https://appellatecases.courtinfo.ca.gov/">' +
            '<input type="text" name="query_caseNumber" id="query_caseNumber" />' +
            '<input type="checkbox" name="bot_check_1" id="bot_check_1" value="Y" checked>' +
            '<input type="checkbox" name="bot_check_6" id="bot_check_6" value="Y">' +
            '</form>';

          $('body').append(searchForm);
        }
        // END.

        // BEGIN Global search field integration.
        const modal = '#case-search-field-modal';

        $('.usa-search input').on('keyup', function() {
          let typedQuery = $('input[type="search"]').val();
          let detectedCases = detectCases(typedQuery);

          // Display case search option only if case number entered.
          if (detectedCases) {
            if ($(modal).length == 0) {
              // Case search contextual modal, screen readers will rely on results page.
              let caseSearch = '<div id="case-search-field-modal" class="usa-prose case-search-field-modal" aria-hidden="true"></div>';
              $('.jcc-header__search').append(caseSearch);

              // Modal form submission event handler.
              $(document).on('click', '.lookup-case--modal', function () {
                caseQuery(this);
              });
            }
            // Insert/Update modal content.
            $(modal).html(caseSearchTriggers(detectedCases, 'modal'));
          } else {
            $(modal).remove();
          }
        });

        // If click or touch outside of modal.
        $(document).click(function(event) {
          if(!$(event.target).closest(modal).length &&
            !$(event.target).closest('input[type="search"]').length &&
            $(modal).is(':visible')) {
            $(modal).remove();
          }
        });
        // END.

        // BEGIN Search results page integration.
        if ($('.search').length > 0 && $('.jcc-case-search').length == 0) {
          const queryString = window.location.search;
          const urlParams = new URLSearchParams(queryString);
          const submittedQuery = urlParams.get('search');
          const searchString = detectCases(submittedQuery);
          const caseSearchBlock = '<div class="search__message--item jcc-case-search">' +
            '<p>' +
            'Looking for opinions or case information from previous terms? We recommend starting with the <a href="https://appellatecases.courtinfo.ca.gov">Appellate Courts docket search</a>, ' +
            'where you can search by case number, case name, or names of the parties associated with the case.' +
            '</p>' +
            '</div>';

          $('.search__form').append(caseSearchBlock);

          if (searchString) {
            const searchResultsIntegration = '<p>' +
                caseSearchTriggers(searchString, 'page') +
              '</p>';
            $('.jcc-case-search').prepend(searchResultsIntegration);

            // Results page form submission event handler.
            $(document).on('click', '.lookup-case--page', function () {
              caseQuery(this);
            });
          }
        }
        // END.

        // BEGIN Custom block view form submit handler.
        if ($('#query-case-number').length > 0) {
          const inputField = '#query-case-number__input';
          const ariaSubmitMessages = 'aria-describedby';
          const caseListing = '#case-search__listing';

          // Submit button handler.
          $('#query-case-number__submit').on('click keypress', function (e) {
            blockViewSubmit(e);
          });

          // Submit from input field using ENTER key.
          $('#query-case-number__input').on('keypress', function (e) {
            if (e.keyCode == 13) {
              blockViewSubmit(e);
            }
          });

          // Submit function.
          function blockViewSubmit(event) {
            let typedQuery = $('.query-case-number__input').val();
            let submittedCases = detectCases(typedQuery);

            // Display helper only if multiple case numbers else submit lookup.
            if (submittedCases != null) {
              $('#query-case-number .usa-alert__wrapper--error').hide();
              $(inputField).removeAttr(ariaSubmitMessages)

              if (submittedCases.length == 1) {
                $(caseListing).hide();
                // Display helper when multiple case numbers.
                let casePrefix = submittedCases[0].charAt(0);
                let district = getDistrictCode(casePrefix);
                $('#searchFormNumber').attr('action', 'https://appellatecases.courtinfo.ca.gov/search/searchResults.cfm?dist=' + district + '&search=number');
                $('#query_caseNumber').val(submittedCases);
                $('form[name="searchFormNumber"]').submit();
              } else {
                $(caseListing).show();
                // Insert/Update listing.
                let listing = caseSearchTriggers(submittedCases, 'list');
                $('.query-case-number__results').html(listing);
                // If submitted by ENTER key, set focus on listing block.
                if (event.keyCode == 13) {
                  $(caseListing).focus();
                }
                // Listing case search form submission event handler.
                $(document).on('click', '.lookup-case--list', function () {
                  caseQuery(this);
                });
              }
            } else {
              // Exception handling.
              $(caseListing).hide();
              $('#query-case-number .usa-alert__wrapper--error').show();
              $('#query-case-number .usa-alert__heading').html('No valid case number submitted.');
              $(inputField).attr(ariaSubmitMessages, 'query-case-number__error');
              $(inputField).focus();
            }
          }
        }
        // END.

        // Detect and extract valid case numbers from query string (e.g. S170280).
        function detectCases(query) {
          query = query.match(/[A-Za-z][0-9]{6}/g);

          return query;
        }

        // Case search triggers.
        function caseSearchTriggers(caseNumbers, type) {
          let cases = new Array();
          let triggerBlock;

          jQuery.each(caseNumbers, function(index, item) {
            item = item.toUpperCase();
            let link = '<button class="jcc-button--unstyled lookup-case__trigger lookup-case--' + type + '" data-case="' + item + '" aria-label="Look up case ' + item + ' from the California Courts website in a new browser window.">' + item + '</button>';
            let isLastElement = index == caseNumbers.length -1;
            if (type == 'list') {
              link = '<li class="jcc-list__item">' + link + '</li>'
            } else if (cases.length > 0 && isLastElement) {
              link = 'and ' + link;
            }
            cases.push(link);
          });

          // Format trigger display.
          switch(type) {
            case 'list':
              triggerBlock = cases;
              break;
            default:
              // Delimit format depending on the number of cases.
              if (cases.length <= 2) {
                cases = cases.join(' ');
              } else {
                cases = cases.join(', ');
              }
              triggerBlock = 'Look up ' + cases + ' from the Appellate Courts docket search website?';
          }

          return triggerBlock;
        }

        // Set case number in hidden form and submit redirected query.
        function caseQuery(trigger) {
          let query = $(trigger).attr('data-case');
          if (query) {
            // Set district for case search URL.
            const casePrefix = query[0].charAt(0);
            let district = getDistrictCode(casePrefix);
            $('#searchFormNumber').attr('action', 'https://appellatecases.courtinfo.ca.gov/search/searchResults.cfm?dist=' + district + '&search=number');
            $('#query_caseNumber').val(query);
            $('form[name="searchFormNumber"]').submit();
          }
        }

        // Return district code for hidden form use by the submitted case prefix.
        function getDistrictCode(prefix) {
          let districtCode;
          switch(prefix.toUpperCase()) {
            case 'S':
              districtCode = 0;
              break;
            case 'A':
              districtCode = 1;
              break;
            case 'B':
              districtCode = 2;
              break;
            case 'C':
              districtCode = 3;
              break;
            case 'D':
              districtCode = 41;
              break;
            case 'E':
              districtCode = 42;
              break;
            case 'F':
              districtCode = 43;
              break;
            case 'F':
              districtCode = 5;
              break;
            case 'G':
              districtCode = 6;
              break;
            default:
              districtCode = -1;
          }

          return districtCode;
        }
      });
    }
  }
} (jQuery, Drupal));
