<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Riwayat Lamaran') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
    .status-card {
        background-color: #28a745;
        color: white;
        border-radius: 12px;
        padding: 20px;
    }

    .status-bar {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .status-item {
        text-align: center;
    }

    .status-item .count {
        font-size: 2.5rem;
        font-weight: bold;
    }

    .status-item .label {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 15px;
    }
    /* Tambahan CSS untuk label status count agar warnanya sesuai */
    .status-item .label.pending { background-color:#ffc107; color:#333; }
    .status-item .label.accepted { background-color:#28a745; color:#fff; }
    .status-item .label.rejected { background-color:#dc3545; color:#fff; }

    .application-list {
        background-color: #fff;
        border-radius: 12px;
        margin-top: -10px; /* Sesuaikan jika Anda menambahkan lebih banyak kartu status di atasnya */
        padding-top: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .application-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .app-logo {
        height: 45px;
        width: 45px;
        border-radius: 8px;
        margin-right: 15px;
        background-color: #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0; /* Mencegah logo menyusut */
    }

    .badge-status {
        font-size: 0.8rem;
        font-weight: 500;
        padding: .5em .8em;
    }

    .item-metadata {
        display: flex;
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 4px;
        flex-wrap: wrap; /* Memastikan metadata bisa wrap */
    }

    .item-metadata .type-label,
    .item-metadata .date-label {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }
    .item-metadata .type-label i,
    .item-metadata .date-label i {
        margin-right: 5px; /* Spasi antara ikon dan teks */
    }

    /* CSS untuk tombol aksi */
    .actions {
        /* Menggunakan ml-auto sudah mendorong ke kanan */
        /* d-flex dan align-items-center sudah ada di div luar */
        margin-left: auto; /* Untuk mendorong ke kanan */
        flex-shrink: 0; /* Mencegah tombol menyusut */
        display: flex; /* Memastikan tombol di dalam actions tetap sejajar */
        align-items: center; /* Untuk vertikal alignment */
        gap: 10px; /* Spasi antar tombol */
    }
    .actions .btn {
        display: flex; /* Untuk ikon dan teks */
        align-items: center;
    }
    .actions .btn i {
        margin-right: 5px;
    }
    </style>
</head>

<body style="background-color: #f8f9fa;">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">...</nav>

    <div class="container my-4">
        <div class="mb-3">
        <a href="<?= site_url('jobseeker/dashboard') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>
        <h2>Status Lamaran</h2>
        <div class="status-card">
            <h4 class="text-center mb-3">Status Lamaran & Pelatihan Anda</h4>
            <div class="status-bar">
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['total']['pending'] ?? 0) ?></div>
                    <div class="label pending">Menunggu</div>
                </div>
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['total']['accepted'] ?? 0) ?></div>
                    <div class="label accepted">Diterima</div>
                </div>
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['total']['rejected'] ?? 0) ?></div>
                    <div class="label rejected">Ditolak</div>
                </div>
            </div>
        </div>

        <div class="status-card mt-4" style="background-color: #007bff;">
            <h4 class="text-center mb-3">Status Lamaran Kerja</h4>
            <div class="status-bar">
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['jobs']['pending'] ?? 0) ?></div>
                    <div class="label pending">Menunggu</div>
                </div>
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['jobs']['accepted'] ?? 0) ?></div>
                    <div class="label accepted">Diterima</div>
                </div>
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['jobs']['rejected'] ?? 0) ?></div>
                    <div class="label rejected">Ditolak</div>
                </div>
            </div>
        </div>

        <div class="status-card mt-4" style="background-color: #6f42c1;">
            <h4 class="text-center mb-3">Status Pendaftaran Pelatihan</h4>
            <div class="status-bar">
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['trainings']['pending'] ?? 0) ?></div>
                    <div class="label pending">Menunggu</div>
                </div>
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['trainings']['accepted'] ?? 0) ?></div>
                    <div class="label accepted">Diterima</div>
                </div>
                <div class="status-item">
                    <div class="count"><?= esc($summaryCounts['trainings']['rejected'] ?? 0) ?></div>
                    <div class="label rejected">Ditolak</div>
                </div>
            </div>
        </div>

        <div class="application-list mt-4">
            <?php if (!empty($history)): ?>
            <?php foreach ($history as $item): ?>
            <div class="application-item">
                <div class="app-logo">
                    <?php
                    if (($item->type ?? '') === 'job_application') {
                        echo substr(esc($item->company_name ?? ''), 0, 2);
                    } elseif (($item->type ?? '') === 'training_enrollment') {
                        echo substr(esc($item->penyelenggara ?? ''), 0, 2);
                    } else {
                        echo '??';
                    }
                    ?>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 font-weight-bold">
                        <?php
                        if (($item->type ?? '') === 'job_application') {
                            echo esc($item->job_title ?? 'Judul Lamaran Tidak Ditemukan');
                        } elseif (($item->type ?? '') === 'training_enrollment') {
                            echo esc($item->title ?? 'Judul Pelatihan Tidak Ditemukan');
                        } else {
                            echo 'Judul Tidak Ditemukan';
                        }
                        ?>
                    </h6>
                    <small class="text-muted">
                        <?php
                        if (($item->type ?? '') === 'job_application') {
                            echo esc($item->company_name ?? 'N/A');
                        } elseif (($item->type ?? '') === 'training_enrollment') {
                            echo 'Oleh: ' . esc($item->penyelenggara ?? 'N/A');
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </small>

                    <div class="item-metadata">
                        <?php if (($item->type ?? '') === 'job_application'): ?>
                        <div class="type-label">
                            <i class="bi bi-briefcase-fill mr-1"></i>
                            Lamaran Kerja
                        </div>
                        <div class="date-label">
                            <i class="bi bi-calendar3 mr-1"></i>
                            Diajukan:
                            <?= isset($item->applied_at) ? date('d M Y', strtotime($item->applied_at)) : 'N/A' ?>
                        </div>
                        <?php elseif (($item->type ?? '') === 'training_enrollment'):  ?>
                        <div class="type-label">
                            <i class="bi bi-easel-fill mr-1"></i>
                            Pelatihan
                        </div>
                        <div class="date-label">
                            <i class="bi bi-calendar3 mr-1"></i>
                            Didaftar:
                            <?= isset($item->enrolled_at) ? date('d M Y', strtotime($item->enrolled_at)) : 'N/A' ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="actions ml-auto d-flex align-items-center">
                    <?php
                    // Tentukan jenis dan status item
                    $isJobApplication = (($item->type ?? '') === 'job_application');
                    $isTrainingEnrollment = (($item->type ?? '') === 'training_enrollment');
                    $currentStatus = $item->status ?? 'pending';

                    // ID yang akan digunakan untuk aksi (application_id atau training_id)
                    $idToActOn = null;
                    if (isset($item->id)) {
                        $idToActOn = $item->id;
                    }
                    ?>

                    <?php if ($idToActOn): ?>
                        <?php if ($currentStatus === 'pending'): ?>
                            <?php if ($isJobApplication): ?>
                                <a href="<?= site_url('jobseeker/applications/edit/' . esc($idToActOn)) ?>" class="btn btn-sm btn-info me-2 d-flex align-items-center">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                            <?php endif; ?>

                            <form action="<?= site_url($isJobApplication ? 'jobseeker/applications/delete/' . esc($idToActOn) : 'jobseeker/trainings/delete-enrollment/' . esc($idToActOn)) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan <?= $isJobApplication ? 'lamaran' : 'pendaftaran' ?> ini?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </form>

                        <?php elseif ($currentStatus === 'accepted'): ?>
                            <form action="<?= site_url($isJobApplication ? 'jobseeker/applications/delete/' . esc($idToActOn) : 'jobseeker/trainings/delete-enrollment/' . esc($idToActOn)) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus <?= $isJobApplication ? 'lamaran' : 'pendaftaran' ?> yang sudah diterima ini? Tindakan ini tidak dapat dibatalkan.');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </form>

                        <?php endif; // End if currentStatus === 'accepted' ?>
                    <?php endif; // End if idToActOn ?>
                </div>
                <?php
                $status_class = [
                    'pending' => 'warning',
                    'accepted' => 'success',
                    'rejected' => 'danger',
                    'completed' => 'primary'
                ];
                $status_text = ($item->status ?? 'unknown') === 'accepted' ? 'Diterima' : ucfirst($item->status ?? 'Unknown');
                ?>
                <div>
                    <span class="badge badge-<?= $status_class[$item->status ?? 'secondary'] ?? 'secondary' ?> badge-status"><?= esc($status_text) ?></span>
                </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p class="text-center text-muted p-4">Anda belum memiliki riwayat lamaran atau pendaftaran pelatihan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>