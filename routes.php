<?php

use \Bramus\Router\Router;

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
// $router->before('GET|POST|PUT|DELETE', '/api/.*', function () {
//   if (!isset($_SESSION['user'])) {
//     header('http/1.0 401 unauthorized');
//     // header('HTTP/1.1 403 Forbidden');
//     header('Content-Type: application/json');
//     echo json_encode([
//       'status' => 403,
//       'statusMessages' => 'Access denied',
//       'msg' => 'User not authenticated'
//     ]);
//     exit();
//   }
// });

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
