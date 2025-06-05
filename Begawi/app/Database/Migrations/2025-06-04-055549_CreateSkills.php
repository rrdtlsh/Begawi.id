<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSkills extends Migration
{
    //table skills
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [ // Nama skill, e.g., "PHP", "JavaScript", "Project Management"
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('skills');
    }

    public function down()
    {
        $this->forge->dropTable('skills');
    }
}