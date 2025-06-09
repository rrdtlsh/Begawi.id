<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\TrainingModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $jobModel = new JobModel();
        $trainingModel = new TrainingModel();

        $total_users = $userModel->countAllResults();
        $total_vendors = $userModel->where('role', 'vendor')->countAllResults();
        $total_jobseekers = $userModel->where('role', 'jobseeker')->countAllResults();

        $total_active_jobs = $jobModel->where('application_deadline >', date('Y-m-d H:i:s'))
            ->countAllResults();

        $total_trainings = $trainingModel->countAllResults();

        $data = [
            'title' => 'Dashboard Admin',
            'total_users' => $total_users,
            'total_vendors' => $total_vendors,
            'total_jobseekers' => $total_jobseekers,
            'total_active_jobs' => $total_active_jobs,
            'total_trainings' => $total_trainings,
        ];

        return view('admin/dashboard/index', $data);
    }
}