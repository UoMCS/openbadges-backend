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

  public function save()
  {
    if ($this->data[static::$primary_key] === null)
    {
      return $this->insert();
    }
    else
    {
      return $this->update();
    }
  }

  private function getEditableFields()
  {
    $fields = $this->data;
    unset($fields[static::$primary_key]);
    $fields = array_keys($fields);

    return $fields;
  }

  protected function insert()
  {
    $fields = $this->getEditableFields();

    $placeholders = array_map(function($field) { return ":$field"; }, $fields);

    $sql = 'INSERT INTO ' . static::$table_name . ' ';
    $sql .= '(' . join(', ', $fields) . ')';
    $sql .= ' VALUES ';
    $sql .= '(' . join(', ', $placeholders) . ')';

    $parameters = array();

    for ($i = 0; $i < count($fields); $i++)
    {
      $parameters[$placeholders[$i]] = $this->data[$fields[$i]];
    }

    $db = SQLite::getInstance();

    $sth = $db->prepare($sql);
    $success = $sth->execute($parameters);

    if (!$success)
    {
      return null;
    }

    $last_insert_id = intval($db->lastInsertId());

    $this->data[static::$primary_key] = $last_insert_id;

    return $last_insert_id;
  }

  protected function update()
  {
    $fields = $this->getEditableFields();

    $placeholders = array_map(function($field) { return ":$field"; }, $fields);

    $set_options = array_map(function($field) { return "$field = :$field"; }, $fields);

    $sql = 'UPDATE ' . static::$table_name . ' SET ';
    $sql .= join(', ', $set_options);
    $sql .= ' WHERE ' . static::$primary_key . ' = :' . static::$primary_key;

    $parameters = array();

    for ($i = 0; $i < count($fields); $i++)
    {
      $parameters[$placeholders[$i]] = $this->data[$fields[$i]];
    }

    $parameters[':' . static::$primary_key] = $this->data[static::$primary_key];

    $db = SQLite::getInstance();

    $sth = $db->prepare($sql);
    $sth->execute($parameters);

    return null;
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
    return json_encode($this->data);
  }
}
