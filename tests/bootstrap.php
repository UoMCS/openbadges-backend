<?php

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/London');

$httpd_command = sprintf(
  'php -S %s:%d -t %s -d error_reporting=%d %s >/dev/null 2>&1 & echo $!',
  WEB_SERVER_HOST,
  WEB_SERVER_PORT,
  WEB_SERVER_DOCROOT,
  ERROR_REPORTING,
  WEB_SERVER_ROUTER
);

echo 'Starting server using: ' . $httpd_command . PHP_EOL;

// Execute command and store process ID
$httpd_output = array();
exec($httpd_command, $httpd_output);
$httpd_pid = (int) $httpd_output[0];

echo sprintf(
  '%s - Web server started on %s:%d with PID %d',
  date('r'),
  WEB_SERVER_HOST,
  WEB_SERVER_PORT,
  $httpd_pid
) . PHP_EOL;

// Sleep for 5 seconds to allow web server to start
echo sprintf('Sleeping for %d seconds to allow web server to start', WEB_SERVER_DELAY) . PHP_EOL;
sleep(WEB_SERVER_DELAY);

// Kill web server when process ends
register_shutdown_function(function() use ($httpd_pid) {
  echo sprintf(
    '%s - Killing process with ID %d',
    date('r'),
    $httpd_pid
  ) . PHP_EOL;

  exec('kill ' . $httpd_pid);
});

// Create and populate database
$db = \OpenBadges\Backend\SQLite::getInstance();
$schema = file_get_contents(__DIR__ . '/../data/schemas.sql');
$db->exec($schema);
