<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// RUTE PUBLIK (Bisa diakses siapa saja tanpa login)

// Rute Halaman Utama & Navigasi
$routes->get('/', 'Home\HomePageController::index');
$routes->get('/about', 'Home\HomePageController::about');
$routes->get('/home', 'Home\HomePageController::index');

// Rute Pencarian Publik
$routes->get('/search/process', 'Home\SearchController::process');
$routes->post('/search/process', 'Home\SearchController::process');

// Rute Halaman Daftar & Detail Lowongan Pekerjaan
$routes->get('/jobs', 'Home\JobPageController::index');
$routes->get('/lowongan/detail/(:num)', 'Home\JobPageController::detail/$1');

// Rute Halaman Daftar & Detail Pelatihan
$routes->get('/trainings', 'Home\TrainingPageController::index');
$routes->get('/pelatihan/detail/(:num)', 'Home\TrainingPageController::detail/$1');

// Rute Halaman Daftar & Detail Perusahaan/Vendor
$routes->get('/companies', 'Home\VendorPageController::index');
$routes->get('/vendor', 'Home\VendorPageController::index');
$routes->get('/vendor/detail/(:num)', 'Home\VendorPageController::detail/$1');

// RUTE AUTENTIKASI (Pendaftaran, Login, Logout)
$routes->get('/register', 'Auth\AuthController::register');
$routes->get('/register/jobseeker', 'Auth\AuthController::registerJobseeker');
$routes->get('/register/vendor', 'Auth\AuthController::registerVendor');
$routes->post('/register/process', 'Auth\AuthController::processRegister');
$routes->get('/login', 'Auth\AuthController::login');
$routes->post('/login/process', 'Auth\AuthController::processLogin');
$routes->get('/logout', 'Auth\AuthController::logout');

// RUTE KHUSUS VENDOR (Memerlukan Login & Peran Vendor)
$routes->group('vendor', ['filter' => 'auth'], function ($routes) {
    // Dashboard Vendor
    $routes->get('dashboard', 'Vendor\DashboardController::index');

    // Pengelolaan Profil Vendor
    $routes->get('profile/edit', 'Vendor\ProfileController::edit');
    $routes->post('profile/update', 'Vendor\ProfileController::update');

    // CRUD Lowongan Pekerjaan (Jobs)
    $routes->get('jobs/new', 'Vendor\JobController::newJob');
    $routes->post('jobs/create', 'Vendor\JobController::createJob');
    $routes->get('jobs/edit/(:num)', 'Vendor\JobController::editJob/$1');
    $routes->post('jobs/update/(:num)', 'Vendor\JobController::updateJob/$1');
    $routes->post('jobs/delete/(:num)', 'Vendor\JobController::deleteJob/$1');

    // Pengelolaan Pelamar Lowongan
    $routes->get('jobs/(:num)/applicants', 'Vendor\JobController::showApplicants/$1');
    $routes->post('applicants/(:num)/status', 'Vendor\JobController::updateApplicantStatus/$1');
    $routes->get('jobs/applicant/(:num)', 'Vendor\JobController::showApplicantDetail/$1');

    // CRUD Pelatihan (Trainings)
    $routes->get('trainings/new', 'Vendor\TrainingController::newTraining');
    $routes->post('trainings/create', 'Vendor\TrainingController::createTraining');
    $routes->get('trainings/edit/(:num)', 'Vendor\TrainingController::editTraining/$1');
    $routes->post('trainings/update/(:num)', 'Vendor\TrainingController::updateTraining/$1');
    $routes->post('trainings/delete/(:num)', 'Vendor\TrainingController::deleteTraining/$1');

    // Pengelolaan Peserta Pelatihan
    $routes->get('trainings/(:num)/participants', 'Vendor\TrainingController::showParticipants/$1');
    $routes->post('trainings/participants/(:num)/status', 'Vendor\TrainingController::updateParticipantStatus/$1');

    // Rute untuk download PDF lowongan dan pelatihan
    $routes->get('jobs/(:num)/download-pdf', 'Vendor\JobController::downloadApplicantsPdf/$1');
    $routes->get('trainings/(:num)/download-pdf', 'Vendor\TrainingController::downloadParticipantsPdf/$1');
});


// RUTE KHUSUS JOBSEEKER (Memerlukan Login & Peran Jobseeker)
$routes->group('jobseeker', ['filter' => 'auth'], function ($routes) {
    // Dashboard Jobseeker
    $routes->get('dashboard', 'Jobseeker\DashboardController::index');

    // Pengelolaan Profil Jobseeker
    $routes->get('profile/edit', 'Jobseeker\ProfileController::edit');
    $routes->post('profile/update', 'Jobseeker\ProfileController::update');

    // Riwayat Lamaran & Pelatihan
    $routes->get('history', 'Jobseeker\HistoryController::index');

    // Chatbot
    $routes->get('chatbot', 'Jobseeker\ChatbotController::index');
    $routes->post('chatbot/ask', 'Jobseeker\ChatbotController::ask');
});

// RUTE LAMARAN/PENDAFTARAN (Memerlukan Login, Umumnya Jobseeker)
$routes->group('lamar', ['filter' => 'auth'], function ($routes) {
    $routes->get('job/(:num)', 'Vendor\JobApplicationController::showApplicationForm/$1');
    $routes->post('job/(:num)', 'Vendor\JobApplicationController::submitApplication/$1');
});

$routes->group('daftar-pelatihan', ['filter' => 'auth'], function ($routes) {
    $routes->post('apply/(:num)', 'Vendor\TrainingApplicationController::apply/$1');
});


// RUTE KHUSUS ADMIN (Memerlukan Login & Peran Admin)
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    // Dasbor Admin
    $routes->get('dashboard', 'Admin\DashboardController::index');
});