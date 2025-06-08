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

    public function getHistoryByJobseeker($jobseekerId)
    {
        return $this->select('
                        job_applications.status,
                        jobs.title as job_title,
                        vendors.company_name,
                        vendors.company_logo_path
                    ')
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->join('vendors', 'vendors.id = jobs.vendor_id')
            ->where('job_applications.jobseeker_id', $jobseekerId)
            ->orderBy('job_applications.applied_at', 'DESC')
            ->findAll();
    }

    public function getStatusCountsByJobseeker($jobseekerId)
    {
        $result = $this->select('status, COUNT(id) as count')
            ->where('jobseeker_id', $jobseekerId)
            ->groupBy('status')
            ->findAll();

        $counts = [
            'pending' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];

        foreach ($result as $row) {
            // Ubah nama status agar cocok dengan desain (accepted -> Diterima, dll)
            if ($row->status === 'pending')
                $counts['pending'] = $row->count;
            if ($row->status === 'accepted')
                $counts['accepted'] = $row->count;
            if ($row->status === 'rejected')
                $counts['rejected'] = $row->count;
        }
        return $counts;
    }

    public function getApplicantsForJob($jobId)
    {
        // Asumsi tabel 'users' berisi 'name' dan 'email'.
        return $this->select(
                'job_applications.id as application_id, 
                job_applications.status, 
                job_applications.applied_at,
                job_applications.resume_file_path, 
                users.fullname as jobseeker_name,
                users.email as jobseeker_email'
            )
            // Hanya perlu join ke tabel users untuk mendapatkan nama & email
            ->join('users', 'users.id = job_applications.jobseeker_id', 'left')
            // JOIN ke jobseeker_profiles dihapus
            ->where('job_applications.job_id', $jobId)
            ->orderBy('job_applications.applied_at', 'DESC')
            ->findAll();
    }

}