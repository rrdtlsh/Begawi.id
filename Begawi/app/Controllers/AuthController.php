<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\JobseekerModel;
use App\Models\VendorModel;

class AuthController extends BaseController
{
    public function register()
    {
        $data['message'] = session()->getFlashdata('message');

        return view('register_page', $data);
    }
}