<?php

namespace UoMCS\OpenBadges\Backend;

class EarnerTest extends DatabaseTestCase
{
  const EARNER_COUNT = 2;

  public function testAllEarnersDB()
  {
    $earners = Earner::getAll();
    $this->assertEquals(count($earners), 2);
  }
}
