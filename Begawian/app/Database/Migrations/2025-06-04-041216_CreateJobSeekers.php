<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobSeekers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'education' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'skills' => [ // Skills dalam format teks, atau bisa dipisah ke tabel user_skills
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jobseekers');
    }

    public function down()
    {
        $this->forge->dropTable('jobseekers');
    }
}