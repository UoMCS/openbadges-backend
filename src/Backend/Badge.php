<?php

namespace OpenBadges\Backend;

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

  /**
   * Get badge ID from URL.
   *
   * @param string $url URL of the badge.
   * @return int Badge ID.
   */
  public static function getIdFromUrl($url)
  {
    $base_url = WEB_SERVER_BASE_URL . '/badges/';
    $id = (int) str_replace($base_url, '', $url);

    return $id;
  }

  /**
   * Get the URL for this badge's email.
   *
   * @return string
   */
  public function getImageUrl()
  {
    return WEB_SERVER_BASE_URL . '/badges/images/' . $this->data['id'];
  }

  public function getResponseData()
  {
    $data = $this->data;

    $data['issuer'] = Issuer::get($this->data['issuer_id'])->getUrl();
    $data['image'] = $this->getImageUrl();

    return $data;
  }
}
