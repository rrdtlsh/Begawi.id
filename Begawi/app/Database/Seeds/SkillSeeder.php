<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->db->table('skills')->truncate();
        $data = [
            ['name' => 'PHP'],
            ['name' => 'JavaScript'],
            ['name' => 'HTML & CSS'],
            ['name' => 'Laravel'],
            ['name' => 'CodeIgniter 4'],
            ['name' => 'React.js'],
            ['name' => 'Vue.js'],
            ['name' => 'Desain Grafis'],
            ['name' => 'Digital Marketing'],
            ['name' => 'SEO Specialist'],
            ['name' => 'Content Writer'],
            ['name' => 'Administrasi Kantor'],
            ['name' => 'Belum Ada']
        ];
        $this->db->table('skills')->insertBatch($data);

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
