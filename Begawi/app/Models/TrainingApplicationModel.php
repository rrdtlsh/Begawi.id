<?php

namespace App\Models;

use CodeIgniter\Model;

class TrainingApplicationModel extends Model
{
    protected $table = 'training_applications';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'training_id',
        'jobseeker_id',
        'status',
    ];

    // Menggunakan timestamps dengan nama kolom custom
    protected $useTimestamps = true;
    protected $createdField = 'enrolled_at';
    protected $updatedField = 'updated_at';

    public function getHistoryByJobseeker($jobseekerId, $limit = 5)
    {
        return $this->select('
                        trainings.title, 
                        training_applications.status, 
                        training_applications.enrolled_at,
                        vendors.company_name as penyelenggara
                    ')
            ->join('trainings', 'trainings.id = training_applications.training_id')
            ->join('vendors', 'vendors.id = trainings.vendor_id', 'left') // left join jika ada pelatihan tanpa vendor
            ->where('training_applications.jobseeker_id', $jobseekerId)
            ->orderBy('training_applications.enrolled_at', 'DESC')
            ->findAll($limit);
    }

    public function getApplicantsForTraining(int $trainingId)
    {
        return $this->select('
                        training_applications.*,
                        users.fullname as jobseeker_name,
                        users.email as jobseeker_email,
                        jobseekers.phone as jobseeker_phone
                    ')
            ->join('jobseekers', 'jobseekers.id = training_applications.jobseeker_id', 'left')
            ->join('users', 'users.id = jobseekers.user_id', 'left')
            ->where('training_applications.training_id', $trainingId)
            ->orderBy('training_applications.enrolled_at', 'ASC')
            ->findAll();
    }

    public function getStatusCountsByJobseeker($jobseekerId)
    {
        $result = $this->select('status, COUNT(id) as count')
            ->where('jobseeker_id', $jobseekerId)
            ->groupBy('status')
            ->findAll();

        // Siapkan array dengan semua kemungkinan status
        $counts = [
            'pending'  => 0,
            'approved' => 0, // Gunakan 'approved' sesuai status pelatihan
            'rejected' => 0,
        ];

        // Isi array dengan hasil hitungan dari database
        foreach ($result as $row) {
            if (array_key_exists($row->status, $counts)) {
                $counts[$row->status] = (int) $row->count;
            }
        }
        return $counts;
    }
}