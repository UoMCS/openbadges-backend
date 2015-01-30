<?php

namespace UoMCS\OpenBadges\Backend;

abstract class Base
{
  public $data = array();

  protected static $table_name = null;
  protected static $primary_key = null;

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
