<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// app/Config/Routes.php

// Routes untuk Autentikasi
$routes->get('/register', 'AuthController::register');
$routes->post('/register/save', 'AuthController::saveRegister');
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');

// ...