<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->db->table('locations')->truncate();
        $data = [
            ['name' => 'Banjarmasin Selatan'],
            ['name' => 'Banjarmasin Timur'],
            ['name' => 'Banjarmasin Barat'],
            ['name' => 'Banjarmasin Tengah'],
            ['name' => 'Banjarmasin Utara'],
        ];
        $this->db->table('locations')->insertBatch($data);

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}