<?php

namespace OpenBadges\Backend;

class EarnerTest extends DatabaseTestCase
{
  const EARNER_COUNT = 2;
  const EARNER_EXISTS_ID = 1;
  const EARNER_DOES_NOT_EXIST_ID = 99999;

  public function testCreateEarnerDB()
  {
    $earner = new Earner();
    $earner->data['identity'] = Utility::identityHash('test@example.net');

    $earner->save();
    $this->assertNotNull($earner->data['id']);
    $this->assertInternalType('integer', $earner->data['id']);
  }

  public function testUpdateEarnerDB()
  {
    $earner_identity = Utility::identityHash('test@example.net');

    $earner_old = Earner::get(self::EARNER_EXISTS_ID);
    $this->assertInstanceof('OpenBadges\\Backend\\Earner', $earner_old);

    $earner_old->data['identity'] = $earner_identity;
    $earner_old->save();

    $earner_new = Earner::get(self::EARNER_EXISTS_ID);
    $this->assertEquals($earner_identity, $earner_new->data['identity']);
  }

  public function testResponseData()
  {
    $earner = Earner::get(self::EARNER_EXISTS_ID);
    $data = $earner->getResponseData();

    $this->assertArrayHasKey('identity', $data);
    $this->assertInternalType('string', $data['identity']);

    $this->assertArrayHasKey('type', $data);
    $this->assertInternalType('string', $data['type']);

    $this->assertArrayHasKey('hashed', $data);
    $this->assertInternalType('boolean', $data['hashed']);
  }

  public function testAllEarnersDB()
  {
    $earners = Earner::getAll();
    $this->assertInternalType('array', $earners);
    $this->assertCount(self::EARNER_COUNT, $earners);

    $this->assertContainsOnlyInstancesOf('OpenBadges\\Backend\\Earner', $earners);
  }

  public function testEarnerExistsDB()
  {
    $earner = Earner::get(self::EARNER_EXISTS_ID);
    $this->assertInstanceOf('OpenBadges\\Backend\\Earner', $earner);
  }

  public function testEarnerDoesNotExistDB()
  {
    $earner = Earner::get(self::EARNER_DOES_NOT_EXIST_ID);
    $this->assertNull($earner);
  }
}
