<?php

namespace Controllers;

use ArrayObject;

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
      'status' => http_response_code(405),
      'msg' => 'Error en el metodo de envio',
      'data' => NULL
    ));
  }

  public function methodBadRequest($message = "")
  {
    header('HTTP/1.1 400 bad request');
    return json_encode(array(
      'status' => http_response_code(400),
      'msg' => $message,
      'success' => false,
      'data' => NULL
    ));
  }

  public function methodNotFound($message = "")
  {
    header('HTTP/1.1 404 not found');
    return json_encode(array(
      'status' => http_response_code(404),
      'msg' => $message,
      'success' => false,
      'data' => NULL
    ));
  }

  public function methodOk($message = "", $data = NULL)
  {
    header('HTTP/1.1 200 ok');
    return json_encode(array(
      'status' => http_response_code(200),
      'msg' => $message,
      'success' => true,
      'data' => $data ? $data : NULL
    ));
  }

  public function methodCreated($message = "", $data = NULL)
  {
    header('HTTP/1.1 201 Created');
    return json_encode(array(
      'status' => http_response_code(201),
      'msg' => $message,
      'success' => true,
      'data' => $data ? $data : NULL
    ));
  }

  public function methodErrorServer($message = "", $data = NULL)
  {
    header('HTTP/1.1 500 Internal Server Error');
    return json_encode(array(
      'status' => http_response_code(500),
      'msg' => $message,
      'success' => false,
      'data' => $data ? $data : NULL
    ));
  }
}
