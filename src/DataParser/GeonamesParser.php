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
}
