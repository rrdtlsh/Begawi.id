<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobseekerSkills extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'jobseeker_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'skill_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
        ]);
        $this->forge->addPrimaryKey(['jobseeker_id', 'skill_id']);
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('skill_id', 'skills', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jobseeker_skills');
    }

    public function down()
    {
        $this->forge->dropTable('jobseeker_skills');
    }
}