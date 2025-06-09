<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<header class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Temukan Pekerjaan Impianmu</h1>
        <p class="lead mb-5">Platform pencarian kerja terpercaya di Banua.</p>

        <div class="search-form-card col-lg-11 mx-auto">
            
            <form class="row g-3 align-items-end" action="<?= site_url('search/process') ?>" method="post">
                <?= csrf_field() ?>

                <div class="col-lg text-start">
                    <label for="search_type" class="form-label fw-bold">Saya mencari...</label>
                    <select class="form-select form-select-lg" id="search_type" name="search_type">
                        <option value="jobs" selected>Pekerjaan</option>
                        <option value="trainings">Pelatihan</option>
                    </select>
                </div>

                <div class="col-lg-4 text-start">
                    <label for="keyword" class="form-label fw-bold">Judul/Kata Kunci</label>
                    <input type="text" class="form-control form-control-lg" id="keyword" name="keyword"
                           placeholder="Contoh: Web Developer">
                </div>

                <div class="col-lg text-start">
                    <label for="location" class="form-label fw-bold">Lokasi</label>
                    <select class="form-select form-select-lg" id="location" name="location">
                        <option value="">Semua Lokasi</option>
                        <?php if (!empty($locations)): foreach ($locations as $loc): ?>
                            <option value="<?= $loc->id ?>"><?= esc($loc->name) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="col-lg text-start">
                     <label for="category" class="form-label fw-bold">Kategori</label>
                    <select class="form-select form-select-lg" id="category" name="category">
                        <option value="">Semua Kategori</option>
                        <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id ?>"><?= esc($cat->name) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="col-lg-auto d-grid">
                    <label class="form-label d-none d-lg-block">&nbsp;</label> <button type="submit" class="btn btn-search btn-lg btn-block w-100">
                        <i class="bi bi-search"></i> Cari
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
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="#" class="d-block p-3 category-card text-center">
                    <div class="category-card-icon"><i class="bi bi-cash-coin" style="font-size: 2.5rem;"></i></div>
                    <h5>Keuangan</h5>
                </a>
            </div>
             <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="#" class="d-block p-3 category-card text-center">
                    <div class="category-card-icon"><i class="bi bi-cart3" style="font-size: 2.5rem;"></i></div>
                    <h5>Keuangan</h5>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="#" class="d-block p-3 category-card text-center">
                    <div class="category-card-icon"><i class="bi bi-code-slash" style="font-size: 2.5rem;"></i></div>
                    <h5>IT & Software</h5>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="#" class="d-block p-3 category-card text-center">
                    <div class="category-card-icon"><i class="bi bi-graph-up-arrow" style="font-size: 2.5rem;"></i></div>
                    <h5>Penjualan</h5>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="#" class="d-block p-3 category-card text-center">
                    <div class="category-card-icon"><i class="bi bi-palette-fill" style="font-size: 2.5rem;"></i></div>
                    <h5>Desain/Kreatif</h5>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="#" class="d-block p-3 category-card text-center">
                    <div class="category-card-icon"><i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;"></i></div>
                    <h5>Pendidikan</h5>
                </a>
            </div>
        </div>
    </div>
</section>


<section class="py-5 bg-white" id="lowongan">
    <div class="container">
        <h2 class="text-center section-title">Lowongan Terbaru</h2>
        <div class="row g-4">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                        <div class="card job-card w-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-start mb-3">
                                    <img src="<?= esc($job->company_logo_path ?? 'https://placehold.co/60x60/A1C349/FFFFFF?text=' . substr(esc($job->company_name), 0, 2) ) ?>"
                                         alt="Logo Perusahaan" class="company-logo me-3">
                                    <div>
                                        <h5 class="card-title mb-1"><a href="#"><?= esc($job->title) ?></a></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= esc($job->company_name) ?></h6>
                                    </div>
                                </div>
                                <div class="mb-3 small">
                                    <p class="job-detail-item"><i class="bi bi-geo-alt-fill"></i> <?= esc($job->location_name ?? 'N/A') ?></p>
                                    <p class="job-detail-item"><i class="bi bi-briefcase-fill"></i> <?= esc($job->job_type) ?></p>
                                    <p class="job-detail-item"><i class="bi bi-calendar3"></i> Diposting: <?= date('j F Y', strtotime($job->created_at)) ?></p>
                                </div>
                                <a href="<?= site_url('lowongan/detail/' . $job->id) ?>" class="btn btn-custom-green mt-auto">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12"><div class="alert alert-warning text-center"><h4>Tidak Ada Lowongan</h4><p>Saat ini belum ada lowongan yang tersedia.</p></div></div>
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
                                <div class="d-flex align-items-start mb-3">
                                    <img src="<?= esc($training->company_logo_path ?? 'https://placehold.co/60x60/F8DE3D/333333?text=' . substr(esc($training->company_name), 0, 2)) ?>"
                                         alt="Logo Penyelenggara" class="company-logo me-3">
                                    <div>
                                        <h5 class="card-title mb-1"><a href="#"><?= esc($training->title) ?></a></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= esc($training->company_name) ?></h6>
                                    </div>
                                </div>
                                <div class="mb-3 small">
                                    <p class="job-detail-item"><i class="bi bi-geo-alt-fill"></i> <?= esc($training->location_name ?? 'Online') ?></p>
                                    <p class="job-detail-item"><i class="bi bi-calendar-event"></i> Mulai: <?= date('d M Y', strtotime($training->start_date)) ?></p>
                                    <p class="job-detail-item"><i class="bi bi-patch-check-fill"></i> <?= $training->is_paid ? 'Gratis' : 'Gratis' // Sesuai gambar, keduanya menampilkan 'Gratis' ?></p>
                                </div>
                                <a href="<?= site_url('pelatihan/detail/' . $training->id) ?>" class="btn btn-custom-green mt-auto">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                 <div class="col-12"><div class="alert alert-info text-center"><p>Belum ada pelatihan yang tersedia saat ini.</p></div></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>