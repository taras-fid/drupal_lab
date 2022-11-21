<?php

namespace Drupal\Tests\Core\Test;

use Drupal\deprecation_test\Deprecation\FixtureDeprecatedClass;
use Drupal\Tests\UnitTestCase;

/**
 * Test how unit tests interact with deprecation errors in process isolation.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @group Test
 * @group legacy
 */
class PhpUnitBridgeIsolatedTest extends UnitTestCase {

  public function testDeprecatedClass() {
    $this->expectDeprecation('Drupal\deprecation_test\Deprecation\FixtureDeprecatedClass is deprecated.');
    $deprecated = new FixtureDeprecatedClass();
    $this->assertEquals('test', $deprecated->testFunction());
  }

}
