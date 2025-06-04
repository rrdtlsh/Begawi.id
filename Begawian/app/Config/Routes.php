<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('job/detail/(:num)', 'Jobs::detail/$1');
$routes->get('/register', 'Auth::register');