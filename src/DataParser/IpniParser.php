<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-04-30
 * Time: 11:23 AM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


class IpniParser extends DataParser
{
  public function parseData() {
    $xml_doc = new \DOMDocument();
    $xml_doc->loadXML($this->data);
    parent::getXmlNodeValue($xml_doc, 'nameComplete');
    parent::getXmlNodeValue($xml_doc, 'genusPart');
    parent::getXmlNodeValue($xml_doc, 'specificEpithet');
  }
}
