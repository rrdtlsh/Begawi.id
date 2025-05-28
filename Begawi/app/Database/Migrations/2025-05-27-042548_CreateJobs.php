<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'vendor_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'salary_range' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('vendor_id', 'users', 'id', 'CASCADE', 'CASCADE'); // FK ke users
        $this->forge->createTable('jobs');
    }

    public function down()
    {
        $this->forge->dropTable('jobs');
    }
}
