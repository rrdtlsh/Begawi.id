<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TrainingApplicationModel; // Model untuk pendaftaran pelatihan
use App\Models\TrainingModel;          // Model pelatihan, untuk cek apakah pelatihan valid
use App\Models\JobseekerModel;         // Model jobseeker, untuk mendapatkan data jobseeker
use CodeIgniter\Exceptions\PageNotFoundException;

class TrainingApplicationController extends BaseController
{
    public function apply($trainingId = null)
    {
        // 1. Keamanan: Filter 'auth' di Rute sudah menangani ini,
        //    tapi pengecekan peran adalah lapisan keamanan tambahan yang baik.
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->to('/login')->with('error', 'Anda harus login sebagai Pencari Kerja untuk mendaftar.');
        }

        $trainingModel = new TrainingModel();
        $applicationModel = new TrainingApplicationModel();

        // 2. Verifikasi bahwa pelatihan yang didaftar itu ada
        $training = $trainingModel->find($trainingId);
        if (!$training) {
            throw PageNotFoundException::forPageNotFound();
        }

        // 3. Ambil ID jobseeker dari sesi
        $jobseekerId = session()->get('profile_id');

        // 4. Cek apakah sudah pernah mendaftar
        $existingApplication = $applicationModel
            ->where('training_id', $trainingId)
            ->where('jobseeker_id', $jobseekerId)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di pelatihan ini.');
        }

        // 5. Siapkan data pendaftaran untuk disimpan
        $dataToSave = [
            'training_id' => $trainingId,
            'jobseeker_id' => $jobseekerId,
            'status' => 'pending',
        ];

        // 6. Simpan pendaftaran
        if ($applicationModel->save($dataToSave)) {
            // Arahkan kembali ke halaman detail dengan pesan sukses
            return redirect()->to('/pelatihan/detail/' . $trainingId)->with('success', 'Pendaftaran pelatihan berhasil!');
        } else {
            return redirect()->back()->with('error', 'Gagal mendaftar pelatihan. Silakan coba lagi.');
        }
    }
}