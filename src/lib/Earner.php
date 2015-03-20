<?php

namespace OpenBadges\Backend;

class Earner extends Base
{
  public $data = array(
    'id' => null,
    'identity' => null,
    'hashed' => null,
    'type' => null,
  );

  protected static $table_name = 'earners';

  /**
   * Given the email of an earner, find the ID.
   *
   * @param string $email
   * @return integer|null ID, or null if no match found.
   */
  public static function getIdFromEmail($email)
  {
    $identity = Utility::identityHash($email);

    return static::getIdFromIdentity($identity);
  }

  /**
   * Given the identity hash of an earner, find the ID.
   *
   * @param string $identity Identity hash.
   * @return integer|null ID, or null if no match found.
   */
  public static function getIdFromIdentity($identity)
  {
    $db = SQLite::getInstance();

    $sql = 'SELECT id FROM ' . static::$table_name . ' WHERE identity = :identity';

    $sth = $db->prepare($sql);
    $sth->execute(array(':identity' => $identity));

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
    $data = $this->data;

    // Remove unnecessary fields
    unset($data['id']);

    $data['hashed'] = (bool) $data['hashed'];

    return $data;
  }

  protected function setDefaultDataValues()
  {
    if ($this->data['type'] === null)
    {
      $this->data['type'] = DEFAULT_EARNER_TYPE;
    }

    if ($this->data['hashed'] === null)
    {
      $this->data['hashed'] = DEFAULT_EARNER_HASHED;
    }
  }
}
