<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use OpenBadges\Backend\Badge;
use OpenBadges\Backend\EarnedBadge;
use OpenBadges\Backend\Earner;
use OpenBadges\Backend\Issuer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
})
->assert('id', '[1-9][0-9]*');

$app->post('/badges', function(Request $request) use ($app) {
  $body = $request->getContent();
  $data = json_decode($body, true);

  if ($data === null)
  {
    $app->abort(400, 'Request body not valid JSON');
  }

  $badge = new Badge($data);
  $badge->save();

  $headers = array(
    'Location' => $badge->getUrl(),
  );

  return new Response('Badge created', 201, $headers);
});

$app->get('/badges', function() use ($app) {
  return $app->json(Badge::getAllResponseData());
});

$app->get('/badges/images/{id}', function($id) use ($app) {
  $badge = Badge::get($id);

  if ($badge === null)
  {
    $app->abort(404, 'Badge not found');
  }

  if (empty($badge->data['image']))
  {
    $app->abort(404, 'Badge image not found');
  }

  $response = new Response();
  $response->setContent(base64_decode($badge->data['image']));
  $response->headers->set('Content-Type', 'image/png');

  return $response;
})
->assert('id', '[1-9][0-9]*');

$app->get('/badges/{id}', function($id) use ($app) {
  $badge = Badge::get($id);

  if ($badge === null)
  {
    $app->abort(404, 'Badge not found');
  }

  return $app->json($badge->getResponseData());
})
->assert('id', '[1-9][0-9]*');

$app->post('/assertions', function(Request $request) use ($app) {
  $body = $request->getContent();
  $data = json_decode($body, true);

  if ($data === null)
  {
    $app->abort(400, 'Request body not valid JSON');
  }

  $earner_id = Earner::getIdFromIdentity($data['recipient']['identity']);
  $badge_id = Badge::getIdFromUrl($data['badge']);

  $assertion_data = array(
    'earner_id' => $earner_id,
    'badge_id' => $badge_id
  );

  $assertion = new EarnedBadge($assertion_data);
  $assertion->save();

  $headers = array(
    'Location' => $assertion->getVerificationUrl()
  );

  return new Response('Assertion created', 201, $headers);
});

$app->get('/assertions/{uid}', function($uid) use ($app) {
  $badge = EarnedBadge::get(EarnedBadge::getIdFromUid($uid));

  if ($badge === null)
  {
    $app->abort(404, 'Badge not found');
  }
  elseif ($badge->isRevoked())
  {
    return $app->json($badge->getResponseData(), EarnedBadge::REVOKED_RESPONSE_CODE);
  }

  return $app->json($badge->getResponseData());
})
->assert('uid', '[0-9a-zA-Z]+');

$app->get('/assertions/images/{uid}', function($uid) use ($app) {
  $badge = EarnedBadge::get(EarnedBadge::getIdFromUid($uid));

  if ($badge === null)
  {
    $app->abort(404, 'Assertion not found');
  }

  if (empty($badge->data['image']))
  {
    $app->abort(404, 'Assertion image not found');
  }

  $response = new Response();
  $response->setContent(base64_decode($badge->data['image']));
  $response->headers->set('Content-Type', 'image/png');

  return $response;
})
->assert('uid', '[0-9a-zA-Z]+');

$app->get('/assertions/{email}', function($email) use ($app) {
  // We assume if something follows /assertions and is not a UID,
  // it must be an email.
  $badges = EarnedBadge::getAllFromEmail($email);

  if ($badges === null)
  {
    $app->abort(404);
  }

  $response_data = array();

  if (count($badges) >= 1)
  {
    foreach ($badges as $badge)
    {
      $response_data[] = $badge->getResponseData();
    }
  }

  return $app->json($response_data);
});

$app['debug'] = OPEN_BADGES_DEBUG_MODE;
$app->run();
