<?php

namespace UoMCS\OpenBadges\Backend;

class EarnerTest extends DatabaseTestCase
{
  const EARNER_COUNT = 2;

  public function testAllEarnersDB()
  {
    $earners = Earner::getAll();
    $this->assertInternalType('array', $earners);
    $this->assertCount(2, $earners);
  }
}
