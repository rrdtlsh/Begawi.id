<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Riwayat Lamaran') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    .application-list {
        background-color: #fff;
        border-radius: 12px;
        margin-top: -10px;
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
    }

    .badge-status {
        font-size: 0.8rem;
        font-weight: 500;
        padding: .5em .8em;
    }
    </style>
</head>

<body style="background-color: #f8f9fa;">
    <!-- Salin Navbar dari halaman dashboard Anda -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">...</nav>

    <div class="container my-4">
        <h2>Status Lamaran</h2>

        <div class="status-card">
            <h4 class="text-center mb-3">Status Lamaran Anda</h4>
            <div class="status-bar">
                <div class="status-item">
                    <div class="count"><?= esc($statusCounts['pending'] ?? 0) ?></div>
                    <div class="label" style="background-color:#ffc107; color:#333;">Menunggu</div>
                </div>
                <div class="status-item">
                    <div class="count"><?= esc($statusCounts['accepted'] ?? 0) ?></div>
                    <div class="label">Diterima</div>
                </div>
                <div class="status-item">
                    <div class="count"><?= esc($statusCounts['rejected'] ?? 0) ?></div>
                    <div class="label" style="background-color:#dc3545;">Ditolak</div>
                </div>
            </div>
        </div>

        <div class="application-list">
            <?php if (!empty($history)): ?>
            <?php foreach($history as $item): ?>
            <div class="application-item">
                <div class="app-logo">
                    <?= isset($item->job_title) ? substr(esc($item->company_name), 0, 2) : substr(esc($item->penyelenggara), 0, 2) ?>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 font-weight-bold"><?= esc($item->job_title ?? $item->title) ?></h6>
                    <small class="text-muted"><?= esc($item->company_name ?? 'Oleh: '.$item->penyelenggara) ?></small>
                </div>
                <?php $status_class = ['pending' => 'warning', 'accepted' => 'success', 'rejected' => 'danger', 'completed' => 'primary']; ?>
                <div><span
                        class="badge badge-<?= $status_class[$item->status] ?? 'secondary' ?> badge-status"><?= ucfirst($item->status) ?></span>
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