<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-04-30
 * Time: 11:35 AM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


class GeonamesParser extends XmlDataParser
{

  public function parseData()
  {
    $xml_doc = new \DOMDocument();
    if(empty($xml_doc->loadXML($this->data))) {
      throw new \Exception('Failed loading data as xml');
    }
    $this->getXmlNodeValue($xml_doc, 'toponymName');
    $this->getXmlNodeValue($xml_doc, 'name');
    $this->getXmlNodeValue($xml_doc, 'lat');
    $this->getXmlNodeValue($xml_doc, 'lng');
    $this->getXmlNodeValue($xml_doc, 'geonameId');
    $this->getXmlNodeValue($xml_doc, 'countryCode');
    $this->getXmlNodeValue($xml_doc, 'countryName');
    $this->getXmlNodeValue($xml_doc, 'fcodeName');
    $this->getXmlNodeValue($xml_doc, 'toponymName');
    $this->getXmlNodeValue($xml_doc, 'population');
    $this->getXmlNodeValue($xml_doc, 'asciiName');
    $this->getXmlNodeValue($xml_doc, 'elevation');
    $this->getXmlNodeValue($xml_doc, 'continentCode');
    $this->getXmlNodeValue($xml_doc, 'adminName1');
    $this->getXmlNodeValue($xml_doc, 'timezone');
    $this->getXmlNodeValue($xml_doc, 'west');
    $this->getXmlNodeValue($xml_doc, 'north');
    $this->getXmlNodeValue($xml_doc, 'east');
    $this->getXmlNodeValue($xml_doc, 'south');
  }

  public function updateEntity($entity) {
    try {
      $dwciri = $entity->get("field_dwciri_municipality")->getValue();
      if(empty($dwciri[0]['value'])) {
        throw new Exception("No URI provided, cannot look up location data.");
      }
      $this->getRemoteData($dwciri[0]['value']);
      $this->parseData();
    } catch (Exception $e) {
      watchdog_exception('islandora_herbarium_object', $e, 'Error getting dwciri');
      drupal_set_message(t('There was an error retrieving Municipality data. %msg. Verify the url is correct %url',
        array('%msg'=> $e->getMessage(), '%url' => $dwciri[0]['value'])), 'error');
      return;
    }
    $entity->set('field_dwc_municipality', $this->getValue('name'));
    $entity->set('field_dwc_countrycode',  $this->getValue('countryCode'));
    $entity->set('field_dwc_country',  $this->getValue('countryName'));
    $entity->set('field_dwc_continent',  $this->getValue('countinentCode'));
    $entity->set('field_dwc_decimallatitude',  $this->getValue('lat'));
    $entity->set('field_dwc_decimallongitude',  $this->getValue('lng'));
    $entity->set('field_dwc_stateprovince',  $this->getValue('adminName1'));
    $point = array(
      'lon' =>  $this->getValue('lng'),
      'lat' =>  $this->getValue('lat'),
    );
    $geo_field_value = \Drupal::service('geofield.wkt_generator')->WktBuildPoint($point);
    $entity->set('field_herb_geo_field', $geo_field_value);
  }
}
