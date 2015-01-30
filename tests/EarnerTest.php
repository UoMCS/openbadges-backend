<?php

namespace UoMCS\OpenBadges\Backend;

class EarnerTest extends DatabaseTestCase
{
  const EARNER_COUNT = 2;
  const EARNER_EXISTS_ID = 1;
  const EARNER_DOES_NOT_EXIST_ID = 99999;

  public function testAllEarnersDB()
  {
    $earners = Earner::getAll();
    $this->assertInternalType('array', $earners);
    $this->assertCount(self::EARNER_COUNT, $earners);
  }

  public function testEarnerExistsDB()
  {
    $earner = Earner::get(self::EARNER_EXISTS_ID);
    $this->assertInstanceOf('UoMCS\\OpenBadges\\Backend\\Earner', $earner);
  }

  public function testEarnerDoesNotExistDB()
  {
    $earner = Earner::get(self::EARNER_DOES_NOT_EXIST_ID);
    $this->assertNull($earner);
  }
}
