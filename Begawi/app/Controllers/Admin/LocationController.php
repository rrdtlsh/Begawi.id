<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\LocationModel;
class LocationController extends BaseController
{
    public function new()
    {
        return view('admin/master_data/form_skill', ['title' => 'Tambah Keahlian Baru']);
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
        $model = new \App\Models\SkillModel();
        $skill = $model->find($id);

        if (empty($skill)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Keahlian tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Keahlian',
            'skill' => $skill
        ];

        // Memanggil view form yang spesifik untuk skill
        return view('admin/master_data/form_skill', $data);
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
}