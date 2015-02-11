<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

use UoMCS\OpenBadges\Backend\Badge;
use UoMCS\OpenBadges\Backend\EarnedBadge;
use UoMCS\OpenBadges\Backend\Issuer;

$app = new Silex\Application();

$app->get('/issuers', function() use($app) {
  return $app->json(Issuer::getAllResponseData());
});

$app->get('/issuers/{id}', function($id) use ($app) {
  $issuer = Issuer::get($id);

  if ($issuer === null)
  {
    $app->abort(404, 'Issuer not found');
  }

  return $app->json($issuer->getResponseData());
});

$app->get('/badges', function() use ($app) {
  return $app->json(Badge::getAllResponseData());
});

$app->get('/badges/{id}', function($id) use($app) {
  $badge = Badge::get($id);

  if ($badge === null)
  {
    $app->abort(404, 'Badge not found');
  }

  return $app->json($badge->getResponseData());
});

$app->get('/assertions/{uid}', function($uid) use ($app) {
  $badge = EarnedBadge::get(EarnedBadge::getIdFromUid($uid));

  if ($badge === null)
  {
    $app->abort(404, 'Badge not found');
  }
  elseif ($badge->isRevoked())
  {
    return $app->json($badge->getResponseData(), 410);
  }

  return $app->json($badge->getResponseData());
});

$app['debug'] = OPEN_BADGES_DEBUG_MODE;
$app->run();
