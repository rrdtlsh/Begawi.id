<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// PERBAIKI DI SINI
use App\Models\JobModel; // <-- Nama yang benar
use App\Models\JobCategoryModel;

class HomeController extends BaseController
{
    public function index()
    {
        // PERBAIKI DI SINI
        $jobModel = new JobModel(); // <-- Nama yang benar
        $categoryModel = new JobCategoryModel();

        $data = [
            'title' => 'Selamat Datang di Begawi',
            'categories' => $categoryModel->findAll(),
            'jobs' => $jobModel->getJobsWithDetails()->orderBy('jobs.created_at', 'DESC')->findAll(10)
        ];

        return view('home', $data);
    }
}