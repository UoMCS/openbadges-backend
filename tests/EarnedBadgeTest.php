<?php

namespace UoMCS\OpenBadges\Backend;

class EarnedBadgeTest extends DatabaseTestCase
{
  const EARNED_BADGE_EXISTS_ID = 1;
  const EARNED_BADGE_DOES_NOT_EXIST_ID = 99999;
  const EARNED_BADGE_COUNT = 2;

  public function testAllEarnedBadgesDB()
  {
    $badges = EarnedBadge::getAll();
    $this->assertInternalType('array', $badges);
    $this->assertCount(self::EARNED_BADGE_COUNT, $badges);

    foreach ($badges as $badge)
    {
      $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\EarnedBadge', $badge);
    }
  }
}
