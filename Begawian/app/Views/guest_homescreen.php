<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Begawi - Temukan Pekerjaan Impian Anda</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        .job-item {
            border: 1px solid #eee;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .job-item h2 {
            margin-top: 0;
        }

        .job-item a {
            text-decoration: none;
            color: #007bff;
        }

        .job-item a:hover {
            text-decoration: underline;
        }

        .auth-links {
            margin-bottom: 20px;
        }

        .auth-links a {
            margin-right: 15px;
        }
    </style>
</head>

<body>

    <header>
        <h1>Begawi - Portal Lowongan Kerja</h1>
        <div class="auth-links">
            <a href="/login">Login</a> | <a href="/register">Register</a>
        </div>
    </header>

    <main>
        <h2>Lowongan Pekerjaan Terbaru</h2>
        <?php if (!empty($jobs) && is_array($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-item">
                    <h2><a href="/job/detail/<?= esc($job['id'], 'url') ?>"><?= esc($job['title']) ?></a></h2>
                    <p><strong>Lokasi:</strong> <?= esc($job['location']) ?></p>
                    <p><strong>Range Gaji:</strong> <?= esc($job['salary_range']) ?></p>
                    <p><?= esc(substr(strip_tags($job['description']), 0, 150)) ?>...</p>
                    <p><a href="/job/detail/<?= esc($job['id'], 'url') ?>">Lihat Detail & Lamar</a></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada lowongan pekerjaan yang tersedia saat ini. Silakan cek kembali nanti.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Begawi. All rights reserved.</p>
    </footer>
</body>

</html>