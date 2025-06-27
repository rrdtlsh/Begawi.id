<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRejectionReasonToTrainingApplications extends Migration
{
    public function up()
    {
        $fields = [
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Alasan penolakan dari penyelenggara pelatihan',
                'after' => 'status',
            ],
        ];
        $this->forge->addColumn('training_applications', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('training_applications', 'rejection_reason');
    }
}
