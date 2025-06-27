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

        .status-item .label.pending {
            background-color: #ffc107;
            color: #333;
        }

        .status-item .label.accepted {
            background-color: #28a745;
            color: #fff;
        }

        .status-item .label.rejected {
            background-color: #dc3545;
            color: #fff;
        }

        .application-list {
            background-color: #fff;
            border-radius: 12px;
            margin-top: -10px;
            padding-top: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .application-item {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .application-item-main {
            display: flex;
            align-items: center;
            width: 100%;
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
            flex-shrink: 0;
        }

        .item-details {
            flex-grow: 1;
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
            flex-wrap: wrap;
            align-items: center;
            gap: 15px;
        }

        .item-metadata .type-label,
        .item-metadata .date-label {
            display: flex;
            align-items: center;
        }

        .item-metadata i {
            margin-right: 5px;
        }

        .actions-and-status {
            margin-left: auto;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .actions-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .actions-buttons .btn {
            display: flex;
            align-items: center;
        }

        .actions-buttons .btn i {
            margin-right: 5px;
        }

        .status-badge-container {
            margin-left: auto;
            flex-shrink: 0;
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
                        <div class="item-details">
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
                        <div class="actions-buttons">
                            <?php
                            $isJobApplication = (($item->type ?? '') === 'job_application');
                            $isTrainingEnrollment = (($item->type ?? '') === 'training_enrollment');
                            $currentStatus = $item->status ?? 'pending';
                            $idToActOn = $item->id ?? null;
                            ?>

                            <?php if ($idToActOn): ?>

                                <?php if ($isJobApplication): ?>
                                    <?php if ($currentStatus === 'pending'): ?>
                                        <a href="<?= site_url('jobseeker/applications/edit/' . esc($idToActOn)) ?>" class="btn btn-sm btn-info me-2 d-flex align-items-center">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </a>
                                        <form action="<?= site_url('jobseeker/applications/delete/' . esc($idToActOn)) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lamaran ini?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>


                                <?php if ($isTrainingEnrollment): ?>
                                    <?php if (in_array($currentStatus, ['pending', 'accepted'])): ?>
                                        <form action="<?= site_url('jobseeker/trainings/delete-enrollment/' . esc($idToActOn)) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran pelatihan ini?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center">
                                                <i class="bi bi-x-circle me-1"></i> Batalkan Pendaftaran
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php endif;  ?>
                        </div>
                        <div class="actions-and-status">
                            <?php
                            $status_class = [
                                'pending' => 'warning',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                'completed' => 'primary'
                            ];
                            $status_text = ($item->status ?? 'unknown') === 'accepted' ? 'Accepted' : ucfirst($item->status ?? 'Unknown');
                            ?>
                            <div>
                                <span class="badge badge-<?= $status_class[$item->status ?? 'secondary'] ?? 'secondary' ?> badge-status"><?= esc($status_text) ?></span>
                            </div>
                        </div>

                        <?php if (($item->type ?? '') === 'job_application' && $item->status === 'rejected' && !empty($item->rejection_reason)): ?>
                            <div class="rejection-reason-box mt-3 p-3" style="background-color: #f8d7da; border-left: 5px solid #dc3545; border-radius: 5px;">
                                <h6 class="font-weight-bold" style="color: #721c24;"><i class="bi bi-info-circle-fill"></i> Umpan Balik dari Perekrut:</h6>
                                <p class="mb-0" style="color: #721c24;"><?= esc($item->rejection_reason) ?></p>
                            </div>
                        <?php elseif (($item->type ?? '') === 'training_enrollment' && $item->status === 'rejected' && !empty($item->rejection_reason)): ?>
                            <div class="rejection-reason-box mt-3 p-3" style="background-color: #f8d7da; border-left: 5px solid #dc3545; border-radius: 5px;">
                                <h6 class="font-weight-bold" style="color: #721c24;"><i class="bi bi-info-circle-fill"></i> Umpan Balik dari Penyelenggara:</h6>
                                <p class="mb-0" style="color: #721c24;"><?= esc($item->rejection_reason) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted p-4">Anda belum memiliki riwayat lamaran atau pendaftaran pelatihan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>