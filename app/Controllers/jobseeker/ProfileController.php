<?php
namespace App\Controllers\Jobseeker;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\JobseekerModel;
use App\Models\LocationModel;
use App\Models\SkillModel;

class ProfileController extends BaseController
{
    public function edit()
    {
        $jobseekerModel = new JobseekerModel();
        $locationModel = new LocationModel();
        $skillModel = new SkillModel();

        $userId = session()->get('user_id');

        $profile = $jobseekerModel->getProfileByUserId($userId);

        if (!$profile) {
            return redirect()->to('/jobseeker/dashboard')->with('error', 'Profil tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Profil Saya',
            'profile' => $profile,
            'locations' => $locationModel->orderBy('name', 'ASC')->findAll(),
            'skills' => $skillModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('jobseeker/profile/form', $data);
    }

    public function update()
    {
        $userModel = new UserModel();
        $jobseekerModel = new JobseekerModel();
        $db = \Config\Database::connect();

        $userId = session()->get('user_id');
        $jobseekerId = session()->get('profile_id');

        $validationRules = [
            'fullname' => 'required|max_length[100]',
            'location_id' => 'required|is_natural_no_zero',
            'profile_picture' => [
                'label' => 'Foto Profil',
                'rules' => 'is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/png]|max_size[profile_picture,1024]',
            ],
            'resume' => [
                'label' => 'File Resume',
                'rules' => 'max_size[resume,2048]|ext_in[resume,pdf,doc,docx]',
            ]
        ];
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $jobseekerData = [
            'location_id' => $this->request->getPost('location_id'),
            'summary' => $this->request->getPost('summary'),
            'phone' => $this->request->getPost('phone'),
        ];

        // Proses upload foto profil
        $pfpFile = $this->request->getFile('profile_picture');
        if ($pfpFile->isValid() && !$pfpFile->hasMoved()) {
            $currentProfile = $jobseekerModel->find($jobseekerId);
            if ($currentProfile->profile_picture_path && file_exists('uploads/avatars/' . $currentProfile->profile_picture_path)) {
                unlink('uploads/avatars/' . $currentProfile->profile_picture_path);
            }
            $newName = $pfpFile->getRandomName();
            $pfpFile->move('uploads/avatars', $newName);
            $jobseekerData['profile_picture_path'] = $newName;
        }

        // --- Logika Upload Resume ---
        $resumeFile = $this->request->getFile('resume');
        if ($resumeFile->isValid() && !$resumeFile->hasMoved()) {
            $currentProfile = $jobseekerModel->find($jobseekerId);
            if ($currentProfile->resume_path && file_exists('uploads/resumes/' . $currentProfile->resume_path)) {
                unlink('uploads/resumes/' . $currentProfile->resume_path);
            }
            $newName = $resumeFile->getRandomName();
            $resumeFile->move('uploads/resumes', $newName);
            $jobseekerData['resume_path'] = $newName;
        }

        $userModel->update($userId, ['fullname' => $this->request->getPost('fullname')]);
        $jobseekerModel->update($jobseekerId, $jobseekerData);

        // Update skills di tabel pivot
        $selectedSkills = $this->request->getPost('skills');
        $db->table('jobseeker_skills')->delete(['jobseeker_id' => $jobseekerId]); // Hapus skill lama
        if (!empty($selectedSkills)) {
            $skillsData = [];
            foreach ($selectedSkills as $skillId) {
                $skillsData[] = ['jobseeker_id' => $jobseekerId, 'skill_id' => $skillId];
            }
            $db->table('jobseeker_skills')->insertBatch($skillsData); // Masukkan skill baru
        }
        session()->set('fullname', $this->request->getPost('fullname'));

        return redirect()->to('/jobseeker/dashboard')->with('success', 'Profil berhasil diperbarui!');
    }
}
?>