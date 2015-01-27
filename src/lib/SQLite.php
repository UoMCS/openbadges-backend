<?php

namespace UoMCS\OpenBadges\Backend;

class SQLite extends \PDO
{
  public function __construct($db_path = null)
  {
    $dsn = 'sqlite:';

    if ($db_path)
    {
      $dsn .= $db_path;
    }
    else
    {
      $dsn .= ':memory:';
    }

    parent::__construct($dsn);
  }
}
