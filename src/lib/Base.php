<?php

namespace UoMCS\OpenBadges\Backend;

class Base
{
  public $data = array();

  public function __construct($data = array())
  {
    $this->setAll($data);
  }

  public function setAll($data)
  {
    foreach ($this->data as $key => $value)
    {
      if (isset($data[$key]))
      {
        $this->data[$key] = $data[$key];
      }
      else
      {
        $this->data[$key] = null;
      }
    }
  }

  public function toJson()
  {
    $json = json_encode($this->data);

    return $json;
  }
}
