<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Manajemen Pelatihan') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><?= esc($title) ?></h1>
            <a href="/vendor/dashboard" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <p>
            <a href="/vendor/trainings/new" class="btn btn-primary">Tambah Pelatihan Baru</a>
        </p>

        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <table class="table table-bordered mt-3">
            <thead class="thead-light">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Judul Pelatihan</th>
                    <th style="width: 20%;">Tanggal Mulai</th>
                    <th style="width: 15%;">Biaya</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($trainings)): ?>
                <?php $i = 1;
                    foreach ($trainings as $training): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($training->title) ?></td>
                    <td><?= date('d M Y, H:i', strtotime($training->start_date)) ?></td>
                    <td>Rp <?= number_format($training->cost, 0, ',', '.') ?></td>
                    <td>
                        <a href="/vendor/trainings/edit/<?= $training->id ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/vendor/trainings/delete/<?= $training->id ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus pelatihan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Anda belum membuat pelatihan apapun.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>