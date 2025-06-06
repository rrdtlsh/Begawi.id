<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'company_name',
        'company_email',
        'company_profile',
        'company_logo_path',
        'industry',
        'company_size',
        'contact',
        'company_address',
        'website'
    ];

    protected $useTimestamps = true;
    protected $deletedField = 'deleted_at';

    public function getVendorProfileByUserId(int $userId)
    {
        return $this->select('vendors.*, locations.name as location_name, users.email as user_email')
                    ->join('users', 'users.id = vendors.user_id')
                    ->join('locations', 'locations.id = vendors.location_id', 'left')
                    ->where('vendors.user_id', $userId)
                    ->first();
    }
}