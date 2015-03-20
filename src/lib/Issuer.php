<?php

namespace OpenBadges\Backend;

class Issuer extends Base
{
  public $data = array(
    'id' => null,
    'name' => null,
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

  public function getResponseData()
  {
    $data = $this->data;

    // Remove unnecessary fields
    unset($data['id']);

    if (empty($data['description']))
    {
      unset($data['description']);
    }

    if (empty($data['image']))
    {
      unset($data['image']);
    }

    if (empty($data['email']))
    {
      unset($data['email']);
    }

    $data['url'] = $this->getUrl();

    return $data;
  }
}
