<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\LocationModel;
use App\Models\JobCategoryModel;
use App\Models\BookmarkedJobModel;

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


        $bookmarkedJobs = [];
        if (session()->get('isLoggedIn') && session()->get('role') === 'jobseeker') {
            $bookmarkedJobModel = new BookmarkedJobModel();
            // Ambil ID semua pekerjaan yang di-bookmark oleh user ini
            $bookmarks = $bookmarkedJobModel->where('jobseeker_id', session()->get('profile_id'))->findAll();
            $bookmarkedJobs = array_column($bookmarks, 'job_id');
        }

        $data = [
            'title' => 'Cari Lowongan Pekerjaan',
            'jobs' => $jobs->paginate(9),
            'pager' => $jobModel->pager,
            'locations' => $locationModel->findAll(),
            'categories' => $jobCategoryModel->findAll(),
            'bookmarkedJobs' => $bookmarkedJobs, // Kirim daftar ID bookmark
            'old_input' => [
                'keyword' => $keyword,
                'location' => $location,
                'category' => $category,
            ]
        ];
        return view('job_list_page', $data);
    }
}
?>