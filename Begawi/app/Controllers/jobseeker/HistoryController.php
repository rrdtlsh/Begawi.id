<?php

namespace App\Controllers\Jobseeker;

use App\Controllers\BaseController;
use App\Models\JobApplicationModel;
use App\Models\TrainingApplicationModel;

class HistoryController extends BaseController
{
    public function index()
    {
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->to('/');
        }

        $jobAppModel = new JobApplicationModel();
        $trainingAppModel = new TrainingApplicationModel();
        $jobseekerId = session()->get('profile_id');

        // Ambil data statistik untuk baris atas
        $statusCounts = $jobAppModel->getStatusCountsByJobseeker($jobseekerId);

        // Ambil SEMUA riwayat lamaran (tanpa limit)
        $applications = $jobAppModel->getHistoryByJobseeker($jobseekerId, null);

        // Ambil SEMUA riwayat pelatihan (tanpa limit)
        $trainings = $trainingAppModel->getHistoryByJobseeker($jobseekerId, null);

        // Gabungkan dan urutkan berdasarkan tanggal
        $history = array_merge($applications, $trainings);
        usort($history, function ($a, $b) {
            // Kita perlu memastikan properti tanggal ada di keduanya
            $timeA = isset($a->applied_at) ? strtotime($a->applied_at) : strtotime($a->enrolled_at);
            $timeB = isset($b->applied_at) ? strtotime($b->applied_at) : strtotime($b->enrolled_at);
            return $timeB <=> $timeA; // Urutkan dari terbaru ke terlama
        });

        $data = [
            'title' => 'Status Lamaran & Pelatihan',
            'statusCounts' => $statusCounts,
            'history' => $history,
        ];

        return view('jobseeker/history_page', $data);
    }
}