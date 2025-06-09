<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Profil Perusahaan') ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Palet Warna & Font Dasar */
        :root {
            --primary-green: #a1c349;
            --dark-green: #2a5c0f;
            --medium-green: #5d8b2f;
            --dark-grey: #495057;
            --light-grey: #f8f9fa;
            --neutral-white: #ffffff;
            --page-bg: #eef2e9; /* Warna background halaman */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--page-bg);
            color: var(--dark-grey);
        }

        /* Kontainer Utama Profil */
        .profile-container {
            background-color: var(--neutral-white);
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        
        /* Header Profil */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 30px;
        }
        .profile-logo {
            width: 80px;
            height: 80px;
            background-color: var(--primary-green);
            color: var(--neutral-white);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            font-size: 2.5rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .profile-info h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #343a40;
            margin-bottom: 0.5rem;
        }
        .profile-details {
            display: flex;
            gap: 20px;
            color: #6c757d;
        }
        .profile-details .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Judul Section */
        .section-title {
            font-weight: 600;
            font-size: 1.5rem;
            margin-top: 2rem;
            margin-bottom: 1.5rem;
        }

        /* Styling Kartu Lowongan/Pelatihan (Mirip Homepage) */
        .job-card {
            background-color: var(--neutral-white);
            border-radius: 0.75rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            height: 100%;
            margin-bottom: 1rem;
        }
        .job-card:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08);
            transform: translateY(-5px);
        }
        .job-card .company-logo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.5rem;
            flex-shrink: 0;
            background-color: var(--primary-green);
            color: var(--neutral-white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .job-card .card-title a {
            color: var(--medium-green);
            text-decoration: none;
            font-weight: 600;
        }
        .job-card .card-title a:hover {
            text-decoration: underline;
        }
        .job-card .card-subtitle {
            font-weight: 500;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .job-card .job-detail-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.5rem;
        }
        .job-detail-item i {
            margin-right: 0.75rem;
            color: var(--medium-green);
        }
        .btn-custom-green {
            background-color: var(--medium-green);
            border-color: var(--medium-green);
            color: var(--neutral-white);
            font-weight: 600;
        }
        .btn-custom-green:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
        }

        /* Tombol Kembali */
        .btn-back {
            background-color: var(--dark-grey);
            border-color: var(--dark-grey);
            color: var(--neutral-white);
            font-weight: 600;
            width: 100%;
            padding: 12px;
            margin-top: 2rem;
        }
        .btn-back:hover {
            background-color: #343a40;
            border-color: #343a40;
            color: var(--neutral-white);
        }

    </style>
</head>

<body>
    <div class="profile-container">
        <?php if (!empty($vendor)): ?>
            <div class="profile-header">
                <div class="profile-logo">
                    <?= esc(substr($vendor->company_name, 0, 2)) ?>
                </div>
                <div class="profile-info">
                    <h1><?= esc($vendor->company_name) ?></h1>
                    <div class="profile-details">
                        <span class="detail-item"><i class="bi bi-geo-alt-fill"></i> <?= esc($vendor->location_name) ?></span>
                        <span class="detail-item"><i class="bi bi-briefcase-fill"></i> <?= esc($vendor->industry) ?></span>
                    </div>
                </div>
            </div>

            <h4 class="section-title">Tentang Perusahaan</h4>
            <p><?= nl2br(esc($vendor->company_profile ?? 'Deskripsi perusahaan belum tersedia.')) ?></p>

            <h4 class="section-title">Lowongan Pekerjaan Tersedia</h4>
            <?php if (!empty($jobs)): ?>
                <div class="row">
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-6 d-flex align-items-stretch">
                        <div class="card job-card w-100">
                             <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="company-logo me-3"><?= esc(substr($vendor->company_name, 0, 2)) ?></div>
                                    <div>
                                        <h5 class="card-title mb-1"><a href="/lowongan/detail/<?= $job->id ?>"><?= esc($job->title) ?></a></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= esc($vendor->company_name) ?></h6>
                                    </div>
                                </div>
                                <div class="mb-3 small flex-grow-1">
                                    <p class="job-detail-item"><i class="bi bi-geo-alt-fill"></i> <?= esc($vendor->location_name ?? 'N/A') ?></p>
                                    <p class="job-detail-item"><i class="bi bi-briefcase-fill"></i> <?= esc($job->job_type) ?></p>
                                    <p class="job-detail-item"><i class="bi bi-calendar3"></i> Diposting: <?= date('j F Y', strtotime($job->created_at)) ?></p>
                                </div>
                                <a href="/lowongan/detail/<?= $job->id ?>" class="btn btn-custom-green mt-auto">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Saat ini tidak ada lowongan pekerjaan dari perusahaan ini.</p>
            <?php endif; ?>

            <h4 class="section-title">Pelatihan Tersedia</h4>
             <?php if (!empty($trainings)): ?>
                <div class="row">
                <?php foreach ($trainings as $training): ?>
                    <div class="col-md-6 d-flex align-items-stretch">
                         <div class="card job-card w-100">
                             <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="company-logo me-3"><?= esc(substr($vendor->company_name, 0, 2)) ?></div>
                                    <div>
                                        <h5 class="card-title mb-1"><a href="/pelatihan/detail/<?= $training->id ?>"><?= esc($training->title) ?></a></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= esc($vendor->company_name) ?></h6>
                                    </div>
                                </div>
                                <div class="mb-3 small flex-grow-1">
                                    <p class="job-detail-item"><i class="bi bi-geo-alt-fill"></i> <?= esc($training->location_name ?? 'N/A') ?></p>
                                    <p class="job-detail-item"><i class="bi bi-calendar-event"></i> Mulai: <?= date('j F Y', strtotime($training->start_date)) ?></p>
                                    <p class="job-detail-item"><i class="bi bi-patch-check-fill"></i> <?= $training->is_paid ? 'Berbayar' : 'Gratis' ?></p>
                                </div>
                                <a href="/pelatihan/detail/<?= $training->id ?>" class="btn btn-custom-green mt-auto">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Saat ini tidak ada pelatihan dari perusahaan ini.</p>
            <?php endif; ?>

            <a href="<?= site_url('/home') ?>" class="btn btn-back">Kembali</a>

        <?php else: ?>
            <div class="alert alert-warning">Profil perusahaan tidak ditemukan.</div>
        <?php endif; ?>
    </div>
</body>

</html>