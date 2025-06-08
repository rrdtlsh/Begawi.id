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

        $data = $this->request->getPost();
        $data['vendor_id'] = session()->get('profile_id');

        if (!$jobModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $jobModel->errors());
        }

        // PERBAIKAN: Redirect ke /vendor/jobs (plural)
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

        // Cek kepemilikan, sudah benar
        if (!$jobModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Akses ditolak.');
        }


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


        if ($updateResult) {
            // 5. Kirim email jika status berubah menjadi 'accepted' DAN status sebelumnya BUKAN 'accepted'
            if ($newStatus === 'accepted' && $oldStatus !== 'accepted') {
                $toEmail = $application->jobseeker_email;
                $toName = $application->jobseeker_name;
                $subject = "Selamat! Lamaran Anda Diterima untuk Posisi " . esc($application->job_title);
                $body = view('emails/application_accepted_email', [
                    'jobTitle' => esc($application->job_title),
                    'companyName' => esc($application->company_name ?? 'Penyedia Jasa'),
                    'jobDetailsLink' => site_url('lowongan/detail/' . $application->job_id),
                ]);
                $altBody = "Selamat! Lamaran Anda Diterima untuk Posisi " . esc($application->job_title) . " di " . esc($application->company_name ?? 'Penyedia Jasa') . ". Silakan cek detailnya di: " . site_url('lowongan/detail/' . $application->job_id);

                if (\send_email_via_phpmailer($toEmail, $toName, $subject, $body, $altBody)) {
                    // Email berhasil dikirim
                    return redirect()->back()->with('success', 'Status pelamar berhasil diperbarui dan email pemberitahuan terkirim.');
                } else {
                    // Email gagal dikirim (error akan di-log oleh helper)
                    return redirect()->back()->with('warning', 'Status pelamar berhasil diperbarui, tetapi email pemberitahuan gagal terkirim.');
                }
            } else {
                // Status berhasil diperbarui, tapi bukan accepted atau sudah accepted sebelumnya
                return redirect()->back()->with('success', 'Status pelamar berhasil diperbarui.');
            }
        } else {
            // Gagal update status di database
            return redirect()->back()->with('error', 'Gagal memperbarui status pelamar.');
        }

    }
}