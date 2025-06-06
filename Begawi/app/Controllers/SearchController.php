<?php
// File: app/Controllers/SearchController.php

namespace App\Controllers;

use App\Models\JobModel;

class SearchController extends BaseController
{
    /**
     * Menangani pencarian untuk lowongan pekerjaan (jobs) via POST
     */
    public function jobs()
    {
        // 1. Ambil input dari form menggunakan metode POST
        $keyword  = $this->request->getPost('keyword');
        $location = $this->request->getPost('location');
        $category = $this->request->getPost('category');

        $jobModel = new JobModel();

        // 2. Minta model untuk mencari pekerjaan
        // Method di JobModel tetap sama, tidak perlu diubah
        $jobs = $jobModel->searchJobs($keyword, $location, $category);

        $data = [
            'title' => 'Hasil Pencarian Lowongan',
            'jobs'  => $jobs,
            'search_terms' => ['keyword' => $keyword, 'location' => $location, 'category' => $category]
        ];

        // 3. Tampilkan hasilnya di view yang sama
        return view('search_result_view', $data);
    }
}