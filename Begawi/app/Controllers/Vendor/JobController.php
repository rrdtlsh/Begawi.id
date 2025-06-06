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

    /**
     * Menampilkan form untuk mengedit lowongan yang sudah ada.
     */
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
        $jobModel = new JobModel();
        $vendorId = session()->get('profile_id');

        if (!$jobModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/jobs')->with('error', 'Akses ditolak.');
        }

        // --- LOGIKA DILENGKAPI ---
        $data = $this->request->getPost();

        if (!$jobModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $jobModel->errors());
        }

        return redirect()->to('/vendor/jobs')->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
        // --- AKHIR LOGIKA YANG DILENGKAPI ---
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