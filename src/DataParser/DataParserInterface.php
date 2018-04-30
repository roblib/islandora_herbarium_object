<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-04-30
 * Time: 1:10 PM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


interface DataParserInterface
{

  public function getXmlNodeValue($xml_doc, $element_name);

  public function getRemoteData($uri) ;

  public function parseData();

}
