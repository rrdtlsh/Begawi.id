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
        'experience_level',
        'salary_min',
        'salary_max',
        'application_deadline'
    ];
    protected $useTimestamps = true;

    /**
     * Mengambil data lowongan terbaru untuk halaman utama.
     */
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
            ->get()->getResult();
    }

    /**
     * Method untuk menangani logika pencarian.
     * INI ADALAH METHOD YANG DIBUTUHKAN SAAT ANDA MENEKAN "CARI".
     */
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

        // Filter berdasarkan kata kunci
        if ($keyword) {
            $builder->groupStart();
            $builder->like('jobs.title', $keyword);
            $builder->orLike('jobs.description', $keyword);
            $builder->groupEnd();
        }

        // Filter berdasarkan lokasi
        if ($location) {
            $builder->where('jobs.location_id', $location);
        }

        // Filter berdasarkan kategori
        if ($category) {
            $builder->where('jobs.category_id', $category);
        }

        // KEMBALIKAN BUILDER-NYA, JANGAN JALANKAN findAll() DI SINI
        return $builder->orderBy('jobs.created_at', 'DESC');
    }

}