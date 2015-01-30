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

  public static function randomString($length)
  {
    $str = '';

    for ($i = 1; $i <= $length; $i++)
    {
      $option = mt_rand(1, 3);

      if ($option == 1)
      {
        $ascii = mt_rand(48, 57);
      }
      elseif ($option == 2)
      {
        $ascii = mt_rand(65, 90);
      }
      elseif ($option == 3)
      {
        $ascii = mt_rand(97, 122);
      }

      $str .= chr($ascii);
    }

    return $str;
  }
}
