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

  /**
   * Get the URL for this badge.
   *
   * @return string
   */
  public function getUrl()
  {
    return WEB_SERVER_BASE_URL . '/badges/' . $this->data['id'];
  }

  public function toJson()
  {
    $data = $this->data;

    $issuer = Issuer::get($this->data['issuer_id']);

    $data['issuer'] = $issuer->getUrl();

    return json_encode($data);
  }
}
