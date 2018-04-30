<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-04-30
 * Time: 11:35 AM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


class GeonamesParser extends DataParser
{

  public function parseData()
  {
    $xml_doc = new \DOMDocument();
    $xml_doc->loadXML($this->data);
    parent::getXmlNodeValue($xml_doc, 'toponymName');
    parent::getXmlNodeValue($xml_doc, 'name');
    parent::getXmlNodeValue($xml_doc, 'lat');
    parent::getXmlNodeValue($xml_doc, 'lng');
    parent::getXmlNodeValue($xml_doc, 'geonameId');
    parent::getXmlNodeValue($xml_doc, 'countryCode');
    parent::getXmlNodeValue($xml_doc, 'countryName');
    parent::getXmlNodeValue($xml_doc, 'fcodeName');
    parent::getXmlNodeValue($xml_doc, 'toponymName');
    parent::getXmlNodeValue($xml_doc, 'population');
    parent::getXmlNodeValue($xml_doc, 'asciiName');
    parent::getXmlNodeValue($xml_doc, 'elevation');
    parent::getXmlNodeValue($xml_doc, 'continentCode');
    parent::getXmlNodeValue($xml_doc, 'adminName1');
    parent::getXmlNodeValue($xml_doc, 'timezone');
    parent::getXmlNodeValue($xml_doc, 'west');
    parent::getXmlNodeValue($xml_doc, 'north');
    parent::getXmlNodeValue($xml_doc, 'east');
    parent::getXmlNodeValue($xml_doc, 'south');
  }
}
