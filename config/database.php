<?php

namespace Config;

use PDO;

class Database
{
	private mixed $host;
	private mixed $db;
	private mixed $user;
	private mixed $pass;
	private mixed $charset;
	private $opt = null;
	private $dsn = null;
	private $connection = null;
	private static $database = null;
	
	/* Private construct that can only be accessed from within this class */
	private function __construct()
	{
		$this->host = $_ENV["DATA_BASE_HOST"] ?? "localhost:33065";
		$this->db = $_ENV["DATA_BASE_DB"] ?? "blog_react_php";
		$this->user = $_ENV["DATA_BASE_USER"] ?? "root";
		$this->pass = $_ENV["DATA_BASE_PASS"] ?? "";
		$this->charset = $_ENV["DATA_BASE_CHARSET"] ?? "utf8";
		
		$this->createConnection();
	}
	
	private function createConnection(): void
	{
		$this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
		$this->opt = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		];
		
		$this->connection = new PDO($this->dsn, $this->user, $this->pass, $this->opt);
	}
	
	/* A static method that will create an object instance once and after that it will reuse the same instance for all other requests */
	public static function getInstance(): Database
	{
		if (self::$database == null) {
			self::$database = new Database();
		}
		return self::$database;
	}
	
	/* A little getter function to access the connection object */
	public function getPool(): PDO
	{
		return $this->connection;
	}
}
