<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Begawi - Temukan Pekerjaan Impian Anda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">Begawi</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="/register">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="jumbotron">
            <h1 class="display-4"><?= esc($title ?? 'Selamat Datang di Begawi') ?></h1>
            <p class="lead">Platform nomor satu untuk mencari pekerjaan dan meningkatkan karir Anda.</p>
        </div>

        <h2>Kategori Populer</h2>
        <hr>
        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= esc($category->name) ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col">Belum ada kategori yang tersedia.</p>
            <?php endif; ?>
        </div>

        <h2 class="mt-5">Lowongan Terbaru</h2>
        <hr>
        <div class="row">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($job->title) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= esc($job->company_name) ?></h6>
                                <p class="card-text">
                                    <strong>Lokasi:</strong> <?= esc($job->location) ?><br>
                                    <strong>Tipe:</strong> <?= esc($job->job_type) ?><br>
                                    <strong>Kategori:</strong> <?= esc($job->category_name ?? 'N/A') ?>
                                </p>
                                <a href="#" class="btn btn-info">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col">Belum ada lowongan pekerjaan yang tersedia saat ini.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>