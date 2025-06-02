<?php

namespace App\Controllers;

use App\Models\JobsModel;

class Auth extends BaseController
{
    public function register ()
    {
        $data['message'] = session()->getFlashdata('message');

        return view('register_page', $data);
    }
}
