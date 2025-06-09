<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JobModel;  // Panggil JobModel Anda
use app\Models\TrainingModel; // Panggil VendorModel Anda
use App\Models\UserModel; // Panggil UserModel Anda

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $jobModel = new JobModel();
        $trainingModel = new TrainingModel();


        $total_active_jobs = $jobModel->where('application_deadline >', date('Y-m-d H:i:s'))
            ->countAllResults();

        $data = [
            'title' => 'Dashboard Admin',
            'total_users' => $userModel->countAllResults(),
            'total_vendors' => $userModel->where('role', 'vendor')->countAllResults(),
            'total_jobseekers' => $userModel->where('role', 'jobseeker')->countAllResults(),
            'total_active_jobs' => $total_active_jobs,
        ];

        // Kirim data ke view
        return view('admin/dashboard/index', $data);
    }
}