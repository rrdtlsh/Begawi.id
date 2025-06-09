<?php

namespace App\Controllers\Jobseeker;

use App\Controllers\BaseController;
use App\Models\JobApplicationModel;
use App\Models\TrainingApplicationModel;
use App\Models\JobseekerModel;

class HistoryController extends BaseController
{
    public function index()
    {
        $jobAppModel = new JobApplicationModel();
        $trainingAppModel = new TrainingApplicationModel();
        $jobseekerModel = new JobseekerModel();

        $jobseekerId = session()->get('profile_id');

        if (!$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        // ===================================================================
        // PERBAIKAN: Logika penghitungan status diperbarui
        // ===================================================================

        // 1. Hitung status dari lamaran kerja
        $jobStatusCounts = $jobAppModel->getStatusCountsByJobseeker($jobseekerId);


        // 2. Hitung status dari pendaftaran pelatihan
        $trainingStatusCounts = $trainingAppModel->getStatusCountsByJobseeker($jobseekerId);

        // 3. Gabungkan hasil hitungan dari keduanya
        $totalStatusCounts = [
            'pending' => ($jobStatusCounts['pending'] ?? 0) + ($trainingStatusCounts['pending'] ?? 0),
            'accepted' => ($jobStatusCounts['accepted'] ?? 0) + ($trainingStatusCounts['approved'] ?? 0),
            'rejected' => ($jobStatusCounts['rejected'] ?? 0) + ($trainingStatusCounts['rejected'] ?? 0),
        ];

        // Ambil riwayat lamaran dan pelatihan untuk digabungkan di daftar
        $applications = $jobAppModel->getHistoryByJobseeker($jobseekerId, null);
        $trainings = $trainingAppModel->getHistoryByJobseeker($jobseekerId, null);
        $history = array_merge($applications, $trainings);

        // Logika sorting (sudah benar)
        usort($history, function ($a, $b) {
            $dateA = isset($a->applied_at) ? $a->applied_at : ($a->enrolled_at ?? null);
            $dateB = isset($b->applied_at) ? $b->applied_at : ($b->enrolled_at ?? null);
            if ($dateA === null || $dateB === null)
                return 0;
            return strtotime($dateB) <=> strtotime($dateA);
        });

        $data = [
            'title' => 'Status Lamaran & Pelatihan',
            // Kirim data hitungan total yang sudah digabung ke view
            'statusCounts' => $totalStatusCounts,
            'history' => $history,
        ];

        return view('jobseeker/dashboard/history_page', $data);
    }
}