<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JobModel; // <-- Nama yang benar
use App\Models\JobCategoryModel;

class HomeController extends BaseController
{
    public function index()
    {
        // PERBAIKI DI SINI
        $jobModel = new JobModel(); 
        $categoryModel = new JobCategoryModel();
        $locationModel = model('LocationModel'); 

        $data = [
            'title' => 'Selamat Datang di Begawi',
            'categories' => $categoryModel->findAll(),
            'jobs' => $jobModel->getJobsWithDetails()->orderBy('jobs.created_at', 'DESC')->findAll(10),
            'locations' => $locationModel->findAll()// Pastikan $locationModel sudah didefinisikan
        ];

        return view('home', $data);
    }
}