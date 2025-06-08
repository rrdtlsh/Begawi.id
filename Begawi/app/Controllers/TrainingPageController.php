<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TrainingModel;

class TrainingPageController extends BaseController
{
    public function index()
    {
        $trainingModel = new TrainingModel();

        // Mengambil data pelatihan dengan paginasi (9 item per halaman)
        // dan menyertakan detail penyelenggara dari tabel vendors
        $data = [
            'title' => 'Daftar Pelatihan & Workshop',
            'trainings' => $trainingModel->getPublishedTrainings(9),
            'pager' => $trainingModel->pager,
        ];

        return view('training_list_page', $data);
    }
}