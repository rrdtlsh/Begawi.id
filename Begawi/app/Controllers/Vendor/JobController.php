<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\JobCategoryModel;
use App\Models\LocationModel;

class JobController extends BaseController
{
    /**
     * Menampilkan daftar lowongan milik vendor yang sedang login.
     */
    public function index()
    {
        $jobModel = new JobModel();
        $vendorId = session()->get('profile_id');

        $data = [
            'title' => 'Manajemen Lowongan Pekerjaan',
            'jobs' => $jobModel->where('vendor_id', $vendorId)->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('vendor/jobs/index', $data);
    }

    /**
     * Menampilkan form untuk membuat lowongan baru.
     */
    public function newJob()
    {
        $jobCategoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Buat Lowongan Pekerjaan',
            'categories' => $jobCategoryModel->findAll(),
            'locations' => $locationModel->findAll(),
        ];

        // PERBAIKAN: Path ke view harusnya 'vendor/jobs/form' (plural)
        return view('vendor/jobs/form', $data);
    }

    /**
     * Memproses data dari form pembuatan lowongan baru.
     */
    public function createJob()
    {
        $jobModel = new JobModel();

        $data = $this->request->getPost();
        $data['vendor_id'] = session()->get('profile_id');

        if (!$jobModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $jobModel->errors());
        }

        // PERBAIKAN: Redirect ke /vendor/jobs (plural)
        return redirect()->to('/vendor/jobs')->with('success', 'Lowongan pekerjaan berhasil dibuat.');
    }

    public function editJob($id = null)
    {
        $jobModel = new JobModel();
        $vendorId = session()->get('profile_id');

        $job = $jobModel->where(['id' => $id, 'vendor_id' => $vendorId])->first();

        if (!$job) {
            // PERBAIKAN: Redirect ke /vendor/jobs (plural)
            return redirect()->to('/vendor/jobs')->with('error', 'Lowongan pekerjaan tidak ditemukan.');
        }

        // --- LOGIKA DILENGKAPI ---
        $jobCategoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Edit Lowongan Pekerjaan',
            'job' => $job, // Mengirim data lowongan yang akan diedit ke view
            'categories' => $jobCategoryModel->findAll(),
            'locations' => $locationModel->findAll(),
        ];

        return view('vendor/jobs/form', $data);
        // --- AKHIR LOGIKA YANG DILENGKAPI ---
    }

    /**
     * Memproses data dari form edit lowongan.
     */
    public function updateJob($id = null)
    {
        $jobModel = new \App\Models\JobModel();
        $vendorId = session()->get('profile_id');

        // Cek kepemilikan, sudah benar
        if (!$jobModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/jobs')->with('error', 'Akses ditolak.');
        }

        // --- LOGIKA BARU UNTUK UPDATE FLEKSIBEL ---

        // 1. Ambil semua data yang dikirim dari form
        $allPostData = $this->request->getPost();

        // 2. Siapkan array kosong untuk menampung data yang benar-benar akan di-update
        $dataToUpdate = [];

        // 3. Loop melalui setiap data yang dikirim
        //    Hanya masukkan ke array jika nilainya TIDAK KOSONG
        foreach ($allPostData as $key => $value) {
            if ($value !== null && $value !== '') {
                $dataToUpdate[$key] = $value;
            }
        }

        // 4. Lakukan update hanya jika ada data yang akan diupdate
        if (!empty($dataToUpdate)) {
            if (!$jobModel->update($id, $dataToUpdate)) {
                // Walaupun fleksibel, tetap bisa ada error dari validasi model jika tipe data salah
                return redirect()->back()->withInput()->with('errors', $jobModel->errors());
            }
        }
        // --- AKHIR LOGIKA BARU ---

        return redirect()->to('/vendor/jobs')->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    /**
     * Menghapus lowongan pekerjaan.
     */
    public function deleteJob($id = null)
    {
        $jobModel = new JobModel();
        $vendorId = session()->get('profile_id');

        $job = $jobModel->where(['id' => $id, 'vendor_id' => $vendorId])->first();

        if (!$job) {
            // PERBAIKAN: Redirect ke /vendor/jobs (plural)
            return redirect()->to('/vendor/jobs')->with('error', 'Lowongan pekerjaan tidak ditemukan.');
        }
        $jobModel->delete($id);

        return redirect()->to('/vendor/jobs')->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }
}