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
  protected static $primary_key_type = \PDO::PARAM_STR;
}
