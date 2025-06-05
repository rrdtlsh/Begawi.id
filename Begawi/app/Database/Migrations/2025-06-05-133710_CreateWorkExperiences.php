<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkExperiences extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'jobseeker_id' => ['type' => 'INT', 'unsigned' => true],
            'job_title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'company_name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'start_date' => ['type' => 'DATE'],
            'end_date' => ['type' => 'DATE', 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('work_experiences');
    }

    public function down()
    {
        $this->forge->dropTable('work_experiences');
    }
}