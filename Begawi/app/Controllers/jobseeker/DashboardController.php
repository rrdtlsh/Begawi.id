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

        $jobseekerModel = new JobseekerModel();
        $jobAppModel = new JobApplicationModel();
        $trainingAppModel = new TrainingApplicationModel();

        $userId = session()->get('user_id');
        $jobseekerId = session()->get('profile_id');

        if (!$userId || !$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        $applications = $jobAppModel->getHistoryByJobseeker($jobseekerId, 5);

        $trainings = $trainingAppModel->getHistoryByJobseeker($jobseekerId, 5);

        $recent_history = array_merge($applications, $trainings);

        usort($recent_history, function ($a, $b) {
            $dateA = isset($a->applied_at) ? $a->applied_at : ($a->enrolled_at ?? null);
            $dateB = isset($b->applied_at) ? $b->applied_at : ($b->enrolled_at ?? null);

            if ($dateA === null || $dateB === null)
                return 0;

            return strtotime($dateB) <=> strtotime($dateA);
        });

        $data = [
            'title' => 'Dashboard Saya',
            'profile' => $jobseekerModel->getProfileByUserId($userId),
            'recent_history' => $recent_history,
        ];

        return view('jobseeker/profile/dashboard', $data);
    }
}
