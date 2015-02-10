<?php

namespace UoMCS\OpenBadges\Backend;

class EarnedBadgeTest extends DatabaseTestCase
{
  const EARNED_BADGE_EXISTS_ID = 1;
  const EARNED_BADGE_EXISTS_UID = 'HMWi4cx8';
  const EARNED_BADGE_DOES_NOT_EXIST_UID = 'zzzzzzzz';
  const EARNED_BADGE_DOES_NOT_EXIST_ID = 99999;
  const EARNED_BADGE_COUNT = 2;

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
  }

  public function testToJson()
  {
    $earned_badge = EarnedBadge::get(self::EARNED_BADGE_EXISTS_ID);
    $data = json_decode($earned_badge->toJson(), true);

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
  }

  public function testGetIdFromUid()
  {
    $id = EarnedBadge::getIdFromUid(self::EARNED_BADGE_EXISTS_UID);
    $this->assertEquals(self::EARNED_BADGE_EXISTS_ID, $id);
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
    $json_body = json_decode($body, true);

    $this->assertNotNull($json_body, 'Body is not valid JSON');

    $badge = EarnedBadge::get(EarnedBadge::getIdFromUid(self::EARNED_BADGE_EXISTS_UID));

    $this->assertEquals($badge->toJson(), $body, 'Badge JSON does not match that returned by HTTP request');
  }

  public function testEarnedBadgeDoesNotExistUrl()
  {
    $response = $this->getEarnedBadgeUrlResponse(self::EARNED_BADGE_DOES_NOT_EXIST_UID);

    $this->assertTrue($response->isNotFound());
  }
}
