<?php

namespace UoMCS\OpenBadges\Backend;

class Utility
{
  const HASH_ALGORITHM = 'sha512';

  public static function identityHash($str, $salt = '')
  {
    $str .= $salt;
    $hash = hash(self::HASH_ALGORITHM, $str);
    $hash = self::HASH_ALGORITHM . '$' . $hash;

    return $hash;
  }
}
