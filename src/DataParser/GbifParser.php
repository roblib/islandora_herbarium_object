<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-05-04
 * Time: 1:19 PM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


class GbifParser extends DataParser
{
  public function parseData() {
    $arr  = json_decode($this->data, TRUE);
    if(empty($arr['results'])) {
      throw new \Exception('Error parsing json data.');
    }
    $this->data_arr = reset($arr['results']);
  }

}
