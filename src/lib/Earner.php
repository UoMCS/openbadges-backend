<?php

namespace UoMCS\OpenBadges\Backend;

class Earner extends Base
{
  public $data = array(
    'id' => null,
    'hash' => null,
    'tyoe' => null,
  );

  protected static $table_name = 'earners';
  protected static $primary_key = 'id';

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
}
