<?php

namespace UoMCS\OpenBadges\Backend;

class EarnedBadgeTest extends DatabaseTestCase
{
  const EARNED_BADGE_EXISTS_ID = 1;
  const EARNED_BADGE_EXISTS_UID = 'HMWi4cx8';
  const EARNED_BADGE_DOES_NOT_EXIST_UID = 'zzzzzzzz';
  const EARNED_BADGE_DOES_NOT_EXIST_ID = 99999;
  const EARNED_BADGE_COUNT = 3;
  const EARNED_BADGE_EARNER_ID = 1;
  const EARNED_BADGE_EMAIL_EXISTS = 'test@example.org';
  const EARNED_BADGE_EMAIL_DOES_NOT_EXIST = 'test@example.net';

  public function testEarnedBadgeConstructor()
  {
    $data = array();
    $badge = new EarnedBadge($data);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\EarnedBadge', $badge);
  }

  public function testCreateEarnedBadgeDB()
  {
    $badge = EarnedBadge::get(self::EARNED_BADGE_EXISTS_ID);

    // Set ID to null so we trigger an INSERT
    $badge->data['id'] = null;
    $badge->save();

    $this->assertNotNull($badge->data['id']);
    $this->assertInternalType('integer', $badge->data['id']);
  }

  public function testRevokeEarnedBadgeDB()
  {
    $badge = EarnedBadge::get(self::EARNED_BADGE_EXISTS_ID);
    $this->assertFalse($badge->isRevoked());

    $badge->revoke();

    $this->assertTrue($badge->isRevoked());

    $response = $this->getEarnedBadgeUrlResponse(self::EARNED_BADGE_EXISTS_UID);
    $this->assertEquals(EarnedBadge::REVOKED_RESPONSE_CODE, $response->getStatusCode());

    $body = $response->getBody();
    $data = json_decode($body, true);

    $this->assertNotNull($data, 'Body is not valid JSON');
    $this->assertArrayHasKey('revoked', $data);
    $this->assertInternalType('boolean', $data['revoked']);
  }

  public function testResponseData()
  {
    $earned_badge = EarnedBadge::get(self::EARNED_BADGE_EXISTS_ID);
    $data = $earned_badge->getResponseData();

    $this->assertArrayHasKey('uid', $data);
    $this->assertInternalType('string', $data['uid']);

    $this->assertArrayHasKey('recipient', $data);
    $this->assertInternalType('array', $data['recipient']);
    $this->assertArrayHasKey('type', $data['recipient']);
    $this->assertInternalType('string', $data['recipient']['type']);
    $this->assertArrayHasKey('hashed', $data['recipient']);
    $this->assertInternalType('boolean', $data['recipient']['hashed']);
    $this->assertArrayHasKey('identity', $data['recipient']);
    $this->assertInternalType('string', $data['recipient']['identity']);

    $this->assertArrayHasKey('badge', $data);
    $this->assertInternalType('string', $data['badge']);

    $this->assertArrayHasKey('verify', $data);
    $this->assertInternalType('array', $data['verify']);
    $this->assertArrayHasKey('type', $data['verify']);
    $this->assertInternalType('string', $data['verify']['type']);
    $this->assertArrayHasKey('url', $data['verify']);
    $this->assertInternalType('string', $data['verify']['url']);

    $this->assertArrayHasKey('issuedOn', $data);
    $this->assertInternalType('string', $data['issuedOn']);

    $client = new \Zend\Http\Client();
    $client->setUri($data['badge']);
    $response = $client->send();
    $this->assertTrue($response->isOk());
  }

  public function testGetIdFromUid()
  {
    $id = EarnedBadge::getIdFromUid(self::EARNED_BADGE_EXISTS_UID);
    $this->assertEquals(self::EARNED_BADGE_EXISTS_ID, $id);
  }

  private function getEmailUrlResponse($email)
  {
    $url = WEB_SERVER_BASE_URL . '/assertions/' . $email;

    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();

    return $response;
  }

  public function testEmailExistsUrl()
  {
    $email = self::EARNED_BADGE_EMAIL_EXISTS;
    $response = $this->getEmailUrlResponse($email);

    $this->assertTrue($response->isOk(), 'Accessing /assertions/' . $email . ' did not return 2xx code, returned : ' . $response->getStatusCode());

    $body = $response->getBody();
    $data = json_decode($body, true);

    $this->assertNotNull($data, 'Body is not valid JSON');


  }

  public function testEmailExistsDB()
  {
    $badges = EarnedBadge::getAllFromEmail(self::EARNED_BADGE_EMAIL_EXISTS);
    $this->assertInternalType('array', $badges);
    $this->assertContainsOnlyInstancesOf('UoMCS\\OpenBadges\\Backend\\EarnedBadge', $badges);
  }

  public function testEmailDoestNotExistDB()
  {
    $badges = EarnedBadge::getAllFromEmail(self::EARNED_BADGE_EMAIL_DOES_NOT_EXIST);
    $this->assertNull($badges);
  }

  public function testEarnedBadgeExistsDB()
  {
    $badge = EarnedBadge::get(self::EARNED_BADGE_EXISTS_ID);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\EarnedBadge', $badge);
    $this->assertEquals(self::EARNED_BADGE_EXISTS_ID, $badge->data['id']);
  }

  public function testEarnedBadgeDoesNotExistDB()
  {
    $badge = EarnedBadge::get(self::EARNED_BADGE_DOES_NOT_EXIST_ID);
    $this->assertNull($badge);
  }

  public function testAllEarnedBadgesDB()
  {
    $badges = EarnedBadge::getAll();
    $this->assertInternalType('array', $badges);
    $this->assertCount(self::EARNED_BADGE_COUNT, $badges);

    $this->assertContainsOnlyInstancesOf('UoMCS\\OpenBadges\\Backend\\EarnedBadge', $badges);
  }

  private function getEarnedBadgeUrlResponse($uid)
  {
    $url = WEB_SERVER_BASE_URL . '/assertions/' . $uid;

    $client = new \Zend\Http\Client();
    $client->setUri($url);
    $response = $client->send();

    return $response;
  }

  public function testEarnedBadgeRevokedUrl()
  {
    $badge = EarnedBadge::get(self::EARNED_BADGE_EXISTS_ID);
    $badge->revoke();

    $response = $this->getEarnedBadgeUrlResponse(self::EARNED_BADGE_EXISTS_UID);

    $this->assertEquals(410, $response->getStatusCode());

    $body = $response->getBody();
    $json_body = json_decode($body, true);

    $this->assertNotNull($json_body, 'Body is not valid JSON');
    $this->assertArrayHasKey('revoked', $json_body);
    $this->assertInternalType('boolean', $json_body['revoked']);
  }

  public function testEarnedBadgeExistsUrl()
  {
    $response = $this->getEarnedBadgeUrlResponse(self::EARNED_BADGE_EXISTS_UID);

    $this->assertTrue($response->isOk(), 'Accessing /assertions/' . self::EARNED_BADGE_EXISTS_UID . ' did not return 2xx code, returned: ' . $response->getStatusCode());

    $body = $response->getBody();
    $data = json_decode($body, true);

    $this->assertNotNull($data, 'Body is not valid JSON');

    $badge = EarnedBadge::get(EarnedBadge::getIdFromUid(self::EARNED_BADGE_EXISTS_UID));

    $this->assertEquals($badge->getResponseData(), $data, 'Badge JSON does not match that returned by HTTP request');
  }

  public function testEarnedBadgeDoesNotExistUrl()
  {
    $response = $this->getEarnedBadgeUrlResponse(self::EARNED_BADGE_DOES_NOT_EXIST_UID);

    $this->assertTrue($response->isNotFound());
  }
}
