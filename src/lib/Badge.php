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

  public function toJson()
  {
    $data = $this->data;

    $issuer = Issuer::get($this->data['issuer_id']);

    $data['issuer'] = $issuer->data;

    return json_encode($data);
  }
}
