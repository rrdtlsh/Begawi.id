<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===================================================================
// RUTE PUBLIK (Bisa diakses siapa saja)
// ===================================================================

// ---- route navbar ---
$routes->get('/', 'HomeController::index');
$routes->get('/about', 'HomeController::about');
$routes->post('/search/process', 'SearchController::process');

$routes->get('/jobs', 'JobPageController::index'); 
$routes->get('/trainings', 'TrainingPageController::index');
$routes->get('/companies', 'VendorPageController::index');
// ---Rute tombol kembali
$routes->get('/home', 'HomeController::index'); 

$routes->post('/lowongan', 'JobPageController::index'); // Menangani filter/pencarian
$routes->get('/lowongan/detail/(:num)', 'JobPageController::detail/$1');

// Rute untuk Halaman Daftar & Detail Pelatihan
$routes->post('/pelatihan', 'TrainingPageController::index');
$routes->get('/pelatihan/detail/(:num)', 'TrainingPageController::detail/$1'); // Aktifkan rute detail pelatihan// Menangani filter/pencarian
$routes->get('/pelatihan/daftar/(:num)', 'TrainingApplicationController::processApplication/$1');

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

//rute untuk halaman perusahaann
$routes->get('/vendor', 'VendorPageController::index');
$routes->get('/vendor/detail/(:num)', 'VendorPageController::detail/$1');

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
    $routes->get('jobs/new', 'Vendor\JobController::newJob');
    $routes->post('jobs/create', 'Vendor\JobController::createJob');
    $routes->get('jobs/edit/(:num)', 'Vendor\JobController::editJob/$1');
    $routes->post('jobs/update/(:num)', 'Vendor\JobController::updateJob/$1');
    $routes->post('jobs/delete/(:num)', 'Vendor\JobController::deleteJob/$1');
    $routes->get('jobs/(:num)/applicants', 'Vendor\JobController::showApplicants/$1');
    $routes->post('applicants/(:num)/status', 'Vendor\JobController::updateApplicantStatus/$1');
    $routes->get('jobs/applicant/(:num)', 'Vendor\JobController::showApplicantDetail/$1');

    // CRUD Pelatihan (Trainings)
    $routes->get('trainings/new', 'Vendor\TrainingController::newTraining');
    $routes->post('trainings/create', 'Vendor\TrainingController::createTraining');
    $routes->get('trainings/edit/(:num)', 'Vendor\TrainingController::editTraining/$1');
    $routes->post('trainings/update/(:num)', 'Vendor\TrainingController::updateTraining/$1');
    $routes->post('trainings/delete/(:num)', 'Vendor\TrainingController::deleteTraining/$1');
    $routes->get('trainings/(:num)/participants', 'Vendor\TrainingController::showParticipants/$1');
    $routes->post('trainings/participants/(:num)/status', 'Vendor\TrainingController::updateParticipantStatus/$1');

    // email
    $routes->get('jobs/applicants/(:num)', 'Vendor\JobController::showApplicants/$1');
    $routes->post('applicants/status/(:num)', 'Vendor\JobController::updateApplicantStatus/$1');
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

    $routes->get('chatbot', 'Jobseeker\ChatbotController::index');
    $routes->post('chatbot/ask', 'Jobseeker\ChatbotController::ask');

});

// --- RUTE LAMARAN PEKERJAAN (Memerlukan Login) ---
$routes->group('lamar', ['filter' => 'auth'], function ($routes) {
    $routes->get('job/(:num)', 'JobApplicationController::showApplicationForm/$1');
    $routes->post('job/(:num)', 'JobApplicationController::submitApplication/$1');
});

$routes->group('daftar-pelatihan', ['filter' => 'auth'], function ($routes) {
    // Akan menangani POST request ke /daftar-pelatihan/apply/2 (contoh)
    $routes->post('apply/(:num)', 'TrainingApplicationController::apply/$1');
});

