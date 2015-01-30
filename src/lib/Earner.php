<?php

namespace UoMCS\OpenBadges\Backend;

class Earner extends Base
{
  public $data = array(
    'id' => null,
    'hash' => null,
    'tyoe' => null,
  );

  protected static $table_name = 'earners';
}
