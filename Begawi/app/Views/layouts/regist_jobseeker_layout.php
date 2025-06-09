<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Registrasi Pencari Kerja - Begawi') ?></title>

    <link rel="stylesheet" href="<?= base_url('css/userregist.css') ?>">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <?= $this->renderSection('content') ?>

</body>
</html>