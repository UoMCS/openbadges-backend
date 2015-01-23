<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/issuers/{id}', function($id) use ($app) {
  
});

$app->get('/badges', function() use ($app) {
  return 'List of all badges';
});

$app->run();
