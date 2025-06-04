<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobApplications extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'job_id' => [ // Foreign Key ke tabel 'jobs'
                'type' => 'INT',
                'unsigned' => true,
            ],
            'jobseeker_id' => [ // Foreign Key ke tabel 'jobseekers'
                'type' => 'INT',
                'unsigned' => true,
            ],
            'resume_file_name' => [ // Nama asli file CV yang diunggah
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'resume_file_path' => [ // Path absolut/relatif di server tempat file CV disimpan
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'resume_file_type' => [ // MIME type dari file CV (e.g., application/pdf)
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'resume_file_size' => [ // Ukuran file CV dalam bytes
                'type' => 'INT',
                'null' => true,
            ],
            'applied_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'status' => [ // Status lamaran: pending, reviewed, interview, accepted, rejected
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'pending',
            ],
            'notes' => [ // Catatan internal vendor terkait aplikasi ini
                'type' => 'TEXT',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_applications');
    }

    public function down()
    {
        $this->forge->dropTable('job_applications');
    }
}