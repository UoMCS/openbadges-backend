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

  /**
   * Get the URL for this badge.
   *
   * @return string
   */
  public function getUrl()
  {
    return WEB_SERVER_BASE_URL . '/issuers/' . $this->data['id'];
  }
}
