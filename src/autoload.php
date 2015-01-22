<?php

spl_autoload_register(function($class) {
  $prefix = 'UoMCS\\OpenBadges\\Backend\\';

  $base_dir = __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;

  $len = strlen($prefix);

  if (strncmp($prefix, $class, $len) !== 0)
  {
    return;
  }

  $relative_class = substr($class, $len);

  $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

  if (file_exists($file))
  {
    require $file;
  }
});
