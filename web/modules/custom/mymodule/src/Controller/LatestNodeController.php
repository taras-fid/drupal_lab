<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\Entity\Node;

class LatestNodeController {

  use StringTranslationTrait;

  public function getNodeList() {
    $query = \Drupal::entityQuery('node');
    $nids = $query->accessCheck(FALSE)
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

    return [
      '#markup' => $htmlList
    ];

  }

  public function getNodeListService() {
    $service = \Drupal::service('mymodule.latestNodeService');
    $htmlList = $service->htmlEntityList();

    return [
      '#markup' => $htmlList
    ];
  }
}
