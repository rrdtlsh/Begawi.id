<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobApplications extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true],
            'jobseeker_id' => ['type' => 'INT', 'unsigned' => true],
            'resume_file_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'reviewed', 'interview', 'accepted', 'rejected'], 'default' => 'pending'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'applied_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_applications');
    }

    public function down()
    {
        $this->forge->dropTable('job_applications');
    }
}