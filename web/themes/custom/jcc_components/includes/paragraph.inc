<?php

/**
 * @file
 * Preprocess and functions for media.
 */

use Drupal\block\Entity\Block;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\views\ViewEntityInterface;

/**
 * Implements hook_preprocess_HOOK().
 */
function jcc_components_preprocess_paragraph(&$variables) {
  $paragraph = $variables['paragraph'];
  $function = "jcc_components_paragraph_" . $paragraph->bundle();
  if (function_exists($function)) {
    $function($variables, $paragraph);
  }
}

/**
 * Implements hook_theme_suggestions_alter().
 */
function jcc_components_theme_suggestions_alter(&$suggestions, $variables, $hook) {
  if ($hook == 'paragraph') {
    $paragraph = $variables['elements']['#paragraph'];
    if ($paragraph->bundle() == 'views_reference') {
      if (!empty($paragraph->field_views_reference)) {
        $target_id = $paragraph->field_views_reference->first()->target_id;
        $display_id = $paragraph->field_views_reference->first()->display_id;
        $suggestion = 'paragraph__' . $paragraph->bundle();
        $suggestion = !empty($target_id) ? $suggestion . "__$target_id" : $suggestion;
        $suggestion = !empty($display_id) ? $suggestion . "__$display_id" : $suggestion;
        $suggestions[] = $suggestion;
      }
    }
  }
}

/**
 * Helper function for cards paragraphs.
 *
 * @param array $variables
 *   The paragraph variables.
 * @param \Drupal\paragraphs\ParagraphInterface $paragraph
 *   The paragraph.
 */
function jcc_components_paragraph_cards(array &$variables, ParagraphInterface $paragraph) {
  $sub_variants = !empty($paragraph->field_sub_variant) ? json_decode($paragraph->field_sub_variant->first()->value, TRUE) : ['card' => 'cta'];
  // Prep the cards due to special handling of linkit links.
  $cards = $paragraph->field_components->referencedEntities();
  foreach ($cards as $card) {
    $title = !empty($card->field_heading) ? $card->field_heading->first()->value : '';
    $link = $card->field_link->view('default');
    $variables['cards'][] = [
      'style' => $sub_variants['card'],
      'title' => $title,
      'brow' => '',
      'body' => $card->field_text->view('default'),
      'link' => [
        'url' => !empty($link[0]) ? $link[0]['#url']->toString() : NULL,
        'text' => !empty($link[0]) ? $link[0]['#title'] : NULL,
      ],
      'classes' => [],
    ];
  }
}

/**
 * Helper function for hero_banner paragraphs.
 *
 * @param array $variables
 *   The paragraph variables.
 * @param \Drupal\paragraphs\ParagraphInterface $paragraph
 *   The paragraph.
 */
function jcc_components_paragraph_hero_banner(array &$variables, ParagraphInterface $paragraph) {
  // Set up values for icon_links to pass to the pattern library template.
  $components = $paragraph->field_components->referencedEntities();
  foreach ($components as $component) {
    if (!empty($component->field_icon)) {
      $variables['content']['icon_links'][] = [
        'icon' => !empty($component->field_icon->view('default')[0]) ? $component->field_icon->view('default')[0] : NULL,
        'link' => !empty($component->field_link->view('default')[0]) ? $component->field_link->view('default')[0] : NULL,
      ];
    }
  }
}

/**
 * Helper function for views_reference paragraphs.
 *
 * @param array $variables
 *   The paragraph variables.
 * @param \Drupal\paragraphs\ParagraphInterface $paragraph
 *   The paragraph.
 */
function jcc_components_paragraph_views_reference(array &$variables, ParagraphInterface $paragraph) {
  // In some cases we need to pass the views results to the paragraph.
  if (!empty($paragraph->field_views_reference)) {
    $view = $paragraph->field_views_reference->referencedEntities()[0];
    $reference = $paragraph->field_views_reference->first();
    $target_id = $reference->target_id;
    $display_id = $reference->display_id;
    $function = "jcc_components_paragraph_views_reference_${target_id}_${display_id}";
  }

  if (!empty($function) && function_exists($function)) {
    $function($variables, $view);
  }
}

/**
 * Helper function for views_reference paragrpahs of type news.
 *
 * @param array $variables
 *   The paragraph variables.
 * @param Drupal\views\ViewEntityInterface $view
 *   The news view.
 */
function jcc_components_paragraph_views_reference_news_sticky_list(array &$variables, ViewEntityInterface $view) {
  $executable = $view ? $view->getExecutable() : NULL;
  // Start with the sticky_list display.
  $sticky_list = $executable ? $executable->setDisplay('sticky_list') : FALSE;
  $display = $executable->executeDisplay();

  if ($sticky_list) {
    $variables['sticky_list'] = !empty($display['#rows']) ? $display['#rows'][0]['#rows'] : [];
  }
  // Reset the executable for the sticky_list_attachment.
  $executable = $view ? $view->getExecutable() : NULL;
  $sticky_list_attachment = $executable->setDisplay('sticky_list_attachment');
  $display = $executable->executeDisplay();
  // Also get the More News path. Should be a node with path /news but fallback
  // to view url if it exists.
  $view_url = !empty($executable->getUrl()) ? $executable->getUrl()->toString() : '';
  $path = \Drupal::service('path.validator')->isValid('/news') ? '/news' : $view_url;

  if ($sticky_list_attachment) {
    $variables['sticky_list_attachment'] = !empty($display['#rows']) ? $display['#rows'][0]['#rows'] : [];
    $variables['news_url'] = $path;
  }

}

/**
 * Helper function for views_reference paragrpahs of type location.
 *
 * @param array $variables
 *   The paragraph variables.
 * @param Drupal\views\ViewEntityInterface $view
 *   The news view.
 */
function jcc_components_paragraph_views_reference_location_map_list(array &$variables, ViewEntityInterface $view) {
  $executable = $view ? $view->getExecutable() : NULL;

  // Start with the map_list display.
  $map_list = $executable ? $executable->setDisplay('map_list') : FALSE;
  $display = $executable->executeDisplay();

  if ($map_list) {
    $variables['map_list'] = !empty($display['#rows']) ? $display['#rows'] : [];
  }
  // Reset the executable for the map_list_attachment.
  $executable = $view ? $view->getExecutable() : NULL;
  $map_list_attachment = $executable->setDisplay('map_list_attachment');
  $display = $executable->executeDisplay();

  if ($map_list_attachment && !empty($display['#rows'])) {
    foreach ($display['#rows'][0]['#rows'] as $row) {
      $variables['map_list_attachment'][] = [
        'title' => $row['#node']->getTitle(),
        'address' => $row['#node']->field_address->view('default'),
      ];
    }
  }
}

/**
 * Helper function for views_reference paragraphs of type forms.
 *
 * @param array $variables
 *   The paragraph variables.
 * @param Drupal\views\ViewEntityInterface $view
 *   The news view.
 */
function jcc_components_paragraph_views_reference_forms_search_forms(array &$variables, ViewEntityInterface $view) {
  // We need to inject the exposed filters form so it can be added to template.
  // We it uses AJAX and autosubmit, and a text field so we must use block to
  // prevent redirecting to the view page on submit and losing focus on the
  // text field when it submits as you're still typing. Just loading the form
  // from the view doesn't prevent this. the block is configured but disabled
  // otherwise we don't have access to it by the bid.
  $bid = 'exposedformformssearch_forms';
  $block = Block::load($bid);
  $render = \Drupal::entityTypeManager()
    ->getViewBuilder('block')
    ->view($block);
  $variables['exposed_form'] = $render;
}