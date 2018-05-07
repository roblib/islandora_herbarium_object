<?php
/**
 * Created by IntelliJ IDEA.
 * User: ppound
 * Date: 2018-04-30
 * Time: 1:10 PM
 */

namespace Drupal\islandora_herbarium_object\DataParser;


interface DataParserInterface
{

  public function getRemoteData($uri);

  public function parseData();

  public function getValue($key);

  public function updateEntity($entity);

}
