<?php

namespace UoMCS\OpenBadges\Backend;

class IssuerTest extends \PHPUnit_Framework_TestCase
{
  public function testConstructor()
  {
    $issuer = new Issuer();
    $this->assertInstanceOf('UoMCS\OpenBadges\Backend\Issuer', $issuer);
  }
}
