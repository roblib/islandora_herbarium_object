<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-06-29
 * Time: 11:00 AM
 */

namespace Drupal\islandora_herbarium_object\Graph;
use Drupal\Core\Url;

class GraphParser implements GraphInterface {

  /**
   * Parse the data retrieved from the triple store for use in a d3 graph.
   *
   * @return string
   *   A json encode string.
   */
  public function parseData($uri_endpoint = 'source') {
    $jsonArr = json_decode($this->data, TRUE);
    $outputArr = [];
    // $nodes = $links = new \Ds\Set();
    $outputArr['nodes'] = [];
    $outputArr['links'] = [];
    $idArr = [];
    foreach ($jsonArr['results']['bindings'] as $result) {
      if (!array_key_exists($result['municipality']['value'], $idArr)) {
        $municipality_uri = ($uri_endpoint == 'solr') ? Url::fromUri('internal:/herbarium-search?search_api_fulltext=&herbarium-search%5B0%5D=municipality:' .
          $result['municipality']['value'])->toString() : $result['municipalityIRI']['value'];         
        $outputArr['nodes'][] = [
          'id' => $result['municipality']['value'],
          'name' => $result['municipality']['value'],
          'uri' => $municipality_uri,
          'group' => 1,
        ];
        $idArr[$result['municipality']['value']] = '';
      }
      if (!array_key_exists($result['name']['value'], $idArr)) {
        $scientific_name_uri = ($uri_endpoint == 'solr') ? Url::fromUri('internal:/herbarium-search?search_api_fulltext=&herbarium-search%5B0%5D=scientific_name:' .
          $result['name']['value'])->toString() : $result['scientificNameIRI']['value'];    
        $outputArr['nodes'][] = [
          'id' => $result['name']['value'],
          'name' => $result['name']['value'],
          'uri' => $scientific_name_uri,
          'group' => 2,
        ];
        $idArr[$result['name']['value']] = '';
      }
      $outputArr['links'][] = [
        'target' => $result['municipality']['value'],
        'source' => $result['name']['value'],
        'type' => 'found',
      ];
    }
    return json_encode($outputArr);
  }

}
