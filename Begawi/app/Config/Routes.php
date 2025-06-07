<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===================================================================
// RUTE PUBLIK (Bisa diakses siapa saja)
// ===================================================================
$routes->get('/', 'HomeController::index');
$routes->get('/tentang-kami', 'HomeController::about');

// Rute untuk Halaman Daftar & Detail Lowongan (Pekerjaan)
$routes->get('/lowongan', 'JobPageController::index');
$routes->post('/lowongan', 'JobPageController::index'); // Menangani filter/pencarian
$routes->get('/lowongan/detail/(:num)', 'JobPageController::detail/$1');

// Rute untuk Halaman Daftar & Detail Pelatihan
$routes->get('/pelatihan', 'TrainingPageController::index');
$routes->post('/pelatihan', 'TrainingPageController::index'); // Menangani filter/pencarian
// $routes->get('/pelatihan/detail/(:num)', 'TrainingPageController::detail/$1'); // (Ini bisa ditambahkan nanti)


// ===================================================================
// RUTE AUTENTIKASI (Login, Register, Logout)
// ===================================================================
$routes->get('/register', 'AuthController::register'); // Halaman pilihan peran
$routes->get('/register/jobseeker', 'AuthController::registerJobseeker'); // Form jobseeker
$routes->get('/register/vendor', 'AuthController::registerVendor');     // Form vendor
$routes->post('/register/process', 'AuthController::processRegister'); // Proses pendaftaran
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');


// ===================================================================
// RUTE UMUM YANG MEMERLUKAN LOGIN (Filter 'auth')
// ===================================================================
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Rute untuk aksi bookmark (hanya jobseeker yang bisa)
    $routes->post('bookmark/toggle', 'BookmarkController::toggle');
});


// ===================================================================
// RUTE KHUSUS VENDOR (Memerlukan Login & Peran Vendor)
// ===================================================================
$routes->group('vendor', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Vendor\DashboardController::index');

    // Profil Vendor
    $routes->get('profile/edit', 'Vendor\ProfileController::edit');
    $routes->post('profile/update', 'Vendor\ProfileController::update');

    // CRUD Lowongan Pekerjaan (Jobs)
    $routes->get('jobs', 'Vendor\JobController::index');
    $routes->get('jobs/new', 'Vendor\JobController::newJob');
    $routes->post('jobs/create', 'Vendor\JobController::createJob');
    $routes->get('jobs/edit/(:num)', 'Vendor\JobController::editJob/$1');
    $routes->post('jobs/update/(:num)', 'Vendor\JobController::updateJob/$1');
    $routes->get('jobs/delete/(:num)', 'Vendor\JobController::deleteJob/$1');

    // CRUD Pelatihan (Trainings)
    $routes->get('trainings', 'Vendor\TrainingController::index');
    $routes->get('trainings/new', 'Vendor\TrainingController::newTraining');
    $routes->post('trainings/create', 'Vendor\TrainingController::createTraining');
    $routes->get('trainings/edit/(:num)', 'Vendor\TrainingController::editTraining/$1');
    $routes->post('trainings/update/(:num)', 'Vendor\TrainingController::updateTraining/$1');
    $routes->get('trainings/delete/(:num)', 'Vendor\TrainingController::deleteTraining/$1');
});


// ===================================================================
// RUTE KHUSUS JOBSEEKER (Memerlukan Login & Peran Jobseeker)
// ===================================================================
$routes->group('jobseeker', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Jobseeker\DashboardController::index');

    // Profil Jobseeker
    $routes->get('profile/edit', 'Jobseeker\ProfileController::edit');
    $routes->post('profile/update', 'Jobseeker\ProfileController::update');

    // Riwayat Lamaran & Pelatihan
    $routes->get('history', 'Jobseeker\HistoryController::index');
});

// --- RUTE LAMARAN PEKERJAAN (Memerlukan Login) ---
$routes->group('lamar', ['filter' => 'auth'], function ($routes) {
    $routes->get('job/(:num)', 'JobApplicationController::showApplicationForm/$1');
    $routes->post('job/(:num)', 'JobApplicationController::submitApplication/$1');
});