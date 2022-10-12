<?php

namespace Services;

use Controllers\AppController;
use UnexpectedValueException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JwtService
{
  private $idUser;
  private $reponse;
  private $privateKey;

  public function __construct($id = NULL)
  {
    $this->idUser = $id;
    $this->reponse = new AppController();
    $this->privateKey = $_ENV['SECRET_KEY_PRIVATE'] ?? 'h7I1u;i9!Fs5fdtE9#Bi9!MEVV=3~kxZEi2!F4E#.{O1DN!3JV';
  }

  public function generateToken()
  {
    try {
      $issuedAt = time();
      $expirationTime = $issuedAt + (60 * 60);
      // $expirationTime = $issuedAt + 60; // jwt valid for 60 seconds from the issued time

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

  public function verifyToken()
  {
    $matches = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
    $token = $matches[1];
    try {
      JWT::decode($token, new Key($this->privateKey, 'HS256'));
    } catch (\Exception $e) {
      if ($e instanceof BeforeValidException) {
        echo $this->reponse->methodUnauthorized("Before validation error");
        die();
      }
      if ($e instanceof ExpiredException) {
        echo $this->reponse->methodUnauthorized("Token has been expired");
        die();
      }
      if ($e instanceof SignatureInvalidException) {
        echo $this->reponse->methodBadRequest("Token malformed");
        die();
      }

      echo $this->reponse->methodBadRequest($e->getMessage());
      die();
    }
  }

  public static function getToken()
  {
    return isset($_SERVER['HTTP_AUTHORIZATION']);
  }

  public static function existHeaderAutorization()
  {
    return !array_key_exists('HTTP_AUTHORIZATION', $_SERVER);
  }
}
