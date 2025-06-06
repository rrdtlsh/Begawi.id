<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Halaman utama
$routes->get('/', 'HomeController::index');


// --- Routes untuk Autentikasi ---
// Halaman pilihan peran
$routes->get('/register', 'AuthController::register');

// Halaman form spesifik berdasarkan peran
$routes->get('/register/jobseeker', 'AuthController::registerJobseeker');
$routes->get('/register/vendor', 'AuthController::registerVendor');

// Endpoint untuk memproses data dari kedua form di atas
$routes->post('/register/process', 'AuthController::processRegister');

// Rute untuk login dan logout
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');


// --- Routes untuk Area Vendor (Memerlukan Login) ---
$routes->group('vendor', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Vendor\DashboardController::index');
    // Rute untuk CRUD Jobs akan ditambahkan di sini nanti
});


// --- Routes untuk Area Jobseeker (Memerlukan Login) ---
$routes->group('jobseeker', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Jobseeker\DashboardController::index');
});

// Rute ini tidak lagi diperlukan untuk alur registrasi sekarang,
// tapi bisa digunakan untuk halaman "Edit Profil" nanti.
// Untuk sementara bisa dikomentari jika belum dibuat.
/*
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('complete', 'Jobseeker\ProfileController::complete');
    $routes->post('update', 'Jobseeker\ProfileController::update');
});
*/