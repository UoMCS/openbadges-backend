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
    $this->setAll($data);
  }

  public function setAll($data)
  {
    $this->id = isset($data->id) ? $data->id : null;
    $this->name = isset($data->name) ? $data->name : null;
    $this->url = isset($data->url) ? $data->url : null;
    $this->description = isset($data->description) ? $data->description : null;
    $this->image = isset($data->image) ? $data->image : null;
    $this->email = isset($data->email) ? $data->email : null;
  }

  public function toJson()
  {
    $data = array(
      'id' => $this->id,
      'name' => $this->name,
      'url' => $this->url,
      'description' => $this->description,
      'image' => $this->image,
      'email' => $this->email,
    );

    $json = json_encode($data);

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
