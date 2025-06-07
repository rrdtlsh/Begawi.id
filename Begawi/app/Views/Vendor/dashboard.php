<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Beranda Vendor') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            margin-left: 250px;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .menu {
            flex-grow: 1;
        }

        .sidebar .menu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .sidebar .menu a.active {
            background-color: #e8f5e9;
            color: #28a745;
            font-weight: bold;
        }

        .sidebar .menu a:hover {
            background-color: #f1f1f1;
        }

        .sidebar .logout {
            width: calc(100% - 40px);
        }

        .profile-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .post-item {
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
        }

        .profile-logo {
            height: 70px;
            width: 70px;
            border-radius: 50%;
            margin-right: 20px;
            object-fit: cover;
            border: 3px solid #eee;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="/logo-begawi.png" alt="Begawi Logo" style="height: 40px;">
        </div>
        <div class="menu">
            <a href="/vendor/dashboard" class="active">Beranda Vendor</a>
            <a href="/vendor/jobs">Kelola Lowongan</a>
            <a href="/vendor/trainings">Kelola Pelatihan</a>
            <a href="/vendor/profile/edit">Edit Profil</a>
        </div>
        <div class="logout">
            <a href="/logout" class="btn btn-outline-danger btn-block">Log Out</a>
        </div>
    </div>

    <div class="main-content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (!empty($vendor)): ?>
            <div class="profile-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <?php if (!empty($vendor->company_logo_path)): ?>
                            <img src="/uploads/logos/<?= esc($vendor->company_logo_path) ?>" alt="Logo" class="profile-logo">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/70" alt="Logo" class="profile-logo">
                        <?php endif; ?>
                        <div>
                            <h4 class="mb-0">Profil: <?= esc($vendor->company_name ?? '') ?></h4>
                            <a href="/vendor/profile/edit" class="btn btn-sm btn-link pl-0">Edit Profile</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Email:</strong><br><?= esc($vendor->user_email ?? 'Belum diisi') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Telepon:</strong><br><?= esc($vendor->contact ?? 'Belum diisi') ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <h4>Postingan Anda</h4>
        <hr>

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mt-4">Lowongan Pekerjaan Terakhir</h5>
            <a href="/vendor/jobs/new" class="btn btn-sm btn-primary">+ Buat Lowongan</a>
        </div>
        <?php if (!empty($jobs)): ?>
            <div class="row mt-3">
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="post-item h-100 d-flex flex-column">
                            <h6><?= esc($job->title) ?></h6>
                            <small class="text-muted">Diposting: <?= date('d M Y', strtotime($job->created_at)) ?></small>
                            <div class="mt-auto pt-2">
                                <a href="/vendor/jobs/edit/<?= $job->id ?>" class="btn btn-sm btn-outline-primary">Kelola</a>
                                <a href="#" class="btn btn-sm btn-outline-success">Lihat Pelamar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="/vendor/jobs" class="btn btn-link">Lihat semua lowongan...</a>
        <?php else: ?>
            <p>Anda belum memiliki postingan lowongan pekerjaan.</p>
        <?php endif; ?>

        <hr class="my-4">

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mt-4">Pelatihan Terakhir</h5>
            <a href="/vendor/trainings/new" class="btn btn-sm btn-primary">+ Buat Pelatihan</a>
        </div>
        <?php if (!empty($trainings)): ?>
            <div class="row mt-3">
                <?php foreach ($trainings as $training): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="post-item h-100 d-flex flex-column">
                            <h6><?= esc($training->title) ?></h6>
                            <small class="text-muted">Diposting: <?= date('d M Y', strtotime($training->created_at)) ?></small>
                            <div class="mt-auto pt-2">
                                <a href="/vendor/trainings/edit/<?= $training->id ?>"
                                    class="btn btn-sm btn-outline-primary">Kelola</a>
                                <a href="#" class="btn btn-sm btn-outline-success">Lihat Peserta</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="/vendor/trainings" class="btn btn-link">Lihat semua pelatihan...</a>
        <?php else: ?>
            <p>Anda belum memiliki postingan pelatihan.</p>
        <?php endif; ?>
    </div>
</body>

</html>