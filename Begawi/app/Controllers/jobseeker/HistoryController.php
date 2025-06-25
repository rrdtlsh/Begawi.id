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

        $jobseekerId = session()->get('profile_id');

        if (!$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        $jobStatusCounts = $jobAppModel->getStatusCountsByJobseeker($jobseekerId); 
        $trainingStatusCounts = $trainingAppModel->getStatusCountsByJobseeker($jobseekerId); 

        $summaryCounts = [
            'jobs' => [
                'pending'  => $jobStatusCounts['pending'] ?? 0,
                'accepted' => $jobStatusCounts['accepted'] ?? 0,
                'rejected' => $jobStatusCounts['rejected'] ?? 0,
            ],
            'trainings' => [
                'pending'  => $trainingStatusCounts['pending'] ?? 0,
                'accepted' => $trainingStatusCounts['accepted'] ?? 0, 
                'rejected' => $trainingStatusCounts['rejected'] ?? 0,
            ],
            'total' => [
                'pending'  => ($jobStatusCounts['pending'] ?? 0) + ($trainingStatusCounts['pending'] ?? 0),
                'accepted' => ($jobStatusCounts['accepted'] ?? 0) + ($trainingStatusCounts['accepted'] ?? 0), 
                'rejected' => ($jobStatusCounts['rejected'] ?? 0) + ($trainingStatusCounts['rejected'] ?? 0),
            ],
        ];

        $applications = $jobAppModel->getHistoryByJobseeker($jobseekerId, null); 
        $trainings = $trainingAppModel->getHistoryByJobseeker($jobseekerId, null); 

        foreach ($applications as &$app) {
            $app->type = 'job_application';
        }
        unset($app); 

        foreach ($trainings as &$train) {
            $train->type = 'training_enrollment';
        }
        unset($train); 

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

        return view('jobseeker/dashboard/history_page', $data); 
    }
}