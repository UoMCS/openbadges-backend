<?php

namespace UoMCS\OpenBadges\Backend;

class Issuer
{
  public $id = null;
  public $name = null;
  public $url = null;
  public $description = null;
  public $image = null;
  public $email = null;

  public static function get($id)
  {
    $db = new SQLite(DB_PATH);
    $db->setAttribute(\PDO::FETCH_CLASS, 'Issuer');

    $sql = 'SELECT id, name, url, description, image, email FROM issuers WHERE id = :id';
    $sth = $db->prepare($sql);
    $sth->execute(array(':id' => $id));

    if ($sth->rowCount() === 1)
    {
      $result = $sth->fetch();

      return $result;
    }
    else
    {
      return null;
    }
  }
}
