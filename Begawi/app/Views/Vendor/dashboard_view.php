<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Dashboard Vendor') ?></title>
    <style>
        /* CSS Kasar untuk layout sederhana */
        body { font-family: sans-serif; display: flex; }
        .sidebar { width: 200px; border-right: 1px solid #ccc; padding: 20px; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; }
        .main-content { flex-grow: 1; padding: 20px; }
        .profile, .postings { border: 1px solid #eee; padding: 15px; margin-bottom: 20px; }
        .post-card { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>Menu</h3>
        <a href="/vendor/dashboard">Beranda Vendor</a>
        <a href="/vendor/posting/new">+ Posting</a>
        <a href="/logout" style="color: red;">Log Out</a>
    </div>

    <div class="main-content">
        <h1>Home Penyedia</h1>

        <?php if ($vendor): ?>
            <div class="profile">
                <h2>Profil Usaha</h2>
                <p><strong>Nama Usaha:</strong> <?= esc($vendor->company_name) ?></p>
                <p><strong>Bidang Kategori Jasa:</strong> <?= esc($vendor->industry) ?></p>
                <p><strong>Domisili Usaha:</strong> <?= esc($vendor->location_name) ?></p>
                <p><strong>Nomor Telepon:</strong> <?= esc($vendor->contact) ?></p>
                <p><strong>Email Usaha:</strong> <?= esc($vendor->user_email) ?></p>
                <a href="/vendor/profile/edit">Edit Profile</a>
            </div>
        <?php else: ?>
            <p>Gagal memuat data profil vendor.</p>
        <?php endif; ?>

        <div class="postings">
            <h2>Postingan Anda</h2>

            <?php if (!empty($postings)): ?>
                <?php foreach ($postings as $post): ?>
                    <div class="post-card">
                        <h4><?= esc($post->title) ?></h4>
                        <p>Lokasi: <?= esc($post->location) ?></p>
                        <p>Tipe: <?= esc($post->type) ?></p>
                        <small>Dibuat pada: <?= esc($post->created_at) ?></small>
                        <br><br>
                        <a href="/vendor/posting/manage/<?= $post->id ?>">Kelola Postingan</a>
                        <a href="/vendor/posting/applicants/<?= $post->id ?>">Lihat Pelamar</a>
                        <a href="/vendor/posting/delete/<?= $post->id ?>" style="color: red;">Hapus</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Anda belum memiliki postingan apapun. Silakan buat postingan baru.</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>