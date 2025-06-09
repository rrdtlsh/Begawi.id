<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan sementara pengecekan foreign key
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // 2. Kosongkan tabel dan masukkan data baru
        $this->db->table('locations')->truncate();
        $data = [
            ['name' => 'Banjarmasin Selatan'],
            ['name' => 'Banjarmasin Timur'],
            ['name' => 'Banjarmasin Barat'],
            ['name' => 'Banjarmasin Tengah'],
            ['name' => 'Banjarmasin Utara'],
        ];
        $this->db->table('locations')->insertBatch($data);

        // 3. Aktifkan kembali pengecekan foreign key (SANGAT PENTING!)
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}