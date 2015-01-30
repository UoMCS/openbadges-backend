<?php

namespace UoMCS\OpenBadges\Backend;

class EarnedBadge extends Base
{
  public $data = array(
    'uid' => null,
    'earner_id' => null,
    'badge_id' => null,
    'verification_type' => null,
    'verification_url' => null,
    'issued' => null,
    'image' => null,
    'evidence' => null,
    'expires' => null,
    'revoked' => null,
    'revoked_reason' => null,
  );

  protected static $table_name = 'earned_badges';
  protected static $primary_key = 'uid';

  public static function get($uid)
  {
    $db = SQLite::getInstance();

    $sql = 'SELECT * FROM earned_badges WHERE uid = :uid';
    $sth = $db->prepare($sql);
    $sth->execute();

    $result = $sth->fetch();

    if ($result)
    {
      return new EarnedBadge($result);
    }
    else
    {
      return null;
    }
  }
}
