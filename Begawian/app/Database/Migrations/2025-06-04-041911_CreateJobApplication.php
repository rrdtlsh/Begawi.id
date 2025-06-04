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
            'job_id' => [ // FK ke tabel 'jobs'
                'type' => 'INT',
                'unsigned' => true,
            ],
            'jobseeker_id' => [ // FK ke tabel 'jobseekers'
                'type' => 'INT',
                'unsigned' => true,
            ],
            'resume_file_name' => [ // Nama file CV yang diunggah
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'resume_file_path' => [ // Path di server tempat file CV disimpan
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'resume_file_type' => [ // Tipe file CV (e.g., application/pdf)
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
            'status' => [ // Contoh: pending, reviewed, interview, accepted, rejected
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'pending',
            ],
            'notes' => [ // Catatan internal vendor terkait aplikasi
                'type' => 'TEXT',
                'null' => true,
            ],
            // 'vendor_rating_jobseeker' => [ // Opsional: Rating dari vendor untuk jobseeker
            //     'type' => 'INT',
            //     'constraint' => 1,
            //     'null' => true,
            // ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jobseeker_id', 'jobseekers', 'id', 'CASCADE', 'CASCADE'); // FK ke jobseekers
        $this->forge->createTable('applications');
    }

    public function down()
    {
        $this->forge->dropTable('applications');
    }
}