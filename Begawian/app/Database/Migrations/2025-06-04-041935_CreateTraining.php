<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTraining extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'vendor_id' => [ // Opsional: FK ke tabel 'vendors' jika pelatihan juga bisa dari vendor
                'type' => 'INT',
                'unsigned' => true,
                'null' => true, // Bisa null jika pelatihan diposting oleh admin
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'prerequisites' => [ // Prasyarat pelatihan
                'type' => 'TEXT',
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'duration' => [ // Contoh: "2 minggu", "40 jam"
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'cost' => [ // Biaya pelatihan
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0.00,
            ],
            'is_paid' => [ // Untuk membedakan pelatihan berbayar atau gratis
                'type' => 'BOOLEAN',
                'default' => false,
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
        // Foreign Key ke vendors.id jika vendor bisa posting pelatihan
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('trainings');
    }

    public function down()
    {
        $this->forge->dropTable('trainings');
    }
}