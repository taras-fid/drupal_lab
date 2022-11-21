<?php

namespace Drupal\Tests\locale\Kernel\Migrate;

use Drupal\Tests\migrate_drupal\Kernel\d6\MigrateDrupal6TestBase;
use Drupal\Tests\SchemaCheckTestTrait;

/**
 * Upgrade variables to locale.settings.yml.
 *
 * @group migrate_drupal_6
 */
class MigrateLocaleConfigsTest extends MigrateDrupal6TestBase {

  use SchemaCheckTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['locale', 'language'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->executeMigration('locale_settings');
  }

  /**
   * Tests migration of locale variables to locale.settings.yml.
   */
  public function testLocaleSettings() {
    $config = $this->config('locale.settings');
    $this->assertTrue($config->get('cache_strings'));
    $this->assertSame('languages', $config->get('javascript.directory'));
    $this->assertConfigSchema(\Drupal::service('config.typed'), 'locale.settings', $config->get());
  }

}
