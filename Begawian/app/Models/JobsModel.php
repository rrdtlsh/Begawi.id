<?php

namespace App\Models;

use CodeIgniter\Model;

class JobsModel extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['vendor_id', 'title', 'description', 'location', 'salary_range'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


    public function getJobsWithVendor()
    {
        $this->select('jobs.*, users.username as vendor_username, vendors.company_name');
        $this->join('users', 'users.id = jobs.vendor_id');
        $this->join('vendors', 'vendors.user_id = users.id', 'left');
        return $this;
    }
}