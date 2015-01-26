<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/issuers', function() use($app) {
  $body = array();
  $json_body = json_encode($body);

  return $json_body;
});

$app->get('/issuers/{id}', function($id) use ($app) {

});

$app->get('/badges', function() use ($app) {
  $body = array();
  $json_body = json_encode($body);

  return $json_body;
});

$app->run();
