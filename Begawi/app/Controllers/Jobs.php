<?php

namespace App\Controllers;

use App\Models\JobsModel; // Pastikan nama model dan namespace sudah benar

class Jobs extends BaseController
{
    protected $jobsModel;
    protected $session; // Deklarasikan properti session

    public function __construct()
    {
        $this->jobsModel = new JobsModel();

        $this->session = \Config\Services::session();
    }

    public function detail($id) 
    {

        if (! $this->session->get('isLoggedIn')) {

            $this->session->set('redirect_url', 'job/detail/' . $id);

            return redirect()->to('register') 
                ->with('message', 'Untuk melihat detail pekerjaan, silakan daftar atau login terlebih dahulu.');
        }

        $data['job'] = $this->jobsModel->find($id); 

        if (empty($data['job'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Pekerjaan dengan ID: {$id} tidak ditemukan.");
        }

        return view('job_detail_page', $data);
    }

}
?>