<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Profil Perusahaan') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .profile-header {
            padding: 40px;
            background-color: #f8f9fa;
            border-radius: 12px;
        }

        .profile-logo-detail {
            width: 100px;
            /* Ganti dengan ukuran yang sesuai */
            height: 100px;
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <?php if (!empty($vendor)): ?>
            <!-- Header Profil -->
            <div class="profile-header text-center text-md-left mb-5">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="<?= esc($vendor->company_logo_path ? '/uploads/logos/' . $vendor->company_logo_path : 'https://placehold.co/100x100/A1C349/FFFFFF?text=Logo') ?>"
                            alt="Logo" class="profile-logo-detail">
                    </div>
                    <div class="col-md-10">
                        <h1><?= esc($vendor->company_name) ?></h1>
                        <p class="lead text-muted"><?= esc($vendor->industry) ?></p>
                        <p><i class="fas fa-map-marker-alt"></i> <?= esc($vendor->location_name) ?> -
                            <?= esc($vendor->company_address) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tentang Perusahaan & Lowongan -->
            <div class="row">
                <div class="col-lg-8">
                    <h4>Tentang Perusahaan</h4>
                    <p><?= nl2br(esc($vendor->company_profile ?? 'Deskripsi perusahaan belum tersedia.')) ?></p>
                    <hr>

                    <!-- Daftar Lowongan -->
                    <h4>Lowongan Pekerjaan Tersedia</h4>
                    <?php if (!empty($jobs)): ?>
                        <?php foreach ($jobs as $job): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($job->title) ?></h5>
                                    <p class="card-text small text-muted"><?= esc($job->job_type) ?> | Batas Lamaran:
                                        <?= date('d M Y', strtotime($job->application_deadline)) ?>
                                    </p>
                                    <a href="/lowongan/detail/<?= $job->id ?>" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Saat ini tidak ada lowongan pekerjaan dari perusahaan ini.</p>
                    <?php endif; ?>

                    <!-- Daftar Pelatihan -->
                    <h4 class="mt-5">Pelatihan yang Diadakan</h4>
                    <?php if (!empty($trainings)): ?>
                        <?php foreach ($trainings as $training): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($training->title) ?></h5>
                                    <p class="card-text small text-muted">Mulai:
                                        <?= date('d M Y', strtotime($training->start_date)) ?>
                                    </p>
                                    <a href="/pelatihan/detail/<?= $training->id ?>" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Saat ini tidak ada pelatihan dari perusahaan ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>