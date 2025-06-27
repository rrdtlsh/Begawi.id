<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/vendorlist.css') ?>">

<style>
    .company-initials-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="container my-5">
    <h1 class="page-title text-center"><?= esc($title ?? 'Daftar Perusahaan Terpercaya') ?></h1>

    <div class="row">
        <?php if (!empty($vendors)): ?>
            <?php foreach ($vendors as $vendor): ?>
                <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">

                    <a href="<?= site_url('vendor/detail/' . esc($vendor->id)) ?>" class="company-card-link">
                        <div class="company-card">
                            <?php
                            $companyName = $vendor->company_name;
                            $words = explode(' ', $companyName);
                            $initials = (count($words) >= 2) ? (substr($words[0], 0, 1) . substr($words[1], 0, 1)) : substr($companyName, 0, 2);
                            $initials = strtoupper($initials);

                            $colors = ['#215546', '#A1C349', '#FFC700', '#00796B', '#5E35B1', '#E53935'];
                            $color = $colors[crc32($companyName) % count($colors)];

                            $logo_path = isset($vendor->company_logo_path) ? trim($vendor->company_logo_path) : '';
                            $has_real_logo = false;
                            if (!empty($logo_path)) {
                                $logo_file_path = 'uploads/logos/' . $logo_path;
                                if (file_exists(FCPATH . $logo_file_path)) {
                                    $has_real_logo = true;
                                    $logo_url = base_url($logo_file_path);
                                }
                            }
                            ?>

                            <?php if ($has_real_logo): ?>
                                <div class="company-initials-avatar" style="background-color: #fff;">
                                    <img src="<?= $logo_url ?>" alt="Logo <?= esc($companyName) ?>">
                                </div>
                            <?php else: ?>
                                <div class="company-initials-avatar" style="background-color: <?= $color; ?>;">
                                    <span><?= esc($initials) ?></span>
                                </div>
                            <?php endif; ?>

                            <h5 class="company-card-name"><?= esc($vendor->company_name) ?></h5>

                            <div class="company-card-details">
                                <p>
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span><?= esc($vendor->location_name ?? 'Lokasi tidak diketahui') ?></span>
                                </p>
                                <p>
                                    <i class="bi bi-building-fill"></i>
                                    <span><?= esc($vendor->industry ?? 'Industri tidak spesifik') ?></span>
                                </p>
                            </div>
                        </div>
                    </a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h4>Belum Ada Perusahaan</h4>
                    <p>Saat ini belum ada perusahaan yang terdaftar di platform kami.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        <?php if (isset($pager) && $pager) : ?>
            <?= $pager->links() ?>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>