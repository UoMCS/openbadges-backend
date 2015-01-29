<?php

if (php_sapi_name() == 'cli-server')
{
  define('OPEN_BADGES_DB_PATH', __DIR__ . '/../data/test.db');
  define('OPEN_BADGES_DEBUG_MODE', true);
}

require_once __DIR__ . '/htdocs/index.php';
