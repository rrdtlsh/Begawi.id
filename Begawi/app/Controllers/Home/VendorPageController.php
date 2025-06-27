<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\VendorModel;
use App\Models\JobModel;
use App\Models\TrainingModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class VendorPageController extends BaseController
{
    public function index()
    {
        $vendorModel = new VendorModel();
        $data = [
            'title' => 'Daftar Perusahaan Terpercaya',
            'vendors' => $vendorModel->getPublishedVendors(12),
            'pager' => $vendorModel->pager,
        ];
        return view('guest/vendor_list_page', $data);
    }

    public function detail($vendorId = null)
    {
        $vendorModel = new VendorModel();
        $jobModel = new JobModel();
        $trainingModel = new TrainingModel();

        $vendor = $vendorModel->getProfileById($vendorId);

        if (!$vendor) {
            throw PageNotFoundException::forPageNotFound();
        }

        $jobs = $jobModel->where('vendor_id', $vendorId)
            ->where('application_deadline >', date('Y-m-d H:i:s'))
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $trainings = $trainingModel->getTrainingsByVendor($vendorId);

        $trainings = $trainingModel->where('vendor_id', $vendorId)
            ->where('start_date >', date('Y-m-d H:i:s'))
            ->orderBy('start_date', 'ASC')
            ->findAll();

        $data = [
            'title' => $vendor->company_name,
            'vendor' => $vendor,
            'jobs' => $jobs,
            'trainings' => $trainings,
        ];
        return view('guest/vendor_detail_page', $data);
    }
}
