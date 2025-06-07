<?php

namespace App\Models;

use CodeIgniter\Model;

class BookmarkedJobModel extends Model
{
    protected $table = 'bookmarked_jobs';
    protected $returnType = 'object';

    protected $allowedFields = [
        'jobseeker_id',
        'job_id',
    ];

    // Mengaktifkan timestamp hanya untuk 'created_at'
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false; // Kita tidak punya kolom updated_at di tabel ini

    public function getBookmarksByJobseeker($jobseekerId, $limit = 5)
    {
        return $this->select('
                        jobs.id as job_id,
                        jobs.title as job_title,
                        vendors.company_name
                    ')
            ->join('jobs', 'jobs.id = bookmarked_jobs.job_id')
            ->join('vendors', 'vendors.id = jobs.vendor_id')
            ->where('bookmarked_jobs.jobseeker_id', $jobseekerId)
            ->orderBy('bookmarked_jobs.created_at', 'DESC')
            ->findAll($limit);
    }
}