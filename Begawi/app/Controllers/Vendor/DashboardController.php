<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\VendorModel;
use App\Models\JobModel; // Asumsi Anda punya model untuk postingan, misal: JobModel

class DashboardController extends BaseController
{
    public function index()
    {

        $vendorModel = new VendorModel();
        $db = \Config\Database::connect();

        $userId = session()->get('user_id');
        $vendorId = session()->get('profile_id');

        // 1. Ambil data profil vendor
        $vendorProfile = $vendorModel->getVendorProfileByUserId($userId);

        // 2. Ambil daftar postingan (gabungan jobs dan trainings)
        $jobsQuery = $db->table('jobs')
            ->select("id, title, 'Lowongan Pekerjaan' as type, created_at")
            ->where('vendor_id', $vendorId);

        $postings = $db->table('trainings')
            ->select("id, title, 'Pelatihan' as type, created_at")
            ->where('vendor_id', $vendorId)
            ->union($jobsQuery) // Gabungkan dengan query jobs
            ->orderBy('created_at', 'DESC') // Urutkan hasil gabungan
            ->get()
            ->getResult();


        // Siapkan semua data untuk dikirim ke view
        $vendorData = [
            'title' => 'Dashboard Vendor',
            'vendor' => $vendorProfile,
            'postings' => $postings,
        ];

        // Jika user bukan vendor atau data tidak ditemukan, arahkan keluar
        if (session()->get('role') !== 'vendor' || !$vendorData) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Akses tidak sah.');
        }

        // 6. Tampilkan view dan kirim datanya
        return view('vendor/dashboard_view', $vendorData);
    }
}