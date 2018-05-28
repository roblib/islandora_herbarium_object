<?php

namespace Drupal\islandora_herbarium_object\DataParser;

/**
 * Provides storage and functions for IPNI data.
 */
abstract class IpniParser extends XmlDataParser {

  /**
   * {@inheritdoc}
   */
  public function parseData() {
    $xml_doc = new \DOMDocument();
    $xml_doc->loadXML($this->data);
    $this->getXmlNodeValue($xml_doc, 'nameComplete');
    $this->getXmlNodeValue($xml_doc, 'genusPart');
    $this->getXmlNodeValue($xml_doc, 'specificEpithet');
  }

}
