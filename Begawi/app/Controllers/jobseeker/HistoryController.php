<?php

namespace App\Controllers\Jobseeker;

use App\Controllers\BaseController;
use App\Models\JobApplicationModel;
use App\Models\TrainingApplicationModel;
use App\Models\JobseekerModel; // Tidak digunakan secara langsung untuk riwayat di sini, tapi dipertahankan jika ada kebutuhan lain

class HistoryController extends BaseController
{
    public function index()
    {
        $jobAppModel = new JobApplicationModel(); //
        $trainingAppModel = new TrainingApplicationModel(); //

        $jobseekerId = session()->get('profile_id');

        if (!$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        // --- Perubahan di sini: Mengambil status count secara terpisah ---
        $jobStatusCounts = $jobAppModel->getStatusCountsByJobseeker($jobseekerId); //
        $trainingStatusCounts = $trainingAppModel->getStatusCountsByJobseeker($jobseekerId); //

        // Data untuk ditampilkan di bagian ringkasan atas
        $summaryCounts = [
            'jobs' => [
                'pending'  => $jobStatusCounts['pending'] ?? 0,
                'accepted' => $jobStatusCounts['accepted'] ?? 0,
                'rejected' => $jobStatusCounts['rejected'] ?? 0,
            ],
            'trainings' => [
                // Perhatikan bahwa status 'accepted' di JobApplicationModel cocok dengan 'approved' di TrainingApplicationModel
                'pending'  => $trainingStatusCounts['pending'] ?? 0,
                'accepted' => $trainingStatusCounts['accepted'] ?? 0, // Menggunakan 'accepted' agar konsisten di view
                'rejected' => $trainingStatusCounts['rejected'] ?? 0,
            ],
            // Opsional: Total gabungan jika masih ingin menampilkannya
            'total' => [
                'pending'  => ($jobStatusCounts['pending'] ?? 0) + ($trainingStatusCounts['pending'] ?? 0),
                'accepted' => ($jobStatusCounts['accepted'] ?? 0) + ($trainingStatusCounts['accepted'] ?? 0), // Memperbaiki penggunaan status 'approved' dari training_applications menjadi 'accepted' untuk total
                'rejected' => ($jobStatusCounts['rejected'] ?? 0) + ($trainingStatusCounts['rejected'] ?? 0),
            ],
        ];


        // Mengambil detail riwayat aplikasi dan pelatihan
        $applications = $jobAppModel->getHistoryByJobseeker($jobseekerId, null); //
        $trainings = $trainingAppModel->getHistoryByJobseeker($jobseekerId, null); //

        // --- Perubahan di sini: Menambahkan properti 'type' ke setiap item ---
        foreach ($applications as &$app) {
            $app->type = 'job_application';
        }
        unset($app); // Penting untuk melepaskan referensi setelah loop

        foreach ($trainings as &$train) {
            $train->type = 'training_enrollment';
        }
        unset($train); // Penting untuk melepaskan referensi setelah loop

        // Menggabungkan dan mengurutkan riwayat
        $history = array_merge($applications, $trainings);

        usort($history, function ($a, $b) {
            $dateA = isset($a->applied_at) ? $a->applied_at : ($a->enrolled_at ?? null);
            $dateB = isset($b->applied_at) ? $b->applied_at : ($b->enrolled_at ?? null);
            if ($dateA === null || $dateB === null) {
                return 0;
            }
            return strtotime($dateB) <=> strtotime($dateA);
        });

        $data = [
            'title'        => 'Status Lamaran & Pelatihan',
            'summaryCounts' => $summaryCounts, 
            'history'      => $history,
        ];

        return view('jobseeker/dashboard/history_page', $data); //
    }
}