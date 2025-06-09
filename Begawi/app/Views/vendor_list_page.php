<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/vendorlist.css') ?>">

<div class="container my-5">
    <h1 class="page-title text-center"><?= esc($title ?? 'Daftar Perusahaan Terpercaya') ?></h1>

    <div class="row">
        <?php if (!empty($vendors)): ?>
            <?php foreach ($vendors as $vendor): ?>
                <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                    
                    <a href="<?= site_url('vendor/detail/' . esc($vendor->id)) ?>" class="company-card-link">
                        <div class="company-card">
                            <?php
                                // Logika untuk membuat avatar inisial perusahaan
                                $companyName = $vendor->company_name;
                                $words = explode(' ', $companyName);
                                $initials = '';
                                if (isset($words[0])) {
                                    $initials .= strtoupper(substr($words[0], 0, 1));
                                }
                                if (isset($words[1]) && !in_array(strtoupper($words[0]), ['PT', 'CV'])) {
                                     $initials .= strtoupper(substr($words[1], 0, 1));
                                } else if (isset($words[1]) && in_array(strtoupper($words[0]), ['PT', 'CV'])) {
                                    $initials = strtoupper(substr($words[1], 0, 1));
                                    if(isset($words[2])) {
                                        $initials .= strtoupper(substr($words[2], 0, 1));
                                    }
                                } else {
                                    // Jika hanya satu kata, ambil 2 huruf pertama
                                    $initials = strtoupper(substr($words[0], 0, 2));
                                }

                                // Memberi warna random untuk background avatar
                                $colors = ['#215546', '#A1C349', '#FFC700', '#00796B', '#5E35B1', '#E53935'];
                                $color = $colors[crc32($companyName) % count($colors)];
                            ?>
                            <div class="company-initials-avatar" style="background-color: <?= $color; ?>;">
                                <span><?= esc($initials) ?></span>
                            </div>

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