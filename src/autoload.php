<?php

spl_autoload_register(function($class) {
  // Assume all classes are under this single namespace
  $prefix = 'UoMCS\\OpenBadges\\Backend\\';

  $base_dir = __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;

  $len = strlen($prefix);

  // Skip this autoloader if class name does not begin with prefix
  if (strncmp($prefix, $class, $len) !== 0)
  {
    return;
  }

  // Relative class is the last part of full class name
  // e.g. UoMCS\OpenBadges\Backend\Utility => Utility
  $relative_class = substr($class, $len);

  $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

  if (file_exists($file))
  {
    require $file;
  }
});
