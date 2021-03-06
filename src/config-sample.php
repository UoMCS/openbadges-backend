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

if (!defined('WEB_SERVER_BASE_URL'))
{
  define('WEB_SERVER_BASE_URL', 'http://localhost:8000');
}

define('DEFAULT_EARNER_TYPE', 'email');
define('DEFAULT_EARNER_HASHED', true);
define('DEFAULT_VERIFICATION_TYPE', 'hosted');
define('TIMESTAMP_FORMAT', 'Y-m-d H:i:s');

date_default_timezone_set('Europe/London');
