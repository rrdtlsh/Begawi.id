<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Dashboard Vendor') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard Penyedia Jasa</h1>
            <a href="/logout" class="btn btn-danger">Log Out</a>
        </div>

        <?php if (!empty($vendor)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h4>Profil Usaha</h4>
            </div>
            <div class="card-body">
                <p><strong>Nama Usaha:</strong> <?= esc($vendor->company_name ?? 'Belum diisi') ?></p>
                <p><strong>Email Usaha:</strong> <?= esc($vendor->user_email ?? 'Belum diisi') ?></p>
                <p><strong>Bidang Jasa:</strong> <?= esc($vendor->industry ?? 'Belum diisi') ?></p>
                <p><strong>Domisili Usaha:</strong> <?= esc($vendor->location_name ?? 'Belum diisi') ?></p>
                <p><strong>Alamat Lengkap:</strong> <?= esc($vendor->company_address ?? 'Belum diisi') ?></p>
                <p><strong>Nomor Telepon:</strong> <?= esc($vendor->contact ?? 'Belum diisi') ?></p>
            </div>
            <div class="card-footer">
                <a href="/vendor/profile/edit" class="btn btn-sm btn-secondary">Edit Profile</a>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">Gagal memuat data profil.</div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Postingan Anda</h2>
            <a href="/vendor/jobs/new" class="btn btn-primary">+ Buat Postingan Baru</a>
        </div>

        <div class="list-group">
            <a href="/vendor/jobs" class="list-group-item list-group-item-action font-weight-bold">Kelola Lowongan
                Pekerjaan</a>
            <a href="/vendor/trainings" class="list-group-item list-group-item-action font-weight-bold">Kelola
                Pelatihan</a>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (!empty($postings)): ?>
                <?php foreach ($postings as $post): ?>
                <div class="border-bottom pb-3 mb-3">
                    <h4><?= esc($post->title) ?></h4>
                    <span class="badge badge-info"><?= esc($post->type) ?></span>
                    <br>
                    <small class="text-muted">Dibuat pada:
                        <?= date('d M Y, H:i', strtotime($post->created_at)) ?></small>
                    <div class="mt-2">
                        <a href="/vendor/jobs/edit/<?= $post->id ?>" class="btn btn-sm btn-outline-warning">Kelola</a>
                        <a href="/vendor/applicants/<?= $post->id ?>" class="btn btn-sm btn-outline-success">Lihat
                            Pelamar</a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p>Anda belum memiliki postingan apapun.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>