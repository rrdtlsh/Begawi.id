<?php
namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\LocationModel;
use App\Models\JobCategoryModel;
use App\Models\JobApplicationModel;

class JobPageController extends BaseController
{
    public function __construct()
    {
        // Memuat Text Helper (untuk word_limiter) dan URL Helper (untuk site_url)
        helper(['text', 'url']);
    }
    public function index()
    {
        $jobModel = new JobModel();
        $locationModel = new LocationModel();
        $jobCategoryModel = new JobCategoryModel();

        $keyword = $this->request->getVar('keyword');
        $location = $this->request->getVar('location');
        $category = $this->request->getVar('category');

        $jobs = $jobModel->getPublishedJobs($keyword, $location, $category);

        $data = [
            'title' => 'Cari Lowongan Pekerjaan',
            'jobs' => $jobs->paginate(9),
            'pager' => $jobModel->pager,
            'locations' => $locationModel->findAll(),
            'categories' => $jobCategoryModel->findAll(),
            'old_input' => [
                'keyword' => $keyword,
                'location' => $location,
                'category' => $category,
            ]
        ];
        return view('job_list_page', $data);
    }

    public function detail($id = null)
    {
        $jobModel = new JobModel();
        $job = $jobModel->getJobDetails($id);

        // Jika lowongan dengan ID tersebut tidak ada, tampilkan halaman 404
        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $hasApplied = false;
        // Cek apakah user sudah melamar, HANYA jika dia adalah jobseeker yang login
        if (session()->get('isLoggedIn') && session()->get('role') === 'jobseeker') {
            $appModel = new JobApplicationModel();
            $jobseekerId = session()->get('profile_id');
            $existingApplication = $appModel->where([
                'job_id' => $id,
                'jobseeker_id' => $jobseekerId
            ])->first();

            if ($existingApplication) {
                $hasApplied = true;
            }
        }

        $data = [
            'title' => $job->title,
            'job' => $job,
            'hasApplied' => $hasApplied, // Kirim status lamaran (true/false) ke view
        ];
        return view('job_detail_page', $data);
    }
}
?>