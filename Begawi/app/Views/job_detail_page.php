<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($job->title ?? 'Detail Lowongan') ?> - Begawi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="/css/detail.css">
</head>

<body>
    <div class="container" style="max-width: 1100px; margin-top: 40px;">
        <a href="<?= site_url('home') ?>" class="btn-back"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
    </div>

    <div class="main-container">
        <div class="row">
            <div class="col-lg-8 pr-lg-5">
                <div class="d-flex align-items-center mb-3 job-header">
                    <div class="logo mr-4">
                        <?php if (!empty($job->company_logo_path)): ?>
                            <img src="/uploads/logos/<?= esc($job->company_logo_path) ?>" alt="Logo"
                                style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <?= substr(esc($job->company_name ?? 'CS'), 0, 2) ?>
                        <?php endif; ?>
                    </div>
                    <div class="job-header-info">
                        <h1><?= esc($job->title ?? 'Web Developer') ?></h1>
                        <h5><?= esc($job->company_name ?? 'PT Cipta Solusi') ?></h5>
                    </div>
                </div>
                <div class="job-header-meta mb-4">
                    <span><i class="bi bi-geo-alt-fill"></i> <?= esc($job->location_name ?? 'Banjarmasin Selatan') ?></span>
                    <span><i class="bi bi-briefcase-fill"></i> <?= esc($job->job_type ?? 'Penuh Waktu (Full Time)') ?></span>
                    <span><i class="bi bi-calendar3"></i> Diposting:
                        <?= isset($job->created_at) ? date('j F Y', strtotime($job->created_at)) : '3 Juni 2025' ?></span>
                </div>
                <hr>

                <h4 class="section-title">Deskripsi Pekerjaan</h4>
                <p><?= isset($job->description) ? nl2br(esc($job->description)) : 'Kami mencari seorang Web Developer yang bersemangat dan berpengalaman...' ?></p>

                <h4 class="section-title mt-4">Kualifikasi</h4>
                <div id="qualifications-list">
                    <?= isset($job->qualifications) ? nl2br(esc($job->qualifications)) : '<ul><li>Minimal S1 di bidang Ilmu Komputer...</li></ul>' ?>
                </div>

                <h4 class="section-title mt-4">Cara Melamar</h4>
                <p><?= isset($job->application_instructions) ? nl2br(esc($job->application_instructions)) : 'Anda dapat melamar posisi ini langsung melalui website kami atau mengirimkan lamaran via email.<br><br>Untuk lamaran via email, kirimkan CV terbaru dan portofolio Anda ke: karir@ciptasolusi.com dengan subjek email <strong>‘Lamaran Web Developer - [Nama Anda]’</strong>.' ?></p>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 class="section-title">Ringkasan Pekerjaan</h5>
                    <ul>
                        <li><strong>Kategori:</strong> <?= esc($job->category_name ?? 'IT & Perangkat Lunak') ?></li>
                        <li><strong>Lokasi:</strong> <?= esc($job->location_name ?? 'Banjarmasin Selatan') ?></li>
                        <li><strong>Tipe Pekerjaan:</strong> <?= esc($job->job_type ?? 'Penuh Waktu (Full Time)') ?></li>
                        <li>
                            <strong>Gaji:</strong>
                            <?php if (!empty($job->salary_min) && !empty($job->salary_max)): ?>
                                Rp <?= number_format($job->salary_min, 0, ',', '.') ?> - Rp
                                <?= number_format($job->salary_max, 0, ',', '.') ?>
                            <?php else: ?>
                                Rp 5.000.000 - Rp 7.000.000
                            <?php endif; ?>
                        </li>
                        <li><strong>Batas Waktu Lamaran:</strong>
                            <?= isset($job->application_deadline) ? date('j F Y', strtotime($job->application_deadline)) : '3 Juli 2025' ?></li>
                    </ul>
                </div>

                <div class="summary-card mt-4">
                    <h5 class="section-title">Tentang Perusahaan</h5>
                    <p><strong><?= esc($job->company_name ?? 'PT Cipta Solusi') ?></strong></p>
                    <p class="company-profile"><?= esc($job->company_profile ?? 'PT Cipta Solusi adalah perusahaan teknologi...') ?></p>
                </div>

                <div class="mt-4">
                    <?php if (function_exists('session') && session()->get('isLoggedIn') && session()->get('role') === 'jobseeker'): ?>
                        <?php if ($hasApplied): ?>
                            <button class="btn btn-success btn-block" disabled><i class="bi bi-check-circle-fill"></i> Anda Sudah Melamar</button>
                        <?php else: ?>
                            <a href="/lamar/job/<?= $job->id ?? '' ?>" class="btn btn-apply btn-block text-center">
                                <i class="bi bi-cursor-fill"></i> Lamar dari Web Ini
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/login?redirect=<?= function_exists('current_url') ? current_url() : '' ?>" class="btn btn-apply btn-block text-center">
                           <i class="bi bi-cursor-fill"></i> Lamar dari Web Ini
                        </a>
                    <?php endif; ?>

                    <?php 
                        $contact_email = $job->contact_email ?? 'karir@ciptasolusi.com';
                        $email_subject = 'Lamaran: ' . ($job->title ?? 'Web Developer');
                    ?>
                    <a href="mailto:<?= esc($contact_email) ?>?subject=<?= rawurlencode($email_subject) ?>" class="btn btn-apply-email btn-block text-center mt-2">
                        <i class="bi bi-envelope"></i> Lamar Via Email
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>