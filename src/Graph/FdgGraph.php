<?php

namespace Drupal\islandora_herbarium_object\Graph;

use Drupal\Core\Url;

/**
 * Class FdgGraph.
 *
 * Retrieve and parse data from the triple store for force directed graph.
 *
 * @package Drupal\islandora_herbarium_object\Graph
 */
class FdgGraph {

  protected $data;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->getData();
  }

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

  /**
   * Retrieve data from the triple store.
   */
  public function getData() {
    $sparqlQuery = 'SELECT DISTINCT ?municipalityIRI ?scientificNameIRI ?name ?municipality  where  { ?s <dwciri:municipality> ?municipalityIRI .
                         ?s <dwciri:scientificName> ?scientificNameIRI .
                         ?s <dwc:scientificName> ?name.
                         ?s <dwc:municipality> ?municipality                                               
                        }';

    $uri = 'http://localhost:8080/bigdata/namespace/islandora/sparql';
    $options = ['query' => ['query' => $sparqlQuery]];
    $url = Url::fromUri($uri, $options);
    $httpClient = \Drupal::httpClient();
    $requestUrl = $url->toString();
    $request = $httpClient->get($requestUrl, [
      'headers' => ['Accept' => 'application/sparql-results+json'],
    ]);
    $data = $request->getBody()->getContents();
    if (empty($data)) {
      throw new Exception("Empty dataset");
    }
    $this->data = $data;
  }

}
