<?php

namespace UoMCS\OpenBadges\Backend;

class BadgeTest extends DatabaseTestCase
{
  const BADGE_EXISTS_ID = 1;
  const BADGE_DOES_NOT_EXIST_ID = 99999;
  const BADGE_COUNT = 2;
  const BADGE_IMAGE_PATH = '/../data/sample-badge.png';

  public function testCreateBadgeUrl()
  {
    $badge = Badge::get(self::BADGE_EXISTS_ID);
    $badge->data['id'] = null;
    $body = $badge->getResponseData();
    $body['image'] = base64_encode(file_get_contents(__DIR__ . self::BADGE_IMAGE_PATH));

    $json = json_encode($body);

    $this->assertNotFalse($json);

    $url = WEB_SERVER_BASE_URL . '/badges';
    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $client->setMethod('POST');
    $client->setRawBody($json);
    $client->setEncType('application/json');
    $response = $client->send();

    $this->assertEquals(201, $response->getStatusCode());

    $headers = $response->getHeaders();
    $location_header = $headers->get('Location');

    $this->assertNotFalse($location_header, 'No Location: header returned');

    $location_value = $location_header->getFieldValue();
    $location_pattern = '#^' . WEB_SERVER_BASE_URL . '/badges/[0-9]+$#';

    $this->assertEquals(1, preg_match($location_pattern, $location_value), 'Location header does not match regex');

    // Check that created badge exists by attempting to fetch it
    $url = $location_value;
    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();

    $this->assertTrue($response->isOk(), 'Could not access created badge at URL: ' . $url);

    $body = $response->getBody();
    $data = json_decode($body, true);
    $this->assertNotNull($data, 'Body is not valid JSON');

    $url = $data['image'];
    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();
    $this->assertTrue($response->isOk(), 'Could not access image at URL: ' . $url);

    $headers = $response->getHeaders();
    $content_type_header = $headers->get('Content-Type');
    $this->assertNotFalse($content_type_header, 'No Content-Type: header returned');

    $content_type_value = $content_type_header->getFieldValue();
    $this->assertEquals('image/png', $content_type_value);
  }

  public function testCreateBadgeDB()
  {
    $badge = Badge::get(self::BADGE_EXISTS_ID);

    // Set ID to null so we trigger an INSERT
    $badge->data['id'] = null;

    // Grab image data
    $badge->data['image'] = file_get_contents(__DIR__ .  self::BADGE_IMAGE_PATH);

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

  private function getBadgeImageUrlResponse($id)
  {
    $url = WEB_SERVER_BASE_URL . "/badges/images/$id";

    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();

    return $response;
  }

  public function testBadgeImageExistsUrl()
  {
    $response = $this->getBadgeImageUrlResponse(self::BADGE_EXISTS_ID);

    $this->assertTrue($response->isOk(), 'Accessing /badges/images/' . self::BADGE_EXISTS_ID . ' did not return 2xx code, returned: ' . $response->getStatusCode());

    $headers = $response->getHeaders();
    $content_type_header = $headers->get('Content-Type');
    $this->assertNotFalse($content_type_header, 'No Content-Type header returned');

    $content_type_value = $content_type_header->getFieldValue();
    $this->assertEquals('image/png', $content_type_value);
  }

  public function testBadgeImageDoesNotExistUrl()
  {
    $response = $this->getBadgeImageUrlResponse(self::BADGE_DOES_NOT_EXIST_ID);
    $this->assertTrue($response->isNotFound());
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
