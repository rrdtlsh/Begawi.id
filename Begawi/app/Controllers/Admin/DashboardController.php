<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JobModel;  // Panggil JobModel Anda
use App\Models\TrainingModel; // Panggil TrainingModel Anda
use App\Models\UserModel; // Panggil UserModel Anda

class DashboardController extends BaseController
{
    public function index()
    {
        // 1. Inisialisasi semua model yang dibutuhkan
        $userModel = new UserModel();
        $jobModel = new JobModel();
        $trainingModel = new TrainingModel();

        // 2. Hitung semua data statistik yang diperlukan untuk kartu dan grafik

        // Data Pengguna
        $total_users = $userModel->countAllResults();
        $total_vendors = $userModel->where('role', 'vendor')->countAllResults();
        $total_jobseekers = $userModel->where('role', 'jobseeker')->countAllResults();

        // Data Lowongan (Jobs)
        // Menghitung lowongan yang batas waktu lamarannya belum lewat
        $total_active_jobs = $jobModel->where('application_deadline >', date('Y-m-d H:i:s'))
            ->countAllResults();

        // Data Pelatihan (Trainings)
        $total_trainings = $trainingModel->countAllResults();


        // 3. Siapkan semua data ke dalam satu array untuk dikirim ke View
        $data = [
            'title' => 'Dashboard Admin',

            // Data untuk kartu statistik
            'total_users' => $total_users,
            'total_vendors' => $total_vendors,
            'total_jobseekers' => $total_jobseekers,
            'total_active_jobs' => $total_active_jobs,
            'total_trainings' => $total_trainings,
        ];

        // 4. Tampilkan file view dan kirim array $data bersamanya
        // Variabel di dalam array $data akan bisa diakses di view
        // contoh: $data['total_users'] menjadi $total_users di file view
        return view('admin/dashboard/index', $data);
    }
}