<?php

namespace Drupal\islandora_herbarium_object\DataParser;

use Drupal\Core\Entity\EntityInterface;

/**
 * A data structure for GBif data.
 */
class GbifParser extends DataParser {

  public $gbifUrlPrefix = 'http://api.gbif.org/v1/species?name=';

  /**
   * {@inheritdoc}
   */
  public function parseData() {
    $arr = json_decode($this->data, TRUE);
    if (empty($arr['results'])) {
      throw new \Exception('Error parsing json data.');
    }
    $this->dataArray = reset($arr['results']);
  }

  /**
   * {@inheritdoc}
   */
  public function updateEntity(EntityInterface $entity) {
    try {
      $taxoniri = $entity->get("field_dwciri_scientificname")->getValue();
      $url = '';
      if (empty($url = $taxoniri[0]['value']) && !empty($entity->get('field_dwc_scientificname')
        ->getValue())) {
        $value = [
          $this->getGbifUrl($entity->get('field_dwc_scientificname')
            ->getValue()[0]['value']),
        ];
        $taxoniri[] = ['value' => $value[0]];
        $url = $taxoniri[0]['value'];
        if (empty($url)) {
          throw new \Exception("No ScientificName URI or ScientificName text provided, cannot look up taxon data.");
        }
        $entity->set('field_dwciri_scientificname', $url);
      }
      $this->getRemoteData($url);
      $this->parseData();
    }
    catch (Exception $e) {
      watchdog_exception('islandora_herbarium_object', $e, 'Error getting dwciri');
      throw ($e);
    }
    $entity->set('field_dwc_scientificname', $this->getValue('canonicalName'));
    $name_arr = explode(' ', $this->getValue('canonicalName'));
    $entity->set('field_dwc_specificepithet', $name_arr[1]);
    $entity->set('field_dwc_kingdom', $this->getValue('kingdom'));
    $entity->set('field_dwc_genus', $this->getValue('genus'));
    $entity->set('field_dwc_phylum', $this->getValue('phylum'));
    $entity->set('field_dwc_order', $this->getValue('order'));
    $entity->set('field_dwc_family', $this->getValue('family'));
    $entity->set('field_dwc_class', $this->getValue('class'));
  }

  /**
   * Return a string representation of a URL.
   *
   * @param string $scientificName
   *   The scientificName of a herbarium Specimen.
   *
   * @return string
   *   A string based on the gbifUrlPrefix and the scientificName.
   */
  public function getGbifUrl($scientificName) {
    return $this->gbifUrlPrefix . $scientificName;
  }

}
