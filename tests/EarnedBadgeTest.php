<?php

namespace UoMCS\OpenBadges\Backend;

class EarnedBadgeTest extends DatabaseTestCase
{
  const EARNED_BADGE_EXISTS_ID = 1;
  const EARNED_BADGE_EXISTS_UID = 'HMWi4cx8';
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

  public function testToJson()
  {
    $earned_badge = EarnedBadge::get(self::EARNED_BADGE_EXISTS_ID);
    $data = json_decode($earned_badge->toJson(), true);

    $this->assertArrayHasKey('uid', $data);
    $this->assertInternalType('string', $data['uid']);

    $this->assertArrayHasKey('recipient', $data);
    $this->assertInternalType('array', $data['recipient']);

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
}
