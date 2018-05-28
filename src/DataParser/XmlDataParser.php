<?php

namespace Drupal\islandora_herbarium_object\DataParser;

/**
 * Provides functions for simple XML data.
 */
abstract class XmlDataParser extends DataParser {

  /**
   * Retrieves the value of the first xml node matching element_name.
   *
   * @param \DOMDocument $xml_doc
   *   An xml document.
   * @param string $element_name
   *   The xml node to lookup.
   */
  public function getXmlNodeValue(\DOMDocument $xml_doc, $element_name) {
    $this->dataArray[$element_name] = $xml_doc->getElementsByTagName($element_name)[0]->nodeValue;
  }

}
