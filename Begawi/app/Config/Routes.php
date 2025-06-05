<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');

// --- RUTE UNTUK FITUR REGISTRASI & LOGIN ---
$routes->get('/register', 'AuthController::register');
$routes->post('/register/process', 'AuthController::processRegister');
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');

// Rute untuk halaman selanjutnya setelah login (bisa dikomentari dulu jika belum dibuat)
/*$routes->group('vendor', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Vendor\DashboardController::index');
});
$routes->group('jobseeker', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Jobseeker\DashboardController::index');
});
$routes->group('profile', ['filter' => 'auth'], function ($routes) {
    $routes->get('complete', 'Jobseeker\ProfileController::complete');
    $routes->post('update', 'Jobseeker\ProfileController::update');
}); */