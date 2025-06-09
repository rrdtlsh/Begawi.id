<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\JobApplicationModel;
use App\Models\JobseekerModel;
use App\Models\JobModel;

class JobApplicationController extends BaseController
{
    public function showApplicationForm($jobId)
    {

        $jobModel = new JobModel();
        $jobseekerModel = new JobseekerModel();

        $job = $jobModel->getJobDetails($jobId);
        $profile = $jobseekerModel->where('user_id', session()->get('user_id'))->first();

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Lamar Posisi: ' . $job->title,
            'job' => $job,
            'profile' => $profile,
        ];

        return view('jobseeker\application\job_application_form', $data);
    }

    public function submitApplication($jobId)
    {
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->back()->with('error', 'Akses tidak sah.');
        }

        $applicationModel = new JobApplicationModel();
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
        $resumeFile->move('uploads/resumes', $newName);

        $data = [
            'job_id' => $jobId,
            'jobseeker_id' => $jobseekerId,
            'notes' => $this->request->getPost('cover_letter'),
            'resume_file_path' => $newName,
            'status' => 'pending',
        ];

        if ($applicationModel->save($data)) {
            return redirect()->to('/lowongan/detail/' . $jobId)->with('success', 'Lamaran Anda berhasil terkirim!');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim lamaran. Silakan coba lagi.');
        }
    }
}