<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/jobpage.css') ?>">

<div class="container my-5">
    <h1 class="page-title text-center"><?= esc($title ?? 'Daftar Pelatihan & Workshop') ?></h1>

    <div class="row">
        <?php if (!empty($trainings)): ?>
            <?php foreach ($trainings as $training): ?>
                <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                    <div class="job-card-new">
                        
                        <div class="card-header-new">
                            <?php
                                $organizerName = $training->penyelenggara ?? 'Umum';
                                $words = explode(' ', $organizerName);
                                $initials = '';
                                if (isset($words[0])) {
                                    $initials .= strtoupper(substr($words[0], 0, 1));
                                }
                                if (isset($words[1])) {
                                    $initials .= strtoupper(substr($words[1], 0, 1));
                                } else {
                                    $initials = strtoupper(substr($words[0], 0, 2));
                                }

                                $colors = ['#00796B', '#5E35B1', '#E53935', '#215546', '#A1C349', '#FFC700'];
                                $color = $colors[crc32($organizerName) % count($colors)];
                            ?>
                            <div class="company-initials-avatar" style="background-color: <?= $color; ?>;">
                                <span><?= esc($initials) ?></span>
                            </div>

                            <div>
                                <h5 class="job-title-new"><?= esc($training->title) ?></h5>
                                <p class="company-name-new">Oleh: <?= esc($organizerName) ?></p>
                            </div>
                        </div>

                        <div class="card-body-new">
                            <ul class="job-details-list">
                                <li>
                                    <i class="bi bi-calendar-event-fill"></i>
                                    <span><?= date('j M Y', strtotime($training->start_date)) ?></span>
                                </li>
                                <li>
                                    <i class="bi bi-clock-history"></i>
                                    <span>Durasi: <?= esc($training->duration) ?></span>
                                </li>
                                <li>
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Lokasi: <?= esc($training->platform) ?></span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card-footer-new">
                            <a href="<?= site_url('pelatihan/detail/' . $training->id) ?>" class="btn-apply-new">Lihat Detail</a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h4>Pelatihan Tidak Ditemukan</h4>
                    <p>Saat ini belum ada pelatihan atau workshop yang tersedia.</p>
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