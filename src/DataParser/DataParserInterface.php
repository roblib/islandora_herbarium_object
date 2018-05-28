<?php

namespace Drupal\islandora_herbarium_object\DataParser;

use Drupal\Core\Entity\EntityInterface;

/**
 * Common functions for herbarium taxon and location data.
 */
interface DataParserInterface {

  /**
   * Retrieve data from a remote URI.
   *
   * @param string $uri
   *   The request uri.
   * @param bool $returnAsVariable
   *   If TRUE return the request data, otherwise set a class data variable(s).
   *
   * @return mixed
   *   Either NULL or the request data, determined by the value of
   *   $returnAsVariable.
   */
  public function getRemoteData($uri, $returnAsVariable = FALSE);

  /**
   * Parse the Data stored in a local data structure.
   *
   * The local data structure is usually populated by the getRemoteData()
   * function.
   */
  public function parseData();

  /**
   * Returns a value from a local data structure based on the corresponding key.
   *
   * @param string $key
   *   The key to use to lookup the data.
   *
   * @return string
   *   The value if the key exists otherwise an empty string.
   */
  public function getValue($key);

  /**
   * Update a Drupal Entity using the local data.
   *
   * @param Drupal\Core\Entity\EntityInterface $entity
   *   A Drupal Entity.
   */
  public function updateEntity(EntityInterface $entity);

}
