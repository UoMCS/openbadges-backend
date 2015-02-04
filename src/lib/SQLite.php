<?php

namespace UoMCS\OpenBadges\Backend;

/**
 * Wrapper class for SQLite database connection.
 *
 * Implements the Singleton design pattern to ensure that the same
 * connection is returned in all cases.
 */
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
