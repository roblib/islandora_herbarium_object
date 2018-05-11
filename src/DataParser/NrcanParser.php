<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-05-07
 * Time: 10:44 AM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


class NrcanParser extends DataParser
{
  public $nrcanUrlSearchPrefix = 'http://geogratis.gc.ca/services/geoname/en/geonames?q=';
  public $nrcanUrlPrefix = 'http://geogratis.gc.ca/services/geoname/en/geonames/';

  public function parseData()
  {
    $arr  = json_decode($this->data, TRUE);
    if(empty($arr)) {
      throw new \Exception('Error parsing geo json data.');
    }
    $this->data_arr = $arr;
  }

  public function updateEntity($entity) {
    try {
      $url = '';
      $dwciri = $entity->get("field_dwciri_municipality")->getValue();
      if(empty($url = $dwciri[0]['value']) && !empty($entity->get("field_dwc_municipality")->getValue())) {
        //throw new Exception("No URI provided, cannot look up location data.");
        $value = array($this->getNrcanUrl($entity->get('field_dwc_municipality')->getValue()[0]['value']));
        $dwciri[]  = ['value' => $value[0]];
        $url = $dwciri[0]['value'];
        if(empty($url)) {
          throw new \Exception("No Municipality URI or Municipality text provided, cannot look up taxon data.");
        }
        $entity->set('field_dwciri_municipality', $url);
      }

      $this->getRemoteData($url);
      $this->parseData();
    } catch (Exception $e) {
      watchdog_exception('islandora_herbarium_object', $e, 'Error getting dwciri');
      drupal_set_message(t('There was an error retrieving Municipality data. %msg. Verify the url is correct %url',
        array('%msg'=> $e->getMessage(), '%url' => $dwciri[0]['value'])), 'error');
      return;
    }
    $entity->set('field_dwc_municipality', $this->getValue('name'));
    // This parser assumes we are in canada
    $entity->set('field_dwc_countrycode',  'CA');
    $entity->set('field_dwc_country',  'Canada');
    $entity->set('field_dwc_continent',  'North America');
    $entity->set('field_dwc_decimallatitude',  $this->getValue('latitude'));
    $entity->set('field_dwc_decimallongitude',  $this->getValue('longitude'));
    $provinceUri = $this->getvalue('province')['links']['self']['href'];
    try {
      $provinceData = $this->getRemoteData($provinceUri, TRUE);
      $p_arr = json_decode($provinceData, TRUE);
      $province = $p_arr['description'];
    }
    catch(Exception $e) {
      drupal_set_message(t('There was an error retrieving Province data. %msg.',
        array('%msg'=> $e->getMessage())), 'error');
    }
    $entity->set('field_dwc_stateprovince',  $province);
    $point = array(
      'lon' =>  $this->getValue('longitude'),
      'lat' =>  $this->getValue('latitude'),
    );
    $geo_field_value = \Drupal::service('geofield.wkt_generator')->WktBuildPoint($point);
    $entity->set('field_herb_geo_field', $geo_field_value);
  }

  public function getNrcanUrl($municipality){
    $url = $this->nrcanUrlSearchPrefix . $municipality . "&province=11";
    $data = $this->getRemoteData($url, TRUE);
    $data_arr = json_decode($data, TRUE);
    $key = '';
    foreach($data_arr['items'] as $item) {
      if($item['name'] === $municipality){
        $key = $item['id'];
        break;
      }
    }
    return $this->nrcanUrlPrefix . $key;
  }
}