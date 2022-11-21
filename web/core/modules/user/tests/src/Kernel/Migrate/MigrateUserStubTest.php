<?php

namespace Drupal\Tests\user\Kernel\Migrate;

use Drupal\migrate_drupal\Tests\StubTestTrait;
use Drupal\Tests\migrate_drupal\Kernel\MigrateDrupalTestBase;

/**
 * Test stub creation for user entities.
 *
 * @group user
 */
class MigrateUserStubTest extends MigrateDrupalTestBase {

  use StubTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['user'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installSchema('system', ['sequences']);
  }

  /**
   * Tests creation of user stubs.
   */
  public function testStub() {
    $this->performStubTest('user');
  }

}
