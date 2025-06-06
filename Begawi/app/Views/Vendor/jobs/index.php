<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><?= esc($title) ?></h1>
            <a href="/vendor/dashboard" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <p>
            <a href="/vendor/jobs/new" class="btn btn-primary">Tambah Lowongan Baru</a>
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
                    <th>No</th>
                    <th>Judul Lowongan</th>
                    <th>Tipe</th>
                    <th>Tanggal Posting</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jobs)):
                    $i = 1;
                    foreach ($jobs as $job): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($job->title) ?></td>
                    <td><?= esc($job->job_type) ?></td>
                    <td><?= date('d M Y, H:i', strtotime($job->created_at)) ?></td>
                    <td>
                        <a href="/vendor/jobs/edit/<?= $job->id ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/vendor/jobs/delete/<?= $job->id ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" class="text-center">Anda belum memposting lowongan pekerjaan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>