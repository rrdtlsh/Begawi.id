<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobseekers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'unique' => true],
            'location_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'profile_picture_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'summary' => ['type' => 'TEXT', 'null' => true],
            'resume_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('jobseekers');
    }

    public function down()
    {
        $this->forge->dropTable('jobseekers');
    }
}