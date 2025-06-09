<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\VendorModel;
use App\Models\JobModel;
use App\Models\TrainingModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (session()->get('role') !== 'vendor') {
            return redirect()->to('/');
        }

        $vendorModel = new VendorModel();
        $jobModel = new JobModel();
        $trainingModel = new TrainingModel();

        $userId = session()->get('user_id');
        $vendorId = session()->get('profile_id');

        $vendorProfile = $vendorModel->getVendorProfileByUserId($userId);

        $latestJobs = $jobModel->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        $latestTrainings = $trainingModel->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        $data = [
            'title' => 'Beranda Vendor',
            'vendor' => $vendorProfile,
            'jobs' => $latestJobs,
            'trainings' => $latestTrainings,
        ];

        return view('vendor/profile/dashboard', $data);
    }
}