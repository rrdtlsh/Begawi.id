<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookmarkedTrainings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'jobseeker_id' => ['type' => 'INT', 'unsigned' => true],
            'training_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addPrimaryKey(['jobseeker_id', 'training_id']);
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('training_id', 'trainings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bookmarked_trainings');
    }

    public function down()
    {
        $this->forge->dropTable('bookmarked_trainings');
    }
}