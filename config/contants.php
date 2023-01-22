<?php

namespace Config\Constants;

define('SECRET_KEY', 'h7I1u;i9!Fs5fdtE9#Bi9!MEVV=3~kxZEi2!F4E#.{O1DN!3JV');

define('ISS', 'http://localhost/blog-api-backend/');

define("AUD", 'http://localhost/');

define('IAT', time());

// $expirationTime = $issuedAt + 60; // jwt valid for 60 seconds from the issued time
define('EXPIRATION_TIME', IAT + (60 * 60));
