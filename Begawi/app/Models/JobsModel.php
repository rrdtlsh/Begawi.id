<?php

namespace App\Models;

use CodeIgniter\Model;

class JobsModel extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'description',
        'location',
        'salary_range',
        'created_at'
    ];
}