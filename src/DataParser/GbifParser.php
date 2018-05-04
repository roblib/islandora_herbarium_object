<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-05-04
 * Time: 1:19 PM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


class GbifParser extends DataParser
{
  public function parseData() {
    $arr  = json_decode($this->data, TRUE);
    if(empty($arr['results'])) {
      throw new \Exception('Error parsing json data.');
    }
    $this->data_arr = reset($arr['results']);
  }

  public function updateEntity($entity) {
    try {
      $taxoniri = $entity->get("field_dwciri_scientificname")->getValue();
      if(empty($taxoniri[0]['value'])) {
        throw new Exception("No URI provided, cannot look up taxon data.");
      }

      $this->getRemoteData($taxoniri[0]['value']);
      $this->parseData();
    } catch (Exception $e) {
      watchdog_exception('islandora_herbarium_object', $e, 'Error getting dwciri');
      drupal_set_message(t('There was an error retrieving Taxon data. %msg. Verify the url is correct %url',
        array('%msg'=> $e->getMessage(), '%url' => $taxoniri[0]['value'])),'error');
      return;
    }
    $entity->set('field_dwc_scientificname',  $this->getValue('canonicalName'));
    $name_arr = explode(' ', $this->getValue('canonicalName'));
    $entity->set('field_dwc_specificepithet',  $name_arr[1]);
    $entity->set('field_dwc_kingdom', $this->getValue('kingdom'));
    $entity->set('field_dwc_genus',  $this->getValue('genus'));
    $entity->set('field_dwc_phylum',  $this->getValue('phylum'));
    $entity->set('field_dwc_order',  $this->getValue('order'));
    $entity->set('field_dwc_family',  $this->getValue('family'));
    $entity->set('field_dwc_vernacularname',  $this->getValue('vernacularName'));
  }

}
