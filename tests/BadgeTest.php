<?php

namespace UoMCS\OpenBadges\Backend;

class BadgeTest extends DatabaseTestCase
{
  const BADGE_EXISTS_ID = 1;
  const BADGE_DOES_NOT_EXIST_ID = 99999;
  const BADGE_COUNT = 2;

  public function testCreateBadgeDB()
  {
    $badge = Badge::get(self::BADGE_EXISTS_ID);

    // Set ID to null so we trigger an INSERT
    $badge->data['id'] = null;
    $badge->save();

    $this->assertNotNull($badge->data['id']);
    $this->assertInternalType('integer', $badge->data['id']);
  }

  public function testUpdateBadgeDB()
  {
    $badge_name = 'New badge name';
    $badge_description = 'New badge description';

    $badge_old = Badge::get(self::BADGE_EXISTS_ID);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\Badge', $badge_old);

    $badge_old->data['name'] = $badge_name;
    $badge_old->data['description'] = $badge_description;
    $badge_old->save();

    $badge_new = Badge::get(self::BADGE_EXISTS_ID);
    $this->assertEquals($badge_description, $badge_new->data['description']);
    $this->assertEquals($badge_name, $badge_new->data['name']);
  }

  public function testAllEarnersDB()
  {
    $badges = Badge::getAll();
    $this->assertInternalType('array', $badges);
    $this->assertCount(self::BADGE_COUNT, $badges);

    $this->assertContainsOnlyInstancesOf('UoMCS\\OpenBadges\\Backend\\Badge', $badges);
  }

  public function testBadgeExistsDB()
  {
    $badge = Badge::get(self::BADGE_EXISTS_ID);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\Badge', $badge, 'Could not fetch badge');
    $this->assertEquals(self::BADGE_EXISTS_ID, $badge->data['id']);
  }

  public function testBadgeDoesNotExistDB()
  {
    $badge = Badge::get(self::BADGE_DOES_NOT_EXIST_ID);
    $this->assertNull($badge);
  }

  public function testResponseData()
  {
    $badge = Badge::get(self::BADGE_EXISTS_ID);
    $data = $badge->getResponseData();

    // Check that array matches specification
    $this->assertArrayHasKey('name', $data);
    $this->assertInternalType('string', $data['name']);

    $this->assertArrayHasKey('description', $data);
    $this->assertInternalType('string', $data['description']);

    $this->assertArrayHasKey('image', $data);
    $this->assertInternalType('string', $data['image']);

    $this->assertArrayHasKey('criteria', $data);
    $this->assertInternalType('string', $data['criteria']);

    $this->assertArrayHasKey('issuer', $data);
    $this->assertInternalType('string', $data['issuer']);
  }

  private function getBadgeUrlResponse($id)
  {
    $url = WEB_SERVER_BASE_URL . "/badges/$id";

    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();

    return $response;
  }

  public function testBadgeExistsUrl()
  {
    $response = $this->getBadgeUrlResponse(self::BADGE_EXISTS_ID);

    $this->assertTrue($response->isOk(), 'Accessing /badges/' . self::BADGE_EXISTS_ID . ' did not return 2xx code, returned: ' . $response->getStatusCode());

    $body = $response->getBody();
    $data = json_decode($body, true);

    $this->assertNotNull($data, 'Body is not valid JSON');

    $badge = Badge::get(self::BADGE_EXISTS_ID);

    $this->assertEquals($badge->getResponseData(), $data, 'Badge JSON does not match that returned by HTTP request');
  }

  public function testBadgeDoesNotExistUrl()
  {
    $response = $this->getBadgeUrlResponse(self::BADGE_DOES_NOT_EXIST_ID);

    $this->assertTrue($response->isNotFound());
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
