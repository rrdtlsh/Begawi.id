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
            ->get()->getResult();
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
}

?>