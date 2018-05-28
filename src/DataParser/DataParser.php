<?php

namespace Drupal\islandora_herbarium_object\DataParser;

/**
 * Abstract implementation of DataParserInterface base on arrays.
 */
abstract class DataParser implements DataParserInterface {

  public $data;

  public $dataArray = [];

  /**
   * {@inheritdoc}
   */
  public function getRemoteData($uri, $returnAsVariable = FALSE) {
    $client = \Drupal::httpClient();
    $request = $client->get(trim($uri));
    $data = $request->getBody()->getContents();
    if (empty($data)) {
      throw new Exception("Empty dataset");
    }
    if (!$returnAsVariable) {
      $this->data = $data;
    }
    else {
      return $data;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getValue($key) {
    return isset($this->dataArray[$key]) ? $this->dataArray[$key] : "";
  }

}
