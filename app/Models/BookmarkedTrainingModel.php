<?php

namespace App\Models;

use CodeIgniter\Model;

class BookmarkedTrainingModel extends Model
{
    protected $table = 'bookmarked_trainings';
    protected $returnType = 'object';
    protected $allowedFields = ['jobseeker_id', 'training_id'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    /**
     * Mengambil daftar pelatihan yang di-bookmark oleh jobseeker.
     */
    public function getBookmarksByJobseeker($jobseekerId, $limit = 5)
    {
        return $this->select('
                        trainings.id as training_id,
                        trainings.title as training_title,
                        vendors.company_name as penyelenggara
                    ')
            ->join('trainings', 'trainings.id = bookmarked_trainings.training_id')
            ->join('vendors', 'vendors.id = trainings.vendor_id', 'left')
            ->where('bookmarked_trainings.jobseeker_id', $jobseekerId)
            ->orderBy('bookmarked_trainings.created_at', 'DESC')
            ->findAll($limit);
    }
}