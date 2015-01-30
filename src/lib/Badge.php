<?php

namespace UoMCS\OpenBadges\Backend;

class Badge extends Base
{
  public $data = array(
    'id' => null,
    'issuer_id' => null,
    'name' => null,
    'description' => null,
    'image' => null,
    'criteria' => null,
  );

  protected static $table_name = 'available_badges';
}
