<?php

namespace Drupal\Tests\menu_link_content\Kernel\Migrate;

use Drupal\migrate_drupal\Tests\StubTestTrait;
use Drupal\Tests\migrate_drupal\Kernel\MigrateDrupalTestBase;

/**
 * Test stub creation for menu link content entities.
 *
 * @group menu_link_content
 */
class MigrateMenuLinkContentStubTest extends MigrateDrupalTestBase {

  use StubTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['menu_link_content', 'link'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('menu_link_content');
  }

  /**
   * Tests creation of menu link content stubs.
   */
  public function testStub() {
    $this->performStubTest('menu_link_content');
  }

}
