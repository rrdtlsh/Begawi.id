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
                            $companyName = esc($job->company_name ?? 'N/A');
                            $words = explode(' ', $companyName);
                            $initials = (count($words) >= 2) ? (substr($words[0], 0, 1) . substr($words[1], 0, 1)) : substr($companyName, 0, 2);
                            $initials = strtoupper($initials);

                            $colors = ['#215546', '#A1C349', '#FFC700', '#00796B', '#5E35B1', '#E53935'];
                            $color = $colors[crc32($companyName) % count($colors)];

                            $logo_path = isset($job->company_logo_path) ? trim($job->company_logo_path) : '';
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
                                <img src="<?= $logo_url ?>" alt="Logo <?= esc($companyName) ?>" class="company-initials-avatar">
                            <?php else: ?>
                                <div class="company-initials-avatar" style="background-color: <?= $color; ?>;">
                                    <span><?= esc($initials) ?></span>
                                </div>
                            <?php endif; ?>

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