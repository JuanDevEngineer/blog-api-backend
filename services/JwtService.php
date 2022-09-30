<?php

namespace Services;

// date_default_timezone_set('America/Bogota');
// require '../vendor/autoload.php';

// load variables enviroment

use UnexpectedValueException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JwtService
{
  private $idUser;
  private $privateKey;

  public function __construct($id)
  {
    $this->idUser = $id;
    $this->privateKey = $_ENV['SECRET_KEY_PRIVATE'] ?? 'h7I1u;i9!Fs5fdtE9#Bi9!MEVV=3~kxZEi2!F4E#.{O1DN!3JV';
  }

  public function generateToken()
  {
    try {
      $issuedAt = time();
      $expirationTime = $issuedAt + (60 * 60);

      $payload = [
        'sub' => $this->idUser,
        'iss' => 'http://localhost/blog-api-backend/',
        'iat' => $issuedAt,
        'exp' => $expirationTime
      ];

      $jwt = JWT::encode($payload, $this->privateKey, 'HS256');
    } catch (\Exception $e) {
      echo $e;
    }

    return $jwt;
  }

  public function decodeToken($token)
  {
    // if (!array_key_exists('HTTP_AUTHORIZATION', $_SERVER)) {
    //   http_response_code(401);
    //   die;
    // }

    // if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
    //   header('HTTP/1.0 400 Bad Request');
    //   echo 'Token not found in request';
    //   exit;
    // }

    // $jwt = $matches[1];
    // if (!$jwt) {
    //   // No token was able to be extracted from the authorization header
    //   header('HTTP/1.0 400 Bad Request');
    //   exit;
    // };

    // $secret_Key  = 'ENTER_SECRET_KEY_HERE';
    // $token = JWT::decode($jwt, $secret_Key, ['HS512']);
    // $now = new DateTimeImmutable();
    // $serverName = "your.domain.name";

    // if (
    //   $token->iss !== $serverName ||
    //   $token->nbf > $now->getTimestamp() ||
    //   $token->exp < $now->getTimestamp()
    // ) {
    //   header('HTTP/1.1 401 Unauthorized');
    //   exit;
    // }

    // $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    // $arr = explode(" ", $authHeader);
    // $jwt = $arr[1];
    $decoded = null;
    // if ($jwt) {
    try {
      $decoded = JWT::decode($token, new Key($this->privateKey, 'HS256'));
    } catch (\Exception $e) {
      if ($e instanceof UnexpectedValueException) {
        echo "entrando";
        die();
      }

      if ($e instanceof BeforeValidException) {
        echo "entrando";
        die();
      }
      if ($e instanceof ExpiredException) {
        echo "entrando en expiración";
        die();
      }
      if ($e instanceof SignatureInvalidException) {
        echo "entrando en token invalido";
        die();
      }

      echo $e->getMessage();
    }
    // }

    return $decoded;
  }
}

// $token = new JwtService(1);

// $currentToken = $token->generateToken();

// $decode = $token->decodeToken($currentToken);
// print_r($decode);
