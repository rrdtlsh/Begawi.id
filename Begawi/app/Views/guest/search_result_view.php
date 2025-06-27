<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
    <div class="row">
        <div class="col-lg-12">

            <h1 class="mb-4"><?= esc($list_title ?? 'Hasil Pencarian') ?></h1>

            <div class="alert alert-info">
                <p class="mb-1">
                    Menampilkan hasil untuk:
                    <?php if (!empty($search_terms['keyword'])): ?>
                        <strong>"<?= esc($search_terms['keyword']) ?>"</strong>
                    <?php else: ?>
                        Semua Postingan
                    <?php endif; ?>
                </p>
                <a href="<?= site_url('/') ?>">Kembali ke halaman utama dan lakukan pencarian baru</a>
            </div>

            <hr>

            <div class="row g-4">
                <?php if (!empty($jobs)): ?>
                    <?php foreach ($jobs as $job): ?>
                        <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                            <div class="card job-card w-100 mb-4">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-start mb-3">
                                        <?php
                                        $companyName = esc($job->company_name ?? 'N/A');
                                        $words = explode(' ', $companyName);
                                        $initials = (count($words) >= 2) ? (substr($words[0], 0, 1) . substr($words[1], 0, 1)) : substr($companyName, 0, 2);
                                        $initials = strtoupper($initials);
                                        $placeholder = 'https://placehold.co/60x60/F8DE3D/333333?text=' . $initials;
                                        $logo_url = $placeholder;

                                        if (isset($job->company_logo_path) && !empty(trim($job->company_logo_path))) {
                                            $logo_file = 'uploads/logos/' . trim($job->company_logo_path);
                                            if (file_exists(FCPATH . $logo_file)) {
                                                $logo_url = base_url($logo_file);
                                            }
                                        }
                                        ?>
                                        <img src="<?= $logo_url ?>" alt="Logo Perusahaan" class="company-logo me-3" style="width:60px; height:60px; border-radius: 0.5rem; object-fit: cover;">
                                        <div>
                                            <h5 class="card-title mb-1"><a
                                                    href="<?= site_url('lowongan/detail/' . $job->id) ?>"><?= esc($job->title) ?></a>
                                            </h5>
                                            <h6 class="card-subtitle mb-2 text-muted"><?= esc($job->company_name) ?></h6>
                                        </div>
                                    </div>
                                    <div class="mb-3 small">
                                        <p class="job-detail-item"><i class="bi bi-geo-alt-fill"></i>
                                            <?= esc($job->location_name ?? 'N/A') ?></p>
                                        <p class="job-detail-item"><i class="bi bi-briefcase-fill"></i>
                                            <?= esc($job->job_type) ?></p>
                                    </div>
                                    <a href="<?= site_url('lowongan/detail/' . $job->id) ?>"
                                        class="btn btn-custom-green mt-auto">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php elseif (!empty($trainings)): ?>
                    <?php foreach ($trainings as $training): ?>
                        <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                            <div class="card job-card w-100 mb-4">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-start mb-3">
                                        <?php
                                        $companyName = esc($training->penyelenggara ?? $training->company_name);
                                        $words = explode(' ', $companyName);
                                        $initials = (count($words) >= 2) ? (substr($words[0], 0, 1) . substr($words[1], 0, 1)) : substr($companyName, 0, 2);
                                        $initials = strtoupper($initials);
                                        $placeholder = 'https://placehold.co/60x60/F8DE3D/333333?text=' . $initials;
                                        $logo_url = $placeholder;

                                        if (isset($training->company_logo_path) && !empty(trim($training->company_logo_path))) {
                                            $logo_file = 'uploads/logos/' . trim($training->company_logo_path);
                                            if (file_exists(FCPATH . $logo_file)) {
                                                $logo_url = base_url($logo_file);
                                            }
                                        }
                                        ?>
                                        <img src="<?= $logo_url ?>" alt="Logo Penyelenggara" class="company-logo me-3" style="width:60px; height:60px; border-radius: 0.5rem; object-fit: cover;">
                                        <div>
                                            <h5 class="card-title mb-1"><a href="<?= site_url('pelatihan/detail/' . $training->id) ?>"><?= esc($training->title) ?></a></h5>
                                            <h6 class="card-subtitle mb-2 text-muted"><?= esc($training->penyelenggara ?? $training->company_name) ?></h6>
                                        </div>
                                    </div>
                                    <div class="mb-3 small">
                                        <p class="job-detail-item"><i class="bi bi-geo-alt-fill"></i> <?= esc($training->location_name ?? 'Online') ?></p>
                                        <p class="job-detail-item"><i class="bi bi-calendar-event"></i> Mulai: <?= date('d M Y', strtotime($training->start_date)) ?></p>
                                    </div>
                                    <a href="<?= site_url('pelatihan/detail/' . $training->id) ?>" class="btn btn-custom-green mt-auto">Lihat Detail Pelatihan</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            <h4>Pencarian Tidak Ditemukan</h4>
                            <p>Maaf, tidak ada postingan yang cocok dengan kriteria pencarian Anda.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>