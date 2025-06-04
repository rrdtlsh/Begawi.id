<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExampleUser extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email'    => 'admin@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT), // Ganti 'password123' dengan password yang aman
                'role'     => 'admin',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'vendor_user',
                'email'    => 'vendor@example.com',
                'password' => password_hash('vendorpass', PASSWORD_DEFAULT), // Ganti 'vendorpass' dengan password yang aman
                'role'     => 'vendor',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'jobseeker_user',
                'email'    => 'jobseeker@example.com',
                'password' => password_hash('jobseekerpass', PASSWORD_DEFAULT), // Ganti 'jobseekerpass' dengan password yang aman
                'role'     => 'jobseeker',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];

        // Using Query Builder
        $this->db->table('users')->insertBatch($data);
    }
}
