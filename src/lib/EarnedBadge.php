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

  public function toJson()
  {
    $data = $this->data;

    $recipient = Earner::get($this->data['earner_id']);
    $data['recipient'] = $recipient->data;

    $badge = Badge::get($this->data['badge_id']);
    $data['badge'] = $badge->getUrl();

    $data['verify'] = array(
      'type' => $this->data['verification_type'],
      'url' => $this->data['verification_url'],
    );

    $data['issuedOn'] = $this->data['issued'];

    return json_encode($data);
  }
}
