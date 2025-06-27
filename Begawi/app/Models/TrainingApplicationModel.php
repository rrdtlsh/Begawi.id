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
        'rejection_reason',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'enrolled_at';
    protected $updatedField = 'updated_at';

    public function getHistoryByJobseeker($jobseekerId, $limit = 5)
    {
        return $this->select('
                            training_applications.id,
                            trainings.title, 
                            training_applications.status, 
                            training_applications.enrolled_at,
                            training_applications.rejection_reason,
                            vendors.company_name as penyelenggara
                        ')
            ->join('trainings', 'trainings.id = training_applications.training_id')
            ->join('vendors', 'vendors.id = trainings.vendor_id', 'left')
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

        $counts = [
            'pending' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];

        foreach ($result as $row) {
            if (array_key_exists($row->status, $counts)) {
                $counts[$row->status] = (int) $row->count;
            }
        }
        return $counts;
    }
}
