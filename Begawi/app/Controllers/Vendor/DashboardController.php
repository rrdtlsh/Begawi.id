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
        // Pengecekan keamanan
        if (session()->get('role') !== 'vendor') {
            return redirect()->to('/');
        }

        $vendorModel = new VendorModel();
        $jobModel = new JobModel();
        $trainingModel = new TrainingModel();

        $userId = session()->get('user_id');
        $vendorId = session()->get('profile_id');

        // 1. Mengambil data profil lengkap
        $vendorProfile = $vendorModel->getVendorProfileByUserId($userId);

        // 2. Mengambil 5 lowongan pekerjaan terakhir
        $latestJobs = $jobModel->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        // 3. Mengambil 5 pelatihan terakhir
        $latestTrainings = $trainingModel->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        // 4. Menyiapkan semua data untuk dikirim ke view
        $data = [
            'title' => 'Beranda Vendor',
            'vendor' => $vendorProfile,
            'jobs' => $latestJobs,
            'trainings' => $latestTrainings,
        ];

        // 5. Menampilkan view dashboard
        return view('vendor/dashboard', $data);
    }
}