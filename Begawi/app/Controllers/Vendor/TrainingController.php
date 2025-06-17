<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\TrainingModel;
use App\Models\JobCategoryModel;
use App\Models\LocationModel;
use App\Models\TrainingApplicationModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TrainingController extends BaseController
{
    public function index()
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');
        $data = [
            'title' => 'Manajemen Pelatihan',
            'trainings' => $trainingModel->where('vendor_id', $vendorId)->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('vendor/trainings/index', $data);
    }

    public function newTraining()
    {
        $categoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Publikasikan Pelatihan Baru',
            'categories' => $categoryModel->findAll(),
            'locations' => $locationModel->findAll(),
            'training' => null,
        ];
        return view('vendor/trainings/form', $data);
    }

    public function createTraining()
    {
        $trainingModel = new TrainingModel();
        $data = $this->request->getPost();
        $data['vendor_id'] = session()->get('profile_id');

        if (isset($data['start_date']) && !empty($data['start_date'])) {
            $data['start_date'] = date('Y-m-d H:i:s', strtotime($data['start_date']));
        }
        if (isset($data['end_date']) && !empty($data['end_date'])) {
            $data['end_date'] = date('Y-m-d H:i:s', strtotime($data['end_date']));
        } else {
            $data['end_date'] = null;
        }

        if (!$trainingModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $trainingModel->errors());
        }

        return redirect()->to('/vendor/dashboard')->with('success', 'Pelatihan berhasil ditambahkan.');
    }

    public function editTraining($id = null)
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');
        $training = $trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first();

        if (!$training) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Pelatihan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $categoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Edit Pelatihan',
            'training' => $training,
            'categories' => $categoryModel->findAll(),
            'locations' => $locationModel->findAll(),
        ];
        return view('vendor/trainings/form', $data);
    }

    public function updateTraining($id = null)
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        if (!$trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        $data = $this->request->getPost();

        foreach ($data as $key => $value) {
            if ($value === '') {
                unset($data[$key]);
            }
        }

        if (isset($data['start_date']) && !empty($data['start_date'])) {
            $data['start_date'] = date('Y-m-d H:i:s', strtotime($data['start_date']));
        }
        if (isset($data['end_date'])) {
            if (empty($data['end_date'])) {
                $data['end_date'] = null;
            } else {
                $data['end_date'] = date('Y-m-d H:i:s', strtotime($data['end_date']));
            }
        }

        if (!$trainingModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $trainingModel->errors());
        }
        return redirect()->to('/vendor/dashboard')->with('success', 'Pelatihan berhasil diperbarui.');
    }

    public function deleteTraining($id = null)
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        if (!$trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        $trainingModel->delete($id);

        return redirect()->to('/vendor/dashboard')->with('success', 'Pelatihan berhasil dihapus.');
    }

    public function showParticipants($trainingId = null)
    {
        $trainingModel = new TrainingModel();
        $applicationModel = new TrainingApplicationModel();
        $vendorId = session()->get('profile_id');

        $training = $trainingModel->where([
            'id' => $trainingId,
            'vendor_id' => $vendorId
        ])->first();

        if (!$training) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Pelatihan tidak ditemukan atau akses ditolak.');
        }

        $participants = $applicationModel->getApplicantsForTraining($trainingId);

        $data = [
            'title' => 'Daftar Peserta: ' . esc($training->title),
            'training' => $training,
            'participants' => $participants,
        ];

        return view('vendor/trainings/participants', $data);
    }

    public function updateParticipantStatus($applicationId)
    {
        $newStatus = $this->request->getPost('status');
        $allowedStatus = ['pending', 'accepted', 'rejected'];
        if (!in_array($newStatus, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $applicationModel = new TrainingApplicationModel();

        $application = $applicationModel
            ->select('trainings.vendor_id')
            ->join('trainings', 'trainings.id = training_applications.training_id')
            ->find($applicationId);

        if (!$application || $application->vendor_id != session()->get('profile_id')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $updateResult = $applicationModel->update($applicationId, ['status' => $newStatus]);

        if ($updateResult) {
            return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui status peserta.');
        }
    }

    public function downloadParticipantsPdf($trainingId = null)
    {
        $trainingModels = new TrainingModel();
        $vendorId = session()->get('profile_id');
        $training = $trainingModels->where(['id' => $trainingId, 'vendor_id' => $vendorId])->first();

        if (!$training) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Pelatihan tidak ditemukan atau akses ditolak.');
        }

        $applicationModel = new TrainingApplicationModel();
        $participants = $applicationModel->getApplicantsForTraining($trainingId);

        $html = view('vendor/trainings/pdf_template', [
            'training' => $training,
            'participants' => $participants,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('peserta_pelatihan_' . $training->id . '.pdf', ['Attachment' => false]);

    }

    public function downloadParticipantsExcel($trainingId = null)
    {
        // 1. Ambil data (logikanya sama persis dengan fungsi PDF)
        $trainingModel = new TrainingModel();
        $applicationModel = new TrainingApplicationModel();
        $vendorId = session()->get('profile_id');

        $training = $trainingModel->getTrainingDetails($trainingId);
        if (!$training || $training->vendor_id != $vendorId) {
            return redirect()->to('vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        $participants = $applicationModel->getApplicantsForTraining($trainingId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // header dan detail pelatihan
        $sheet->setCellValue('A1', 'Laporan Daftar Peserta');
        $sheet->setCellValue('A2', 'Judul Pelatihan:');
        $sheet->setCellValue('B2', $training->title);
        $sheet->setCellValue('A3', 'Penyelenggara:');
        $sheet->setCellValue('B3', $training->penyelenggara);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:A3')->getFont()->setBold(true);

        //header untuk tabel peserta
        $sheet->setCellValue('A5', 'No.');
        $sheet->setCellValue('B5', 'Nama Peserta');
        $sheet->setCellValue('C5', 'Email');
        $sheet->setCellValue('D5', 'Tanggal Mendaftar');
        $sheet->setCellValue('E5', 'Status');
        $sheet->getStyle('A5:E5')->getFont()->setBold(true);
        $sheet->getStyle('A5:E5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2F2F2');

        $rowNumber = 6;
        foreach ($participants as $index => $participant) {
            $sheet->setCellValue('A' . $rowNumber, $index + 1);
            $sheet->setCellValue('B' . $rowNumber, $participant->jobseeker_name);
            $sheet->setCellValue('C' . $rowNumber, $participant->jobseeker_email);
            $sheet->setCellValue('D' . $rowNumber, date('d-m-Y H:i', strtotime($participant->enrolled_at)));
            $sheet->setCellValue('E' . $rowNumber, ucfirst($participant->status));
            $rowNumber++;
        }

        // Atur lebar kolom otomatis
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // 6. Kirim file Excel ke browser untuk diunduh
        $writer = new Xlsx($spreadsheet);
        $fileName = 'laporan-peserta-' . url_title($training->title, '-', true) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
        exit();
    }
}