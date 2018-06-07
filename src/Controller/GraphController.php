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
  public function printFdgGraph() {
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
   */
  public function printFdgData() {
    $fdgGraph = \Drupal::service('islandora_herbarium_object.fdggraph');
    $graphJson = $fdgGraph->parseData();
    print $graphJson;
    exit();
  }

}
