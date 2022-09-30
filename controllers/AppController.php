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

  protected function validatePassword($passwordRequest, $hash)
  {
    return password_verify($passwordRequest, $hash);
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

  public function methodAllowed()
  {
    header("HTTP/1.0 405 Method Not Allowed");
    return json_encode(array(
      'status' => 405,
      'msg' => 'error en el metodo de envio',
    ));
  }

  public function methodBadRequest()
  {
    header('HTTP/1.1 400 bad request');
  }

  public function methodOk()
  {
    header('HTTP/1.1 200 ok');
  }
}
