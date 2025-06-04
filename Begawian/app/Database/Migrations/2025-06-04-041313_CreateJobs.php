<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'vendor_id' => [ // Merujuk ke ID dari tabel 'vendors'
                'type' => 'INT',
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'requirements' => [ // Persyaratan pekerjaan
                'type' => 'TEXT',
                'null' => true,
            ],
            'qualifications' => [ // Kualifikasi (pendidikan, sertifikasi)
                'type' => 'TEXT',
                'null' => true,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'salary_range' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'job_type' => [ // Full-time, Part-time, Internship, Contract, Freelance
                'type' => 'ENUM',
                'constraint' => ['Full-time', 'Part-time', 'Internship', 'Contract', 'Freelance'],
                'null' => false,
                'default' => 'Full-time',
            ],
            'is_skill_required' => [ // Untuk membedakan pekerjaan skill dan non-skill
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [ // Ditambahkan untuk pelacakan update
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        // Penting: Foreign Key ke vendors.id, bukan users.id untuk jobs
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jobs');
    }

    public function down()
    {
        $this->forge->dropTable('jobs');
    }
}