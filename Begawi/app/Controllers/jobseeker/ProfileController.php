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

        $rules = [
            'fullname' => 'required|min_length[3]',
            'location_id' => 'required|is_natural_no_zero',
            'profile_picture' => [
                'label' => 'Foto Profil',
                'rules' => 'is_image[profile_picture]|max_size[profile_picture,1024]',
            ],
            'resume' => [
                'label' => 'File Resume',
                'rules' => 'max_size[resume,2048]|ext_in[resume,pdf,doc,docx]',
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel->update($userId, ['fullname' => $this->request->getPost('fullname')]);

        $jobseekerData = [
            'location_id' => $this->request->getPost('location_id'),
            'phone' => $this->request->getPost('phone'),
            'summary' => $this->request->getPost('summary'),
        ];

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

        $jobseekerModel->update($jobseekerId, $jobseekerData);

        $selectedSkills = $this->request->getPost('skills');
        $db->table('jobseeker_skills')->delete(['jobseeker_id' => $jobseekerId]);
        if (!empty($selectedSkills)) {
            $skillsData = [];
            foreach ($selectedSkills as $skillId) {
                $skillsData[] = ['jobseeker_id' => $jobseekerId, 'skill_id' => $skillId];
            }
            $db->table('jobseeker_skills')->insertBatch($skillsData);
        }

        session()->set('fullname', $this->request->getPost('fullname'));

        return redirect()->to('/jobseeker/dashboard')->with('success', 'Profil berhasil diperbarui!');
    }

    public function deleteAccount()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'jobseeker') {
            return redirect()->to('/login')->with('error', 'Akses tidak sah.');
        }

        $userId = session()->get('user_id');

        $userModel = new UserModel();

    
        $deleted = $userModel->delete($userId, true);

        if ($deleted) {
            session()->destroy();
            return redirect()->to('/')->with('success', 'Akun Anda telah berhasil dihapus secara permanen. Terima kasih telah menggunakan layanan kami.');
        } else {
            return redirect()->to('/jobseeker/dashboard')->with('error', 'Gagal menghapus akun. Silakan coba lagi.');
        }
    }
}