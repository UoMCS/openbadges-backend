<?php

namespace UoMCS\OpenBadges\Backend;

abstract class Base
{
  public $data = array();

  protected static $table_name = null;
  protected static $primary_key = 'id';
  protected static $primary_key_type = \PDO::PARAM_INT;

  public function __construct($data = array())
  {
    $this->setAll($data);
  }

  public function setAll($data)
  {
    foreach ($this->data as $key => $value)
    {
      if (isset($data[$key]))
      {
        $this->data[$key] = $data[$key];
      }
      else
      {
        $this->data[$key] = null;
      }
    }
  }

  public static function get($id)
  {
    $class_name = get_called_class();
    $primary_key_param = ':' . static::$primary_key;

    $db = SQLite::getInstance();

    $sql = 'SELECT * FROM ' . static::$table_name . ' WHERE ' . static::$primary_key . ' = ' . $primary_key_param;

    $sth = $db->prepare($sql);
    $sth->bindParam($primary_key_param, $id, static::$primary_key_type);
    $sth->execute();

    $result = $sth->fetch();

    if ($result)
    {
      return new $class_name($result);
    }
    else
    {
      return null;
    }
  }

  public static function getAll()
  {
    $class_name = get_called_class();

    $db = SQLite::getInstance();

    $sql = 'SELECT * FROM ' . static::$table_name . ' ORDER BY ' . static::$primary_key . ' ASC';

    $sth = $db->prepare($sql);
    $sth->execute();

    $results = array();

    while ($result = $sth->fetch())
    {
      $results[] = new $class_name($result);
    }

    return $results;
  }

  public function toJson()
  {
    $json = json_encode($this->data);

    return $json;
  }
}
