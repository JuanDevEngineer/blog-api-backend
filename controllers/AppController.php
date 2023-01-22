<?php

namespace Controllers;

class AppController
{
	protected static mixed $request;
	
	public function __construct()
	{
		self::$request = $_SERVER['CONTENT_TYPE'] ?? '';
	}
	
	public function acceptsJson(): bool
	{
		return (strtolower(self::$request) == 'application/json');
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
	
	public function allowRequestMethod($method = 'GET'): bool
	{
		return ($_SERVER['REQUEST_METHOD'] == $method);
	}
	
	public function methodAllowed(): bool|string
	{
		header("HTTP/1.0 405 Method Not Allowed");
		return json_encode(array(
			'status' => http_response_code(405),
			'msg' => 'Error en el metodo de envio',
			'data' => null
		));
	}
	
	public function methodBadRequest($message = ""): bool|string
	{
		header('HTTP/1.1 400 bad request');
		return json_encode(array(
			'status' => http_response_code(400),
			'msg' => $message,
			'success' => false,
			'data' => null
		));
	}
	
	public function methodNotFound($message = ""): bool|string
	{
		header('HTTP/1.1 404 not found');
		return json_encode(array(
			'status' => http_response_code(404),
			'msg' => $message,
			'success' => false,
			'data' => null
		));
	}
	
	public function methodUnauthorized($message = ""): bool|string
	{
		header('HTTP/1.1 401 Unauthorized');
		return json_encode(array(
			'status' => http_response_code(401),
			'msg' => $message,
			'success' => false,
			'data' => null
		));
	}
	
	public function methodOk($message = "", $data = null): bool|string
	{
		header('HTTP/1.1 200 ok');
		return json_encode(array(
			'status' => http_response_code(200),
			'msg' => $message,
			'success' => true,
			'data' => $data ?? null
		));
	}
	
	public function methodCreated($message = "", $data = null): bool|string
	{
		header('HTTP/1.1 201 Created');
		return json_encode(array(
			'status' => http_response_code(201),
			'msg' => $message,
			'success' => true,
			'data' => $data ?? null
		));
	}
	
	public function methodErrorServer($message = "", $data = null): bool|string
	{
		header('HTTP/1.1 500 Internal Server Error');
		return json_encode(array(
			'status' => http_response_code(500),
			'msg' => $message,
			'success' => false,
			'data' => $data ?? null
		));
	}
}
