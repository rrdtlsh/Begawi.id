<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/jobpage.css') ?>">

<div class="container my-5">
    <h1 class="page-title text-center"><?= esc($title ?? 'Daftar Lowongan') ?></h1>

    <div class="row">
        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                    <div class="job-card-new">
                        
                        <div class="card-header-new">
                            <?php
                                $companyName = $job->company_name;
                                $words = explode(' ', $companyName);
                                $initials = '';
                                if (isset($words[0])) {
                                    $initials .= strtoupper(substr($words[0], 0, 1));
                                }
                                if (isset($words[1])) {
                                    $initials .= strtoupper(substr($words[1], 0, 1));
                                } else {
                                    $initials = strtoupper(substr($words[0], 0, 2));
                                }

                                $colors = ['#215546', '#A1C349', '#FFC700', '#00796B', '#5E35B1', '#E53935'];
                                $color = $colors[crc32($companyName) % count($colors)];
                            ?>
                            <div class="company-initials-avatar" style="background-color: <?= $color; ?>;">
                                <span><?= esc($initials) ?></span>
                            </div>

                            <div>
                                <h5 class="job-title-new"><?= esc($job->title) ?></h5>
                                <p class="company-name-new"><?= esc($companyName) ?></p>
                            </div>
                        </div>

                        <div class="card-body-new">
                            <ul class="job-details-list">
                                <li>
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span><?= esc($job->location_name ?? 'N/A') ?></span>
                                </li>
                                <li>
                                    <i class="bi bi-briefcase-fill"></i>
                                    <span><?= esc($job->job_type) ?></span>
                                </li>
                                <li>
                                    <i class="bi bi-calendar-check-fill"></i>
                                    <span>Diposting: <?= date('j M Y', strtotime($job->created_at)) ?></span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card-footer-new">
                            <a href="/lowongan/detail/<?= $job->id ?>" class="btn-apply-new">Lihat Detail</a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h4>Lowongan Tidak Ditemukan</h4>
                    <p>Tidak ada lowongan yang cocok dengan kriteria pencarian Anda saat ini.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        <?php if ($pager) : ?>
            <?= $pager->links() ?>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>