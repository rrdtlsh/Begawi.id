<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<header class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Temukan Peluang Karirmu</h1>
        <p class="lead mb-5">Platform terpercaya untuk pekerjaan dan pelatihan di Kalimantan Selatan.</p>

        <div class="search-form-card col-lg-11 mx-auto">
            
            <!-- Form ini sekarang SELALU mengirim ke 'search/process' -->
            <form class="row g-3 align-items-end" action="<?= site_url('search/process') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Dropdown untuk memilih Tipe Pencarian -->
                <div class="col-lg text-start">
                    <label for="search_type" class="form-label fw-bold">Saya mencari...</label>
                    <select class="form-control form-control-lg" id="search_type" name="search_type">
                        <option value="jobs" selected>Pekerjaan</option>
                        <option value="trainings">Pelatihan</option>
                    </select>
                </div>

                <!-- Input Kata Kunci -->
                <div class="col-lg-4 text-start">
                    <label for="keyword" class="form-label fw-bold">Judul/Kata Kunci</label>
                    <input type="text" class="form-control form-control-lg" id="keyword" name="keyword"
                        placeholder="Contoh: Web Developer">
                </div>

                <!-- Dropdown Lokasi -->
                <div class="col-lg text-start">
                    <label for="location" class="form-label fw-bold">Lokasi</label>
                    <select class="form-control form-control-lg" id="location" name="location">
                        <option value="">Semua Lokasi</option>
                        <?php if (!empty($locations)): foreach ($locations as $loc): ?>
                            <option value="<?= $loc->id ?>"><?= esc($loc->name) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <!-- Dropdown Kategori -->
                <div class="col-lg text-start">
                    <label for="category" class="form-label fw-bold">Kategori</label>
                    <select class="form-control form-control-lg" id="category" name="category">
                        <option value="">Semua Kategori</option>
                        <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id ?>"><?= esc($cat->name) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <!-- Tombol Cari -->
                <div class="col-lg-auto d-grid">
                     <label class="form-label d-none d-md-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Cari</button>
                </div>
            </form>
        </div>
    </div>
</header>

<section class="py-5">
    <div class="container">
        <h2 class="text-center section-title">Kategori Populer</h2>
        <div class="row g-4 justify-content-center">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="#" class="d-block p-3 category-card text-center">
                            <div class="category-card-icon">
                                <i class="bi <?= esc($category->icon_path, 'attr') ?>" style="font-size: 2.5rem;"></i>
                            </div>
                            <h5><?= esc($category->name) ?></h5>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col text-center">Belum ada kategori yang tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-5 bg-white" id="lowongan">
    <div class="container">
        <h2 class="text-center section-title"><?= esc($list_title) ?></h2>
        <div class="row g-4">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                        <div class="card job-card w-100">
                            <!-- UBAH STRUKTUR CARD-BODY DI SINI -->
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-start mb-3">
                                    <!-- Tampilkan gambar logo -->
                                    <img src="<?= esc($job->company_logo_path ?? 'https://placehold.co/60x60/A1C349/FFFFFF?text=PT') ?>"
                                        alt="Logo Perusahaan" class="company-logo me-3"
                                        style="width:60px; height:60px; border-radius: 0.5rem; object-fit: cover;">

                                    <!-- Bungkus judul dan subjudul -->
                                    <div>
                                        <h5 class="card-title mb-1"><a href="#"><?= esc($job->title) ?></a></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= esc($job->company_name) ?></h6>
                                    </div>
                                </div>
                                <div class="mb-3 small">
                                    <p class="job-detail-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <?= esc($job->location_name ?? 'N/A') ?>
                                    </p>
                                    <p class="job-detail-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <?= esc($job->job_type) ?>
                                    </p>
                                    <p class="job-detail-item">
                                        <i class="bi bi-calendar3"></i>
                                        Diposting: <?= date('j F Y', strtotime($job->created_at)) ?>
                                    </p>
                                </div>
                                <a href="<?= site_url('lowongan/detail/' . $job->id) ?>" class="btn btn-custom-green mt-auto">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <h4>Tidak Ada Lowongan</h4>
                        <p>Saat ini belum ada lowongan yang tersedia atau cocok dengan kriteria Anda.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-5" id="pelatihan">
    <div class="container">
        <h2 class="text-center section-title">Pelatihan Terbaru</h2>
        <div class="row g-4">
            <?php if (!empty($trainings)): ?>
                <?php foreach ($trainings as $training): ?>
                    <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                        <div class="card job-card w-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1"><a href="#"><?= esc($training->title) ?></a></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= esc($training->company_name) ?></h6>
                                <div class="mb-3 small">
                                    <p class="job-detail-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <?= esc($training->location_name ?? 'Online') ?>
                                    </p>
                                    <p class="job-detail-item">
                                        <i class="bi bi-calendar-event"></i>
                                        Mulai: <?= date('d M Y', strtotime($training->start_date)) ?>
                                    </p>
                                    <p class="job-detail-item">
                                        <i class="bi bi-tag-fill"></i>
                                        <?= $training->is_paid ? 'Berbayar (Rp ' . number_format($training->cost, 0, ',', '.') . ')' : 'Gratis' ?>
                                    </p>
                                </div>
                                <a href="<?= site_url('pelatihan/detail/' . $training->id) ?>" class="btn btn-custom-green mt-auto">Lihat Detail Pelatihan</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <p>Belum ada pelatihan yang tersedia saat ini.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>