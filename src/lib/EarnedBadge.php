<?php

namespace UoMCS\OpenBadges\Backend;

class EarnedBadge extends Base
{
  const UID_LENGTH = 8;

  public $data = array(
    'id' => null,
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

  protected function insert()
  {
    do
    {
      $uid = Utility::randomString(self::UID_LENGTH);
      $id = self::getIdFromUid($uid);
    } while ($id != null);

    $this->data['uid'] = $uid;

    parent::insert();
  }

  public static function getIdFromUid($uid)
  {
    $db = SQLite::getInstance();

    $sql = 'SELECT id FROM ' . static::$table_name . ' WHERE uid = :uid';
    $sth = $db->prepare($sql);
    $sth->execute(array(':uid' => $uid));

    $result = $sth->fetch();

    if ($result)
    {
      return $result['id'];
    }
    else
    {
      return null;
    }
  }

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
