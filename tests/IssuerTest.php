<?php

namespace UoMCS\OpenBadges\Backend;

class IssuerTest extends DatabaseTestCase
{
  const ISSUER_EXISTS_ID = 1;
  const ISSUER_DOES_NOT_EXIST_ID = 99999;

  public function testCreateIssuerDB()
  {
    $issuer = new Issuer();
    $issuer->data['name'] = 'Test Issuer insertion';
    $issuer->data['description'] = 'Test description of issuer';

    $issuer->save();

    $this->assertNotNull($issuer->data['id']);
    $this->assertInternalType('integer', $issuer->data['id']);
  }

  public function testUpdateIssuerDB()
  {
    $issuer_name = 'Test issuer (modified)';

    $issuer_old = Issuer::get(self::ISSUER_EXISTS_ID);

    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\Issuer', $issuer_old, 'Could not fetch issuer');

    $issuer_old->data['name'] = $issuer_name;

    $issuer_old->save();

    $issuer_new = Issuer::get(self::ISSUER_EXISTS_ID);

    $this->assertEquals($issuer_name, $issuer_new->data['name']);
  }

  public function testIssuerExistsDB()
  {
    $issuer = Issuer::get(self::ISSUER_EXISTS_ID);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\Issuer', $issuer, 'Could not fetch issuer');
  }

  public function testIssuerDoesNotExistDB()
  {
    $issuer = Issuer::get(self::ISSUER_DOES_NOT_EXIST_ID);
    $this->assertNull($issuer);
  }

  public function testResponseData()
  {
    $data = Issuer::get(self::ISSUER_EXISTS_ID)->getResponseData();

    // Check that array matches specification
    $this->assertArrayHasKey('name', $data);
    $this->assertInternalType('string', $data['name']);

    $this->assertArrayHasKey('url', $data);
    $this->assertInternalType('string', $data['url']);

    $this->assertArrayHasKey('description', $data);
    $this->assertInternalType('string', $data['description']);
  }

  private function getIssuerUrlResponse($id)
  {
    $url = WEB_SERVER_BASE_URL . "/issuers/$id";

    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();

    return $response;
  }

  public function testIssuerExistsUrl()
  {
    $response = $this->getIssuerUrlResponse(self::ISSUER_EXISTS_ID);

    $this->assertTrue($response->isOk(), 'Accessing /issuers/<id> did not return 2xx code, returned: ' . $response->getStatusCode());

    $body = $response->getBody();
    $data = json_decode($body, true);

    $this->assertNotNull($data, 'Body is not valid JSON');

    $issuer = Issuer::get(self::ISSUER_EXISTS_ID);

    $this->assertEquals($issuer->getResponseData(), $data, 'Issuer JSON does not match that returned by HTTP request');
  }

  public function testIssuerDoesNotExistUrl()
  {
    $response = $this->getIssuerUrlResponse(self::ISSUER_DOES_NOT_EXIST_ID);

    $this->assertTrue($response->isNotFound());
  }

  public function testIssuersUrl()
  {
    $url = WEB_SERVER_BASE_URL . '/issuers';

    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();

    $this->assertTrue($response->isOk(), 'Accessing /issuers did not return 2xx code');

    $body = $response->getBody();
    $data = json_decode($body, true);

    $this->assertNotNull($data, 'Body is not valid JSON');
  }
}
