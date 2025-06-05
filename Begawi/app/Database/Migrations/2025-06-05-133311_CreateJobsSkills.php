<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobSkills extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'job_id' => ['type' => 'INT', 'unsigned' => true],
            'skill_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey(['job_id', 'skill_id']);
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('skill_id', 'skills', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_skills');
    }

    public function down()
    {
        $this->forge->dropTable('job_skills');
    }
}