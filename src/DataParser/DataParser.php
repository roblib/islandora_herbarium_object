<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-04-30
 * Time: 11:25 AM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


abstract class DataParser implements DataParserInterface
{
  public $data;
  public $data_arr = array();

  public function getXmlNodeValue($xml_doc, $element_name)
  {
    $this->data_arr[$element_name] = $xml_doc->getElementsByTagName($element_name)[0]->nodeValue;
  }

  public function getRemoteData($uri) {
    $client = \Drupal::httpClient();
    $request = $client->get(trim($uri));
    $data = $request->getBody()->getContents();
    $this->data = $data;
  }


}
