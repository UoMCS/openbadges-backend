<?php

namespace UoMCS\OpenBadges\Backend;

class IssuerTest extends DatabaseTestCase
{
  public function testIssuerExistsDB()
  {
    $issuer = Issuer::get(1);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\Issuer', $issuer, 'Could not fetch issuer');
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
