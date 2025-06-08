<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($training->title ?? 'Detail Pelatihan') ?> - Begawi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
        }

        .main-container {
            max-width: 1100px;
            margin: 40px auto;
        }

        .detail-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .training-header .logo {
            width: 80px;
            height: 80px;
            background-color: #A1C349;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            border-radius: 12px;
        }

        .training-header-info h1 {
            font-weight: bold;
        }

        .training-header-meta {
            color: #777;
        }

        .training-header-meta span {
            margin-right: 20px;
        }

        .section-title {
            font-weight: bold;
            color: #0c392c;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .summary-card {
            background-color: #f0f2f5;
            border-radius: 12px;
            padding: 20px;
        }

        .summary-card ul {
            list-style: none;
            padding-left: 0;
        }

        .summary-card ul li {
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 0.9rem;
        }

        .summary-card ul li:last-child {
            border-bottom: none;
        }

        .btn-register {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            padding: 12px;
        }

        .btn-register:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <!-- Anda bisa menyertakan layout navbar utama di sini jika ada -->
    <!-- Contoh: <?= $this->include('layouts/navbar') ?> -->

    <div class="main-container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="detail-card">
            <div class="row">
                <!-- Kolom Kiri (Deskripsi) -->
                <div class="col-lg-7">
                    <!-- Header Pelatihan -->
                    <div class="d-flex align-items-center mb-3 training-header">
                        <div class="logo mr-4">
                            <?php if (!empty($training->company_logo_path)): ?>
                                <img src="/uploads/logos/<?= esc($training->company_logo_path) ?>" alt="Logo Penyelenggara"
                                    style="width:100%; height:100%; border-radius:12px; object-fit:cover;">
                            <?php else: ?>
                                <?= substr(esc($training->penyelenggara), 0, 2) ?>
                            <?php endif; ?>
                        </div>
                        <div class="training-header-info">
                            <h1><?= esc($training->title) ?></h1>
                            <h5>Oleh: <?= esc($training->penyelenggara) ?></h5>
                        </div>
                    </div>
                    <div class="training-header-meta mb-4">
                        <span><i class="bi bi-geo-alt-fill"></i> <?= esc($training->location_name ?? 'Online') ?></span>
                        <span><i class="bi bi-calendar-event"></i> Mulai:
                            <?= date('d F Y', strtotime($training->start_date)) ?></span>
                    </div>
                    <hr class="mb-4">

                    <h4 class="section-title">Deskripsi Pelatihan</h4>
                    <p><?= nl2br(esc($training->description)) ?></p>

                    <h4 class="section-title mt-4">Platform Pelatihan</h4>
                    <p><?= nl2br(esc($training->platform)) ?></p>

                    <h4 class="section-title mt-4">Instruksi Pendaftaran</h4>
                    <p><?= nl2br(esc($training->registration_instructions ?? 'Tidak ada instruksi khusus.')) ?></p>
                </div>

                <!-- Kolom Kanan (Ringkasan & Aksi) -->
                <div class="col-lg-5">
                    <div class="summary-card">
                        <h5>Ringkasan Pelatihan</h5>
                        <ul class="mt-3">
                            <li><strong>Kategori:</strong> <?= esc($training->category_name ?? 'N/A') ?></li>
                            <li><strong>Jadwal:</strong> <?= date('d M Y, H:i', strtotime($training->start_date)) ?>
                            </li>
                            <li><strong>Durasi:</strong> <?= esc($training->duration) ?></li>
                            <li><strong>Biaya:</strong>
                                <?= ($training->cost > 0) ? 'Rp ' . number_format($training->cost, 0, ',', '.') : 'Gratis' ?>
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
                            <?php else: ?>
                                <form action="<?= site_url('daftar-pelatihan/apply/' . $training->id) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-register btn-block mb-2"
                                        onclick="return confirm('Apakah Anda yakin ingin mendaftar pelatihan ini?');">
                                        <i class="bi bi-person-plus-fill"></i> Daftar Sekarang
                                    </button>
                                </form>
                            <?php endif; ?>

                        <?php elseif (!session()->get('isLoggedIn')): ?>
                            <a href="/login?redirect=<?= current_url() ?>" class="btn btn-register btn-block">Login untuk
                                Mendaftar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>