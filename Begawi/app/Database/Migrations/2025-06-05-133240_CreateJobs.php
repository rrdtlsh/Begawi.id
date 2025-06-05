<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'vendor_id' => ['type' => 'INT', 'unsigned' => true],
            'category_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'qualifications' => ['type' => 'TEXT', 'null' => true],
            'location' => ['type' => 'VARCHAR', 'constraint' => 255],
            'job_type' => ['type' => 'ENUM', 'constraint' => ['Full-time', 'Part-time']],
            'experience_level' => ['type' => 'ENUM', 'constraint' => ['Entry-level', 'Mid-level', 'Senior', 'Manager', 'Director'], 'null' => true],
            'salary_min' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true],
            'salary_max' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true],
            'application_deadline' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'job_categories', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('jobs');
    }

    public function down()
    {
        $this->forge->dropTable('jobs');
    }
}