<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\SkillModel;
class SkillController extends BaseController
{
    public function new()
    {
        return view('admin/master_data/form_simple', ['title' => 'Tambah Keahlian Baru']);
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
}