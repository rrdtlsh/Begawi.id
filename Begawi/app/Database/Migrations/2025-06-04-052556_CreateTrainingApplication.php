<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrainingApplication extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'training_id' => [ // Foreign Key ke tabel 'trainings'
                'type' => 'INT',
                'unsigned' => true,
            ],
            'jobseeker_id' => [ // Foreign Key ke tabel 'jobseekers'
                'type' => 'INT',
                'unsigned' => true,
            ],
            'enrolled_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'status' => [ // Status pendaftaran: pending, accepted, rejected, completed
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'pending',
            ],
            // Anda bisa tambahkan kolom lain seperti 'payment_status', 'certificate_issued_at', dll.
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('training_id', 'trainings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('training_Application');
    }

    public function down()
    {
        $this->forge->dropTable('training_Application');
    }
}