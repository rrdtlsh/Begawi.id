<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TrainingModel;
use App\Models\TrainingApplicationModel; // Panggil model aplikasi

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
        return view('training_list_page', $data);
    }

    /**
     * Menampilkan halaman detail untuk satu pelatihan.
     */
    public function detail($id = null)
    {
        $trainingModel = new TrainingModel();

        // --- PERBAIKAN: Gunakan fungsi baru dari model ---
        $training = $trainingModel->getTrainingDetails($id);

        // Jika pelatihan tidak ditemukan, tampilkan halaman 404
        if (!$training) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Cek apakah user sudah terdaftar di pelatihan ini
        $isRegistered = false;
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
        }

        $data = [
            'title' => esc($training->title),
            'training' => $training,
            'isRegistered' => $isRegistered,
        ];

        return view('training_detail_page', $data);
    }
}