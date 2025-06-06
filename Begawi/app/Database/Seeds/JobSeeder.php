<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class JobSeeder extends Seeder
{
    public function run()
    {
        // Inisialisasi Faker untuk membuat data palsu yang realistis
        $faker = Factory::create('id_ID');

        // Data lowongan pekerjaan yang lebih lengkap
        $jobsData = [
            [
                'vendor_id'       => 1, // Asumsi ID dari vendor PT Teknologi Nusantara
                'category_id'     => 1, // Asumsi ID dari kategori Teknologi Informasi
                'location_id'     => 1, // Asumsi ID dari lokasi Banjarmasin Selatan
                'title'           => 'Senior Backend Engineer (PHP/Laravel)',
                'description'     => $faker->realText(500),
                'qualifications'  => "- Pengalaman minimal 3 tahun dengan PHP & Laravel.\n- Mahir dengan database MySQL atau PostgreSQL.\n- Memahami konsep RESTful API.\n- Mampu bekerja dalam tim.",
                'application_instructions' => 'Kirimkan CV dan portofolio terbaru Anda ke email karir@teknus.co.id dengan subjek "Backend Engineer".',
                'job_type'        => 'Full-time', // Harus sesuai dengan ENUM
                'experience_level'=> 'Senior',    // Harus sesuai dengan ENUM
                'salary_min'      => 9000000.00,
                'salary_max'      => 15000000.00,
                'application_deadline' => date('Y-m-d H:i:s', strtotime('+2 months')),
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'vendor_id'       => 2, // Asumsi ID dari vendor CV Kreasi Digital
                'category_id'     => 2, // Asumsi ID dari kategori Pemasaran
                'location_id'     => 2, // Asumsi ID dari lokasi Banjarmasin Utara
                'title'           => 'Digital Marketing Specialist (SEO/SEM)',
                'description'     => $faker->realText(500),
                'qualifications'  => "- Menguasai Google Ads, Facebook Ads, dan platform iklan lainnya.\n- Memiliki sertifikasi Google Ads adalah nilai tambah.\n- Mampu menganalisis data dan membuat laporan.",
                'application_instructions' => 'Lamar melalui website resmi kami di www.kreasidigital.com/karir.',
                'job_type'        => 'Full-time',
                'experience_level'=> 'Mid-level',
                'salary_min'      => 5000000.00,
                'salary_max'      => 8000000.00,
                'application_deadline' => date('Y-m-d H:i:s', strtotime('+1 month')),
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'vendor_id'       => 3, // Asumsi ID dari vendor Warung Kreatif Banua
                'category_id'     => 5, // Asumsi ID dari kategori Desain & Kreatif
                'location_id'     => 3, // Asumsi ID dari lokasi Banjarmasin Tengah
                'title'           => 'UI/UX Designer (Magang)',
                'description'     => $faker->realText(500),
                'qualifications'  => "- Mahasiswa tingkat akhir atau fresh graduate.\n- Menguasai Figma atau Adobe XD.\n- Memiliki portofolio desain aplikasi atau website.",
                'application_instructions' => 'Kirimkan CV dan link portofolio Anda ke hrd@wkbanua.id.',
                'job_type'        => 'Internship',
                'experience_level'=> 'Entry-level',
                'salary_min'      => 1000000.00,
                'salary_max'      => 1500000.00,
                'application_deadline' => date('Y-m-d H:i:s', strtotime('+3 weeks')),
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        // Menggunakan Query Builder untuk memasukkan semua data
        $this->db->table('jobs')->insertBatch($jobsData);
    }
}