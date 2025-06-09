<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\TrainingApplicationModel;
use App\Models\TrainingModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class TrainingApplicationController extends BaseController
{
    public function apply($trainingId = null)
    {
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->to('/login')->with('error', 'Anda harus login sebagai Pencari Kerja untuk mendaftar.');
        }

        $trainingModel = new TrainingModel();
        $applicationModel = new TrainingApplicationModel();

        $training = $trainingModel->find($trainingId);
        if (!$training) {
            throw PageNotFoundException::forPageNotFound();
        }

        $jobseekerId = session()->get('profile_id');

        $existingApplication = $applicationModel
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

        if ($applicationModel->save($dataToSave)) {
            // Arahkan kembali ke halaman detail dengan pesan sukses
            return redirect()->to('/pelatihan/detail/' . $trainingId)->with('success', 'Pendaftaran pelatihan berhasil!');
        } else {
            return redirect()->back()->with('error', 'Gagal mendaftar pelatihan. Silakan coba lagi.');
        }
    }
}