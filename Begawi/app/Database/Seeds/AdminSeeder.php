<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $adminData = [
            'fullname' => 'Administrator',
            'email' => 'begawiofficial@gmail.com',
            'password' => 'makannasi',
            'role' => 'admin',
        ];

        // Cek apakah email sudah ada
        if (!$userModel->where('email', $adminData['email'])->first()) {
            $userModel->save($adminData);
            echo "Akun admin berhasil dibuat.\n";
        } else {
            echo "Akun admin dengan email tersebut sudah ada.\n";
        }
    }
}