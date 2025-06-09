<?php

namespace App\Controllers\Jobseeker;

use App\Controllers\BaseController;
use App\Models\JobseekerModel;
use App\Models\JobApplicationModel;
use App\Models\TrainingApplicationModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->to('/');
        }
        
        // Inisialisasi semua model yang dibutuhkan
        $jobseekerModel = new JobseekerModel();
        $jobAppModel = new JobApplicationModel();
        $trainingAppModel = new TrainingApplicationModel();

        $userId = session()->get('user_id');
        $jobseekerId = session()->get('profile_id');

        // Jika tidak ada ID di sesi, lebih baik redirect ke halaman login
        if (!$userId || !$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        // 1. Ambil riwayat lamaran kerja terbaru (misalnya, 5 terakhir)
        $applications = $jobAppModel->getHistoryByJobseeker($jobseekerId, 5);

        // 2. Ambil riwayat pelatihan terbaru (misalnya, 5 terakhir)
        $trainings = $trainingAppModel->getHistoryByJobseeker($jobseekerId, 5);

        // 3. Gabungkan kedua riwayat menjadi satu array
        $recent_history = array_merge($applications, $trainings);

        // 4. Urutkan riwayat gabungan dari yang paling baru
        usort($recent_history, function ($a, $b) {
            $dateA = isset($a->applied_at) ? $a->applied_at : ($a->enrolled_at ?? null);
            $dateB = isset($b->applied_at) ? $b->applied_at : ($b->enrolled_at ?? null);

            // Jika tanggal tidak ada, jangan error
            if ($dateA === null || $dateB === null) return 0;

            // Lakukan perbandingan
            return strtotime($dateB) <=> strtotime($dateA);
        });

        $data = [
            'title' => 'Dashboard Saya',
            'profile' => $jobseekerModel->getProfileByUserId($userId),
            // 5. Kirim riwayat yang sudah digabung & diurutkan ke view
            'recent_history' => $recent_history, 
        ];

        // Pastikan nama view sudah benar ('dashboard_page' atau 'dashboard')
        return view('jobseeker/profile/dashboard', $data);
    }
}
