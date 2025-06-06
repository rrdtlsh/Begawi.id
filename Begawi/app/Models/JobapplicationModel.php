<?php

namespace App\Models;

use CodeIgniter\Model;

class JobApplicationModel extends Model
{
    protected $table = 'job_applications';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'job_id',
        'jobseeker_id',
        'resume_file_path',
        'status',
        'notes',
    ];

    // Menggunakan timestamps
    protected $useTimestamps = true;
    protected $createdField = 'applied_at';
    protected $updatedField = 'updated_at';
}