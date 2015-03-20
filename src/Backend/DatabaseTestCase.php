<?php

namespace OpenBadges\Backend;

abstract class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
  static private $pdo = null;
  private $connection = null;

  final protected function getConnection()
  {
    if ($this->connection === null)
    {
      if (self::$pdo === null)
      {
        self::$pdo = SQLite::getInstance();
      }

      $this->connection = $this->createDefaultDBConnection(self::$pdo);
    }

    return $this->connection;
  }

  protected function getDataSet()
  {
    return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/../../data/test.yml');
  }
}
