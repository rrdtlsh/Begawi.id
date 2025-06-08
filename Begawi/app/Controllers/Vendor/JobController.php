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
        $applicationModel = new \App\Models\JobApplicationModel();
        $vendorId = session()->get('profile_id');

        $job = $jobModel->where(['id' => $jobId, 'vendor_id' => $vendorId])->first();
        if (!$job) {
            return redirect()->to('vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        $data = [
            'title' => 'Daftar Pelamar: ' . esc($job->title),
            'job' => $job,
            'applicants' => $applicationModel->getApplicantsForJob($jobId),
        ];

        return view('vendor/jobs/applicants', $data);
    }

    public function updateApplicantStatus($applicationId)
    {
        $newStatus = $this->request->getPost('status');
        $allowedStatus = ['pending', 'reviewed', 'accepted', 'rejected'];
        if (!in_array($newStatus, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $applicationModel = new \App\Models\JobApplicationModel();

        // Verifikasi kepemilikan
        $application = $applicationModel->find($applicationId);
        $jobModel = new JobModel();
        $job = $jobModel->find($application->job_id);
        if ($job->vendor_id != session()->get('profile_id')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // Update status
        $applicationModel->update($applicationId, ['status' => $newStatus]);

        // Kirim email JIKA statusnya 'accepted'
        if ($newStatus === 'accepted') {
            helper('email');
            $appDetail = $applicationModel->getApplicationDetailsForEmail($applicationId);

            $emailData = [
                'jobseeker_name' => $appDetail->jobseeker_name,
                'job_title' => $appDetail->job_title,
                'company_name' => $appDetail->company_name,
            ];
            $body = view('emails/application_accepted_email', $emailData);

            send_email($appDetail->jobseeker_email, $appDetail->jobseeker_name, 'Kabar Baik Lamaran Anda!', $body);
        }

        return redirect()->back()->with('success', 'Status pelamar berhasil diperbarui.');
    }

    public function showApplicantDetail($applicationId)
{
    $applicationModel = new \App\Models\JobApplicationModel();
    $jobseekerModel = new \App\Models\JobseekerModel(); // Untuk mengambil skills
    $vendorId = session()->get('profile_id');

    // 1. Ambil detail lamaran dari model
    $applicant = $applicationModel->getApplicantDetail($applicationId);

    // 2. Verifikasi: Cek apakah lamaran ada & milik vendor yang benar
    if (!$applicant || $applicant->vendor_id != $vendorId) {
        return redirect()->to('vendor/dashboard')->with('error', 'Lamaran tidak ditemukan atau akses ditolak.');
    }

    // 3. Ambil data skills pelamar
    // Pastikan jobseeker_id ada sebelum memanggil method ini
    $applicant->skills = $jobseekerModel->getJobseekerSkills($applicant->jobseeker_id);

    $data = [
        'title' => 'Detail Pelamar: ' . esc($applicant->jobseeker_name),
        'applicant' => $applicant
    ];

    // 4. Tampilkan view baru yang akan kita buat selanjutnya
    return view('vendor/jobs/applicant_detail', $data);
}
}