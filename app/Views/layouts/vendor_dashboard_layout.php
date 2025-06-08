<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Vendor Dashboard - Begawi') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Khusus Vendor (Pastikan file ini ada di public/css/) -->
    <link rel="stylesheet" href="<?= base_url('css/vendor.css') ?>">

</head>
<body>

    <!-- Sidebar Menu -->
    <aside class="sidebar d-flex flex-column p-3" id="sidebar">
        <div class="text-center mb-4">
           <a href="<?= site_url('vendor/dashboard') ?>">
                <img src="<?= base_url('images/Logo_Begawi.png') ?>" 
                     onerror="this.onerror=null;this.src='https://placehold.co/200x80/2A5C0F/ffffff?text=BEGAWI';" 
                     alt="Logo Begawi" style="height: 98px; object-fit: contain;">
           </a>
        </div>
        <nav class="nav sidebar-nav flex-column">
            <a class="nav-link active" href="<?= site_url('vendor/dashboard') ?>"><i class="bi bi-house-door-fill"></i><span>Beranda Vendor</span></a>
            
            <!-- Posting dengan submenu -->
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="postingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-plus-circle-fill"></i><span>Posting</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="postingDropdown">
                    <li><a class="dropdown-item" href="<?= site_url('vendor/jobs/new') ?>">Lowongan</a></li>
                    <li><a class="dropdown-item" href="<?= site_url('vendor/trainings/new') ?>">Pelatihan</a></li>
                </ul>
            </div>
             <a class="nav-link" href="<?= site_url('vendor/profile/edit') ?>"><i class="bi bi-person-circle"></i><span>Edit Profil</span></a>
        </nav>
        <div class="mt-auto p-3">
            <a href="<?= site_url('logout') ?>" class="btn logout-btn w-100 d-flex align-items-center justify-content-center">
                <i class="bi bi-box-arrow-right me-2"></i>
                <span class="fw-bold">Log Out</span>
            </a>
        </div>
    </aside>

    <!-- Konten Utama -->
    <div id="main-content">
        <main class="p-3 p-md-4">
            <!-- Menampilkan pesan flashdata jika ada -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Slot untuk konten spesifik halaman -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
