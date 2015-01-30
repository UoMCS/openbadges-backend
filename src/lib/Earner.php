<?php

namespace UoMCS\OpenBadges\Backend;

class Earner extends Base
{
  public $data = array(
    'id' => null,
    'hash' => null,
    'tyoe' => null,
  );

  public static function getAll()
  {
    $db = SQLite::getInstance();

    $sql = 'SELECT * FROM earners ORDER BY id ASC';
    $sth = $db->prepare($sql);
    $sth->execute();

    $results = array();

    while ($result = $sth->fetch())
    {
      $results[] = new Earner($result);
    }

    return $results;
  }
}
