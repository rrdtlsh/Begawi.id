<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Dashboard Saya') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .profile-pic {
            height: 80px;
            width: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eee;
        }

        .card-header h6 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .list-group-item small {
            color: #6c757d;
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
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="<?= !empty($profile->profile_picture_path) ? '/uploads/avatars/' . esc($profile->profile_picture_path) : 'https://via.placeholder.com/120' ?>"
                                alt="Foto Profil" class="img-thumbnail rounded-circle mb-3"
                                style="width:120px; height:120px; object-fit:cover;">
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

        <!-- DAFTAR AKTIVITAS -->
        <div class="row">
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-tasks"></i> Aktivitas Terakhir (Lamaran & Pelatihan)</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (empty($applications) && empty($training_apps)): ?>
                            <div class="list-group-item text-center text-muted">Belum ada aktivitas.</div>
                        <?php endif; ?>
                        <?php if (!empty($applications)):
                            foreach ($applications as $app): ?>
                                <a href="/jobseeker/history" class="list-group-item list-group-item-action">...</a>
                            <?php endforeach; endif; ?>
                        <?php if (!empty($training_apps)):
                            foreach ($training_apps as $app): ?>
                                <a href="/jobseeker/history" class="list-group-item list-group-item-action">...</a>
                            <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-bookmark"></i> Postingan Disimpan</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (empty($bookmarked_jobs) && empty($bookmarked_trainings)): ?>
                            <div class="list-group-item text-center text-muted">Tidak ada postingan disimpan.</div>
                        <?php endif; ?>
                        <?php if (!empty($bookmarked_jobs)):
                            foreach ($bookmarked_jobs as $bookmark): ?>
                                <a href="#" class="list-group-item list-group-item-action">...</a>
                            <?php endforeach; endif; ?>
                        <?php if (!empty($bookmarked_trainings)):
                            foreach ($bookmarked_trainings as $bookmark): ?>
                                <a href="#" class="list-group-item list-group-item-action">...</a>
                            <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>