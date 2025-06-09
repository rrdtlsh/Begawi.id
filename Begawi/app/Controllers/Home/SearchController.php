<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\TrainingModel;
use App\Models\LocationModel;
use App\Models\JobCategoryModel;

class SearchController extends BaseController
{

    public function process()
    {

        $searchType = $this->request->getPost('search_type');
        $keyword = $this->request->getPost('keyword');
        $location = $this->request->getPost('location');
        $category = $this->request->getPost('category');

        $jobModel = new JobModel();
        $trainingModel = new TrainingModel();

        $data = [
            'jobs' => [],
            'trainings' => [],
            'search_terms' => [
                'type' => $searchType,
                'keyword' => $keyword,
                'location' => $location,
                'category' => $category
            ]
        ];

        if ($searchType === 'jobs') {
            $data['title'] = 'Hasil Pencarian Lowongan';
            $data['list_title'] = 'Hasil Pencarian Lowongan';
            $data['jobs'] = $jobModel->searchJobs($keyword, $location, $category);

        } elseif ($searchType === 'trainings') {
            $data['title'] = 'Hasil Pencarian Pelatihan';
            $data['list_title'] = 'Hasil Pencarian Pelatihan';
            $data['trainings'] = $trainingModel->searchTrainings($keyword, $location, $category);
        }

        $locationModel = new LocationModel();
        $categoryModel = new JobCategoryModel();
        $data['locations'] = $locationModel->findAll();
        $data['categories'] = $categoryModel->findAll();

        $data['search_action'] = site_url('search/process');

        return view('guest/search_result_view', $data);
    }
}
