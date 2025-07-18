<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\JobCategoryModel;
use App\Models\LocationModel;
use App\Models\JobApplicationModel;
use App\Models\JobSeekerModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JobController extends BaseController
{
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

    public function newJob()
    {
        $jobCategoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Buat Lowongan Pekerjaan',
            'categories' => $jobCategoryModel->findAll(),
            'locations' => $locationModel->findAll(),
        ];

        return view('vendor/jobs/form', $data);
    }

    public function createJob()
    {
        $jobModel = new JobModel();

        $postData = $this->request->getPost();

        if (!empty($postData['application_deadline'])) {
            $postData['application_deadline'] = str_replace('T', ' ', $postData['application_deadline']) . ':00';
        }

        if (!empty($postData['salary_min'])) {
            $postData['salary_min'] = preg_replace('/[^0-9]/', '', $postData['salary_min']);
        }
        if (!empty($postData['salary_max'])) {
            $postData['salary_max'] = preg_replace('/[^0-9]/', '', $postData['salary_max']);
        }

        $postData['vendor_id'] = session()->get('profile_id');

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
            return redirect()->to('/vendor/jobs')->with('error', 'Lowongan pekerjaan tidak ditemukan.');
        }

        $jobCategoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Edit Lowongan Pekerjaan',
            'job' => $job,
            'categories' => $jobCategoryModel->findAll(),
            'locations' => $locationModel->findAll(),
        ];

        return view('vendor/jobs/form', $data);
    }

    public function updateJob($id = null)
    {
        $jobModel = new JobModel();
        $vendorId = session()->get('profile_id');

        if (!$jobModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        $postData = $this->request->getPost();
        if (!empty($postData['application_deadline'])) {
            $postData['application_deadline'] = str_replace('T', ' ', $postData['application_deadline']) . ':00';
        }

        if (isset($postData['salary_min'])) {
            $postData['salary_min'] = preg_replace('/[^0-9]/', '', $postData['salary_min']);
        }
        if (isset($postData['salary_max'])) {
            $postData['salary_max'] = preg_replace('/[^0-9]/', '', $postData['salary_max']);
        }

        $dataToUpdate = [];
        foreach ($postData as $key => $value) {
            if ($value !== null && $value !== '') {
                $dataToUpdate[$key] = $value;
            }
        }

        if (!empty($dataToUpdate)) {
            if (!$jobModel->update($id, $dataToUpdate)) {
                return redirect()->back()->withInput()->with('errors', $jobModel->errors());
            }
        }

        return redirect()->to('/vendor/dashboard')->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    public function deleteJob($id = null)
    {
        $jobModel = new JobModel();
        $vendorId = session()->get('profile_id');

        $job = $jobModel->where(['id' => $id, 'vendor_id' => $vendorId])->first();

        if (!$job) {
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

        $data = [
            'title' => 'Daftar Pelamar: ' . esc($job->title),
            'job' => $job,
            'applicants' => $applicationModel->getApplicantsForJob($jobId),
        ];

        return view('vendor/jobs/applicants', $data);
    }

    public function updateApplicantStatus($applicationId)
    {
        $postData = $this->request->getPost();
        $newStatus = $postData['status'] ?? null;
        $rejectionReason = $postData['rejection_reason'] ?? null;
        $allowedStatus = ['pending', 'reviewed', 'accepted', 'rejected'];
        if (!in_array($newStatus, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $applicationModel = new JobApplicationModel();

        $application = $applicationModel->find($applicationId);

        $jobModel = new JobModel();
        $job = $jobModel->find($application->job_id);
        if ($job->vendor_id != session()->get('profile_id')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $dataToUpdate = [
            'status' => $newStatus,
            'rejection_reason' => ($newStatus === 'rejected') ? $rejectionReason : null,
        ];

        $applicationModel->update($applicationId, $dataToUpdate, ['status' => $newStatus]);

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
        $applicationModel = new JobApplicationModel();
        $jobseekerModel = new JobseekerModel();
        $vendorId = session()->get('profile_id');

        $applicant = $applicationModel->getApplicantDetail($applicationId);

        if (!$applicant || $applicant->vendor_id != $vendorId) {
            return redirect()->to('vendor/dashboard')->with('error', 'Lamaran tidak ditemukan atau akses ditolak.');
        }

        $applicant->skills = $jobseekerModel->getJobseekerSkills($applicant->jobseeker_id);

        $data = [
            'title' => 'Detail Pelamar: ' . esc($applicant->jobseeker_name),
            'applicant' => $applicant
        ];

        return view('vendor/jobs/applicant_detail', $data);
    }

    public function downloadApplicantsPdf($jobId = null)
    {
        $jobModel = new JobModel();
        $applicationModel = new JobApplicationModel();
        $vendorId = session()->get('profile_id');

        $job = $jobModel->getJobDetails($jobId);
        if (!$job || $job->vendor_id != $vendorId) {
            return redirect()->to('vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        $applicants = $applicationModel->getApplicantsForJob($jobId);

        $data = [
            'job' => $job,
            'applicants' => $applicants,
        ];

        $html = view('vendor/jobs/pdf_template', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('pendaftar_lowongan' . $job->id . '.pdf', ['Attachment' => false]);
    }

    public function downloadApplicantsExcel($jobId = null)
    {
        $jobModel = new JobModel();
        $applicationModel = new JobApplicationModel();
        $vendorId = session()->get('profile_id');

        $job = $jobModel->getJobDetails($jobId);
        if (!$job || $job->vendor_id != $vendorId) {
            return redirect()->to('vendor/dashboard')->with('error', 'Akses ditolak.');
        }
        $applicants = $applicationModel->getApplicantsForJob($jobId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Laporan Daftar Pelamar');
        $sheet->setCellValue('A2', 'Judul Lowongan:');
        $sheet->setCellValue('B2', $job->title);
        $sheet->setCellValue('A3', 'Perusahaan:');
        $sheet->setCellValue('B3', $job->company_name);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:A3')->getFont()->setBold(true);

        $sheet->setCellValue('A5', 'No.');
        $sheet->setCellValue('B5', 'Nama Pelamar');
        $sheet->setCellValue('C5', 'Email');
        $sheet->setCellValue('D5', 'Tanggal Melamar');
        $sheet->setCellValue('E5', 'Status');
        $sheet->getStyle('A5:E5')->getFont()->setBold(true);
        $sheet->getStyle('A5:E5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2F2F2');

        $rowNumber = 6;
        foreach ($applicants as $index => $applicant) {
            $sheet->setCellValue('A' . $rowNumber, $index + 1);
            $sheet->setCellValue('B' . $rowNumber, $applicant->jobseeker_name);
            $sheet->setCellValue('C' . $rowNumber, $applicant->jobseeker_email);
            $sheet->setCellValue('D' . $rowNumber, \CodeIgniter\I18n\Time::parse($applicant->applied_at, 'UTC')->setTimezone('Asia/Makassar')->format('d-m-Y H:i'));
            $sheet->setCellValue('E' . $rowNumber, ucfirst($applicant->status));
            $rowNumber++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'laporan-pelamar-' . url_title($job->title, '-', true) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
        exit();
    }
}
