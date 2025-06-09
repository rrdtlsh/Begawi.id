<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\TrainingModel;
use App\Models\TrainingApplicationModel;

class TrainingPageController extends BaseController
{
    public function __construct()
    {
        helper(['text', 'url']);
    }

    public function index()
    {
        $trainingModel = new TrainingModel();
        $data = [
            'title' => 'Daftar Pelatihan & Workshop',
            'trainings' => $trainingModel->getPublishedTrainings(9),
            'pager' => $trainingModel->pager,
        ];
        return view('guest/training_list_page', $data);
    }

    public function detail($id = null)
    {
        $trainingModel = new TrainingModel();

        $training = $trainingModel->getTrainingDetails($id);

        if (!$training) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Cek apakah user sudah terdaftar di pelatihan ini
        $isRegistered = false;
        $isQuotaFull = false;
        if (session()->get('isLoggedIn') && session()->get('role') === 'jobseeker') {
            $trainingApplicationModel = new TrainingApplicationModel();
            $jobseekerId = session()->get('profile_id');
            $existingApplication = $trainingApplicationModel
                ->where('training_id', $id)
                ->where('jobseeker_id', $jobseekerId)
                ->first();
            if ($existingApplication) {
                $isRegistered = true;
            }
            if ($training->quota > 0 && !$isRegistered) { // tambahan: logika cek kuota dan apakah sudah terdafatar
                $applicantCount = $trainingApplicationModel->where('training_id', $id)->countAllResults();
                if ($applicantCount >= $training->quota) {
                    $isQuotaFull = true;
                }
            }
        }


        $data = [
            'title' => esc($training->title),
            'training' => $training,
            'isRegistered' => $isRegistered,
            'isQuotaFull' => $isQuotaFull,
        ];

        return view('guest/training_detail_page', $data);
    }
}