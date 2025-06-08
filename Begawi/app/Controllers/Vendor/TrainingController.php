<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\TrainingModel;
use App\Models\JobCategoryModel; // Digunakan untuk kategori pelatihan
use App\Models\LocationModel;    // Digunakan untuk lokasi pelatihan
use App\Models\TrainingApplicationModel; // Model untuk aplikasi pelatihan

class TrainingController extends BaseController
{
    /**
     * Menampilkan daftar pelatihan milik vendor yang sedang login.
     */
    public function index()
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        // Ambil pelatihan yang terkait dengan vendor_id yang sedang login
        $data = [
            'title' => 'Manajemen Pelatihan',
            'trainings' => $trainingModel->where('vendor_id', $vendorId)->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('vendor/trainings/index', $data);
    }

    /**
     * Menampilkan form untuk membuat pelatihan baru.
     */
    public function newTraining()
    {
        $categoryModel = new JobCategoryModel(); // Menggunakan JobCategoryModel untuk kategori pelatihan
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Publikasikan Pelatihan Baru',
            'categories' => $categoryModel->findAll(),
            'locations' => $locationModel->findAll(),
            'training' => null, // Untuk mode 'create', tidak ada data pelatihan lama
        ];
        return view('vendor/trainings/form', $data);
    }

    /**
     * Memproses data dari form pembuatan pelatihan baru.
     */
    public function createTraining()
    {
        $trainingModel = new TrainingModel();

        // Ambil semua data POST
        $data = $this->request->getPost();

        // Tambahkan vendor_id dari sesi
        $data['vendor_id'] = session()->get('profile_id');

        // Pastikan tanggal dan waktu diformat dengan benar untuk database jika diperlukan
        // Input datetime-local dari HTML adalah 'Y-m-d\TH:i'
        // Database membutuhkan 'Y-m-d H:i:s'
        if (isset($data['start_date']) && !empty($data['start_date'])) {
            $data['start_date'] = date('Y-m-d H:i:s', strtotime($data['start_date']));
        }
        if (isset($data['end_date']) && !empty($data['end_date'])) {
            $data['end_date'] = date('Y-m-d H:i:s', strtotime($data['end_date']));
        } else {
            // Jika end_date kosong, set sebagai null di database
            $data['end_date'] = null;
        }

        // Coba simpan data ke database
        if (!$trainingModel->save($data)) {
            // Jika validasi gagal, kembali ke form dengan input sebelumnya dan pesan error
            return redirect()->back()->withInput()->with('errors', $trainingModel->errors());
        }

        // Jika berhasil, redirect ke dashboard dengan pesan sukses
        return redirect()->to('/vendor/dashboard')->with('success', 'Pelatihan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit pelatihan.
     */
    public function editTraining($id = null)
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        // Cari data pelatihan berdasarkan ID dan vendor_id untuk memastikan kepemilikan
        $training = $trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first();

        // Jika pelatihan tidak ditemukan atau bukan milik vendor ini
        if (!$training) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Pelatihan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $categoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Edit Pelatihan',
            'training' => $training, // Kirim data pelatihan yang akan diedit ke view
            'categories' => $categoryModel->findAll(),
            'locations' => $locationModel->findAll(),
        ];
        return view('vendor/trainings/form', $data);
    }

    /**
     * Memproses data dari form edit pelatihan.
     */
    public function updateTraining($id = null)
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        // Cek kepemilikan pelatihan yang akan diedit
        if (!$trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        // Ambil semua data POST
        $data = $this->request->getPost();

        // Hapus field kosong agar tidak menimpa data yang ada saat update
        // Kecuali jika memang ingin diset ke NULL, maka biarkan saja.
        foreach ($data as $key => $value) {
            // Jika value kosong string (''), unset agar tidak menimpa data lama dengan kosong
            // Ini akan membuat field tersebut tidak di-update
            if ($value === '') {
                unset($data[$key]);
            }
        }

        // Handle format tanggal/waktu untuk start_date dan end_date
        if (isset($data['start_date']) && !empty($data['start_date'])) {
            $data['start_date'] = date('Y-m-d H:i:s', strtotime($data['start_date']));
        }
        // Pastikan end_date juga ditangani jika ada perubahan atau menjadi kosong
        if (isset($data['end_date'])) { // Cek jika field end_date dikirim di POST
            if (empty($data['end_date'])) {
                $data['end_date'] = null; // Set ke NULL jika kosong dari form
            } else {
                $data['end_date'] = date('Y-m-d H:i:s', strtotime($data['end_date']));
            }
        }

        // Lakukan update data pelatihan
        if (!$trainingModel->update($id, $data)) {
            // Jika validasi gagal, kembali ke form dengan input sebelumnya dan pesan error
            return redirect()->back()->withInput()->with('errors', $trainingModel->errors());
        }

        // Jika berhasil, redirect ke dashboard dengan pesan sukses
        return redirect()->to('/vendor/dashboard')->with('success', 'Pelatihan berhasil diperbarui.');
    }

    /**
     * Menghapus pelatihan.
     */
    public function deleteTraining($id = null)
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        // Cek kepemilikan pelatihan yang akan dihapus
        if (!$trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Akses ditolak.');
        }

        // Lakukan soft delete
        $trainingModel->delete($id);

        // Redirect ke dashboard dengan pesan sukses
        return redirect()->to('/vendor/dashboard')->with('success', 'Pelatihan berhasil dihapus.');
    }

    public function showParticipants($trainingId = null)
    {
        $trainingModel = new TrainingModel();
        $applicationModel = new TrainingApplicationModel();
        $vendorId = session()->get('profile_id');

        // 1. Verifikasi kepemilikan pelatihan
        $training = $trainingModel->where([
            'id' => $trainingId,
            'vendor_id' => $vendorId
        ])->first();

        if (!$training) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Pelatihan tidak ditemukan atau akses ditolak.');
        }

        // 2. Ambil data peserta
        $participants = $applicationModel->getApplicantsForTraining($trainingId);

        // 3. Siapkan data untuk view
        $data = [
            'title'        => 'Daftar Peserta: ' . esc($training->title),
            'training'     => $training,
            'participants' => $participants,
        ];

        // 4. Tampilkan view baru
        return view('vendor/trainings/participants', $data);
    }

    /**
     * Memproses pembaruan status peserta pelatihan.
     */
    public function updateParticipantStatus($applicationId)
    {
        // 1. Validasi input
        $newStatus = $this->request->getPost('status');
        $allowedStatus = ['pending', 'approved', 'rejected'];
        if (!in_array($newStatus, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        // 2. Inisialisasi model
        $applicationModel = new TrainingApplicationModel();

        // 3. Verifikasi bahwa vendor berhak mengubah status ini
        $application = $applicationModel
            ->select('trainings.vendor_id')
            ->join('trainings', 'trainings.id = training_applications.training_id')
            ->find($applicationId);

        if (!$application || $application->vendor_id != session()->get('profile_id')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // 4. Update status di database
        $applicationModel->update($applicationId, ['status' => $newStatus]);

        // 5. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
    }
}