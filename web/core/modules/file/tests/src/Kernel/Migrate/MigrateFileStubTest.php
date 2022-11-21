<?php

namespace Drupal\Tests\file\Kernel\Migrate;

use Drupal\migrate_drupal\Tests\StubTestTrait;
use Drupal\Tests\migrate_drupal\Kernel\MigrateDrupalTestBase;

/**
 * Test stub creation for file entities.
 *
 * @group file
 */
class MigrateFileStubTest extends MigrateDrupalTestBase {

  use StubTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['file'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('file');
  }

  /**
   * Tests creation of file stubs.
   */
  public function testStub() {
    $this->performStubTest('file');
  }

}
