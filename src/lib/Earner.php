<?php

namespace UoMCS\OpenBadges\Backend;

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
}
