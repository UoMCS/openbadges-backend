<?php

namespace UoMCS\OpenBadges\Backend;

class IssuerTest extends DatabaseTestCase
{
  const ISSUER_EXISTS_ID = 1;
  const ISSUER_DOES_NOT_EXIST_ID = 99999;

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

  public function testToJson()
  {
    $issuer = Issuer::get(self::ISSUER_EXISTS_ID);
    $data = json_decode($issuer->toJson());

    $this->assertNotNull($data, 'Issuer->toJson() does not return valid JSON');
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

    $this->assertTrue($response->isOk(), 'Accessing /issuers/<id> did not return 2xx code');

    $body = $response->getBody();
    $json_body = json_decode($body, true);

    $this->assertNotNull($json_body, 'Body is not valid JSON');
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
    $json_body = json_decode($body, true);

    $this->assertNotNull($json_body, 'Body is not valid JSON');
  }
}
