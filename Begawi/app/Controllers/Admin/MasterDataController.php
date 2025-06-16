<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JobCategoryModel;
use App\Models\SkillModel;
use App\Models\LocationModel;

class MasterDataController extends BaseController
{

    public function index()
    {
        $categoryModel = new JobCategoryModel();
        $skillModel = new SkillModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Manajemen Data Master',
            'categories' => $categoryModel->orderBy('id', 'ASC')->findAll(),
            'skills' => $skillModel->orderBy('id', 'ASC')->findAll(),
            'locations' => $locationModel->orderBy('id', 'ASC')->findAll(),
        ];

        return view('admin/master_data/index', $data);


    }
}