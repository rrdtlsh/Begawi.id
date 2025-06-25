<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\SkillModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
class SkillController extends BaseController
{
    public function new()
    {
        return view('admin/master_data/form_skill', ['title' => 'Tambah Keahlian Baru']);
    }

    public function create()
    {
        if (!$this->validate(['name' => 'required|is_unique[skills.name]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new SkillModel())->save($this->request->getPost());
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Keahlian berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $model = new \App\Models\SkillModel();
        $skill = $model->find($id);

        if (empty($skill)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Keahlian tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Keahlian',
            'skill' => $skill
        ];

        return view('admin/master_data/form_skill', $data);
    }


    public function update($id = null)
    {
        if (!$this->validate(['name' => "required|is_unique[skills.name,id,{$id}]"])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new SkillModel())->update($id, $this->request->getPost());
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Keahlian berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        (new SkillModel())->delete($id);
        return redirect()->to(route_to('admin.master-data.index'))->with('success', 'Keahlian berhasil dihapus.');
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

        $skillModel = new SkillModel();
        $skillModel->ignore(true)->insertBatch($dataToInsert);
        $count = $skillModel->db->affectedRows();
        return redirect()->to(route_to('admin.master-data.index'))->with('success', "{$count} data keahlian baru berhasil diimpor.");
    }
}