<?php

namespace Drupal\jcc_autocomplete_duplicates\Controller;

use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\Tags;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\Core\Site\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\jcc_autocomplete_duplicates\JccEntityAutocompleteMatcher;



class JccEntityAutocompleteController extends \Drupal\system\Controller\EntityAutocompleteController {

  /**
   * The autocomplete matcher for entity references.
   */
  protected $matcher;

  /**
   * The key value store.
   *
   * @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface
   */
  protected $keyValue;

  /**
   * {@inheritdoc}
   */
  public function __construct(JccEntityAutocompleteMatcher $matcher, KeyValueStoreInterface $key_value) {
    $this->matcher = $matcher;
    $this->keyValue = $key_value;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('jcc_autocomplete_duplicates.autocomplete_matcher'),
      $container->get('keyvalue')->get('entity_autocomplete')
    );
  }

  /**
   * Autocomplete the label of an entity.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object that contains the typed tags.
   * @param string $target_type
   *   The ID of the target entity type.
   * @param string $selection_handler
   *   The plugin ID of the entity reference selection handler.
   * @param string $selection_settings_key
   *   The hashed key of the key/value entry that holds the selection handler
   *   settings.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The matched entity labels as a JSON response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Thrown if the selection settings key is not found in the key/value store
   *   or if it does not match the stored data.
   */
  public function handleAutocomplete(Request $request, $target_type, $selection_handler, $selection_settings_key) {
    $matches = array();

    // Get the typed string from the URL, if it exists.
    if ($input = $request->query
      ->get('q')) {
      $typed_string = Tags::explode($input);
      $typed_string = mb_strtolower(array_pop($typed_string));

      // Selection settings are passed in as a hashed key of a serialized array
      // stored in the key/value store.
      $selection_settings = $this->keyValue
        ->get($selection_settings_key, FALSE);
      if ($selection_settings !== FALSE) {
        $selection_settings_hash = Crypt::hmacBase64(serialize($selection_settings) . $target_type . $selection_handler, Settings::getHashSalt());
        if ($selection_settings_hash !== $selection_settings_key) {

          // Disallow access when the selection settings hash does not match the
          // passed-in key.
          throw new AccessDeniedHttpException('Invalid selection settings key.');
        }
      }
      else {

        // Disallow access when the selection settings key is not found in the
        // key/value store.
        throw new AccessDeniedHttpException();
      }
      $matches = $this->matcher
        ->getMatches($target_type, $selection_handler, $selection_settings, $typed_string);
    }

    // Matcing autocomplete_deluxe structure
    $items = [];
    foreach ($matches as $item) {
      $items[$item['value']] = $item['label'];
    }

    $matches = $items;

    return new JsonResponse($matches);
  }
}
