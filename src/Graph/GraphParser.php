<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-06-29
 * Time: 11:00 AM
 */

namespace Drupal\islandora_herbarium_object\Graph;


class GraphParser implements GraphInterface {

  /**
   * Parse the data retrieved from the triple store for use in a d3 graph.
   *
   * @return string
   *   A json encode string.
   */
  public function parseData() {
    $jsonArr = json_decode($this->data, TRUE);
    $outputArr = [];
    // $nodes = $links = new \Ds\Set();
    $outputArr['nodes'] = [];
    $outputArr['links'] = [];
    $idArr = [];

    foreach ($jsonArr['results']['bindings'] as $result) {
      if (!array_key_exists($result['municipality']['value'], $idArr)) {
        $outputArr['nodes'][] = [
          'id' => $result['municipality']['value'],
          'name' => $result['municipality']['value'],
          'uri' => $result['municipalityIRI']['value'],
          'group' => 1,
        ];
        $idArr[$result['municipality']['value']] = '';
      }
      $outputArr['nodes'][] = [
        'id' => $result['name']['value'],
        'name' => $result['name']['value'],
        'uri' => $result['scientificNameIRI']['value'],
        'group' => 2,
      ];
      $outputArr['links'][] = [
        'target' => $result['municipality']['value'],
        'source' => $result['name']['value'],
        'type' => 'found',
      ];
    }
    return json_encode($outputArr);
  }

}
