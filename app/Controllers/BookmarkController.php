<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookmarkedJobModel;
use App\Models\BookmarkedTrainingModel;

class BookmarkController extends BaseController
{
    public function toggle()
    {
        // Pastikan ini adalah jobseeker yang sedang login
        if (session()->get('role') !== 'jobseeker') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Hanya pencari kerja yang bisa menyimpan.'])->setStatusCode(403);
        }

        $jobseekerId = session()->get('profile_id');
        $itemId = $this->request->getPost('item_id');
        $itemType = $this->request->getPost('item_type');

        // Pilih model yang sesuai
        $model = ($itemType === 'job') ? new BookmarkedJobModel() : new BookmarkedTrainingModel();
        $key = ($itemType === 'job') ? 'job_id' : 'training_id';

        $existing = $model->where('jobseeker_id', $jobseekerId)->where($key, $itemId)->first();

        if ($existing) {
            // Jika sudah ada, hapus
            $model->where('jobseeker_id', $jobseekerId)->where($key, $itemId)->delete();
            return $this->response->setJSON(['status' => 'success', 'action' => 'removed']);
        } else {
            // Jika belum ada, tambahkan
            $model->insert(['jobseeker_id' => $jobseekerId, $key => $itemId]);
            return $this->response->setJSON(['status' => 'success', 'action' => 'added']);
        }
    }
}