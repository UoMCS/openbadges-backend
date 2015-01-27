<?php

namespace UoMCS\OpenBadges\Backend;

class IssuerTest extends \PHPUnit_Framework_TestCase
{
  public function testConstructor()
  {
    $data = array(
      'id' => null,
      'name' => null,
      'url' => null,
      'description' => null,
      'image' => null,
      'email' => null
    );

    $issuer = new Issuer($data);
    $this->assertInstanceOf('UoMCS\OpenBadges\Backend\Issuer', $issuer);
  }

  public function testGetIssuer()
  {
    $id = 1;

    $issuer = Issuer::get($id);
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
