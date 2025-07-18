<?php

namespace App\Models;

use CodeIgniter\Model;

class JobSeekerModel extends Model
{
    protected $table = 'jobseekers';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'location_id',
        'profile_picture_path',
        'summary',
        'resume_path',
        'phone'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function getProfileByUserId(int $userId)
    {
        $profile = $this->select('jobseekers.*, locations.name as location_name')
            ->join('locations', 'locations.id = jobseekers.location_id', 'left')
            ->where('jobseekers.user_id', $userId)
            ->first();

        if (!$profile) {
            return null;
        }

        $db = \Config\Database::connect();
        $skills = $db->table('jobseeker_skills')
            ->select('skills.id, skills.name')
            ->join('skills', 'skills.id = jobseeker_skills.skill_id')
            ->where('jobseeker_skills.jobseeker_id', $profile->id)
            ->get()->getResult();

        $profile->skills = $skills;
        return $profile;
    }

    public function getJobseekerSkills(int $jobseekerId)
    {
        if ($jobseekerId <= 0) {
            return [];
        }

        return $this->db->table('jobseeker_skills')
            ->select('skills.id, skills.name')
            ->join('skills', 'skills.id = jobseeker_skills.skill_id')
            ->where('jobseeker_skills.jobseeker_id', $jobseekerId)
            ->get()
            ->getResultObject();
    }
}