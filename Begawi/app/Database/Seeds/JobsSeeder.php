<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JobsSeeder extends Seeder
{
    public function run()
    {
        $jobsData = [
            [
                'vendor_id'    => 2, // ID user vendor yang ada
                'title'        => 'Senior PHP Developer (Remote)',
                'description'  => 'Kami mencari Senior PHP Developer berpengalaman untuk bergabung dengan tim kami. Anda akan mengerjakan proyek-proyek skala besar dengan teknologi terkini. Mampu bekerja secara remote dan memiliki portofolio yang kuat.',
                'location'     => 'Banjarmasin (Remote)',
                'salary_range' => 'Rp 10.000.000 - Rp 15.000.000',
                'created_at'   => date('Y-m-d H:i:s') // Tanggal dan waktu saat ini
            ],
            [
                'vendor_id'    => 2, // ID user vendor yang ada
                'title'        => 'Digital Marketing Specialist',
                'description'  => 'Bergabunglah dengan tim marketing kami sebagai Spesialis Pemasaran Digital. Tugas utama meliputi manajemen kampanye iklan online, SEO/SEM, dan analisis data. Pengalaman minimal 2 tahun di bidang serupa.',
                'location'     => 'Jakarta Pusat',
                'salary_range' => 'Rp 7.000.000 - Rp 10.000.000',
                'created_at'   => date('Y-m-d H:i:s')
            ],
            [
                'vendor_id'    => 2, // ID user vendor yang ada
                'title'        => 'Frontend Developer (Vue.js)',
                'description'  => 'Dibutuhkan Frontend Developer yang mahir dengan Vue.js. Anda akan bertanggung jawab untuk mengembangkan antarmuka pengguna yang responsif dan interaktif. Pemahaman yang baik tentang REST API adalah nilai plus.',
                'location'     => 'Surabaya',
                'salary_range' => 'Rp 8.000.000 - Rp 12.000.000',
                'created_at'   => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('jobs')->insertBatch($jobsData);
    }
}
