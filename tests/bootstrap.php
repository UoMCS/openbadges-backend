<?php

require_once __DIR__ . '/../src/autoload.php';

date_default_timezone_set('Europe/London');

$httpd_command = sprintf(
  'php -S %s:%d -t %s >/dev/null 2>&1 & echo $!',
  WEB_SERVER_HOST,
  WEB_SERVER_PORT,
  WEB_SERVER_DOCROOT
);

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

// Kill web server when process ends
register_shutdown_function(function() use ($httpd_pid) {
  echo sprintf(
    '%s - Killing process with ID %d',
    date('r'),
    $httpd_pid
  ) . PHP_EOL;

  exec('kill ' . $httpd_pid);
});
