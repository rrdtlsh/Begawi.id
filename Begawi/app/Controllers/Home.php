<?php

namespace App\Controllers;

use App\Models\JobsModel;

class Home extends BaseController
{
    protected $jobsModel;

    public function __construct()
    {
        $this->jobsModel = new JobsModel();
    }

    public function index()
    {
        $data['jobs'] = $this->jobsModel->findAll();
        return view('guest_homescreen', $data);
    }
}
