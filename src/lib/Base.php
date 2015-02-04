<?php

namespace UoMCS\OpenBadges\Backend;

/**
 * Base class for all concrete entity classes to inherit from.
 *
 * Base class which provides generic methods such as get(), getAll() etc.
 * for all other entity classes. Some child classes may need to override
 * methods such as insert() to take account of specific needs.
 */
abstract class Base
{
  public $data = array();

  protected static $table_name = null;
  protected static $primary_key = 'id';
  protected static $primary_key_type = \PDO::PARAM_INT;

  /**
   * Create a new instance of the class.
   *
   * @param array $data Optional data to populate the object.
   */
  public function __construct($data = array())
  {
    $this->setAll($data);
  }

  /**
   * Get a JSON string representing all the objects.
   *
   * Use this function over getAll + toJson.
   *
   * @return string JSON representation of all objects as an array.
   */
  public static function getAllJson()
  {
    $items = static::getAll();
    $data = array();

    if (count($items) >= 1)
    {
      foreach ($items as $item)
      {
        $data[] = json_decode($item->toJson());
      }
    }

    return json_encode($data);
  }

  /**
   * Save object to persistent storage (usually a database).
   *
   * @return integer|null The last inserted ID, or null if not applicable.
   */
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

  /**
   * Get all the fields which can be edited (everything bar primary key).
   *
   * @return array
   */
  private function getEditableFields()
  {
    $fields = $this->data;
    unset($fields[static::$primary_key]);
    $fields = array_keys($fields);

    return $fields;
  }

  /**
   * Insert a new row representing this object.
   *
   * This function should only be called by save or via parent::
   * in a child class.
   *
   * @return integer|null ID of inserted row, or null if insert failed.
   */
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

  /**
   * Update the row representing this object in the database.
   *
   * @return null
   */
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

  /**
   * Set all data elements for this object.
   *
   * Set all data elements, defaulting to null if a particular
   * element does not exist in $data.
   *
   * @param array $data
   * @return void
   */
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

  /**
   * Get a specific object based on its ID (primary key).
   *
   * @param int $id
   * @return object
   */
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

  /**
   * Get all objects of this type.
   *
   * @return array
   */
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

  /**
   * Produce a JSON representation of this object.
   *
   * Note: This method will often need to be implemented in child classes.
   *
   * @return string
   */
  public function toJson()
  {
    return json_encode($this->data);
  }
}
