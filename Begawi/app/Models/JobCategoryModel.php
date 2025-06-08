<?php

namespace App\Models;

use CodeIgniter\Model;

class JobCategoryModel extends Model
{
    protected $table = 'job_categories';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $allowedFields = ['name', 'icon_path'];

    protected $useTimestamps = false;
}