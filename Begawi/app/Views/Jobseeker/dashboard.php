<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">Begawi</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        Halo, **<?= esc(session()->get('fullname')) ?>**!
                    </span>
                </li>
                <li class="nav-item ml-3">
                    <a class="btn btn-danger" href="/logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2><?= esc($title) ?></h2>
        <hr>

        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Lamaran Terkirim</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($total_applications) ?></h5>
                        <p class="card-text">Total lamaran yang telah Anda kirim.</p>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-4">Menu Utama</h3>
        <p>Gunakan menu di bawah ini untuk mengelola aktivitas Anda.</p>
        <div class="list-group">
            <a href="/" class="list-group-item list-group-item-action font-weight-bold">Cari Lowongan Baru</a>
            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1"
                aria-disabled="true">Riwayat Lamaran</a>
            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Edit
                Profil Saya</a>
        </div>
    </div>
</body>

</html>