<?php

namespace App\Controllers\jobseeker;

use App\Controllers\BaseController;
use App\Models\TrainingApplicationModel;
use App\Models\TrainingModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class TrainingApplicationController extends BaseController
{
    protected $trainingApplicationModel;
    protected $trainingModel;

    public function __construct()
    {
        $this->trainingApplicationModel = new TrainingApplicationModel();
        $this->trainingModel = new TrainingModel();
    }

    public function apply($trainingId = null)
    {
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->to('/login')->with('error', 'Anda harus login sebagai Pencari Kerja untuk mendaftar.');
        }

        $training = $this->trainingModel->find($trainingId);
        if (!$training) {
            throw PageNotFoundException::forPageNotFound();
        }

        $jobseekerId = session()->get('profile_id');

        $existingApplication = $this->trainingApplicationModel
            ->where('training_id', $trainingId)
            ->where('jobseeker_id', $jobseekerId)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di pelatihan ini.');
        }

        $dataToSave = [
            'training_id' => $trainingId,
            'jobseeker_id' => $jobseekerId,
            'status' => 'pending',
        ];

        if ($this->trainingApplicationModel->save($dataToSave)) {
            return redirect()->to('/pelatihan/detail/' . $trainingId)->with('success', 'Pendaftaran pelatihan berhasil!');
        } else {
            return redirect()->back()->with('error', 'Gagal mendaftar pelatihan. Silakan coba lagi.');
        }
    }

    public function deleteEnrollment($enrollmentId)
    {
        $jobseekerId = session()->get('profile_id');

        if (!$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        $enrollment = $this->trainingApplicationModel->find($enrollmentId);


        if (!$enrollment || $enrollment->jobseeker_id != $jobseekerId) {
            return redirect()->to('/jobseeker/history')->with('error', 'Aksi tidak diizinkan.');
        }

        if ($this->trainingApplicationModel->delete($enrollmentId)) {
            return redirect()->to('/jobseeker/history')->with('success', 'Pendaftaran pelatihan berhasil dibatalkan.');
        } else {
            return redirect()->back()->with('error', 'Gagal membatalkan pendaftaran pelatihan.');
        }
    }
}