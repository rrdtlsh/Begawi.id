<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true
            ],
            'icon_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('job_categories');
    }

    public function down()
    {
        $this->forge->dropTable('job_categories');
    }
}