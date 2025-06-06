<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\VendorModel;
use App\Models\JobModel;
use App\Models\TrainingModel; // Pastikan ini dipanggil

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

        // 1. Ambil data profil vendor
        $vendorProfile = $vendorModel->getVendorProfileByUserId($userId);

        // 2. Ambil 5 lowongan pekerjaan terakhir secara terpisah
        $latestJobs = $jobModel->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        // 3. Ambil 5 pelatihan terakhir secara terpisah
        $latestTrainings = $trainingModel->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        // 4. Siapkan semua data untuk dikirim ke view
        $data = [
            'title' => 'Beranda Vendor',
            'vendor' => $vendorProfile,
            'jobs' => $latestJobs,      // Kirim daftar lowongan
            'trainings' => $latestTrainings, // Kirim daftar pelatihan
        ];

        // 5. Tampilkan view dashboard yang baru
        return view('vendor/dashboard', $data);
    }
}