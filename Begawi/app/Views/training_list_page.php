<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Daftar Pelatihan') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .training-card {
        border: 1px solid #eee;
        transition: box-shadow 0.3s ease-in-out;
    }

    .training-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .card-footer {
        background-color: #f8f9fa;
    }
    </style>
</head>

<body>
    <!-- Anda bisa menyertakan layout utama Anda di sini jika ada -->
    <!-- Contoh: <?= $this->extend('layouts/main_layout') ?> -->
    <!-- Contoh: <?= $this->section('content') ?> -->

    <div class="container my-5">
        <h1 class="text-center mb-4"><?= esc($title) ?></h1>

        <div class="row">
            <?php if (!empty($trainings)): ?>
            <?php foreach ($trainings as $training): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card training-card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= esc($training->title) ?></h5>
                        <p class="card-text text-muted">
                            Oleh: <strong><?= esc($training->penyelenggara ?? 'N/A') ?></strong>
                        </p>
                        <ul class="list-unstyled text-muted small">
                            <li>
                                <strong>Tanggal:</strong> <?= date('d M Y', strtotime($training->start_date)) ?>
                            </li>
                            <li>
                                <strong>Durasi:</strong> <?= esc($training->duration) ?>
                            </li>
                            <li>
                                <strong>Lokasi:</strong> <?= esc($training->platform) ?>
                            </li>
                        </ul>
                        <h4 class="mt-auto pt-3">
                            <?= ($training->cost > 0) ? 'Rp ' . number_format($training->cost, 0, ',', '.') : '<span class="badge badge-success">GRATIS</span>' ?>
                        </h4>
                    </div>
                    <div class="card-footer">
                                <a href="<?= site_url('pelatihan/detail/' . $training->id) ?>"
                                    class="btn btn-primary btn-block">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Saat ini belum ada pelatihan yang tersedia.
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tampilkan Link Paginasi -->
        <div class="mt-4">
            <?= $pager->links() ?>
        </div>
    </div>

    <!-- Contoh: <?= $this->endSection() ?> -->
</body>

</html>