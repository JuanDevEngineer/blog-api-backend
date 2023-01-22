<?php

namespace Services;

// use UnexpectedValueException;
use Config\Config;
use Controllers\AppController;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JwtService
{
	private mixed $idUser;
	private AppController $response;
	private mixed $privateKey;
	
	public function __construct($id = null)
	{
		$this->idUser = $id;
		$this->response = new AppController();
		$this->privateKey = $_ENV['SECRET_KEY_PRIVATE'] ?? Config::SECRET_KEY;
	}
	
	public function generateToken(): string
	{
		try {
			$payload = [
				'sub' => $this->idUser,
				'iss' => Config::ISS,
				'iat' => Config::iat(),
				'exp' => Config::iat() + Config::EXPIRATION_TIME
			];
			
			$jwt = JWT::encode($payload, $this->privateKey, Config::HASH_KEY);
		} catch (Exception $e) {
			echo $this->response->methodErrorServer('Error generate token', $e->getCode());
			die();
		}
		return $jwt;
	}
	
	public function verifyToken(): void
	{
		$matches = explode(' ', self::authorization());
		$token = $matches[1];
		try {
			JWT::decode($token, new Key($this->privateKey, Config::HASH_KEY));
		} catch (Exception $e) {
			if ($e instanceof BeforeValidException) {
				echo $this->response->methodUnauthorized("Before validation error");
				die();
			}
			if ($e instanceof ExpiredException) {
				echo $this->response->methodUnauthorized("Token has been expired");
				die();
			}
			if ($e instanceof SignatureInvalidException) {
				echo $this->response->methodBadRequest("Token malformed");
				die();
			}
			
			echo $this->response->methodBadRequest($e->getMessage());
			die();
		}
	}
	
	public static function getToken(): bool
	{
		return isset($_SERVER['HTTP_AUTHORIZATION']);
	}
	
	public static function authorization()
	{
		return $_SERVER['HTTP_AUTHORIZATION'];
	}
	
	public static function existHeaderAuthorization(): bool
	{
		return !array_key_exists('HTTP_AUTHORIZATION', $_SERVER);
	}
}
