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

  public function getRemoteData($uri) {
    $client = \Drupal::httpClient();
    $request = $client->get(trim($uri));
    $data = $request->getBody()->getContents();
    if(empty($data)) {
      throw new Exception("Empty dataset");
    }
    $this->data = $data;
  }

  public function getValue($key) {
    return isset($this->data_arr[$key]) ? $this->data_arr[$key] : "";
  }

}
