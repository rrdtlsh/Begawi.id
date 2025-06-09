<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\VendorModel;
use App\Models\JobModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class VendorPageController extends BaseController
{
    /**
     * Menampilkan halaman daftar semua perusahaan.
     */
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

    /**
     * Menampilkan halaman detail satu perusahaan.
     */
    public function detail($vendorId = null)
    {
        $vendorModel = new VendorModel();
        $jobModel = new JobModel();

        // Menggunakan FUNGSI YANG SAMA, hanya beda kriteria
        $vendor = $vendorModel->getProfileById($vendorId);

        if (!$vendor) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Ambil semua lowongan aktif dari perusahaan ini
        $jobs = $jobModel->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => $vendor->company_name,
            'vendor' => $vendor,
            'jobs' => $jobs,
        ];
        return view('guest/vendor_detail_page', $data);
    }
}