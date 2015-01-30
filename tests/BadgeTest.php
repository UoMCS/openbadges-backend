<?php

namespace UoMCS\OpenBadges\Backend;

class BadgeTest extends DatabaseTestCase
{
  const BADGE_EXISTS_ID = 1;
  const BADGE_DOES_NOT_EXIST_ID = 99999;

  public function testBadgeExistsDB()
  {
    $badge = Badge::get(self::BADGE_EXISTS_ID);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\Badge', $badge, 'Could not fetch badge');
    $this->assertEquals(self::BADGE_EXISTS_ID, $badge->data['id']);
  }

  public function testBadgesUrl()
  {
    $url = WEB_SERVER_BASE_URL . '/badges';

    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();

    $this->assertTrue($response->isOk(), 'Accessing /badges did not return 2xx code');

    $body = $response->getBody();
    $json_body = json_decode($body, true);

    $this->assertNotNull($json_body, 'Body is not valid JSON');
  }
}
