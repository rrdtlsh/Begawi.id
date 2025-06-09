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
        return view('guest/job_list_page', $data);
    }

    public function detail($id = null)
    {
        $jobModel = new JobModel();
        $job = $jobModel->getJobDetails($id);

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $hasApplied = false;
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
            'hasApplied' => $hasApplied,
        ];
        return view('guest/job_detail_page', $data);
    }
}
?>