<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\JobseekerModel;
use App\Models\VendorModel;
use App\Models\LocationModel;
use App\Models\SkillModel;

class AuthController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function register()
    {
        return view('register_choice_page');
    }

    public function registerJobseeker()
    {
        $locationModel = new LocationModel();
        $skillModel = new SkillModel();
        $data = [
            'title' => 'Registrasi Jobseeker',
            'locations' => $locationModel->findAll(),
            'skills' => $skillModel->findAll(),
        ];
        return view('register_jobseeker_page', $data);
    }

    public function registerVendor()
    {
        $locationModel = new LocationModel();
        $data = [
            'title' => 'Registrasi Vendor',
            'locations' => $locationModel->findAll(),
        ];
        return view('register_vendor_page', $data);
    }

    public function processRegister()
    {
        $role = $this->request->getPost('role');
        $userModel = new UserModel();

        // Aturan validasi
        $validationRules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];
        if ($role === 'jobseeker') {
            $validationRules['fullname'] = 'required|min_length[3]';
            $validationRules['js_location_id'] = 'required';
            $validationRules['skills'] = 'required';
        } elseif ($role === 'vendor') {
            $validationRules['company_name'] = 'required|min_length[3]';
            $validationRules['vendor_location_id'] = 'required';
            $validationRules['contact'] = 'required';
        }

        if (!$this->validate($validationRules)) {
            $redirectUrl = ($role === 'vendor') ? '/register/vendor' : '/register/jobseeker';
            return redirect()->to($redirectUrl)->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Simpan data user
        $userFullname = ($role === 'vendor') ? $this->request->getPost('company_name') : $this->request->getPost('fullname');
        $userData = [
            'fullname' => $userFullname,
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $role,
        ];

        if (!$userModel->save($userData)) {
            $db->transRollback();
            return redirect()->to('/register/jobseeker')->withInput()->with('errors', $userModel->errors());
        }
        $userId = $userModel->getInsertID();

        // =============================================================
        // PERBAIKAN STRUKTUR LOGIKA: if/elseif sekarang sejajar
        // =============================================================
        if ($role === 'jobseeker') {
            $jobseekerModel = new JobseekerModel();
            $jobseekerData = [
                'user_id' => $userId,
                'location_id' => $this->request->getPost('js_location_id'),
            ];

            if (!$jobseekerModel->save($jobseekerData)) {
                $db->transRollback();
                return redirect()->to('/register/jobseeker')->withInput()->with('errors', $jobseekerModel->errors());
            }
            $jobseekerId = $jobseekerModel->getInsertID();

            $selectedSkillId = $this->request->getPost('skills');
            if (!empty($selectedSkillId) && $jobseekerId > 0) {
                $skillsData = ['jobseeker_id' => $jobseekerId, 'skill_id' => $selectedSkillId];
                $db->table('jobseeker_skills')->insert($skillsData);
            }

        } elseif ($role === 'vendor') {
            $vendorModel = new VendorModel();
            $vendorData = [
                'user_id' => $userId,
                'company_name' => $this->request->getPost('company_name'),
                'industry' => $this->request->getPost('industry'), // Pastikan field ini ada di form Anda
                'location_id' => $this->request->getPost('vendor_location_id'),
                'contact' => $this->request->getPost('contact'),
                'company_address' => $this->request->getPost('company_address'), // Pastikan field ini ada di form Anda
            ];
            if (!$vendorModel->save($vendorData)) {
                $db->transRollback();
                return redirect()->to('/register/vendor')->withInput()->with('errors', $vendorModel->errors());
            }
        }

        // Selesaikan transaksi jika semua berhasil
        $db->transComplete();

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->to('/register/jobseeker')->withInput()->with('error', 'Terjadi kesalahan pada database, registrasi gagal.');
        }

        // Redirect terakhir, berada di luar semua kondisi if/else
        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ... (method login, processLogin, logout) ...
    public function login()
    {
        return view('login_page');
    }

    public function processLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        $profileId = null;
        if ($user->role === 'jobseeker') {
            $jobseekerModel = new JobseekerModel();
            $profile = $jobseekerModel->where('user_id', $user->id)->first();
            if ($profile) {
                $profileId = $profile->id;
            }
        } elseif ($user->role === 'vendor') {
            $vendorModel = new VendorModel();
            $profile = $vendorModel->where('user_id', $user->id)->first();
            if ($profile) {
                $profileId = $profile->id;
            }
        }

        $sessionData = [
            'user_id' => $user->id,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'role' => $user->role,
            'profile_id' => $profileId,
            'isLoggedIn' => true,
        ];
        session()->set($sessionData);

        if ($user->role === 'vendor') {
            return redirect()->to('/vendor/dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/jobseeker/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}