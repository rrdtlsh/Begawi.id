<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVendors extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'unique' => true],
            'company_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'company_email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'location_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'company_address' => ['type' => 'TEXT', 'null' => true],
            'company_profile' => ['type' => 'TEXT', 'null' => true],
            'company_logo_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'industry' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'company_size' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'contact' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'website' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('vendors');
    }

    public function down()
    {
        $this->forge->dropTable('vendors');
    }
}