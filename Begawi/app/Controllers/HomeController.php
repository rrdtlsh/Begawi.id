<?php

namespace App\Controllers;

// 1. Pastikan semua model yang dibutuhkan sudah dipanggil
use App\Models\JobModel;
use App\Models\JobCategoryModel;
use App\Models\LocationModel;
use App\Models\TrainingModel;

class HomeController extends BaseController
{
    public function index()
    {
        // 2. Buat instance dari setiap model
        $jobModel = new JobModel();
        $categoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();
        $trainingModel = new TrainingModel();

        // 3. Siapkan data yang selalu ada di halaman utama
        $data = [
            'title'        => 'Selamat Datang di Begawi',
            'categories'   => $categoryModel->findAll(),
            'locations'    => $locationModel->findAll(),
            'search_terms' => null,
            'training' => $trainingModel->getLatestTrainings(3), 
        ];

        // 4. Logika untuk mengisi data 'jobs' dan 'list_title'
        if ($this->request->getMethod() === 'post') {
            // Jika ada PENCARIAN (metode POST)
            $keyword  = $this->request->getPost('keyword');
            $location = $this->request->getPost('location');
            $category = $this->request->getPost('category');
            
            $data['jobs'] = $jobModel->searchJobs($keyword, $location, $category);
            $data['search_terms'] = ['keyword' => $keyword, 'location' => $location, 'category' => $category];
            $data['list_title'] = 'Hasil Pencarian'; // Judul untuk hasil pencarian
        } else {
            // Jika hanya membuka halaman (metode GET)
            $data['jobs'] = $jobModel->getLatestJobs(6);
            $data['list_title'] = 'Lowongan Terbaru'; // Judul untuk halaman utama
        }
        return view('home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'Tentang Begawi'
        ];

        return view('about_us_view', $data);
    }
}
