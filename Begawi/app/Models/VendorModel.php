<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = true;

    // --- PASTIKAN FIELD INI LENGKAP ---
    protected $allowedFields = [
        'user_id',
        'company_name',
        'company_email',
        'location_id',
        'company_address', // Tambahkan ini
        'company_profile',
        'company_logo_path',
        'industry',
        'contact',
    ];

    protected $useTimestamps = true;
    protected $deletedField = 'deleted_at';
    //push github
    public function getVendorProfileByUserId(int $userId)
    {
        return $this->select(
            'vendors.*, 
            locations.name as location_name, 
            users.email as user_email'
        )
            ->join('users', 'users.id = vendors.user_id')
            ->join('locations', 'locations.id = vendors.location_id', 'left')
            ->where('vendors.user_id', $userId)
            ->first();
    }

    public function getProfileById(int $vendorId)
    {
        return $this->select('vendors.*, locations.name as location_name, users.email as user_email')
            ->join('users', 'users.id = vendors.user_id')
            ->join('locations', 'locations.id = vendors.location_id', 'left')
            ->where('vendors.id', $vendorId)
            ->first();
    }

    public function getPublishedVendors(int $perPage = 12)
    {
        return $this->select('vendors.id, vendors.company_name, vendors.industry, vendors.company_logo_path, locations.name as location_name')
            ->join('locations', 'locations.id = vendors.location_id', 'left')
            ->orderBy('vendors.company_name', 'ASC')
            ->paginate($perPage);
    }//
}