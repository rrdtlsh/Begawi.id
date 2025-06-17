<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\LocationModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
class LocationController extends BaseController
{
    public function new()
    {
        return view('admin/master_data/form_location', ['title' => 'Tambah lokasi Baru']);
    }

    public function create()
    {
        if (!$this->validate(['name' => 'required|is_unique[locations.name]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new LocationModel())->save($this->request->getPost());
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $model = new \App\Models\LocationModel();
        $location = $model->find($id);

        if (empty($location)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Lokasi tidak ditemukan.');
        }
        $data = [
            'title' => 'Edit Lokasi',
            'location' => $location
        ];
        return view('admin/master_data/form_location', $data);
    }

    public function update($id = null)
    {
        if (!$this->validate(['name' => "required|is_unique[locations.name,id,{$id}]"])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new LocationModel())->update($id, $this->request->getPost());
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        (new LocationModel())->delete($id);
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Lokasi berhasil dihapus.');
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
            if (!empty(trim($row[0] ?? ''))) {
                $dataToInsert[] = ['name' => trim($row[0])];
            }
        }

        if (empty($dataToInsert)) {
            return redirect()->to(route_to('admin.master-data.index'))->with('error', 'Tidak ada data valid untuk diimpor.');
        }

        $locationModel = new LocationModel();
        $locationModel->ignore(true)->insertBatch($dataToInsert);
        $count = $locationModel->db->affectedRows();
        return redirect()->to(route_to('admin.master-data.index'))->with('success', "{$count} data lokasi baru berhasil diimpor.");
    }
}