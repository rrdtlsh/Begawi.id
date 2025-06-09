<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\JobApplicationModel;
use App\Models\JobseekerModel;
use App\Models\JobModel; // <-- Pastikan JobModel dipanggil di sini

class JobApplicationController extends BaseController
{
    /**
     * Menampilkan halaman form lamaran pekerjaan.
     */
    public function showApplicationForm($jobId)
    {
        // ... (kode pengecekan session tetap sama) ...

        $jobModel = new JobModel();
        $jobseekerModel = new JobseekerModel(); // Panggil JobseekerModel

        $job = $jobModel->getJobDetails($jobId);
        // Ambil data profil untuk mendapatkan nomor telepon
        $profile = $jobseekerModel->where('user_id', session()->get('user_id'))->first();

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Lamar Posisi: ' . $job->title,
            'job' => $job,
            'profile' => $profile, // Kirim data profil ke view
        ];

        return view('job_application_form', $data);
    }

    /**
     * Memproses dan menyimpan data dari form lamaran.
     */
    public function submitApplication($jobId)
    {
        // Keamanan: Hanya jobseeker yang boleh mengirim lamaran
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->back()->with('error', 'Akses tidak sah.');
        }

        $applicationModel = new JobApplicationModel();
        $jobseekerId = session()->get('profile_id');

        // Aturan validasi untuk form
        $rules = [
            'cover_letter' => 'required',
            'resume' => [
                'label' => 'File CV',
                'rules' => 'uploaded[resume]|max_size[resume,2048]|ext_in[resume,pdf,doc,docx]',
            ]
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembali ke form dengan pesan error
            // Perbaikan redirect: kembali ke halaman form lamaran, bukan halaman generik
            return redirect()->to('/lamar/job/' . $jobId)->withInput()->with('errors', $this->validator->getErrors());
        }

        // Proses upload file CV
        $resumeFile = $this->request->getFile('resume');
        $newName = $resumeFile->getRandomName();
        $resumeFile->move('uploads/resumes', $newName);

        // Siapkan data untuk disimpan
        $data = [
            'job_id' => $jobId,
            'jobseeker_id' => $jobseekerId,
            'notes' => $this->request->getPost('cover_letter'),
            'resume_file_path' => $newName,
            'status' => 'pending',
        ];

        // Simpan data ke database
        if ($applicationModel->save($data)) {
            // Jika berhasil, arahkan kembali ke halaman detail dengan pesan sukses
            return redirect()->to('/lowongan/detail/' . $jobId)->with('success', 'Lamaran Anda berhasil terkirim!');
        } else {
            // Jika gagal, kembali ke form dengan pesan error
            return redirect()->back()->with('error', 'Gagal mengirim lamaran. Silakan coba lagi.');
        }
    }

    // Fungsi applyJob() yang lama sudah kita hapus karena tidak digunakan lagi
}