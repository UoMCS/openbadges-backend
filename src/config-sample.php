<?php

if (!defined('OPEN_BADGES_DB_PATH'))
{
  define('OPEN_BADGES_DB_PATH', __DIR__ . '/data.db');
}

if (!defined('OPEN_BADGES_DEBUG_MODE'))
{
  define('OPEN_BADGES_DEBUG_MODE', false);
}

define('OPEN_BADGES_DB_DSN', 'sqlite:' . OPEN_BADGES_DB_PATH);
