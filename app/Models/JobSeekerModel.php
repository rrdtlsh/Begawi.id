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
    protected $deletedField = 'deleted_at';

    public function getProfileByUserId(int $userId)
    {
        $profile = $this->select('jobseekers.*, locations.name as location_name')
            ->join('locations', 'locations.id = jobseekers.location_id', 'left')
            ->where('jobseekers.user_id', $userId)
            ->first();

        // Jika profil tidak ditemukan, kembalikan null
        if (!$profile) {
            return null;
        }

        // Ambil data keahlian (skills) dari tabel pivot
        $db = \Config\Database::connect();
        $skills = $db->table('jobseeker_skills')
            ->select('skills.name')
            ->join('skills', 'skills.id = jobseeker_skills.skill_id')
            ->where('jobseeker_skills.jobseeker_id', $profile->id)
            ->get()
            ->getResult();

        // Tambahkan data skills ke dalam objek profil
        $profile->skills = $skills;

        return $profile;
    }
}