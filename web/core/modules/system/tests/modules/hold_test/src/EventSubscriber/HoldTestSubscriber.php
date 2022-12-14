<?php

namespace Drupal\hold_test\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Response subscriber to test hold.
 */
class HoldTestSubscriber implements EventSubscriberInterface {

  const HOLD_REQUEST = 'request';
  const HOLD_RESPONSE = 'response';

  /**
   * Time in microseconds to wait for before checking if the file is updated.
   *
   * @var int
   */
  const WAIT = 100000;

  /**
   * The site path.
   *
   * @var string
   */
  protected $sitePath;

  /**
   * HoldTestSubscriber constructor.
   *
   * @param string $site_path
   *   The site path.
   */
  public function __construct(string $site_path) {
    $this->sitePath = $site_path;
  }

  /**
   * Request hold.
   */
  public function onRequest() {
    $this->hold(static::HOLD_REQUEST);
  }

  /**
   * Response hold.
   */
  public function onRespond() {
    $this->hold(static::HOLD_RESPONSE);
  }

  /**
   * Hold process by type.
   *
   * @param string $type
   *   Type of hold.
   */
  protected function hold($type) {
    $path = "{$this->sitePath}/hold_test_$type.txt";
    do {
      $status = (bool) file_get_contents($path);
    } while ($status && (NULL === usleep(static::WAIT)));
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onRequest'];
    $events[KernelEvents::RESPONSE][] = ['onRespond'];
    return $events;
  }

}
