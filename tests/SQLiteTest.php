<?php

namespace UoMCS\OpenBadges\Backend;

class SQLiteTest extends \PHPUnit_Framework_TestCase
{
  protected $dbh = null;
  protected $schema_file = null;
  protected $data_file = null;

  protected function setUp()
  {
    $this->dbh = new SQLite();
    $this->assertInstanceOf('PDO', $this->dbh, 'Could not connect to database');

    $sql_directory =  __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
    $this->assertTrue(file_exists($sql_directory), 'SQL directory does not exist');
    $this->assertTrue(is_dir($sql_directory), 'SQL directory is not a directory');

    $this->schema_file = $sql_directory . 'schemas.sql';
    $this->assertTrue(file_exists($this->schema_file), 'Schema file does not exist: ' . $this->schema_file);
    $this->assertTrue(is_file($this->schema_file), 'Schema file is not a file: ' .  $this->schema_file);

    $this->data_file = $sql_directory . 'test-data.sql';
    $this->assertTrue(file_exists($this->data_file), 'Data file does not exist: ' . $this->data_file);
    $this->assertTrue(is_file($this->data_file), 'Data file is not a file: ' . $this->data_file);
  }

  protected function tearDown()
  {
    $this->dbh = null;
  }

  protected function createSchemas()
  {
    $schema = file_get_contents($this->schema_file);
    $this->assertNotEmpty($schema);

    $affected_rows = $this->dbh->exec($schema);
    $this->assertNotFalse($affected_rows, 'Could not import schema');
  }

  protected function importData()
  {
    $data = file_get_contents($this->data_file);
    $this->assertNotEmpty($data);

    $affected_rows = $this->dbh->exec($data);
    $this->assertNotFalse($affected_rows, 'Could not import data');
  }

  public function testCreateSchemas()
  {
    $this->createSchemas();
  }

  public function testImportData()
  {
    $this->createSchemas();
    $this->importData();
  }
}
