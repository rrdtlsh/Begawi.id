<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JobCategoryModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JobCategoryController extends BaseController
{

    public function new()
    {
        $data = ['title' => 'Tambah Kategori Baru'];
        return view('admin/master_data/form_category', $data);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|is_unique[job_categories.name]',
            'icon_path' => 'permit_empty|string',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new JobCategoryModel())->save($this->request->getPost());
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
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
    }

    public function update($id = null)
    {
        $rules = ['name' => "required|is_unique[job_categories.name,id,{$id}]", 'icon_path' => 'permit_empty|string'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new JobCategoryModel())->update($id, $this->request->getPost());
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        (new JobCategoryModel())->delete($id);
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Kategori berhasil dihapus.');
    }


    public function processImport()
    {
        $rules = ['excel_file' => 'uploaded[excel_file]|max_size[excel_file,5120]|ext_in[excel_file,xlsx,xls]'];
        if (!$this->validate($rules)) {
            return redirect()->to(route_to('admin.master-data.index'))->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('excel_file');
        $spreadsheet = IOFactory::load($file->getTempName());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $dataToInsert = [];
        foreach (array_slice($rows, 1) as $row) {
            $categoryName = trim($row[0] ?? '');
            $iconPath = trim($row[1] ?? '');

            if (!empty($categoryName)) {
                $dataToInsert[] = ['name' => $categoryName, 'icon_path' => $iconPath];
            }
        }

        if (empty($dataToInsert)) {
            return redirect()->to(route_to('admin.master-data.index'))->with('error', 'Tidak ada data valid untuk diimpor.');
        }

        $categoryModel = new JobCategoryModel();
        $categoryModel->ignore(true)->insertBatch($dataToInsert);
        $count = $categoryModel->db->affectedRows();
        return redirect()->to(route_to('admin.master-data.index'))->with('success', "{$count} data kategori baru berhasil diimpor.");
    }
}