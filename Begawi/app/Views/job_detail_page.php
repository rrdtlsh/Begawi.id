<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($job->title ?? 'Detail Lowongan') ?> - Begawi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #0c392c;
            color: #333;
        }

        .main-container {
            max-width: 1100px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .job-header .logo {
            width: 80px;
            height: 80px;
            background-color: #4CAF50;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            border-radius: 12px;
        }

        .job-header-info h1 {
            font-weight: bold;
        }

        .job-header-meta {
            color: #777;
        }

        .job-header-meta span {
            margin-right: 20px;
        }

        .section-title {
            font-weight: bold;
            color: #4CAF50;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .summary-card {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
        }

        .summary-card ul {
            list-style: none;
            padding-left: 0;
        }

        .summary-card ul li {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .summary-card ul li:last-child {
            border-bottom: none;
        }

        .btn-apply {
            background-color: #0c392c;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            padding: 12px;
        }

        .btn-apply:hover {
            background-color: #082a20;
        }

        .btn-apply-email {
            background-color: transparent;
            border: 2px solid #0c392c;
            color: #0c392c;
            font-weight: bold;
            border-radius: 8px;
            padding: 12px;
        }

        .btn-apply-email:hover {
            background-color: #0c392c;
            color: white;
        }
    </style>
</head>

<body>

    <div class="main-container">
        <div class="row">
            <!-- Kolom Kiri (Deskripsi) -->
            <div class="col-lg-7">
                <!-- Header Lowongan -->
                <div class="d-flex align-items-center mb-3 job-header">
                    <div class="logo mr-4">
                        <?php if (!empty($job->company_logo_path)): ?>
                            <img src="/uploads/logos/<?= esc($job->company_logo_path) ?>" alt="Logo"
                                style="width:100%; height:100%; border-radius:12px; object-fit:cover;">
                        <?php else: ?>
                            <?= substr(esc($job->company_name), 0, 2) ?>
                        <?php endif; ?>
                    </div>
                    <div class="job-header-info">
                        <h1><?= esc($job->title) ?></h1>
                        <h5><?= esc($job->company_name) ?></h5>
                    </div>
                </div>
                <div class="job-header-meta mb-4">
                    <span><i class="bi bi-geo-alt-fill"></i> <?= esc($job->location_name ?? 'N/A') ?></span>
                    <span><i class="bi bi-briefcase-fill"></i> <?= esc($job->job_type) ?></span>
                    <span><i class="bi bi-calendar3"></i> Diposting:
                        <?= date('d F Y', strtotime($job->created_at)) ?></span>
                </div>
                <hr class="mb-4">

                <!-- Detail Deskripsi -->
                <h4 class="section-title">Deskripsi Pekerjaan</h4>
                <p><?= nl2br(esc($job->description)) ?></p>

                <h4 class="section-title mt-4">kualifikasi pekerjaan</h4>
                <p><?= nl2br(esc($job->qualifications)) ?></p> <!-- Asumsi ini disimpan di kualifikasi -->

                <h4 class="section-title mt-4">Cara Melamar</h4>
                <p><?= nl2br(esc($job->application_instructions)) ?></p>
            </div>

            <!-- Kolom Kanan (Ringkasan & Aksi) -->
            <div class="col-lg-5">
                <div class="summary-card">
                    <h5>Ringkasan Pekerjaan</h5>
                    <ul class="mt-3">
                        <li><strong>Kategori:</strong> <?= esc($job->category_name ?? 'N/A') ?></li>
                        <li><strong>Lokasi:</strong> <?= esc($job->location_name ?? 'N/A') ?></li>
                        <li><strong>Tipe Pekerjaan:</strong> <?= esc($job->job_type) ?></li>
                        <li>
                            <strong>Gaji:</strong>
                            <?php if (!empty($job->salary_min) && !empty($job->salary_max)): ?>
                                Rp <?= number_format($job->salary_min, 0, ',', '.') ?> - Rp
                                <?= number_format($job->salary_max, 0, ',', '.') ?>
                            <?php else: ?>
                                Kompetitif
                            <?php endif; ?>
                        </li>
                        <li><strong>Batas Waktu Lamaran:</strong>
                            <?= date('d F Y', strtotime($job->application_deadline)) ?></li>
                    </ul>
                </div>

                <div class="summary-card mt-4">
                    <h5>Tentang Perusahaan</h5>
                    <p><?= esc($job->company_profile ?? 'Informasi tentang perusahaan belum tersedia.') ?></p>
                </div>

                <div class="mt-4">
                    <?php if (session()->get('isLoggedIn') && session()->get('role') === 'jobseeker'): ?>
                        <?php if ($hasApplied): ?>
                            <button class="btn btn-success btn-block" disabled><i class="bi bi-check-circle-fill"></i> Anda
                                Sudah Melamar</button>
                        <?php else: ?>
                            <a href="/lamar/job/<?= $job->id ?>" class="btn btn-apply btn-block mb-2">
                                <i class="bi bi-cursor-fill"></i> Lamar dari Web Ini
                            </a>
                            <?php if (!empty($job->contact_email)): ?>
                                <a href="mailto:<?= esc($job->contact_email) ?>" class="btn btn-apply-email btn-block"><i
                                        class="bi bi-envelope-fill"></i> Lamar Via Email</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/login?redirect=<?= current_url() ?>" class="btn btn-apply btn-block">Login untuk
                            Melamar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>