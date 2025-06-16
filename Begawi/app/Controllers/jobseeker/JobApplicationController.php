<?php

namespace App\Controllers\jobseeker;

use App\Controllers\BaseController;
use App\Models\JobApplicationModel;
use App\Models\JobseekerModel;
use App\Models\JobModel;

class JobApplicationController extends BaseController
{
    protected $jobApplicationModel;
    protected $jobModel;
    protected $jobseekerModel;

    public function __construct()
    {
        $this->jobApplicationModel = new JobApplicationModel();
        $this->jobModel = new JobModel();
        $this->jobseekerModel = new JobseekerModel(); 
    }

    public function showApplicationForm($jobId)
    {
        $job = $this->jobModel->getJobDetails($jobId);
        $profile = $this->jobseekerModel->where('user_id', session()->get('user_id'))->first();

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Lamar Posisi: ' . $job->title,
            'job' => $job,
            'profile' => $profile,
            'application' => null
        ];

        return view('jobseeker\application\job_application_form', $data);
    }

    public function submitApplication($jobId)
    {
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->back()->with('error', 'Akses tidak sah.');
        }

        $jobseekerId = session()->get('profile_id');

        $rules = [
            'cover_letter' => 'required',
            'resume' => [
                'label' => 'File CV',
                'rules' => 'uploaded[resume]|max_size[resume,2048]|ext_in[resume,pdf,doc,docx]',
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/lamar/job/' . $jobId)->withInput()->with('errors', $this->validator->getErrors());
        }

        $resumeFile = $this->request->getFile('resume');
        $newName = $resumeFile->getRandomName();
        $resumeFile->move(ROOTPATH . 'public/uploads/resumes', $newName);

        $data = [
            'job_id' => $jobId,
            'jobseeker_id' => $jobseekerId,
            'notes' => $this->request->getPost('cover_letter'),
            'resume_file_path' => $newName,
            'status' => 'pending',
        ];

        if ($this->jobApplicationModel->save($data)) {
            return redirect()->to('/lowongan/detail/' . $jobId)->with('success', 'Lamaran Anda berhasil terkirim!');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim lamaran. Silakan coba lagi.');
        }
    }

    public function edit ($applicationId)
    {
        $jobseekerId = session()->get('profile_id');

        if (!$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        $application = $this->jobApplicationModel->find($applicationId);

        if (!$application || $application->jobseeker_id != $jobseekerId || $application->status !== 'pending') {
            return redirect()->to('/jobseeker/history')->with('error', 'Lamaran tidak ditemukan, atau tidak dapat diedit karena statusnya.');
        }

        $job = $this->jobModel->find($application->job_id); 

        $data = [
            'title' => 'Edit Lamaran: ' . esc($job->title ?? 'Tidak Diketahui'),
            'application' => $application, 
            'job' => $job, 
            'profile' => $this->jobseekerModel->where('user_id', session()->get('user_id'))->first(), 
        ];

        return view('jobseeker\application\job_application_form', $data); 
    }

    public function update($applicationId)
    {
        $jobseekerId = session()->get('profile_id');

        if (!$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        $application = $this->jobApplicationModel->find($applicationId);

        // Validasi: lamaran harus ada, milik jobseeker yang login, dan statusnya 'pending'
        if (!$application || $application->jobseeker_id != $jobseekerId || $application->status !== 'pending') {
            return redirect()->to('/jobseeker/history')->with('error', 'Lamaran tidak ditemukan, atau tidak dapat diupdate karena statusnya.');
        }

        // Aturan validasi untuk update lamaran
        $rules = [
            'cover_letter' => 'required|max_length[500]', // Pastikan nama field sesuai dengan form HTML
            'resume' => [ // Opsional: jika jobseeker bisa mengganti CV
                'label' => 'File CV',
                'rules' => 'if_exist|uploaded[resume]|max_size[resume,2048]|ext_in[resume,pdf,doc,docx]',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataToUpdate = [
            'notes' => $this->request->getPost('cover_letter'), 
            'updated_at' => date('Y-m-d H:i:s'), 
        ];

        // Handle upload file resume jika ada file baru
        $resumeFile = $this->request->getFile('resume');
        if ($resumeFile && $resumeFile->isValid() && !$resumeFile->hasMoved()) {
            $oldFilePath = $application->resume_file_path; 
            $newName = $resumeFile->getRandomName();
            $resumeFile->move(ROOTPATH . 'public/uploads/resumes', $newName);
            $dataToUpdate['resume_file_path'] = 'uploads/resumes/' . $newName;

            if ($oldFilePath && file_exists(ROOTPATH . 'public/' . $oldFilePath)) {
                unlink(ROOTPATH . 'public/' . $oldFilePath);
            }
        }

        if ($this->jobApplicationModel->update($applicationId, $dataToUpdate)) {
            return redirect()->to('/jobseeker/history')->with('success', 'Lamaran Anda berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui lamaran. Silakan coba lagi.');
        }
    }

    public function delete($applicationId)
    {
        $jobseekerId = session()->get('profile_id');

        if (!$jobseekerId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        $application = $this->jobApplicationModel->find($applicationId);

        // Validasi: lamaran harus ada dan milik jobseeker yang login
        if (!$application || $application->jobseeker_id != $jobseekerId) {
            return redirect()->to('/jobseeker/history')->with('error', 'Lamaran tidak ditemukan, atau Anda tidak memiliki akses.');
        }

        // Hapus file resume terkait jika ada
        if ($application->resume_file_path && file_exists(ROOTPATH . 'public/' . $application->resume_file_path)) {
            unlink(ROOTPATH . 'public/' . $application->resume_file_path);
        }

        if ($this->jobApplicationModel->delete($applicationId)) {
            return redirect()->to('/jobseeker/history')->with('success', 'Lamaran berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus lamaran.');
        }
    }
}