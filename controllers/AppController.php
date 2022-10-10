<?php

namespace Controllers;

class AppController
{

  protected static $request;

  public function __construct()
  {
    self::$request = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
  }

  public function acceptsJson()
  {
    if (strtolower(self::$request) == 'application/json') {
      return true;
    }
    return false;
  }

  protected function validateFormatImage($file, $formats = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png')): bool
  {
    return (in_array($file, $formats));
  }

  protected function validatePassword($passwordRequest, $hash): bool
  {
    return password_verify($passwordRequest, $hash);
  }

  public function validateSize($fileSize): bool
  {
    return $fileSize < 10000000;
  }

  public function request()
  {
    return json_decode(file_get_contents('php://input'), true);
  }

  public function allowRequestMethod($method = 'GET')
  {
    return ($_SERVER['REQUEST_METHOD'] == $method);
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

  public function methodUnauthorized($message = "")
  {
    header('HTTP/1.1 401 Unauthorized');
    return json_encode(array(
      'status' => http_response_code(401),
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
      'data' => $data ?? NULL
    ));
  }

  public function methodCreated($message = "", $data = NULL)
  {
    header('HTTP/1.1 201 Created');
    return json_encode(array(
      'status' => http_response_code(201),
      'msg' => $message,
      'success' => true,
      'data' => $data ?? NULL
    ));
  }

  public function methodErrorServer($message = "", $data = NULL)
  {
    header('HTTP/1.1 500 Internal Server Error');
    return json_encode(array(
      'status' => http_response_code(500),
      'msg' => $message,
      'success' => false,
      'data' => $data ?? NULL
    ));
  }
}
