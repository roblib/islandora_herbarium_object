<?php

namespace Drupal\islandora_herbarium_object\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for d3 graphs.
 */
class GraphController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   A renderable array.
   */
  public function printFdgGraph($scientificName = NULL, $municipality = NULL) {
    return [
      '#theme' => 'fdg_graph',
      '#attached' => [
        'library' => [
          'islandora_herbarium_object/d3js',
          'islandora_herbarium_object/d3-selection',
          'islandora_herbarium_object/fdg-graph',
        ],
      ],
    ];
  }

  /**
   * Directly outputs the json for calls from javascript.
   *
   * TODO: expose as a proper rest endpoint
   */
  public function printFdgData($scientificName = NULL, $municipality = NULL) {
    $fdgGraph = \Drupal::service('islandora_herbarium_object.fdggraph');
    $fdgGraph->getData($scientificName, $municipality);
    $graphJson = $fdgGraph->parseData();
    print $graphJson;
    exit();
  }

}
