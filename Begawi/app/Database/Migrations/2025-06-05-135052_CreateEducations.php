<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEducations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'jobseeker_id' => ['type' => 'INT', 'unsigned' => true],
            'institution_name' => ['type' => 'VARCHAR', 'constraint' => 200],
            'degree' => ['type' => 'VARCHAR', 'constraint' => 100],
            'field_of_study' => ['type' => 'VARCHAR', 'constraint' => 150],
            'start_year' => ['type' => 'VARCHAR', 'constraint' => 4],
            'end_year' => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('educations');
    }

    public function down()
    {
        $this->forge->dropTable('educations');
    }
}