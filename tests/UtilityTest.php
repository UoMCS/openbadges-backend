<?php

namespace UoMCS\OpenBadges\Backend;

class UtilityTest extends \PHPUnit_Framework_TestCase
{
  public function testIdentityHash()
  {
    $str = 'test';
    $salt = 'salty';
    $hash = 'sha512$8d8e45e4dfcee6c5c0248d33717d87a3988817246db4f200b89bda0070444fd8f435638db28e6a8d0b2ce3d9d338c5871cf0f203f37ff678ac2a92a3cca6acd3';

    $this->assertEquals($hash, Utility::identityHash($str, $salt));
  }
}
