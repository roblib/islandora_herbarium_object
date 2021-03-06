<?php

namespace Drupal\islandora_herbarium_object\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;


/**
 * Plugin implementation of the 'FDG Graph_Field_Formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "fdg__graph_text_field_formatter",
 *   module = "islandora_herbarium_object",
 *   label = @Translation("FDG Graph Field Formatter"),
 *   description = @Translation("Create links to a FDG Graph"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "string_long",
 *     "string"
 *   }
 * )
 */
class FdgGraphTextFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Format string or text fields as links to a FDG Graph.  '
      . 'Only useful for Scientific Name or Municipalty fields');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $field = $item->getFieldDefinition();
      // TODO: more robust fieldname parsing
      $fieldLabel = str_replace(' ', '_', $field->getLabel());
      $municipality = ($fieldLabel == 'Municipality') ? $item->value : 'NULL';
      $scientificName = ($fieldLabel == 'Scientific_Name') ? $item->value : 'NULL';
      $link_fdg_source = Link::fromTextAndUrl("[Force-Directed Graph with Links to External Source Data]", Url::fromroute('islandora_herbarium_object.graph',
        ['scientificName' => $scientificName, 'municipality' => $municipality, 'uri_endpoint' => 'source']));
      $link_fdg_solr = Link::fromTextAndUrl("[Force-Directed Graph with Links to Internal Search]", Url::fromroute('islandora_herbarium_object.graph',
        ['scientificName' => $scientificName, 'municipality' => $municipality, 'uri_endpoint' => 'solr']));
      $link = Link::fromTextAndUrl($item->value, Url::fromroute('view.herbarium_search.page_1',
        [], ['query' => ['search_api_fulltext' => '','herbarium-search[0]' => $fieldLabel . ':' . $item->value]]));
      $element[$delta] = [$link->toRenderable(), $link_fdg_solr->toRenderable(),
        $link_fdg_source->toRenderable()];
    }

    return $element;
  }
}
