<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run()
    {
        // KOSONGKAN TABEL VENDORS TERLEBIH DAHULU
        // Ini akan menghapus semua data lama agar tidak ada duplikat
        $this->db->table('vendors')->truncate();

        // Data vendor yang akan dimasukkan (tetap sama)
        $vendorsData = [
            [
                'user_id'         => 2,
                'company_name'    => 'PT Teknologi Nusantara',
                'company_email'   => 'hr@teknus.co.id',
                'location_id'     => 1,
                'company_address' => 'Jl. A. Yani Km. 6, Banjarmasin',
                'industry'        => 'Teknologi Informasi',
                'contact'         => '081234567890',
                'website'         => 'https://teknus.co.id',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'         => 3,
                'company_name'    => 'CV Kreasi Digital',
                'company_email'   => 'contact@kreasidigital.com',
                'location_id'     => 2,
                'company_address' => 'Jl. Sultan Adam, Banjarmasin',
                'industry'        => 'Pemasaran & Agensi',
                'contact'         => '082234567891',
                'website'         => 'https://kreasidigital.com',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'         => 4,
                'company_name'    => 'Warung Kreatif Banua',
                'company_email'   => 'info@wkbanua.id',
                'location_id'     => 3,
                'company_address' => 'Jl. Kayu Tangi, Banjarmasin',
                'industry'        => 'Desain & Kreatif',
                'contact'         => '085712345678',
                'website'         => 'https://wkbanua.id',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        // Memasukkan data ke tabel 'vendors'
        $this->db->table('vendors')->insertBatch($vendorsData);
    }
}