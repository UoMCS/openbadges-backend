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

  /**
   * Check whether am earned badge has been revoked.
   *
   * @return bool True if badge has been revoked, false otherwise.
   */
  public function isRevoked()
  {
    return ($this->data['revoked'] !== null);
  }

  /**
   * Revoke an earned badge.
   *
   * @param string $reason Reason for revoking the badge. Defaults to empty string.
   * @param integer $timestamp When the badge was revoked. Defaults to current time.
   */
  public function revoke($reason = '', $timestamp = null)
  {
    if (empty($timestamp))
    {
      $timestamp = time();
    }

    $this->data['revoked'] = date('Y-m-d H:i:s', $timestamp);
    $this->data['revoked_reason'] = $reason;
    $this->save();
  }

  protected function insert()
  {
    do
    {
      // Try random UIDs until we find one which isn't in use
      $uid = Utility::randomString(self::UID_LENGTH);
      $id = self::getIdFromUid($uid);
    } while ($id != null);

    $this->data['uid'] = $uid;

    parent::insert();
  }

  /**
   * Given the UID of an earned badge, find the ID.
   *
   * @param string $uid
   * @return integer|null ID, or null if no match found.
   */
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

  public function getResponseData()
  {
    if ($this->isRevoked())
    {
      return array('revoked' => true);
    }

    $data = $this->data;

    return $data;
  }

  public function toJson()
  {
    if ($this->isRevoked())
    {
      $data = array('revoked' => true);

      return json_encode($data);
    }

    $data = $this->data;

    // Remove unnecessary elements
    unset($data['id']);

    if (empty($data['image']))
    {
      unset($data['image']);
    }

    if (empty($data['evidence']))
    {
      unset($data['evidence']);
    }

    if (empty($data['expires']))
    {
      unset($data['expires']);
    }

    $recipient = Earner::get($this->data['earner_id']);
    $data['recipient'] = json_decode($recipient->toJson());

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
