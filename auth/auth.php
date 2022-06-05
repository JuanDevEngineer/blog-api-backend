<?php

use Firebase\JWT\JWT;

class AuthJWT {

    private static $private_key = 'S3CR3T_K3Y_PRIV@TE';
    private static $public_key = 'S3CR3T_K3Y_P0BL1C';
    private static $encrypt = ['HS256'];
    protected $iss = 'localhost:80';
    protected $sub = 'example@example.com.co';
    protected $aud = 'localhost:80/api/v1';
    protected $payload = [];

    public function __construct($claims = [], $uid)
    {
        $this->payload = [
            "iss" => $this->iss,
            "sub" => $this->sub,
            "aud" => $this->aud,
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "uid" => $uid,
            "claims" => [$claims]
        ];
    }

    public function encodeJwt()
    {
        return JWT::encode($this->payload, self::$private_key);
    }

    public function decodeJwt($token)
    {
        return (array) JWT::decode($token, self::$public_key, self::$encrypt);
    }

    public function checkToken($token)
    {
        if(empty($token)) {
            throw new Exception("Invalid user logged in");
        }

        $decodeToken = $this->decodeJwt($token);

        if($decodeToken['aud'] != $this->aud) {
            throw new Exception("Invalid user logged in");
        }

    }

    public function getUser($token)
    {
        return $this->decodeJwt($token)['sub'];
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }

}

// $auth = new AuthJWT();
// var_dump($auth->encodeJwt());