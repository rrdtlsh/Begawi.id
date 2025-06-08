<?php
namespace App\Models;

use CodeIgniter\Model;

class TrainingModel extends Model
{
    protected $table = 'trainings';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'vendor_id',
        'category_id',
        'location_id',
        'title',
        'description',
        'platform',
        'registration_instructions',
        'contact_email',
        'contact_phone',
        'start_date',
        'end_date',
        'duration',
        // 'duration_type', // <<< HAPUS BARIS INI
        'quota'
    ];

    protected $useTimestamps = true;
    protected $deletedField = 'deleted_at';

    // Aturan validasi untuk CREATE (pembuatan baru)
    protected $validationRules = [
        'title' => 'required|min_length[5]|max_length[255]',
        'category_id' => 'required|is_natural_no_zero',
        'location_id' => 'required|is_natural_no_zero',
        'description' => 'required',
        'platform' => 'required|max_length[100]',
        'registration_instructions' => 'required',
        'contact_email' => 'required|valid_email',
        'contact_phone' => 'permit_empty|regex_match[/^[0-9\s\-\(\)]+$/]|max_length[20]',
        'start_date' => 'required', // <<< PASTIKAN TANGGAL MULAI DI MASA DEPAN
        'end_date' => 'permit_empty',
        'duration' => 'required|integer|greater_than[0]', // <<< KEMBALIKAN KE INTEGER
        // 'duration_type'             => 'required|in_list[jam,hari]', // <<< HAPUS BARIS INI
        'quota' => 'permit_empty|integer|greater_than_equal_to[1]',
    ];

    // Aturan validasi untuk UPDATE (edit)
    protected $validationRulesUpdate = [
        'title' => 'permit_empty|min_length[5]|max_length[255]',
        'category_id' => 'permit_empty|is_natural_no_zero',
        'location_id' => 'permit_empty|is_natural_no_zero',
        'description' => 'permit_empty',
        'platform' => 'permit_empty|max_length[100]',
        'registration_instructions' => 'permit_empty',
        'contact_email' => 'permit_empty|valid_email',
        'contact_phone' => 'permit_empty|regex_match[/^[0-9\s\-\(\)]+$/]|max_length[20]',
        'start_date' => 'permit_empty',
        'end_date' => 'permit_empty|after_field[start_date]',
        'duration' => 'permit_empty|integer|greater_than[0]', // <<< KEMBALIKAN KE INTEGER
        'quota' => 'permit_empty|integer|greater_than_equal_to[1]',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Judul pelatihan wajib diisi.',
            'min_length' => 'Judul minimal 5 karakter.',
            'max_length' => 'Judul maksimal 255 karakter.'
        ],
        'category_id' => [
            'required' => 'Kategori pelatihan wajib dipilih.',
            'is_natural_no_zero' => 'Kategori pelatihan tidak valid.'
        ],
        'location_id' => [
            'required' => 'Lokasi pelatihan wajib dipilih.',
            'is_natural_no_zero' => 'Lokasi pelatihan tidak valid.'
        ],
        'description' => [
            'required' => 'Deskripsi pelatihan wajib diisi.',
            'min_length' => 'Deskripsi minimal 20 karakter.'
        ],
        'platform' => [
            'required' => 'Platform pelatihan wajib diisi.'
        ],
        'registration_instructions' => [
            'required' => 'Instruksi pendaftaran wajib diisi.'
        ],
        'contact_email' => [
            'required' => 'Email kontak wajib diisi.',
            'valid_email' => 'Format email kontak tidak valid.'
        ],
        'start_date' => [
            'required' => 'Tanggal mulai pelatihan wajib diisi.',
            'valid_date' => 'Format tanggal mulai tidak valid.',
            'after_now' => 'Tanggal mulai harus di masa depan.'
        ],
        'end_date' => [
            'valid_date' => 'Format tanggal selesai tidak valid.',
            'after_field' => 'Tanggal selesai harus setelah tanggal mulai.'
        ],
        'duration' => [
            'required' => 'Durasi pelatihan wajib diisi.',
            'integer' => 'Durasi harus berupa angka bulat.',
            'greater_than' => 'Durasi harus lebih besar dari 0.'
        ],
        // 'duration_type' => [ // <<< HAPUS PESAN VALIDASI INI
        //     'required' => 'Tipe durasi wajib dipilih.',
        //     'in_list' => 'Tipe durasi tidak valid (pilih Jam atau Hari).'
        // ],
        'quota' => [
            'integer' => 'Kuota harus berupa angka bulat.',
            'greater_than_equal_to' => 'Kuota tidak boleh negatif.'
        ]
    ];

    public function getLatestTrainings(int $limit = 3)
    {
        return $this->select('
                        trainings.*,
                        vendors.company_name,
                        locations.name as location_name
                    ')
            ->join('vendors', 'vendors.id = trainings.vendor_id', 'left')
            ->join('locations', 'locations.id = trainings.location_id', 'left')
            ->orderBy('trainings.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getPublishedTrainings(int $perPage = 9)
    {
        return $this->select('
                                trainings.*,
                                vendors.company_name as penyelenggara,
                                vendors.company_logo_path
                            ')
            ->join('vendors', 'vendors.id = trainings.vendor_id', 'left')
            ->orderBy('trainings.start_date', 'ASC')
            ->paginate($perPage);
    }
    //
    public function getTrainingDetails(int $id)
    {
        return $this->select('
                        trainings.*, 
                        vendors.company_name as penyelenggara, 
                        vendors.company_profile,
                        vendors.company_logo_path,
                        locations.name as location_name,
                        job_categories.name as category_name
                    ')
            ->join('vendors', 'vendors.id = trainings.vendor_id', 'left')
            ->join('locations', 'locations.id = trainings.location_id', 'left')
            ->join('job_categories', 'job_categories.id = trainings.category_id', 'left')
            ->where('trainings.id', $id)
            ->first();
    }
}