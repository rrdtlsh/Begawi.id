<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $table = 'locations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];

    protected $useTimestamps = false;

    protected $returnType = 'object';
}