<?= $this->extend('layouts/vendor_dashboard_layout') ?>

<?= $this->section('content') ?>

<?php if (!empty($vendor)): ?>
    <section class="card profile-card mb-4">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
            <div class="flex-shrink-0 text-white d-flex align-items-center justify-content-center mb-3 mb-sm-0 me-sm-4"
                style="width: 80px; height: 80px; border-radius: 0.5rem; font-size: 2.5rem; font-weight: bold; background-color: var(--sasirangan-green-medium, #5d8b2f);">
                <?php
                $companyInitial = substr($vendor->company_name, 0, 2);
                echo esc(strtoupper($companyInitial));
                ?>
            </div>
            <div>
                <h2 class="h4 fw-bold"><?= esc($vendor->company_name) ?></h2>
                <div class="mt-2 text-secondary">
                    <p class="mb-2 d-flex align-items-center"><i class="bi bi-geo-alt-fill me-2"></i>
                        <?= esc($vendor->location_name ?? 'Lokasi belum diatur') ?></p>
                    <p class="mb-2 d-flex align-items-center"><i class="bi bi-building-fill me-2"></i>
                        <?= esc($vendor->industry ?? 'Industri belum diatur') ?></p>
                    <p class="mb-0 d-flex align-items-center"><i class="bi bi-envelope-fill me-2"></i>
                        <?= esc($vendor->user_email) ?></p>
                </div>
            </div>
        </div>
        <hr class="my-3">
        <div>
            <h6 class="fw-bold small">Deskripsi Perusahaan:</h6>
            <p class="text-secondary small mb-0">
                <?= nl2br(esc($vendor->company_profile ?? 'Deskripsi perusahaan belum diisi. Silakan edit profil untuk menambahkannya.')) ?>
            </p>
        </div>
    </section>
<?php endif; ?>

<section class="mb-5">
    <h2 class="h4 fw-bold mb-4">Pekerjaan Anda</h2>
    <div class="row g-4">

        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100">
                        <div class="card-body posting-card d-flex flex-column">
                            <h3 class="h6 card-title fw-bold mb-2"><?= esc($job->title) ?></h3>
                            <div class="text-secondary small mb-4">
                                <p class="mb-1"><strong>Tipe:</strong> <?= esc($job->job_type) ?></p>
                                <p class="mb-0"><strong>Diposting:</strong> <?= date('d M Y', strtotime($job->created_at)) ?>
                                </p>
                            </div>
                            <div class="mt-auto d-grid gap-2 d-sm-flex justify-content-center align-items-center">
                                <a href="<?= site_url('vendor/jobs/edit/' . $job->id) ?>"
                                    class="btn btn-secondary btn-sm flex-fill">Kelola</a>
                                <a href="<?= site_url('vendor/jobs/' . $job->id . '/applicants') ?>"
                                    class="btn btn-brand-green btn-sm flex-fill">Lihat Pelamar</a>
                                <form action="<?= site_url('vendor/jobs/delete/' . $job->id) ?>" method="post"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-secondary">
                        <p class="mb-0">Anda belum memposting lowongan kerja apapun.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section>
    <h2 class="h4 fw-bold mb-4">Pelatihan Anda</h2>
    <div class="row g-4">
        <?php if (!empty($trainings)): ?>
            <?php foreach ($trainings as $training): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100">
                        <div class="card-body posting-card d-flex flex-column">
                            <h3 class="h6 card-title fw-bold mb-2"><?= esc($training->title) ?></h3>
                            <div class="text-secondary small mb-4">
                                <p class="mb-1"><strong>Mulai:</strong> <?= date('d M Y', strtotime($training->start_date)) ?>
                                </p>
                            </div>
                            <div class="mt-auto d-grid gap-2 d-sm-flex justify-content-center align-items-center">
                                <a href="<?= site_url('vendor/trainings/edit/' . $training->id) ?>"
                                    class="btn btn-secondary btn-sm flex-fill">Kelola</a>
                                <a href="<?= site_url('vendor/trainings/' . $training->id . '/participants') ?>" class="btn btn-brand-green btn-sm flex-fill">Lihat Peserta</a>
                                <form action="<?= site_url('vendor/trainings/delete/' . $training->id) ?>" method="post"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelatihan ini?');">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-secondary">
                        <p class="mb-0">Anda belum memposting pelatihan apapun.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>