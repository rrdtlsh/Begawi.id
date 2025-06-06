<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\VendorModel;
use App\Models\JobModel; // Asumsi Anda punya model untuk postingan, misal: JobModel

class DashboardController extends BaseController
{
    public function index()
    {
        // 1. Ambil ID user dari session yang sedang login
        $userId = session()->get('user_id');

        // 2. Load model yang diperlukan
        $vendorModel = new VendorModel();
        
        // Asumsi Anda punya model untuk postingan (lowongan/training)
        // Jika belum ada, Anda bisa kosongkan bagian ini dulu
        // $jobModel = new JobModel();

        // 3. Ambil data profil vendor dari database berdasarkan user_id
        // Kita akan buat method getVendorProfileByUserId() di VendorModel
        $vendorData = $vendorModel->getVendorProfileByUserId($userId);
        
        // 4. Ambil data postingan yang dibuat oleh vendor ini
        // Gunakan profile_id dari session yang sudah kita set saat login
        $vendorProfileId = session()->get('profile_id');
        // $postings = $jobModel->where('vendor_id', $vendorProfileId)->findAll(); // Contoh query

        // 5. Siapkan semua data untuk dikirim ke view
        $data = [
            'title' => 'Dashboard Vendor',
            'vendor' => $vendorData,
            'postings' => [] // Kita isi array kosong dulu untuk postingan
            // 'postings' => $postings // Gunakan ini jika JobModel sudah siap
        ];
        
        // Jika user bukan vendor atau data tidak ditemukan, arahkan keluar
        if (session()->get('role') !== 'vendor' || !$vendorData) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Akses tidak sah.');
        }

        // 6. Tampilkan view dan kirim datanya
        return view('vendor/dashboard_view', $data);
    }
}