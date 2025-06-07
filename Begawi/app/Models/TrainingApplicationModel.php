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
}