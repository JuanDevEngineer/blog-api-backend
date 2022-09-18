<?php

namespace Controllers;

class AppController
{
  public function __construct()
  {
  }

  protected function validateFormatImage($file)
  {
    $flag = false;
    $type = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');

    if (in_array($file, $type)) {
      $flag = true;
    }
    return $flag;
  }

  public function validateSize($fileSize)
  {
    $flag = false;

    if ($fileSize < 10000000) {
      $flag = true;
    }
    return $flag;
  }

  public function request()
  {
    return json_decode(file_get_contents('php://input'), true);
  }
}
