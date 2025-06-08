<?php

namespace App\Controllers\Jobseeker;

use App\Controllers\BaseController;
use App\Models\JobseekerModel;
use App\Models\BookmarkedJobModel;
use App\Models\BookmarkedTrainingModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->to('/');
        }
        if (empty(session()->get('fullname'))) {
            return redirect()->to('/jobseeker/profile/edit')->with('info', 'Silakan lengkapi profil Anda.');
        }

        $jobseekerModel = new JobseekerModel();
        $bookmarkedJobModel = new BookmarkedJobModel();
        $bookmarkedTrainingModel = new BookmarkedTrainingModel();

        $userId = session()->get('user_id');
        $jobseekerId = session()->get('profile_id');

        $data = [
            'title' => 'Dashboard Saya',
            'profile' => $jobseekerModel->getProfileByUserId($userId),
            'bookmarked_jobs' => $bookmarkedJobModel->getBookmarksByJobseeker($jobseekerId, 10),
            'bookmarked_trainings' => $bookmarkedTrainingModel->getBookmarksByJobseeker($jobseekerId, 10),
        ];

        return view('jobseeker/dashboard', $data);
    }
}