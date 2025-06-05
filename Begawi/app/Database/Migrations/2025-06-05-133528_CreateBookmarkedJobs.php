<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookmarkedJobs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'jobseeker_id' => ['type' => 'INT', 'unsigned' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addPrimaryKey(['jobseeker_id', 'job_id']);
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bookmarked_jobs');
    }

    public function down()
    {
        $this->forge->dropTable('bookmarked_jobs');
    }
}