<?php

namespace Drupal\mymodule\Form;

use Drupal\node\Entity\Node;
use \Drupal\Core\Entity\Query\QueryInterface;

class LatestNodeService
{

  /**
   * @var QueryInterface
   */
  protected QueryInterface $query;

  public function __construct() {

    $this->query = \Drupal::entityQuery('node');

  }

  public function htmlEntityList() {
    $nids = $this->query->accessCheck(FALSE)
      ->exists('field_image')
      ->sort('changed', 'DESC')
      ->range(0,5)
      ->execute();

    $entities = Node::loadMultiple($nids);
    $htmlList = '<div id="related_links"><ul>';
    foreach ($entities as $entity) {
      $htmlList .= "<li><a href='https://test-site.ddev.site/node/{$entity->id()}'>{$entity->getTitle()}</a></li>";
    }
    $htmlList .= '</ul></div>';

    return $htmlList;
  }
}
