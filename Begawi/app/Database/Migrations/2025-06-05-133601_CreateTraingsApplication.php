<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrainingApplications extends Migration
{
    // app/Database/Migrations/xxxx_CreateTrainings.php

    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'vendor_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],

            // --- PENYESUAIAN UI ---
            'category_id' => [ // Kategori pelatihan
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'location_id' => [ // Lokasi pelatihan
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'platform' => [ // Tempat/Platform (e.g., Zoom, Gedung A)
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'registration_instructions' => [ // Kolom untuk "Cara Mendaftar"
                'type' => 'TEXT',
                'null' => true,
            ],
            'contact_email' => [ // Email kontak spesifik
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'contact_phone' => [ // Telepon kontak spesifik
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            // Mengubah DATE menjadi DATETIME untuk menyertakan waktu
            'start_date' => ['type' => 'DATETIME', 'null' => true],
            'end_date' => ['type' => 'DATETIME', 'null' => true],
            // --- AKHIR PENYESUAIAN ---

            'duration' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'cost' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'is_paid' => ['type' => 'BOOLEAN', 'default' => false],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'SET NULL', 'CASCADE');
        // Tambahkan foreign key baru
        $this->forge->addForeignKey('category_id', 'job_categories', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('trainings');
    }

    public function down()
    {
        $this->forge->dropTable('training_applications');
    }
}