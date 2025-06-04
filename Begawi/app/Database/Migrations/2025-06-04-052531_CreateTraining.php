<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrainings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'vendor_id' => [ // Foreign Key opsional ke tabel 'vendors' jika pelatihan juga bisa dari vendor
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
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'duration' => [ // Durasi pelatihan (misal: "2 minggu", "40 jam", "3 bulan")
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
            'is_paid' => [ // Indikator apakah pelatihan berbayar atau gratis
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
            'deleted_at' => [ // Untuk soft delete
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'SET NULL', 'CASCADE'); // SET NULL jika vendor dihapus tapi pelatihan tetap ada
        $this->forge->createTable('trainings');
    }

    public function down()
    {
        $this->forge->dropTable('trainings');
    }
}