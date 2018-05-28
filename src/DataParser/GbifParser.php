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
  public $gbifUrlPrefix = 'http://api.gbif.org/v1/species?name=';
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
      $url = '';
      if(empty($url = $taxoniri[0]['value']) && !empty($entity->get('field_dwc_scientificname')->getValue())) {
        $value = array($this->getGbifUrl($entity->get('field_dwc_scientificname')->getValue()[0]['value']));
        $taxoniri[] = ['value' => $value[0]];
        $url = $taxoniri[0]['value'];
        if(empty($url)) {
          throw new \Exception("No ScientificName URI or ScientificName text provided, cannot look up taxon data.");
        }
        $entity->set('field_dwciri_scientificname', $url);
      }
      $this->getRemoteData($url);
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
    $entity->set('field_dwc_class', $this->getValue('class'));
    $entity->set('field_dwc_vernacularname',  $this->getValue('vernacularName'));
  }

  public function getGbifUrl($scientificName){
    return $this->gbifUrlPrefix . $scientificName;
  }

}
