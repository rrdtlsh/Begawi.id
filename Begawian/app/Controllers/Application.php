<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserSkills extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'jobseeker_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'skill_name' => [ // Atau 'skill_id' jika ada tabel 'skills' master
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'proficiency_level' => [ // Contoh: beginner, intermediate, advanced
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_skills');
    }

    public function down()
    {
        $this->forge->dropTable('user_skills');
    }
}