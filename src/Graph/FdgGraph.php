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
class FdgGraph extends GraphParser {

  protected $data;

  /**
   * Retrieve data from the triple store.
   */
  public function getData($scientificName = NULL, $municipality = NULL) {
    $scientificName = ($scientificName == NULL || $scientificName == 'NULL') ? '' :
      'Filter (?name="' . $scientificName . '"^^xsd:string)';
    $municipality = ($municipality == NULL || $municipality == 'NULL') ? '' :
      'Filter (?municipality="' . $municipality . '"^^xsd:string)';
    $sparqlQuery = 'SELECT DISTINCT ?municipalityIRI ?scientificNameIRI ?name ?municipality  where  { 
                         ?s <dwciri:municipality> ?municipalityIRI .
                         ?s <dwciri:scientificName> ?scientificNameIRI .
                         ?s <dwc:scientificName> ?name .
                         ?s <dwc:municipality> ?municipality ' .
      $scientificName . ' ' . $municipality . ' ' .
      '}';
    // TODO: discover or store sparql endpoint somehow.
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
