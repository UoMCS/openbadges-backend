<?php

namespace UoMCS\OpenBadges\Backend;

class Badge extends Base
{
  public $data = array(
    'id' => null,
    'issuer_id' => null,
    'name' => null,
    'description' => null,
    'image' => null,
    'criteria' => null,
  );

  public static function get($id)
  {
    $db = SQLite::getInstance();

    $sql = 'SELECT * FROM available_badges WHERE id = :id';
    $sth = $db->prepare($sql);
    $sth->bindParam(':id', $id, \PDO::PARAM_INT);
    $sth->execute();

    $result = $sth->fetch();

    if ($result)
    {
      return new Badge($result);
    }
    else
    {
      return null;
    }
  }
}
