<?php

use \Bramus\Router\Router;

// Create Router instance
$router = new Router();

// set controllers
$router->setNamespace('\Controllers');

// Define routes

/**
 * Routes Blogs
 */
$router->get('/api/blogs', 'BlogController@findAll');
$router->get('/api/blogs/{id}', 'BlogController@findById');
$router->post('/api/blogs', 'BlogController@create');
$router->put('/api/blogs/update/{id}', 'BlogController@update');
$router->delete('/api/blogs/delete/{id}', 'BlogController@delete');

/**                                                     
 * *****************************************************
 * *****************************************************
 */

// Run it!
$router->run();
