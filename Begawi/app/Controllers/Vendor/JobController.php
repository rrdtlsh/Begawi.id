<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\JobCategoryModel;
use App\Models\LocationModel;
use App\Models\JobApplicationModel;

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

        // Ambil semua data dari form
        $postData = $this->request->getPost();
        
        // =============================================================
        // PERBAIKAN: Bersihkan input gaji sebelum disimpan
        // =============================================================
        if (!empty($postData['salary_min'])) {
            // Hapus semua karakter kecuali angka (0-9)
            $postData['salary_min'] = preg_replace('/[^0-9]/', '', $postData['salary_min']);
        }
        if (!empty($postData['salary_max'])) {
            // Hapus semua karakter kecuali angka (0-9)
            $postData['salary_max'] = preg_replace('/[^0-9]/', '', $postData['salary_max']);
        }

        // Tambahkan vendor_id
        $postData['vendor_id'] = session()->get('profile_id');

        // Lakukan save dengan data yang sudah bersih
        if (!$jobModel->save($postData)) {
            return redirect()->back()->withInput()->with('errors', $jobModel->errors());
        }

        return redirect()->to('/vendor/dashboard')->with('success', 'Lowongan pekerjaan berhasil dibuat.');
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
        $jobModel = new JobModel();
        $vendorId = session()->get('profile_id');

        // 1. Verifikasi kepemilikan
        if (!$jobModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        // 2. Ambil semua data dari form
        $postData = $this->request->getPost();

        // =============================================================
        // PENGGABUNGAN LOGIKA: Bersihkan gaji & hanya update yang diisi
        // =============================================================
        
        // 3. Bersihkan input gaji terlebih dahulu
        if (isset($postData['salary_min'])) {
            $postData['salary_min'] = preg_replace('/[^0-9]/', '', $postData['salary_min']);
        }
        if (isset($postData['salary_max'])) {
            $postData['salary_max'] = preg_replace('/[^0-9]/', '', $postData['salary_max']);
        }

        // 4. Logika Fleksibel: Hanya siapkan data yang diisi untuk di-update
        $dataToUpdate = [];
        foreach ($postData as $key => $value) {
            // Hanya masukkan ke array jika nilainya tidak kosong
            if ($value !== null && $value !== '') {
                $dataToUpdate[$key] = $value;
            }
        }
        
        // 5. Lakukan update hanya jika ada data yang akan diupdate
        if (!empty($dataToUpdate)) {
            if (!$jobModel->update($id, $dataToUpdate)) {
                return redirect()->back()->withInput()->with('errors', $jobModel->errors());
            }
        }

        return redirect()->to('/vendor/dashboard')->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
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
            return redirect()->to('/vendor/dashboard')->with('error', 'Lowongan pekerjaan tidak ditemukan.');
        }
        $jobModel->delete($id);

        return redirect()->to('/vendor/dashboard')->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }

    public function showApplicants($jobId = null)
    {
        $jobModel = new JobModel();
        $applicationModel = new JobApplicationModel();
        $vendorId = session()->get('profile_id');

        $job = $jobModel->where(['id' => $jobId, 'vendor_id' => $vendorId])->first();
        if (!$job) {
            return redirect()->to('vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        // 2. Ambil data pelamar menggunakan method dari JobApplicationModel
        $applicants = $applicationModel->getApplicantsForJob($jobId);

        $data = [
            'title' => 'Daftar Pelamar: ' . esc($job->title),
            'job' => $job,
            'applicants' => $applicants,
        ];

        // 3. Muat view baru yang akan menampilkan daftar
        return view('vendor/jobs/applicants', $data);
    }

    public function updateApplicantStatus($applicationId)
    {
        // 1. Validasi input
        $newStatus = $this->request->getPost('status');
        $allowedStatus = ['pending', 'reviewed', 'accepted', 'rejected'];
        if (!in_array($newStatus, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        // 2. Inisialisasi model
        $applicationModel = new JobApplicationModel();

        // 3. (PENTING) Verifikasi bahwa vendor berhak mengubah lamaran ini
        //    Ini mencegah vendor lain mengubah status lamaran yang bukan miliknya.
        $application = $applicationModel->getApplicationDetailsForEmail($applicationId);

        if (!$application || $application->vendor_id != session()->get('profile_id')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $oldStatus = $application->status;

        $updateResult = $applicationModel->update($applicationId, ['status' => $newStatus]);
    }
}