<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\JobseekerModel;
use App\Models\VendorModel;

class AuthController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
    }
    public function register()
    {
        return view('register_page');
    }

    public function processRegister()
    {
        $userModel = new UserModel();
        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role')
        ];

        if (!$userModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        $userId = $userModel->getinsertID();
        $role = $data['role'];

        if ($role === 'jobseeker') {
            $jobseekerModel = new JobseekerModel();
            $jobseekerModel->save(['user_id' => $userId]);
        } elseif ($role === 'vendor') {
            $vendorModel = new VendorModel();
            $vendorModel->save([
                'user_id' => $userId,
                'company_name' => $data['fullname']
            ]);
        }

        return redirect()->to('/login')->with('success', 'Registration successful, please login.');
    }

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
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
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
            'email' => $user->email,
            'role' => $user->role,
            'profile_id' => $profileId,
            'is_logged_in' => true
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