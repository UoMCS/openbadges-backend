<?php

namespace OpenBadges\Backend;

use PNG\Image;

class EarnedBadge extends Base
{
  const UID_LENGTH = 8;
  const REVOKED_RESPONSE_CODE = 410;

  public $data = array(
    'id' => null,
    'uid' => null,
    'earner_id' => null,
    'badge_id' => null,
    'verification_type' => null,
    'issued' => null,
    'image' => null,
    'evidence' => null,
    'expires' => null,
    'revoked' => null,
    'revoked_reason' => null,
  );

  protected static $table_name = 'earned_badges';

  /**
   * Fetch all earned badges for a given email address.
   *
   * @param string Email address
   * @return array|null Array of badges, or null if earner does not exist
   */
  public static function getAllFromEmail($email)
  {
    $earner_id = Earner::getIdFromEmail($email);

    if ($earner_id === null)
    {
      return null;
    }

    $db = SQLite::getInstance();

    $sql = 'SELECT * FROM ' . static::$table_name . ' WHERE earner_id = :earner_id';

    $sth = $db->prepare($sql);
    $sth->execute(array(':earner_id' => $earner_id));

    $results = array();

    while ($result = $sth->fetch())
    {
      $results[] = new EarnedBadge($result);
    }

    return $results;
  }

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

  protected function setDefaultDataValues()
  {
    if ($this->data['verification_type'] === null)
    {
      $this->data['verification_type'] = DEFAULT_VERIFICATION_TYPE;
    }

    if ($this->data['issued'] === null)
    {
      $this->data['issued'] = date(TIMESTAMP_FORMAT);
    }

    if ($this->data['image'] === null)
    {
      $badge = Badge::get($this->data['badge_id']);
      $response_data = $this->getResponseData();
      $json = json_encode($response_data);

      $png = new Image();
      $png->setContents(base64_decode($badge->data['image']));
      $png->addITXtChunk('openbadges', 'json', $json);

      $this->data['image'] = base64_encode($png->getContents());
    }
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

  public function getVerificationUrl()
  {
    return WEB_SERVER_BASE_URL . '/assertions/' . $this->data['uid'];
  }

  public function getResponseData()
  {
    if ($this->isRevoked())
    {
      return array('revoked' => true);
    }

    $data = $this->data;

    // Remove unnecessary elements
    unset($data['id']);
    unset($data['revoked']);
    unset($data['revoked_reason']);

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

    $data['recipient'] = Earner::get($this->data['earner_id'])->getResponseData();
    $data['badge'] = Badge::get($this->data['badge_id'])->getUrl();

    $data['verify'] = array(
      'type' => $this->data['verification_type'],
      'url' => $this->getVerificationUrl(),
    );

    $data['issuedOn'] = $this->data['issued'];

    return $data;
  }
}
