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

  public function __construct($data)
  {
    $this->id = $data['id'];
    $this->name = $data['name'];
    $this->url = $data['url'];
    $this->description = $data['description'];
    $this->image = $data['image'];
    $this->email = $data['email'];
  }

  public static function get($id)
  {
    $db = new \PDO('sqlite:' . DB_PATH);
    $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

    $sql = 'SELECT id, name, url, description, image, email FROM issuers WHERE id = :id';
    $sth = $db->prepare($sql);
    $sth->execute(array(':id' => $id));

    if ($sth->rowCount() === 1)
    {
      $result = $sth->fetch();

      return new Issuer($result);
    }
    else
    {
      return null;
    }
  }
}
