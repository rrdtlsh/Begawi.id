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

    /**
     * Menampilkan halaman registrasi dan mengirimkan data untuk <dropdown class=""></dropdown>
     */
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

    /**
     * Memproses data dari form registrasi yang dinamis.
     */
    public function processRegister()
    {
        $role = $this->request->getPost('role');
        $userModel = new UserModel();

        // Aturan validasi bersama untuk semua peran
        $validationRules = [
            'fullname' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        // Menambahkan aturan validasi spesifik berdasarkan peran yang dipilih
        if ($role === 'jobseeker') {
            $validationRules['fullname'] = 'required|min_length[3]';
            $validationRules['js_location_id'] = 'required';
        } elseif ($role === 'vendor') {
            $validationRules['company_name'] = 'required|min_length[3]';
            $validationRules['vendor_location_id'] = 'required';
            $validationRules['contact'] = 'required';
        }

        // Jalankan validasi
        if (!$this->validate($validationRules)) {
            $redirectUrl = ($role === 'vendor') ? '/register/vendor' : '/register/jobseeker';
            return redirect()->to($redirectUrl)->withInput()->with('errors', $this->validator->getErrors());

        }

        // Siapkan data untuk tabel 'users'
        $userFullname = ($role === 'vendor') ? $this->request->getPost('company_name') : $this->request->getPost('fullname');
        $userData = [
            'fullname' => $userFullname,
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'), // Dihash otomatis oleh UserModel
            'role' => $role,
        ];

        // Simpan data user dan dapatkan ID-nya
        $userModel->save($userData);
        $userId = $userModel->getInsertID();

        // Simpan data profil spesifik berdasarkan peran
        if ($role === 'jobseeker') {
            $jobseekerModel = new JobseekerModel();
            $jobseekerData = [
                'user_id' => $userId,
                'location_id' => $this->request->getPost('js_location_id'),
            ];
            $jobseekerModel->save($jobseekerData);
            $jobseekerId = $jobseekerModel->getInsertID();

            // Simpan skills ke tabel pivot
            $selectedSkills = $this->request->getPost('skills');
            if (!empty($selectedSkills)) {
                $skillsData = [];
                foreach ($selectedSkills as $skillId) {
                    $skillsData[] = ['jobseeker_id' => $jobseekerId, 'skill_id' => $skillId];
                }
                $db = \Config\Database::connect();
                $db->table('jobseeker_skills')->insertBatch($skillsData);
            }

        } elseif ($role === 'vendor') {
            $vendorModel = new VendorModel();
            $vendorData = [
                'user_id' => $userId,
                'company_name' => $this->request->getPost('company_name'),
                'industry' => $this->request->getPost('industry'),
                'location_id' => $this->request->getPost('vendor_location_id'),
                'contact' => $this->request->getPost('contact'),
            ];
            $vendorModel->save($vendorData);
        }

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * Menampilkan halaman login.
     */
    public function login()
    {
        return view('login_page');
    }

    /**
     * Memproses data login, membuat session, dan mengarahkan ke dashboard.
     */
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
            if ($profile)
                $profileId = $profile->id;

        } elseif ($user->role === 'vendor') {
            $vendorModel = new VendorModel();
            $profile = $vendorModel->where('user_id', $user->id)->first();
            if ($profile)
                $profileId = $profile->id;
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
        }

        return redirect()->to('/jobseeker/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}