<?php

namespace Drupal\Tests\ban\Kernel\Migrate\d7;

use Drupal\Tests\migrate_drupal\Kernel\d7\MigrateDrupal7TestBase;
use Drupal\Tests\SchemaCheckTestTrait;

/**
 * Migrate blocked IPs.
 *
 * @group ban
 */
class MigrateBlockedIpsTest extends MigrateDrupal7TestBase {

  use SchemaCheckTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['ban'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installSchema('ban', ['ban_ip']);
    $this->executeMigration('d7_blocked_ips');
  }

  /**
   * Tests migration of blocked IPs.
   */
  public function testBlockedIps() {
    $this->assertTrue(\Drupal::service('ban.ip_manager')->isBanned('111.111.111.111'));
  }

}
