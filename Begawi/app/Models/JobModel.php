<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'vendor_id',
        'category_id',
        'location_id',
        'title',
        'description',
        'qualifications',
        'application_instructions',
        'job_type',
        'salary_min',
        'salary_max',
        'application_deadline',
        'contact_email',
        'contact_phone'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'category_id' => 'required|integer',
        'location_id' => 'required|integer',
        'description' => 'required',
        'qualifications' => 'permit_empty|string',
        'application_instructions' => 'permit_empty|string',
        'job_type' => 'required|in_list[Full-time,Part-time,Contract,Internship,Freelance]',
        'salary_min' => 'permit_empty|integer|less_than_equal_to[salary_max]',
        'salary_max' => 'permit_empty|integer',
        'application_deadline' => 'required',
        'quota' => 'permit_empty|integer|greater_than_equal_to[1]',
        'contact_email' => 'required|valid_email',
        'contact_phone' => 'permit_empty|regex_match[/^[0-9\s\-\(\)]+$/]|max_length[20]',
    ];

    protected $validationRulesUpdate = [
        'title' => 'permit_empty|max_length[255]',
        'category_id' => 'permit_empty|integer',
        'location_id' => 'permit_empty|integer',
        'description' => 'permit_empty',
        'qualifications' => 'permit_empty|string',
        'application_instructions' => 'permit_empty|string',
        'job_type' => 'permit_empty|in_list[Full-time,Part-time,Contract,Internship,Freelance]',
        'salary_min' => 'permit_empty|integer|less_than_equal_to[salary_max]',
        'salary_max' => 'permit_empty|integer',
        'application_deadline' => 'permit_empty|valid_date[Y-m-d H:i:s]|after_now',
        'quota' => 'permit_empty|integer|greater_than_equal_to[1]',
        'contact_email' => 'permit_empty|valid_email',
        'contact_phone' => 'permit_empty|regex_match[/^[0-9\s\-\(\)]+$/]|max_length[20]',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Judul lowongan wajib diisi.',
            'max_length' => 'Judul maksimal {param} karakter.'
        ],
        'description' => [
            'required' => 'Deskripsi lowongan wajib diisi.',
        ],
        'category_id' => [
            'required' => 'Kategori wajib dipilih.',
            'integer' => 'Kategori tidak valid.'
        ],
        'location_id' => [
            'required' => 'Lokasi wajib dipilih.',
            'integer' => 'Lokasi tidak valid.'
        ],
        'application_deadline' => [
            'required' => 'Batas waktu lamaran wajib diisi.',
            'valid_date' => 'Format batas waktu tidak valid.',
            'after_now' => 'Batas waktu lamaran harus di masa depan.'
        ],
        'contact_email' => [
            'required' => 'Email kontak wajib diisi.',
            'valid_email' => 'Format email kontak tidak valid.'
        ],
        'salary_min' => [
            'less_than_equal_to' => 'Gaji minimum tidak boleh lebih besar dari gaji maksimum.',
            'integer' => 'Gaji minimum harus berupa angka.'
        ],
        'salary_max' => [
            'integer' => 'Gaji maksimum harus berupa angka.'
        ],
    ];

    public function getLatestJobs(int $limit = 6)
    {
        return $this->select('
                jobs.*,
                vendors.company_name,
                vendors.company_logo_path,
                locations.name as location_name
            ')
            ->join('vendors', 'vendors.id = jobs.vendor_id', 'left')
            ->join('locations', 'locations.id = jobs.location_id', 'left')
            ->orderBy('jobs.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function searchJobs($keyword, $locationId, $categoryId)
    {
        $builder = $this->select('
                jobs.*,
                vendors.company_name,
                vendors.company_logo_path,
                locations.name as location_name
            ')
            ->join('vendors', 'vendors.id = jobs.vendor_id', 'left')
            ->join('locations', 'locations.id = jobs.location_id', 'left');

        if (!empty($keyword)) {
            $builder->like('jobs.title', $keyword);
        }
        if (!empty($locationId)) {
            $builder->where('jobs.location_id', $locationId);
        }
        if (!empty($categoryId)) {
            $builder->where('jobs.category_id', $categoryId);
        }

        return $builder->orderBy('jobs.created_at', 'DESC')->get()->getResult();
    }

    public function getPublishedJobs($keyword = null, $location = null, $category = null)
    {
        $builder = $this->select('
                            jobs.*,
                            vendors.company_name,
                            vendors.company_logo_path,
                            locations.name as location_name
                        ')
            ->join('vendors', 'vendors.id = jobs.vendor_id')
            ->join('locations', 'locations.id = jobs.location_id', 'left');

        if ($keyword) {
            $builder->groupStart();
            $builder->like('jobs.title', $keyword);
            $builder->orLike('jobs.description', $keyword);
            $builder->groupEnd();
        }

        if ($location) {
            $builder->where('jobs.location_id', $location);
        }

        if ($category) {
            $builder->where('jobs.category_id', $category);
        }
        return $builder->orderBy('jobs.created_at', 'DESC');
    }

    public function getJobDetails(int $id)
    {
        return $this->select('
                        jobs.*,
                        vendors.company_name,
                        vendors.company_profile,
                        vendors.company_logo_path,
                        locations.name as location_name,
                        job_categories.name as category_name
                    ')
            ->join('vendors', 'vendors.id = jobs.vendor_id')
            ->join('locations', 'locations.id = jobs.location_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->where('jobs.id', $id)
            ->first();
    }
}