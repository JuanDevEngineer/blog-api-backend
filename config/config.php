<?php

namespace Config;

class Config
{
	const ISS = 'http://localhost/blog-api-backend/';
	
	const AUD = 'http://localhost/';
	
	const SECRET_KEY = 'h7I1u;i9!Fs5fdtE9#Bi9!MEVV=3~kxZEi2!F4E#.{O1DN!3JV';
	
	const HASH_KEY = 'HS256';
	
	const EXPIRATION_TIME = (60 * 60);
	
	const PUBLIC_PATH = 'public/uploads/blogs/';
	
	public function __construct()
  {
  }
	
	public static function iat(): int {
		return time();
	}
}
