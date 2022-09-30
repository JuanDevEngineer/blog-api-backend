<?php

// define set timezone
date_default_timezone_set('America/Bogota');

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

// load variables enviroment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once('routes.php');
