<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRejectionReasonToJobApplication extends Migration
{
    public function up()
    {
        $fields = [
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Alasan penolakan dari vendor',
                'after' => 'notes',
            ],
        ];
        $this->forge->addColumn('job_applications', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('job_applications', 'rejection_reason');
    }
}
