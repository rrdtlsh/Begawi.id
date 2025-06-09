<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($training->title ?? 'Detail Pelatihan') ?> - Begawi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="/css/detail.css"> 
</head>
<body>
    <div class="container" style="max-width: 1100px; margin-top: 40px;">
        <a href="/home" class="btn-back"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
    </div>

    <div class="main-container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8 pr-lg-5">
                <div class="d-flex align-items-center mb-3 job-header">
                    <div class="logo mr-4">
                        <?php if (!empty($training->company_logo_path)): ?>
                            <img src="/uploads/logos/<?= esc($training->company_logo_path) ?>" alt="Logo Penyelenggara"
                                style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <?= substr(esc($training->penyelenggara ?? 'P'), 0, 2) ?>
                        <?php endif; ?>
                    </div>
                    <div class="job-header-info">
                        <h1><?= esc($training->title ?? 'Judul Pelatihan') ?></h1>
                        <h5>Oleh: <?= esc($training->penyelenggara ?? 'Penyelenggara') ?></h5>
                    </div>
                </div>
                <div class="job-header-meta mb-4">
                    <span><i class="bi bi-geo-alt-fill"></i> <?= esc($training->location_name ?? 'Online') ?></span>
                    <span><i class="bi bi-calendar-event"></i> Mulai:
                        <?= isset($training->start_date) ? date('d F Y', strtotime($training->start_date)) : 'Segera' ?></span>
                </div>
                <hr>

                <h4 class="section-title">Deskripsi Pelatihan</h4>
                <p><?= isset($training->description) ? nl2br(esc($training->description)) : 'Deskripsi belum tersedia.' ?></p>

                <h4 class="section-title mt-4">Platform Pelatihan</h4>
                <p><?= isset($training->platform) ? nl2br(esc($training->platform)) : 'Informasi platform belum tersedia.' ?></p>

                <h4 class="section-title mt-4">Instruksi Pendaftaran</h4>
                <p><?= isset($training->registration_instructions) ? nl2br(esc($training->registration_instructions)) : 'Tidak ada instruksi khusus.' ?></p>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 class="section-title">Ringkasan Pelatihan</h5>
                    <ul>
                        <li><strong>Kategori:</strong> <?= esc($training->category_name ?? 'N/A') ?></li>
                        <li><strong>Jadwal:</strong> <?= isset($training->start_date) ? date('d M Y, H:i', strtotime($training->start_date)) : 'N/A' ?></li>
                        <li><strong>Durasi:</strong> <?= esc($training->duration ?? 'N/A') ?></li>
                        <li><strong>Biaya:</strong>
                            <?= (isset($training->cost) && $training->cost > 0) ? 'Rp ' . number_format($training->cost, 0, ',', '.') : 'Gratis' ?>
                        </li>
                        <li><strong>Kuota:</strong> <?= esc($training->quota ?? 'Tidak terbatas') ?> Peserta</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <?php if (session()->get('isLoggedIn') && session()->get('role') === 'jobseeker'): ?>
                        <?php if ($isRegistered): ?>
                            <button class="btn btn-success btn-block" disabled>
                                <i class="bi bi-check-circle-fill"></i> Anda Sudah Terdaftar
                            </button>
                        <?php elseif ($isQuotaFull): ?>
                            <button class="btn btn-danger btn-block" disabled>
                                <i class="bi bi-x-circle-fill"></i> Kuota Penuh
                            </button>
                        <?php else: ?>
                            <form action="<?= site_url('daftar-pelatihan/apply/' . ($training->id ?? '')) ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-register btn-block mb-2"
                                    onclick="return confirm('Apakah Anda yakin ingin mendaftar pelatihan ini?');">
                                    <i class="bi bi-person-plus-fill"></i> Daftar Sekarang
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php elseif (!session()->get('isLoggedIn')): ?>
                         <a href="/login?redirect=<?= urlencode(current_url(true)) ?>" class="btn btn-register btn-block">
                            <i class="bi bi-box-arrow-in-right"></i> Login untuk Mendaftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>