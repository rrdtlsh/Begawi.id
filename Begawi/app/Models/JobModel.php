<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'vendor_id',
        'category_id',
        'location_id',
        'title',
        'description',
        'qualifications',
        'job_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'application_deadline',
        'application_instructions',
        'contact_email',
        'contact_phone',
        'quota'
    ];

    protected $useTimestamps = true;
    protected $deletedField = 'deleted_at';

    protected $validationMessages = [
        'category_id' => [
            'required' => 'Kategori pekerjaan wajib dipilih.',
            'is_natural_no_zero' => 'Kategori pekerjaan tidak valid.'
        ],
        'location_id' => [
            'required' => 'Lokasi wajib dipilih.',
            'is_natural_no_zero' => 'Lokasi tidak valid.'
        ]
    ];

    public function getJobsWithDetails()
    {
        $this->select('jobs.*, vendors.company_name, vendors.company_logo_path, job_categories.name as category_name');
        $this->join('vendors', 'vendors.id = jobs.vendor_id');
        $this->join('job_categories', 'job_categories.id = jobs.category_id', 'left');
        return $this;
    }
}