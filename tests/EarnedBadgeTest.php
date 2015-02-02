<?php

namespace UoMCS\OpenBadges\Backend;

class EarnedBadgeTest extends DatabaseTestCase
{
  const EARNED_BADGE_EXISTS_ID = 'HMWi4cx8';
  const EARNED_BADGE_DOES_NOT_EXIST_ID = 'zzzzzzzz';
  const EARNED_BADGE_COUNT = 2;

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

  public function testEarnedBadgeExistsDB()
  {
    $badge = EarnedBadge::get(self::EARNED_BADGE_EXISTS_ID);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\EarnedBadge', $badge);
    $this->assertEquals(self::EARNED_BADGE_EXISTS_ID, $badge->data['uid']);
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
