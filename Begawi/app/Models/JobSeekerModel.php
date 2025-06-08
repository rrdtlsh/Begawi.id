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

    public function getJobseekerSkills(int $jobseekerId)
    {
        // Asumsi 'jobseeker_skills' adalah tabel pivot dengan 'jobseeker_id' dan 'skill_id'
        // Asumsi 'skills' adalah tabel skill dengan 'id' dan 'name'
        return $this->db->table('jobseeker_skills')
            ->select('skills.id, skills.name')
            ->join('skills', 'skills.id = jobseeker_skills.skill_id')
            ->where('jobseeker_id', $jobseekerId)
            ->get()->getResultObject();
    }

    protected $validationRules = [
        'fullname' => 'required|min_length[3]|max_length[255]',
        'location_id' => 'required|integer',
        'phone' => 'permit_empty|regex_match[/^[0-9\s\-\(\)]+$/]|max_length[20]',
        'summary' => 'permit_empty|string',
        'profile_picture' => 'permit_empty|uploaded[profile_picture]|max_size[profile_picture,2048]|is_image[profile_picture]',
        'resume' => 'permit_empty|uploaded[resume]|max_size[resume,2048]|ext_in[resume,pdf,doc,docx]',
        'skills' => 'required' // Jika skills wajib diisi saat daftar
    ];

    // ATURAN VALIDASI UNTUK UPDATE (EDIT PROFIL)
    protected $validationRulesUpdate = [
        'fullname' => 'permit_empty|min_length[3]|max_length[255]', // TIDAK WAJIB
        'location_id' => 'permit_empty|integer', // TIDAK WAJIB
        'phone' => 'permit_empty|regex_match[/^[0-9\s\-\(\)]+$/]|max_length[20]', // TIDAK WAJIB
        'summary' => 'permit_empty|string', // TIDAK WAJIB
        'profile_picture' => 'permit_empty|uploaded[profile_picture]|max_size[profile_picture,2048]|is_image[profile_picture]',
        'resume' => 'permit_empty|uploaded[resume]|max_size[resume,2048]|ext_in[resume,pdf,doc,docx]',
        'skills' => 'permit_empty' // Jika skills tidak wajib diisi ulang saat edit
    ];

    protected $validationMessages = [
        'fullname' => [
            'required' => 'Nama lengkap wajib diisi.',
            'max_length' => 'Nama lengkap maksimal {param} karakter.'
        ],
        'location_id' => [
            'required' => 'Domisili wajib dipilih.',
            'is_natural_no_zero' => 'Domisili tidak valid.'
        ],
        'phone' => [
            'regex_match' => 'Format nomor telepon tidak valid.'
        ],
        'profile_picture' => [
            'is_image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mime_in' => 'Format gambar tidak didukung (gunakan jpg, jpeg, png).',
            'max_size' => 'Ukuran foto profil maksimal {param}KB.'
        ],
        'resume' => [
            'max_size' => 'Ukuran file CV maksimal {param}KB.',
            'ext_in' => 'Format file CV tidak didukung (gunakan PDF, DOC, DOCX).'
        ],
        'skills' => [
            'required' => 'Keahlian wajib dipilih.'
        ]
    ];
}