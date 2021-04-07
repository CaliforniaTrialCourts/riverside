<?php

/**
 * @file
 * Preprocess and functions for media.
 */

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
        'icon' => !empty($component->field_icon->view()[0]) ? $component->field_icon->view()[0] : NULL,
        'link' => !empty($component->field_link->view()[0]) ? $component->field_link->view()[0] : NULL,
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
  $view = !empty($paragraph->field_views_reference) ? $paragraph->field_views_reference->referencedEntities()[0] : NULL;
  $function = 'jcc_components_paragraph_views_reference_' . $view->id();

  if (function_exists($function)) {
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
function jcc_components_paragraph_views_reference_news(array &$variables, ViewEntityInterface $view) {
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
function jcc_components_paragraph_views_reference_location(array &$variables, ViewEntityInterface $view) {
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
        'address' => $row['#node']->field_address->view(),
      ];
    }
  }
}