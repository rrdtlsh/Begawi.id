<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\JobCategoryModel;
use App\Models\LocationModel;
use App\Models\TrainingModel;

class HomePageController extends BaseController
{
    public function index()
    {

        $jobModel = new JobModel();
        $categoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();
        $trainingModel = new TrainingModel();

        $data = [
            'title' => 'Selamat Datang di Begawi',
            'categories' => $categoryModel->findAll(),
            'locations' => $locationModel->findAll(),
            'search_terms' => null,
            'trainings' => $trainingModel->getLatestTrainings(3),
        ];

        if ($this->request->getMethod() === 'post') {
            $keyword = $this->request->getPost('keyword');
            $location = $this->request->getPost('location');
            $category = $this->request->getPost('category');

            $data['jobs'] = $jobModel->searchJobs($keyword, $location, $category);
            $data['search_terms'] = ['keyword' => $keyword, 'location' => $location, 'category' => $category];
            $data['list_title'] = 'Hasil Pencarian';
        } else {
            $data['jobs'] = $jobModel->getLatestJobs(6);
            $data['list_title'] = 'Lowongan Terbaru';
        }
        return view('guest/home', $data);

    }

    public function about()
    {
        $data = [
            'title' => 'Tentang Begawi'
        ];

        return view('guest/about_us_view', $data);
    }
}