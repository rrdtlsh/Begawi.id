<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Teknologi Informasi & Perangkat Lunak',
                'icon_path' => 'bi-code-slash', 
            ],
            [
                'name' => 'Pemasaran & Komunikasi',
                'icon_path' => 'bi-megaphone-fill',
            ],
            [
                'name' => 'Penjualan & Bisnis',
                'icon_path' => 'bi-graph-up-arrow',
            ],
            [
                'name' => 'Keuangan & Akuntansi',
                'icon_path' => 'bi-bank',
            ],
            [
                'name' => 'Desain & Kreatif',
                'icon_path' => 'bi-palette-fill',
            ],
            [
                'name' => 'Pendidikan & Pelatihan',
                'icon_path' => 'bi-book-half',
            ],
            [
                'name' => 'Konstruksi & Properti',
                'icon_path' => 'bi-building',
            ],
            [
                'name' => 'Layanan Pelanggan',
                'icon_path' => 'bi-headset',
            ],
            [
                'name' => 'Kesehatan & Medis',
                'icon_path' => 'bi-heart-pulse-fill',
            ],
            [
                'name' => 'Administrasi & Kantor',
                'icon_path' => 'bi-briefcase-fill',
            ],
            [
                'name' => 'Kuliner & F&B',
                'icon_path' => 'bi-cup-hot-fill',
            ],
        ];

       
        $this->db->table('job_categories')->insertBatch($data);
    }
}