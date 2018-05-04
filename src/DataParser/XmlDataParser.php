<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-05-04
 * Time: 1:16 PM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


abstract class XmlDataParser extends DataParser
{
  public function getXmlNodeValue($xml_doc, $element_name)
  {
    $this->data_arr[$element_name] = $xml_doc->getElementsByTagName($element_name)[0]->nodeValue;
  }
}
