<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Siapkan data user
        $usersData = [
            [
                // User ini akan mendapat ID 1
                'fullname'   => 'Admin Begawi',
                'email'      => 'admin@begawi.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                // User ini akan mendapat ID 2 (untuk PT Teknologi Nusantara)
                'fullname'   => 'PT Teknologi Nusantara',
                'email'      => 'hr@teknus.co.id',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'vendor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                // User ini akan mendapat ID 3 (untuk CV Kreasi Digital)
                'fullname'   => 'CV Kreasi Digital',
                'email'      => 'contact@kreasidigital.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'vendor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                // User ini akan mendapat ID 4 (untuk Warung Kreatif Banua)
                'fullname'   => 'Warung Kreatif Banua',
                'email'      => 'info@wkbanua.id',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'vendor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                // User ini akan mendapat ID 5
                'fullname'   => 'Andi Pencari Kerja',
                'email'      => 'andi.jobseeker@email.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'jobseeker',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Memasukkan data ke tabel 'users'
        $this->db->table('users')->insertBatch($usersData);
    }
}