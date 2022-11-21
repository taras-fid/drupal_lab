<?php

namespace Drupal\Tests\hal\Functional\system;

use Drupal\Tests\rest\Functional\CookieResourceTestTrait;
use Drupal\Tests\system\Functional\Rest\ActionResourceTestBase;

/**
 * @group hal
 * @group legacy
 */
class ActionHalJsonCookieTest extends ActionResourceTestBase {

  use CookieResourceTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['hal'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $format = 'hal_json';

  /**
   * {@inheritdoc}
   */
  protected static $mimeType = 'application/hal+json';

  /**
   * {@inheritdoc}
   */
  protected static $auth = 'cookie';

}
