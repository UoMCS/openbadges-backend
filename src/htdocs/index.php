<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

use UoMCS\OpenBadges\Backend\Issuer;

$app = new Silex\Application();

$app->get('/issuers', function() use($app) {
  $body = array();
  $json_body = json_encode($body);

  return $json_body;
});

$app->get('/issuers/{id}', function($id) use ($app) {
  $issuer = Issuer::get($id);

  if ($issuer === null)
  {
    $app->abort(404, 'Issuer not found');
  }

  return $issuer->toJson();
});

$app->get('/badges', function() use ($app) {
  $body = array();
  $json_body = json_encode($body);

  return $json_body;
});

$app['debug'] = OPEN_BADGES_DEBUG_MODE;
$app->run();
