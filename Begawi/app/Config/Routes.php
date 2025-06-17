<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// RUTE PUBLIK (Bisa diakses siapa saja tanpa login)
//
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
$routes->post('/register/process', 'Auth\AuthController::processRegister', ['filter' => 'throttle:10,300']);
$routes->get('/login', 'Auth\AuthController::login');
$routes->post('/login/process', 'Auth\AuthController::processLogin', ['filter' => 'throttle:5,60']);
$routes->get('/logout', 'Auth\AuthController::logout');

// RUTE KHUSUS VENDOR (Memerlukan Login & Peran Vendor)
$routes->group('vendor', ['filter' => 'auth'], function ($routes) {
    // Dashboard Vendor
    $routes->get('dashboard', 'Vendor\DashboardController::index');

    // Pengelolaan Profil Vendor
    $routes->get('profile/edit', 'Vendor\ProfileController::edit');
    $routes->post('profile/update', 'Vendor\ProfileController::update');
    $routes->post('profile/delete', 'Vendor\ProfileController::deleteAccount');

    // CRUD Lowongan Pekerjaan (Jobs)
    $routes->get('jobs/new', 'Vendor\JobController::newJob');
    $routes->post('jobs/create', 'Vendor\JobController::createJob', ['filter' => 'throttle:10,600']);
    $routes->get('jobs/edit/(:num)', 'Vendor\JobController::editJob/$1');
    $routes->post('jobs/update/(:num)', 'Vendor\JobController::updateJob/$1', ['filter' => 'throttle:10,600']);
    $routes->post('jobs/delete/(:num)', 'Vendor\JobController::deleteJob/$1');

    // Pengelolaan Pelamar Lowongan
    $routes->get('jobs/(:num)/applicants', 'Vendor\JobController::showApplicants/$1');
    $routes->post('applicants/(:num)/status', 'Vendor\JobController::updateApplicantStatus/$1');
    $routes->get('jobs/applicant/(:num)', 'Vendor\JobController::showApplicantDetail/$1');

    // CRUD Pelatihan (Trainings)
    $routes->get('trainings/new', 'Vendor\TrainingController::newTraining');
    $routes->post('trainings/create', 'Vendor\TrainingController::createTraining', ['filter' => 'throttle:10,600']);
    $routes->get('trainings/edit/(:num)', 'Vendor\TrainingController::editTraining/$1');
    $routes->post('trainings/update/(:num)', 'Vendor\TrainingController::updateTraining/$1', ['filter' => 'throttle:10,600']);
    $routes->post('trainings/delete/(:num)', 'Vendor\TrainingController::deleteTraining/$1');

    // Pengelolaan Peserta Pelatihan
    $routes->get('trainings/(:num)/participants', 'Vendor\TrainingController::showParticipants/$1');
    $routes->post('trainings/participants/(:num)/status', 'Vendor\TrainingController::updateParticipantStatus/$1');

    // Rute untuk download PDF lowongan dan pelatihan
    $routes->get('jobs/(:num)/download-pdf', 'Vendor\JobController::downloadApplicantsPdf/$1');
    $routes->get('trainings/(:num)/download-pdf', 'Vendor\TrainingController::downloadParticipantsPdf/$1');

    // Rute untuk download laporan Excel lowongan dan pelatihan
    $routes->get('jobs/(:num)/download-excel', 'Vendor\JobController::downloadApplicantsExcel/$1');
    $routes->get('trainings/(:num)/download-excel', 'Vendor\TrainingController::downloadParticipantsExcel/$1');
});


// RUTE KHUSUS JOBSEEKER (Memerlukan Login & Peran Jobseeker)
$routes->group('jobseeker', ['filter' => 'auth'], function ($routes) {
    // Dashboard Jobseeker
    $routes->get('dashboard', 'Jobseeker\DashboardController::index');

    // Pengelolaan Profil Jobseeker
    $routes->get('profile/edit', 'Jobseeker\ProfileController::edit');
    $routes->post('profile/update', 'Jobseeker\ProfileController::update');
    $routes->post('profile/delete', 'Jobseeker\ProfileController::deleteAccount');

    // Riwayat Lamaran & Pelatihan
    $routes->get('history', 'Jobseeker\HistoryController::index');

    // Chatbot
    $routes->get('chatbot', 'Jobseeker\ChatbotController::index');
    $routes->post('chatbot/ask', 'Jobseeker\ChatbotController::ask');

    $routes->get('applications/edit/(:num)', 'Jobseeker\JobApplicationController::edit/$1');
    $routes->post('applications/update/(:num)', 'Jobseeker\JobApplicationController::update/$1');
    $routes->post('applications/delete/(:num)', 'Jobseeker\JobApplicationController::delete/$1');

    $routes->post('trainings/delete-enrollment/(:num)', 'Jobseeker\TrainingApplicationController::deleteEnrollment/$1');
});

// RUTE LAMARAN/PENDAFTARAN (Memerlukan Login, Umumnya Jobseeker)
$routes->group('lamar', ['filter' => 'auth'], function ($routes) {
    $routes->get('job/(:num)', 'Jobseeker\JobApplicationController::showApplicationForm/$1');
    $routes->post('job/(:num)', 'Jobseeker\JobApplicationController::submitApplication/$1', ['filter' => 'throttle:15,300']);
});

$routes->group('daftar-pelatihan', ['filter' => 'auth'], function ($routes) {
    $routes->post('apply/(:num)', 'Jobseeker\TrainingApplicationController::apply/$1', ['filter' => 'throttle:15,300']);
});


// RUTE KHUSUS ADMIN (Memerlukan Login & Peran Admin)
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    // Dasbor Admin
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Rute BARU untuk halaman master data 
    $routes->get('master-data', 'Admin\MasterDataController::index', ['as' => 'admin.master-data.index']);

    // Rute untuk Kategori
    $routes->get('job-categories/new', 'Admin\JobCategoryController::new', ['as' => 'admin.job-categories.new']);
    $routes->post('job-categories/create', 'Admin\JobCategoryController::create', ['as' => 'admin.job-categories.create']);
    $routes->get('job-categories/edit/(:num)', 'Admin\JobCategoryController::edit/$1', ['as' => 'admin.job-categories.edit']);
    $routes->post('job-categories/update/(:num)', 'Admin\JobCategoryController::update/$1', ['as' => 'admin.job-categories.update']);
    $routes->post('job-categories/delete/(:num)', 'Admin\JobCategoryController::delete/$1', ['as' => 'admin.job-categories.delete']);

    // Rute untuk Skills
    $routes->get('skills/new', 'Admin\SkillController::new', ['as' => 'admin.skills.new']);
    $routes->post('skills/create', 'Admin\SkillController::create', ['as' => 'admin.skills.create']);
    $routes->get('skills/edit/(:num)', 'Admin\SkillController::edit/$1', ['as' => 'admin.skills.edit']);
    $routes->post('skills/update/(:num)', 'Admin\SkillController::update/$1', ['as' => 'admin.skills.update']);
    $routes->post('skills/delete/(:num)', 'Admin\SkillController::delete/$1', ['as' => 'admin.skills.delete']);

    // Rute untuk Locations
    $routes->get('locations/new', 'Admin\LocationController::new', ['as' => 'admin.locations.new']);
    $routes->post('locations/create', 'Admin\LocationController::create', ['as' => 'admin.locations.create']);
    $routes->get('locations/edit/(:num)', 'Admin\LocationController::edit/$1', ['as' => 'admin.locations.edit']);
    $routes->post('locations/update/(:num)', 'Admin\LocationController::update/$1', ['as' => 'admin.locations.update']);
    $routes->post('locations/delete/(:num)', 'Admin\LocationController::delete/$1', ['as' => 'admin.locations.delete']);

    // rute untuk menginport menggunakan excel
    $routes->get('locations/import', 'Admin\LocationController::showImportForm', ['as' => 'admin.locations.import']);
    $routes->post('locations/import', 'Admin\LocationController::processImport');

    $routes->get('skills/import', 'Admin\SkillController::showImportForm', ['as' => 'admin.skills.import']);
    $routes->post('skills/import', 'Admin\SkillController::processImport');

    $routes->get('job-categories/import', 'Admin\JobCategoryController::showImportForm', ['as' => 'admin.job-categories.import']);
    $routes->post('job-categories/import', 'Admin\JobCategoryController::processImport');

});