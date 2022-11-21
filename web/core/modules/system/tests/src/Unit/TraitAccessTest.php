<?php

namespace Drupal\Tests\system\Unit;

use Drupal\Tests\system\Traits\TestTrait;
use Drupal\Tests\UnitTestCase;

/**
 * Test whether traits are autoloaded during PHPUnit discovery time.
 *
 * @group system
 * @group Test
 */
class TraitAccessTest extends UnitTestCase {

  use TestTrait;

  /**
   * @coversNothing
   */
  public function testSimpleStuff() {
    $stuff = $this->getStuff();
    $this->assertSame($stuff, 'stuff', "Same old stuff");
  }

}
