<?php

namespace Drupal\islandora_herbarium_object\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Plugin implementation of the 'LinktoXMLField:qFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "xml__link_field_formatter",
 *   module = "islandora_herbarium_object",
 *   label = @Translation("Link to XML metadata"),
 *   description = @Translation("Create links to a XML representation"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "string_long",
 *     "string"
 *   }
 * )
 */
class LinktoXMLFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Format string or text fields as links to a XML representatio of the metadata.  '
      . 'links to the herbarium_xml_view');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $field = $item->getFieldDefinition();
      $id = \Drupal::routeMatch()->getRawParameter('node');

      $link = Link::fromTextAndUrl($this->t('@pid XML Metadata view',
            array('@pid' => $item->value)), Url::fromUri('base:herb_xml_view/' . $id));

      $element[$delta] = [$link->toRenderable()];
    }

    return $element;
  }

}
