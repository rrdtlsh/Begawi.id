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
        'cost',
        'is_paid',
        'quota'
    ];

    protected $useTimestamps = true;
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'title' => 'required|min_length[5]',
        'category_id' => 'required|is_natural_no_zero',
        'location_id' => 'required|is_natural_no_zero',
        'start_date' => 'required|valid_date',
        'duration' => 'required',
        'cost' => 'required|decimal',
    ];

    protected $validationMessages = [
        'category_id' => [
            'required' => 'Kategori pelatihan wajib dipilih.'
        ],
        'location_id' => [
            'required' => 'Lokasi pelatihan wajib dipilih.'
        ]
    ];
}

?>