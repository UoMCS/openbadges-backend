<?php

namespace UoMCS\OpenBadges\Backend;

class Issuer extends Base
{
  public $data = array(
    'id' => null,
    'name' => null,
    'url' => null,
    'description' => null,
    'image' => null,
    'email' => null,
  );

  public function toJson()
  {
    $json = json_encode($this->data);

    return $json;
  }

  public static function get($id)
  {
    $db = SQLite::getInstance();

    $sql = 'SELECT id, name, url, description, image, email FROM issuers WHERE id = :id';
    $sth = $db->prepare($sql);
    $sth->bindParam(':id', $id, \PDO::PARAM_INT);
    $sth->execute();

    $result = $sth->fetch();

    if ($result)
    {
      return new Issuer($result);
    }
    else
    {
      return null;
    }
  }
}
