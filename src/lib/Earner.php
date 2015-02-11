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

  public function getResponseData()
  {
    $data = $this->data;

    // Remove unnecessary fields
    unset($data['id']);

    $data['hashed'] = (bool) $data['hashed'];

    return $data;
  }
}
