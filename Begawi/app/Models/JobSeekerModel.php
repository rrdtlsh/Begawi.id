<?php

namespace App\Models;

use CodeIgniter\Model;

class JobSeekerModel extends Model
{
    protected $table = 'jobseekers';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'profile_picture_path',
        'summary',
        'resume_path',
        'phone',
        'address'
    ];

    protected $useTimestamps = true;
    protected $deletedField = 'deleted_at';
}