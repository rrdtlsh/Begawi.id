<?php 

namespace App\Controllers;

use App\Models\JobsModel;

class Jobs extends BaseController
{
    protected $jobsModel;

    public function __construct()
    {
        $this->jobsModel = \Config\Services::jobsModel();
    }

    public function detail($id)
    {
        $data['jobs'] = $this->jobsModel->find($id);

        if (empty($data['jobs'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['isLoggedIn'] = session()->get('isLoggedIn');
        return view('job_detail_page', $data);
    }

    public function apply($id)
    {
        
    }
}

?>