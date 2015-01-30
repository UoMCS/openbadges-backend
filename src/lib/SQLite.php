<?php

namespace UoMCS\OpenBadges\Backend;

class SQLite
{
  private static $instance = null;

  public static function getInstance()
  {
    if (self::$instance === null)
    {
      self::$instance = new \PDO(OPEN_BADGES_DB_DSN);
      self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      self::$instance->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    return self::$instance;
  }
}
