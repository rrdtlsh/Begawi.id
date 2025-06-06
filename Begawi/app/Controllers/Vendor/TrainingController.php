<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\TrainingModel; // Ganti ke TrainingModel
use App\Models\JobCategoryModel;
use App\Models\LocationModel;

class TrainingController extends BaseController
{
    /**
     * Menampilkan daftar pelatihan milik vendor.
     */
    public function index()
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        $data = [
            'title' => 'Manajemen Pelatihan',
            'trainings' => $trainingModel->where('vendor_id', $vendorId)->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('vendor/trainings/index', $data); // Arahkan ke view trainings
    }

    /**
     * Menampilkan form untuk membuat pelatihan baru.
     */
    public function newTraining()
    {
        $categoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Publikasikan Pelatihan Baru',
            'categories' => $categoryModel->findAll(),
            'locations' => $locationModel->findAll(),
        ];
        return view('vendor/trainings/form', $data); // Arahkan ke view trainings
    }

    /**
     * Memproses data dari form pembuatan pelatihan baru.
     */
    public function createTraining()
    {
        $trainingModel = new TrainingModel();

        $data = $this->request->getPost();
        $data['vendor_id'] = session()->get('profile_id');

        if (!$trainingModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $trainingModel->errors());
        }

        return redirect()->to('/vendor/trainings')->with('success', 'Pelatihan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit pelatihan.
     */
    public function editTraining($id = null)
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        $training = $trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first();

        if (!$training) {
            return redirect()->to('/vendor/trainings')->with('error', 'Pelatihan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $categoryModel = new JobCategoryModel();
        $locationModel = new LocationModel();

        $data = [
            'title' => 'Edit Pelatihan',
            'training' => $training, // Kirim data $training
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

        if (!$trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/trainings')->with('error', 'Akses ditolak.');
        }

        $data = $this->request->getPost();

        // Hapus field kosong agar tidak menimpa data yang ada saat update
        foreach ($data as $key => $value) {
            if ($value === '') {
                unset($data[$key]);
            }
        }

        if (!$trainingModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $trainingModel->errors());
        }

        return redirect()->to('/vendor/trainings')->with('success', 'Pelatihan berhasil diperbarui.');
    }

    /**
     * Menghapus pelatihan.
     */
    public function deleteTraining($id = null)
    {
        $trainingModel = new TrainingModel();
        $vendorId = session()->get('profile_id');

        if (!$trainingModel->where(['id' => $id, 'vendor_id' => $vendorId])->first()) {
            return redirect()->to('/vendor/trainings')->with('error', 'Akses ditolak.');
        }

        $trainingModel->delete($id);

        return redirect()->to('/vendor/trainings')->with('success', 'Pelatihan berhasil dihapus.');
    }
}
?>