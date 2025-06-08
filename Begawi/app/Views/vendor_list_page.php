<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Daftar Perusahaan') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .company-card {
            text-decoration: none;
            color: inherit;
        }

        .company-card .card {
            transition: all 0.3s ease;
        }

        .company-card:hover .card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .company-logo {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-5"><?= esc($title) ?></h1>
        <div class="row">
            <?php if (!empty($vendors)): ?>
                <?php foreach ($vendors as $vendor): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="<?= site_url('vendor/detail/' . esc($vendor->id)) ?>" class="company-card">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <img src="<?= esc($vendor->company_logo_path ? '/uploads/logos/' . $vendor->company_logo_path : 'https://placehold.co/60x60/A1C349/FFFFFF?text=Logo') ?>"
                                        alt="Logo" class="company-logo mb-3">
                                    <h5 class="card-title"><?= esc($vendor->company_name) ?></h5>
                                    <p class="text-muted mb-1"><?= esc($vendor->industry ?? 'Industri tidak spesifik') ?></p>
                                    <p class="small text-muted"><i class="fas fa-map-marker-alt"></i>
                                        <?= esc($vendor->location_name ?? 'Lokasi tidak diketahui') ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col text-center">Belum ada perusahaan yang terdaftar.</p>
            <?php endif; ?>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <?= $pager->links() ?>
        </div>
    </div>
</body>

</html>