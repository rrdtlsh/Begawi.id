<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VendorModel;
use App\Models\LocationModel;

class ProfileController extends BaseController
{
    /**
     * Menampilkan halaman form untuk mengedit profil vendor.
     */
    public function edit()
    {
        $vendorModel = new VendorModel();
        $locationModel = new LocationModel();

        $userId = session()->get('user_id');

        // Ambil data profil vendor yang sedang login
        $vendor = $vendorModel->getVendorProfileByUserId($userId);

        if (!$vendor) {
            return redirect()->to('/vendor/dashboard')->with('error', 'Profil tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Profil Usaha',
            'vendor' => $vendor,
            'locations' => $locationModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('vendor/profile/form', $data);
    }

    /**
     * Memproses data dari form edit profil dengan validasi.
     */
    public function update()
    {
        $userModel = new UserModel();
        $vendorModel = new VendorModel();

        $userId = session()->get('user_id');
        $vendorId = session()->get('profile_id');

        // --- PERUBAHAN DI SINI: Aturan Validasi untuk Field Penting ---
        $rules = [
            'company_name' => 'required|min_length[3]',
            'location_id' => 'required|is_natural_no_zero',
            'company_address' => 'required|min_length[10]',
            'contact' => 'required|min_length[9]',
        ];

        $messages = [
            'location_id' => [
                'required' => 'Domisili Usaha wajib dipilih.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // --- AKHIR PERUBAHAN VALIDASI ---

        // Siapkan data untuk tabel 'vendors'
        $vendorData = [
            'company_name' => $this->request->getPost('company_name'),
            'industry' => $this->request->getPost('industry'),
            'location_id' => $this->request->getPost('location_id'),
            'company_address' => $this->request->getPost('company_address'),
            'contact' => $this->request->getPost('contact'),
            'website' => $this->request->getPost('website'),
            'company_profile' => $this->request->getPost('company_profile'),
        ];

        // Siapkan data untuk tabel 'users' (menyamakan nama)
        $userData = [
            'fullname' => $this->request->getPost('company_name')
        ];

        // Lakukan update ke kedua tabel
        $vendorModel->update($vendorId, $vendorData);
        $userModel->update($userId, $userData);

        // Perbarui session dengan nama baru
        session()->set('fullname', $userData['fullname']);

        return redirect()->to('/vendor/dashboard')->with('success', 'Profil berhasil diperbarui.');
    }
}