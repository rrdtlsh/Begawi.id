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
            'vendor_id' => [ // Foreign Key ke tabel 'vendors'
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
            'requirements' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'qualifications' => [
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
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'title',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'description',
            ],
            'application_deadline' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'salary_range',
            ],
            'platform' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'location',
            ],
            'job_type' => [ // Tipe pekerjaan: Full-time, Part-time, Internship, Contract, Freelance
                'type' => 'ENUM',
                'constraint' => ['Full-time', 'Part-time',],
                'null' => false,
                'default' => 'Full-time',
            ],
            'is_skill_required' => [ // Indikator apakah pekerjaan memerlukan skill tertentu
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'deleted_at' => [ // Untuk soft delete
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jobs');
    }

    public function down()
    {
        $this->forge->dropTable('jobs');
    }
}