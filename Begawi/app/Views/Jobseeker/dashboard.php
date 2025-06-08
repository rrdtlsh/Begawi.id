<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Dashboard Saya') ?></title>
    <!-- Bootstrap dan Font Awesome dari CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap Icons untuk ikon pembeda -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .profile-card .img-thumbnail {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        .card-header h6 {
            margin-bottom: 0;
            font-weight: 600;
        }
        .list-group-item .badge {
            font-size: 0.8em;
            padding: .5em .75em;
        }
    </style>
</head>

<body style="background-color: #f8f9fa;">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/"><b>Begawi</b></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/">Cari Lowongan</a></li>
                    <li class="nav-item"><a class="nav-link" href="/jobseeker/history">Riwayat Aktivitas</a></li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> Halo, **<?= esc(session()->get('fullname')) ?>**!
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="/jobseeker/dashboard">Dashboard Saya</a>
                            <a class="dropdown-item" href="/jobseeker/profile/edit">Edit Profil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h2><?= esc($title) ?></h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <!-- KARTU PROFIL LENGKAP -->
        <?php if (!empty($profile)): ?>
            <div class="card mb-4 profile-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="<?= !empty($profile->profile_picture_path) ? '/uploads/avatars/' . esc($profile->profile_picture_path) : 'https://via.placeholder.com/120' ?>"
                                alt="Foto Profil" class="img-thumbnail rounded-circle mb-3">
                        </div>
                        <div class="col-md-7">
                            <h3><?= esc(session()->get('fullname')) ?></h3>
                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= esc($profile->location_name ?? 'Domisili belum diisi') ?>
                                | <i class="fas fa-phone"></i> <?= esc($profile->phone ?? 'Telepon belum diisi') ?>
                            </p>
                            <h6><strong>Keahlian Anda:</strong></h6>
                            <p>
                                <?php if (!empty($profile->skills)): ?>
                                    <?php foreach ($profile->skills as $skill): ?>
                                        <span class="badge badge-info mr-1 p-2"><?= esc($skill->name) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada keahlian ditambahkan.</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-3 text-md-right">
                            <a href="/jobseeker/profile/edit" class="btn btn-primary mb-2 btn-block">Edit Profil</a>
                            <?php if (!empty($profile->resume_path)): ?>
                                <a href="/uploads/resumes/<?= esc($profile->resume_path) ?>"
                                    class="btn btn-outline-secondary btn-block" target="_blank"><i class="fas fa-file-alt"></i>
                                    Lihat CV</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- DAFTAR AKTIVITAS (TELAH DIPERBARUI) -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6><i class="fas fa-tasks"></i> Aktivitas Terakhir</h6>
                        <a href="/jobseeker/history" class="btn btn-sm btn-outline-primary">Lihat Semua Riwayat</a>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (empty($recent_history)): ?>
                            <div class="list-group-item text-center text-muted">Belum ada aktivitas.</div>
                        <?php else: ?>
                            <?php foreach (array_slice($recent_history, 0, 5) as $item): // Tampilkan maksimal 5 item ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        
                                        <!-- =============================================== -->
                                        <!-- PERBAIKAN: Menampilkan detail lamaran/pelatihan -->
                                        <!-- =============================================== -->
                                        <h6 class="mb-1">
                                            <?php if (isset($item->job_title)): // Ini adalah Lamaran Kerja ?>
                                                <i class="bi bi-briefcase-fill text-primary" title="Lamaran Kerja"></i>
                                                <span class="ml-2">Melamar di: <strong><?= esc($item->job_title) ?></strong></span>
                                            <?php else: // Ini adalah Pendaftaran Pelatihan ?>
                                                <i class="bi bi-easel-fill text-success" title="Pelatihan"></i>
                                                <span class="ml-2">Mendaftar Pelatihan: <strong><?= esc($item->title) ?></strong></span>
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?php
                                                $date = isset($item->applied_at) ? $item->applied_at : $item->enrolled_at;
                                                echo date('d M Y', strtotime($date));
                                            ?>
                                        </small>
                                    </div>
                                    <p class="mb-1" style="font-size: 0.9rem;">
                                        <?php
                                            $company = esc($item->company_name ?? $item->penyelenggara ?? 'N/A');
                                            $status_class = ['pending' => 'warning', 'accepted' => 'success', 'approved' => 'success', 'rejected' => 'danger'];
                                            $status_text = ($item->status === 'approved') ? 'Diterima' : ucfirst($item->status);
                                        ?>
                                        Di <?= $company ?>
                                        <span class="badge badge-<?= $status_class[$item->status] ?? 'secondary' ?> float-right"><?= esc($status_text) ?></span>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- KOLOM BOOKMARK SUDAH DIHAPUS -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
