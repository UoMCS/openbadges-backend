<?php

namespace UoMCS\OpenBadges\Backend;

class UtilityTest extends \PHPUnit_Framework_TestCase
{
  const HASH_ALGORITHM = 'sha512';

  public function testConstructor()
  {
    $utility = new Utility();
    $this->assertInstanceOf('UoMCS\OpenBadges\Backend\Utility', $utility);
  }

  public function testIdentityHashSalt()
  {
    $str = 'test';
    $salt = 'salty';
    $hash = '8d8e45e4dfcee6c5c0248d33717d87a3988817246db4f200b89bda0070444fd8f435638db28e6a8d0b2ce3d9d338c5871cf0f203f37ff678ac2a92a3cca6acd3';
    $hash = self::HASH_ALGORITHM . '$' . $hash;

    $this->assertEquals($hash, Utility::identityHash($str, $salt));
  }

  public function testIdentityHashNoSalt()
  {
    $str = 'test';
    $hash = 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff';
    $hash = self::HASH_ALGORITHM . '$' . $hash;

    $this->assertEquals($hash, Utility::identityHash($str));
  }
}
