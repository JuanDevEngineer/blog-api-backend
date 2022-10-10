<?php

use \Bramus\Router\Router;
use Controllers\AppController;
use \Services\JwtService;

// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("X-Requested-With: XMLHttpRequest");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Create Router instance
$router = new Router();

// set controllers
$router->setNamespace('\Controllers');

/**
 * Routes Auth
 */
$router->post('/auth/sign-in', 'AuthController@login');
$router->post('/auth/sign-up', 'AuthController@register');

// Define routes middleware
$router->before('GET|POST|PUT|PATCH|DELETE', '/api/.*', function () {
  header('Content-Type: application/json');
  $response = new AppController();

  if (JwtService::getToken() == NULL) {
    echo $response->methodUnauthorized("Token not found in request");
    die();
  }

  $token = new JwtService();
  $token->verifyToken();
});

/**
 * Routes Blogs
 */
$router->get('/api/blogs', 'BlogController@findAll');
$router->get('/api/blogs/{id}', 'BlogController@findById');
$router->post('/api/blogs', 'BlogController@create');
$router->put('/api/blogs/update/{id}', 'BlogController@update');
$router->delete('/api/blogs/delete/{id}', 'BlogController@delete');

/**
 * Routes Categories
 */
$router->post('/api/categories', 'CategoryController@create');
$router->get('/api/categories', 'CategoryController@findAll');
$router->get('/api/categories/{id}', 'CategoryController@findById');
$router->put('/api/categories/{id}', 'CategoryController@update');
$router->delete('/api/categories/{id}', 'CategoryController@delete');

/**
 * Routes Users
 */
$router->post('/api/users', 'UserController@create');
$router->get('/api/users', 'UserController@findAll');
$router->get('/api/users/{id}', 'UserController@findById');
$router->put('/api/users/{id}/update', 'UserController@update');
$router->delete('/api/users/{id}/delete', 'UserController@delete');

/**
 * Routes Users
 */
$router->post('/api/roles', 'RoleController@create');
$router->get('/api/roles', 'RoleController@findAll');
$router->get('/api/roles/{id}', 'RoleController@findById');
$router->put('/api/roles/{id}/update', 'RoleController@update');
$router->delete('/api/roles/{id}/delete', 'RoleController@delete');

/**                                                     
 * *****************************************************
 * *****************************************************
 */
$router->set404('/api(/.*)?', function () {
  header('HTTP/1.1 404 Not Found');
  header('Content-Type: application/json');

  $jsonArray = array();
  $jsonArray['status'] = "404";
  $jsonArray['status_text'] = "Route not defined";

  echo json_encode($jsonArray);
});

// Run it!
$router->run();
