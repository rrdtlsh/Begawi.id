<?php
namespace App\Controllers\Jobseeker;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\JobseekerModel;
use App\Models\LocationModel;
use App\Models\SkillModel; // Untuk mengambil semua skill yang tersedia
// Jika Anda punya model pivot untuk skill, import juga:
// use App\Models\JobseekerSkillModel; // Misal nama model pivotnya

class ProfileController extends BaseController
{
    public function edit()
    {
        $jobseekerModel = new JobseekerModel();
        $locationModel = new LocationModel();
        $skillModel = new SkillModel(); // Untuk mengambil daftar semua skill

        $userId = session()->get('user_id');

        // Ambil profil jobseeker. Pastikan getProfileByUserId() mengembalikan data yang diperlukan.
        $profile = $jobseekerModel->getProfileByUserId($userId);

        if (!$profile) {
            return redirect()->to('/jobseeker/dashboard')->with('error', 'Profil tidak ditemukan.');
        }

        // Ambil skills yang dimiliki jobseeker (dari tabel pivot)
        // Jika Anda tidak punya JobseekerSkillModel, gunakan DB builder seperti di bawah
        $jobseekerSkills = $jobseekerModel->getJobseekerSkills($profile->id);
        $userSkillIds = array_column($jobseekerSkills, 'id'); // Array ID skill yang dimiliki user

        $data = [
            'title' => 'Edit Profil Saya',
            'profile' => $profile,
            'locations' => $locationModel->orderBy('name', 'ASC')->findAll(),
            'skills' => $skillModel->orderBy('name', 'ASC')->findAll(), // Semua daftar skill tersedia
            'userSkillIds' => $userSkillIds, // Kirim ID skill user ke view
        ];

        return view('jobseeker/profile/form', $data);
    }

    public function update()
    {
        $userModel = new UserModel();
        $jobseekerModel = new JobseekerModel();
        $db = \Config\Database::connect(); // Untuk operasi tabel pivot skills

        $userId = session()->get('user_id');
        $jobseekerProfileId = session()->get('profile_id'); // ID dari tabel jobseekers

        // 1. Ambil semua data POST dan file yang diupload
        $allPostData = $this->request->getPost();
        $allFilesData = $this->request->getFiles();

        // Gabungkan untuk validasi, karena validate() bisa memvalidasi file juga
        $validationData = array_merge($allPostData, $allFilesData);

        // --- Jalankan Validasi ---
        // Penting: Gunakan properti validationRulesUpdate dari JobseekerModel
        if (!$this->validate($jobseekerModel->validationRulesUpdate, $jobseekerModel->validationMessages, $validationData)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Mulai transaksi database jika ada banyak operasi update/insert
        $db->transBegin();

        try {
            $dataToUpdateJobseeker = [];
            $dataToUpdateUser = [];

            // Proses fullname (di tabel users)
            if (isset($allPostData['fullname']) && $allPostData['fullname'] !== '') {
                $dataToUpdateUser['fullname'] = $allPostData['fullname'];
            }

            // Proses field jobseeker lainnya
            // Loop melalui allowedFields atau field yang diharapkan
            $jobseekerFields = ['location_id', 'phone', 'summary']; // Field dari tabel jobseekers
            foreach ($jobseekerFields as $field) {
                if (isset($allPostData[$field]) && $allPostData[$field] !== '') {
                    $dataToUpdateJobseeker[$field] = $allPostData[$field];
                }
            }

            // --- Proses Upload Foto Profil ---
            $pfpFile = $allFilesData['profile_picture'] ?? null;
            if ($pfpFile && $pfpFile->isValid() && !$pfpFile->hasMoved()) {
                $currentProfile = $jobseekerModel->find($jobseekerProfileId); // Ambil data profil saat ini
                if ($currentProfile && !empty($currentProfile->profile_picture_path) && file_exists(FCPATH . 'uploads/avatars/' . $currentProfile->profile_picture_path)) {
                    unlink(FCPATH . 'uploads/avatars/' . $currentProfile->profile_picture_path); // Hapus gambar lama
                }
                $newName = $pfpFile->getRandomName();
                $pfpFile->move(FCPATH . 'uploads/avatars', $newName);
                $dataToUpdateJobseeker['profile_picture_path'] = $newName;
            }

            // --- Proses Upload Resume ---
            $resumeFile = $allFilesData['resume'] ?? null;
            if ($resumeFile && $resumeFile->isValid() && !$resumeFile->hasMoved()) {
                $currentProfile = $jobseekerModel->find($jobseekerProfileId); // Ambil data profil saat ini
                if ($currentProfile && !empty($currentProfile->resume_path) && file_exists(FCPATH . 'uploads/resumes/' . $currentProfile->resume_path)) {
                    unlink(FCPATH . 'uploads/resumes/' . $currentProfile->resume_path); // Hapus resume lama
                }
                $newName = $resumeFile->getRandomName();
                $resumeFile->move(FCPATH . 'uploads/resumes', $newName);
                $dataToUpdateJobseeker['resume_path'] = $newName;
            }

            // --- Update Data ---
            if (!empty($dataToUpdateUser)) {
                $userModel->update($userId, $dataToUpdateUser);
            }
            if (!empty($dataToUpdateJobseeker)) {
                $jobseekerModel->update($jobseekerProfileId, $dataToUpdateJobseeker);
            }

            // --- Update Skills (menggunakan tabel pivot 'jobseeker_skills') ---
            $selectedSkills = $allPostData['skills'] ?? []; // Ambil array skills yang dipilih dari form

            // Hapus semua skill lama yang terkait dengan jobseeker ini
            $db->table('jobseeker_skills')->where('jobseeker_id', $jobseekerProfileId)->delete();

            // Masukkan skill baru jika ada yang dipilih
            if (!empty($selectedSkills) && is_array($selectedSkills)) { // Pastikan $selectedSkills adalah array
                $skillsData = [];
                foreach ($selectedSkills as $skillId) {
                    $skillsData[] = ['jobseeker_id' => $jobseekerProfileId, 'skill_id' => $skillId];
                }
                $db->table('jobseeker_skills')->insertBatch($skillsData);
            }

            // Update fullname di sesi juga (penting agar langsung tercermin di header/navbar)
            if (isset($dataToUpdateUser['fullname'])) {
                session()->set('fullname', $dataToUpdateUser['fullname']);
            }

            // Commit transaksi jika semua operasi berhasil
            $db->transCommit();

            return redirect()->to('/jobseeker/dashboard')->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            $db->transRollback();
            log_message('error', 'Error updating jobseeker profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui profil. Silakan coba lagi. ' . $e->getMessage())->withInput();
        }
    }
}