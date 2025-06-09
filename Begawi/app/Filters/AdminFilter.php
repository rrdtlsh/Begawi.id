<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika sesi 'isLoggedIn' tidak ada ATAU peran bukan 'admin'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            // Redirect ke halaman login dengan pesan error
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki hak akses ke halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa
    }
}