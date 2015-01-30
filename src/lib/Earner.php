<?php

namespace UoMCS\OpenBadges\Backend;

class Earner extends Base
{
  public $data = array(
    'id' => null,
    'hash' => null,
    'tyoe' => null,
  );

  public static function get($id)
  {
    $db = SQLite::getInstance();

    $sql = 'SELECT * FROM earners WHERE id = :id';
    $sth = $db->prepare($sql);
    $sth->bindParam(':id', $id, \PDO::PARAM_INT);
    $sth->execute();

    $result = $sth->fetch();

    if ($result)
    {
      return new Earner($result);
    }
    else
    {
      return null;
    }
  }

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
