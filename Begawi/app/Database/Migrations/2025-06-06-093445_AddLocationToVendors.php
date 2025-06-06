<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationToVendors extends Migration
{
    public function up()
    {
        $this->forge->addColumn('vendors', [
            'location_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'industry', // Opsional: meletakkan kolom setelah 'industry'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('vendors', 'location_id');
    }
}
