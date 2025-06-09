<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\TrainingModel;
use App\Models\LocationModel;
use App\Models\JobCategoryModel;

class SearchController extends BaseController
{
    /**
     * Memproses semua jenis pencarian dari satu form.
     */
    public function process()
    {
        // 1. Ambil semua input dari form, termasuk tipe pencarian
        $searchType = $this->request->getPost('search_type');
        $keyword    = $this->request->getPost('keyword');
        $location   = $this->request->getPost('location');
        $category   = $this->request->getPost('category');

        $jobModel      = new JobModel();
        $trainingModel = new TrainingModel();
        
        // Siapkan variabel data untuk dikirim ke view
        $data = [
            'jobs'         => [], // default array kosong
            'trainings'    => [], // default array kosong
            'search_terms' => [ // Untuk menampilkan kembali apa yang dicari
                'type'     => $searchType,
                'keyword'  => $keyword, 
                'location' => $location, 
                'category' => $category
            ]
        ];

        // 2. Logika IF/ELSEIF untuk menentukan apa yang dicari berdasarkan dropdown
        if ($searchType === 'jobs') {
            $data['title']      = 'Hasil Pencarian Lowongan';
            $data['list_title'] = 'Hasil Pencarian Lowongan';
            $data['jobs']       = $jobModel->searchJobs($keyword, $location, $category);

        } elseif ($searchType === 'trainings') {
            $data['title']      = 'Hasil Pencarian Pelatihan';
            $data['list_title'] = 'Hasil Pencarian Pelatihan';
            $data['trainings']  = $trainingModel->searchTrainings($keyword, $location, $category);
        }

        // 3. Muat data tambahan yang mungkin dibutuhkan oleh view hasil pencarian
        //    (misalnya, untuk mengisi ulang dropdown di halaman hasil)
        $locationModel = new LocationModel();
        $categoryModel = new JobCategoryModel();
        $data['locations'] = $locationModel->findAll();
        $data['categories'] = $categoryModel->findAll();
        
        // Set action untuk form di halaman hasil, agar pencarian selanjutnya benar
        $data['search_action'] = site_url('search/process');

        // 4. Tampilkan hasilnya di view search_result_view
        return view('search_result_view', $data);
    }
}
