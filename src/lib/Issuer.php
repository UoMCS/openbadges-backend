<?php

namespace UoMCS\OpenBadges\Backend;

class Issuer extends Base
{
  public $data = array(
    'id' => null,
    'name' => null,
    'url' => null,
    'description' => null,
    'image' => null,
    'email' => null,
  );

  protected static $table_name = 'issuers';
}
