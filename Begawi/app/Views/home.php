<?php // File: app/Views/home.php ?>
<?= $this->extend('layouts/guest_layout') ?>

<?= $this->section('content') ?>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Temukan Pekerjaan Impianmu</h1>
            <p class="lead mb-5">Platform pencarian kerja terpercaya di Kalimantan Selatan.</p>
            
            <!-- Mengisi bagian yang tadinya kosong -->
            <div class="search-form-card col-lg-10 mx-auto">
                <form class="row g-3 align-items-end" action="<?= site_url('search/jobs') ?>" method="post">
                    
                    <!-- Input Kata Kunci -->
                    <div class="col-md-4 text-start">
                        <label for="job-title" class="form-label">Judul Pekerjaan/Kata Kunci</label>
                        <input type="text" class="form-control form-control-lg" id="job-title" name="keyword" placeholder="Contoh: Web Developer">
                    </div>
                    
                    <!-- Dropdown Lokasi (Dinamis) -->
                    <div class="col-md-3 text-start">
                        <label for="location" class="form-label">Lokasi</label>
                        <select class="form-select form-select-lg" id="location" name="location">
                            <option selected value="">Pilih Lokasi</option>
                            <?php if (!empty($locations)): ?>
                                <?php foreach ($locations as $loc): ?>
                                    <option value="<?= $loc->id ?>"><?= esc($loc->name) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Dropdown Kategori (Dinamis) -->
                    <div class="col-md-3 text-start">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select form-select-lg" id="category" name="category">
                            <option selected value="">Semua Kategori</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>"><?= esc($cat->name) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-custom-green btn-lg btn-search">
                            <i class="bi bi-search me-2"></i>Cari
                        </button>
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

    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center section-title">Lowongan Terbaru</h2>
            <div class="row g-4">
                <?php if (!empty($jobs)): ?>
                    <?php foreach ($jobs as $job): ?>
                        <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                            <div class="card job-card w-100">
                                <div class="card-body">
                                    <h5 class="card-title"><a href="#"><?= esc($job->title) ?></a></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?= esc($job->company_name) ?></h6>
                                    <p class="job-detail-item">Lokasi: <?= esc($job->location_name) ?></p>
                                    <p class="job-detail-item">Tipe: <?= esc($job->job_type) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>
