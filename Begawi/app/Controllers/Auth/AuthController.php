<?php

namespace App\Controllers\Auth;

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
        return view('auth/register_choice_page');
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
        return view('auth/register_jobseeker_page', $data);
    }

    public function registerVendor()
    {
        $locationModel = new LocationModel();
        $data = [
            'title' => 'Registrasi Vendor',
            'locations' => $locationModel->findAll(),
        ];
        return view('auth/register_vendor_page', $data);
    }

    public function processRegister()
    {
        $role = $this->request->getPost('role');
        $userModel = new UserModel();

        $validationRules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];
        if ($role === 'jobseeker') {
            $validationRules['fullname'] = 'required';
            $validationRules['js_location_id'] = 'required';
            $validationRules['skills'] = 'required';
        } elseif ($role === 'vendor') {
            $validationRules['company_name'] = 'required';
            $validationRules['vendor_location_id'] = 'required';
            $validationRules['contact'] = 'required';
        }

        $validationMessages = [
            'email' => [
                'required' => 'Alamat email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.'
            ],
            'password' => [
                'required' => 'Kata sandi wajib diisi.',
                'min_length' => 'Kata sandi minimal harus 8 karakter.'
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi kata sandi wajib diisi.',
                'matches' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi.'
            ],
            'fullname' => [
                'required' => 'Nama lengkap wajib diisi.',
            ],
            'js_location_id' => [
                'required' => 'Domisili wajib dipilih.'
            ],
            'skills' => [
                'required' => 'Keahlian utama wajib dipilih.'
            ],
            'company_name' => [
                'required' => 'Nama perusahaan wajib diisi.'
            ],
            'vendor_location_id' => [
                'required' => 'Domisili usaha wajib dipilih.'
            ],
            'contact' => [
                'required' => 'Nomor kontak wajib diisi.'
            ]
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $redirectUrl = ($role === 'vendor') ? '/register/vendor' : '/register/jobseeker';
            return redirect()->to($redirectUrl)->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

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
                'industry' => $this->request->getPost('industry'),
                'location_id' => $this->request->getPost('vendor_location_id'),
                'contact' => $this->request->getPost('contact'),
                'company_address' => $this->request->getPost('company_address'),
            ];
            if (!$vendorModel->save($vendorData)) {
                $db->transRollback();
                return redirect()->to('/register/vendor')->withInput()->with('errors', $vendorModel->errors());
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->to('/register/jobseeker')->withInput()->with('error', 'Terjadi kesalahan pada database, registrasi gagal.');
        }

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function login()
    {
        return view('auth/login_page');
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
        session()->regenerate();

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