<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $table = 'locations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];

    // Kita tidak menggunakan created_at/updated_at di tabel locations
    protected $useTimestamps = false;

    protected $returnType = 'object';
}