<?php

namespace UoMCS\OpenBadges\Backend;

class SQLiteTest extends \PHPUnit_Framework_TestCase
{
  const DSN = 'sqlite::memory:';

  protected $dbh = null;
  protected $schema_file = null;

  public function setUp()
  {
    $this->dbh = new \PDO(self::DSN);

    $this->schema_file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'sqlite.sql';
    $this->schema_file = realpath($this->schema_file);

    $this->assertNotFalse($this->schema_file);

    $schema_file_exists = file_exists($this->schema_file);
    $this->assertTrue($schema_file_exists);
  }

  public function tearDown()
  {
    $this->dbh = null;
  }

  public function testCreateSchemas()
  {
    $schema = file_get_contents($this->schema_file);

    $this->assertNotEmpty($schema);

    $affected_rows = $this->dbh->exec($schema);
    $this->assertNotFalse($affected_rows, 'Could not import schema');
  }
}
