<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Halaman utama
$routes->get('/', 'HomeController::index');

$routes->post('/search/jobs', 'SearchController::jobs');

// --- Routes untuk Autentikasi ---
$routes->get('/register', 'AuthController::register');
$routes->get('/register/jobseeker', 'AuthController::registerJobseeker');
$routes->get('/register/vendor', 'AuthController::registerVendor');
$routes->post('/register/process', 'AuthController::processRegister');
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');


// --- Routes untuk Area Vendor (Memerlukan Login) ---
// app/Config/Routes.php

// --- Routes untuk Area Vendor (Memerlukan Login) ---
$routes->group('vendor', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Vendor\DashboardController::index');

    $routes->get('jobs', 'Vendor\JobController::index');

    $routes->get('jobs/new', 'Vendor\JobController::newJob');

    $routes->post('jobs/create', 'Vendor\JobController::createJob');

    $routes->get('jobs/edit/(:num)', 'Vendor\JobController::editJob/$1');

    $routes->post('jobs/update/(:num)', 'Vendor\JobController::updateJob/$1');

    $routes->get('jobs/delete/(:num)', 'Vendor\JobController::deleteJob/$1');
});

// app/Config/Routes.php

$routes->group('vendor', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Vendor\DashboardController::index');

    // --- Rute CRUD untuk Lowongan (Jobs) ---
    $routes->get('jobs', 'Vendor\JobController::index');
    $routes->get('jobs/new', 'Vendor\JobController::newJob');
    // ... dan seterusnya untuk jobs ...
    $routes->get('jobs/delete/(:num)', 'Vendor\JobController::deleteJob/$1');

    // --- RUTE BARU UNTUK CRUD PELATIHAN (TRAININGS) ---
    $routes->get('trainings', 'Vendor\TrainingController::index');
    $routes->get('trainings/new', 'Vendor\TrainingController::newTraining');
    $routes->post('trainings/create', 'Vendor\TrainingController::createTraining');
    $routes->get('trainings/edit/(:num)', 'Vendor\TrainingController::editTraining/$1');
    $routes->post('trainings/update/(:num)', 'Vendor\TrainingController::updateTraining/$1');
    $routes->get('trainings/delete/(:num)', 'Vendor\TrainingController::deleteTraining/$1');
});

// --- Routes untuk Area Jobseeker (Memerlukan Login) ---
$routes->group('jobseeker', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Jobseeker\DashboardController::index');
});