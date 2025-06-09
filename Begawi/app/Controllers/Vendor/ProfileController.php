<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VendorModel;
use App\Models\LocationModel;

class ProfileController extends BaseController
{
    public function edit()
    {
        $vendorModel = new VendorModel();
        $locationModel = new LocationModel();
        $userId = session()->get('user_id');
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

    public function update()
    {
        $userModel = new UserModel();
        $vendorModel = new VendorModel();
        $userId = session()->get('user_id');
        $vendorId = session()->get('profile_id');

        $rules = [
            'company_name' => 'required|min_length[3]',
            'location_id' => 'required|is_natural_no_zero',
            'contact' => 'required|min_length[9]',
            'company_logo' => [
                'label' => 'Logo Perusahaan',
                'rules' => 'is_image[company_logo]|mime_in[company_logo,image/jpg,image/jpeg,image/png]|max_size[company_logo,1024]',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $logoFile = $this->request->getFile('company_logo');
        $vendorData = [
            'company_name' => $this->request->getPost('company_name'),
            'industry' => $this->request->getPost('industry'),
            'location_id' => $this->request->getPost('location_id'),
            'company_address' => $this->request->getPost('company_address'),
            'contact' => $this->request->getPost('contact'),
            'website' => $this->request->getPost('website'),
            'company_profile' => $this->request->getPost('company_profile'),
        ];

        if ($logoFile->isValid() && !$logoFile->hasMoved()) {
            $currentVendor = $vendorModel->find($vendorId);
            $oldLogo = $currentVendor->company_logo_path;

            if ($oldLogo && file_exists('uploads/logos/' . $oldLogo)) {
                unlink('uploads/logos/' . $oldLogo);
            }
            $newLogoName = $logoFile->getRandomName();
            $logoFile->move('uploads/logos', $newLogoName);
            $vendorData['company_logo_path'] = $newLogoName;
        }

        $vendorModel->update($vendorId, $vendorData);

        $userData = ['fullname' => $this->request->getPost('company_name')];
        $userModel->update($userId, $userData);
        session()->set('fullname', $userData['fullname']);

        return redirect()->to('/vendor/dashboard')->with('success', 'Profil berhasil diperbarui.');
    }
}