<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrainingApplications extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'training_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'jobseeker_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => [
                    'pending',
                    'accepted',
                    'rejected',
                    'completed'
                ],
                'default' => 'pending'
            ],
            'enrolled_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('training_id', 'trainings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('training_applications');
    }

    public function down()
    {
        $this->forge->dropTable('training_applications');
    }
}