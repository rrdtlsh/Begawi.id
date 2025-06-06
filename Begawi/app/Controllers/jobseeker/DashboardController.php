<?php
namespace App\Controllers\Jobseeker;
use App\Controllers\BaseController;
use App\Models\JobApplicationModel;

class DashboardController extends BaseController
{
    public function index()
    {
        // Cek apakah pengguna adalah jobseeker
        if (session()->get('role') !== 'jobseeker') {
            return redirect()->to('/');
        }

        if (empty(session()->get('fullname'))) {
            return redirect()->to('/profile/complete')->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        // Inisialisasi model untuk mengambil data aplikasi pekerjaan
        $applicationModel = new JobApplicationModel();

        // Ambil ID jobseeker dari sesi
        $jobseeker_id = session()->get('profile_id');

        // Hitung total aplikasi pekerjaan yang telah diajukan oleh jobseeker
        $totalApplications = $applicationModel->where('jobseeker_id', $jobseeker_id)->countAllResults();

        // Siapkan data untuk dikirim ke view
        $data = [
            'title' => 'Dashboard Jobseeker',
            'total_applications' => $totalApplications,
        ];

        // Tampilkan view dashboard jobseeker
        return view('jobseeker/dashboard', $data);
    }
}
?>