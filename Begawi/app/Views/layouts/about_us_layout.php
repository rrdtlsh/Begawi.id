<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Tentang Kami - Begawi') ?></title>
    
    <!-- CSS Framework & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Global -->
    <link rel="stylesheet" href="<?= base_url('css/about.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/footer.css') ?>">
    
    <!-- CSS Khusus untuk halaman ini -->
    <link rel="stylesheet" href="<?= base_url('css/about_us.css') ?>">

</head>
<body>

    <!-- Memanggil Navbar -->
    <?= $this->include('layouts/navbar') ?>

    <!-- Tempat untuk Konten Utama -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Memanggil Footer -->
    <?= $this->include('layouts/footer') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>