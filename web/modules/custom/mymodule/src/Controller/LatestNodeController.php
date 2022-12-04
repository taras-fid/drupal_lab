<?php

namespace Drupal\mymodule\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\mymodule\Form\LatestNodeService;
use Drupal\node\Entity\Node;

class LatestNodeController extends ControllerBase implements ContainerInjectionInterface {

  use StringTranslationTrait;

  protected LatestNodeService $myService;

  public function __construct(LatestNodeService $myService) {
    $this->myService = $myService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('mymodule.latestNodeService')
    );
  }

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
//    $service = \Drupal::service('mymodule.latestNodeService');
    $htmlList = $this->myService->htmlEntityList();

    return [
      '#markup' => $htmlList
    ];
  }
}
