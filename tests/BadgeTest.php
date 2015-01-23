<?php

namespace UoMCS\OpenBadges\Backend;

class BadgeTest extends \PHPUnit_Framework_TestCase
{
  public function testConstructor()
  {
    $badge = new Badge();
    $this->assertInstanceOf('UoMCS\OpenBadges\Backend\Badge', $badge);
  }
}
