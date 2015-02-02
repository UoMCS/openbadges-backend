<?php

namespace UoMCS\OpenBadges\Backend;

class Earner extends Base
{
  public $data = array(
    'id' => null,
    'identity' => null,
    'hashed' => null,
    'type' => null,
  );

  protected static $table_name = 'earners';

  public function toJson()
  {
    $data = $this->data;

    $data['hashed'] = (bool) $data['hashed'];

    return json_encode($data, true);
  }
}
