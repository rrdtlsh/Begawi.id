<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JobCategoryModel; // Jangan lupa use Model

class JobCategoryController extends BaseController
{
    // Method index bisa dihapus karena tidak kita gunakan di rute
    // public function index() { ... }

    // Method show bisa dihapus karena tidak kita gunakan di rute
    // public function show($id = null) { ... }

    public function new()
    {
        $data = ['title' => 'Tambah Kategori Baru'];
        return view('admin/master_data/form_category', $data);
    }

    public function create()
    {
        // === SALIN MULAI DARI SINI ===
        $rules = [
            'name' => 'required|is_unique[job_categories.name]',
            'icon_path' => 'permit_empty|string',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new JobCategoryModel())->save($this->request->getPost());
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Kategori berhasil ditambahkan.');
        // === AKHIR SALIN ===
    }

    public function edit($id = null)
    {
        // === SALIN MULAI DARI SINI ===
        $model = new JobCategoryModel();
        $category = $model->find($id);
        if (empty($category)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan.');
        }
        $data = [
            'title' => 'Edit Kategori',
            'category' => $category,
        ];
        return view('admin/master_data/form_category', $data);
        // === AKHIR SALIN ===
    }

    public function update($id = null)
    {
        // === SALIN MULAI DARI SINI ===
        $rules = ['name' => "required|is_unique[job_categories.name,id,{$id}]", 'icon_path' => 'permit_empty|string'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new JobCategoryModel())->update($id, $this->request->getPost());
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Kategori berhasil diperbarui.');
        // === AKHIR SALIN ===
    }

    public function delete($id = null)
    {
        // === SALIN MULAI DARI SINI ===
        (new JobCategoryModel())->delete($id);
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Kategori berhasil dihapus.');
        // === AKHIR SALIN ===
    }
}