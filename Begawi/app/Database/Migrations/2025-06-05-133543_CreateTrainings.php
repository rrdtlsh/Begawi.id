<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrainings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'vendor_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'category_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'location_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],
            'platform' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'registration_instructions' => ['type' => 'TEXT', 'null' => true],
            'contact_email' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'contact_phone' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'start_date' => ['type' => 'DATETIME', 'null' => true],
            'end_date' => ['type' => 'DATETIME', 'null' => true],
            'duration' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'cost' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'quota' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'comment' => 'Kuota maksimal pelamar'
            ],

            'is_paid' => ['type' => 'BOOLEAN', 'default' => false],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'job_categories', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('trainings');
    }

    public function down()
    {
        $this->forge->dropTable('trainings');
    }
}