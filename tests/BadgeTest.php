<?php

namespace UoMCS\OpenBadges\Backend;

class BadgeTest extends DatabaseTestCase
{
  public function testConstructor()
  {
    $badge = new Badge();
    $this->assertInstanceOf('UoMCS\OpenBadges\Backend\Badge', $badge);
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
