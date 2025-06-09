<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">

<div class="container my-5">

    <h1 class="page-title mb-4">Profil Saya</h1>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (!empty($profile)): ?>
    <div class="profile-card-main shadow-sm mb-5">
        <div class="profile-avatar">
            <img src="<?= !empty($profile->profile_picture_path) ? base_url('uploads/avatars/' . esc($profile->profile_picture_path)) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' ?>"
                alt="Foto Profil">
        </div>

        <div class="profile-info">
            <h2><?= esc($profile->user_fullname ?? session()->get('fullname')) ?></h2>
            <p class="contact-info">
                <span><i
                        class="bi bi-geo-alt-fill"></i><?= esc($profile->location_name ?? 'Domisili belum diisi') ?></span>
                |
                <span><i class="bi bi-telephone-fill"></i><?= esc($profile->phone ?? 'Telepon belum diisi') ?></span>
            </p>
            <div class="skills-section">
                <h6>Keahlian Anda:</h6>
                <div>
                    <?php if (!empty($profile->skills)): ?>
                    <?php foreach ($profile->skills as $skill): ?>
                    <span class="skill-tag"><?= esc($skill->name) ?></span>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <span class="text-muted">Belum ada keahlian ditambahkan.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <a href="/jobseeker/profile/edit" class="btn"><i class="bi bi-pencil-fill"></i> Edit Profil</a>
            <?php if (!empty($profile->resume_path)): ?>
            <a href="<?= base_url('uploads/resumes/' . esc($profile->resume_path)) ?>" class="btn" target="_blank"><i
                    class="bi bi-file-earmark-text-fill"></i> Lihat CV</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>


    <div class="activity-card shadow-sm">
        <div class="activity-header">
            <h5><i class="bi bi-list-task"></i> Aktivitas Terakhir (Lamaran & Pelatihan)</h5>
            <a href="/jobseeker/history" class="btn-view-all">Lihat Semua Status</a>
        </div>
        <div class="activity-list">
            <?php if (empty($recent_history)): ?>
            <div class="text-center text-muted p-5">Belum ada aktivitas.</div>
            <?php else: ?>
            <?php foreach ($recent_history as $item): ?>
            <div class="activity-item">
                <div class="d-flex align-items-center">
                    <div class="activity-icon">
                        <?php if (isset($item->job_title)): // Lamaran Kerja ?>
                        <i class="bi bi-briefcase-fill" title="Lamaran Kerja"></i>
                        <?php else: // Pelatihan ?>
                        <i class="bi bi-easel2-fill" title="Pelatihan"></i>
                        <?php endif; ?>
                    </div>
                    <div class="activity-details">
                        <?php
                                $title = isset($item->job_title) ? "Melamar di: <strong>" . esc($item->job_title) . "</strong>" : "Mendaftar Pelatihan: <strong>" . esc($item->title) . "</strong>";
                                $company = esc($item->company_name ?? $item->penyelenggara ?? 'N/A');
                                ?>
                        <div class="activity-title"><?= $title ?></div>
                        <div class="activity-company"><?= $company ?></div>
                    </div>
                </div>

                <div class="activity-status">
                    <?php
                            $date = isset($item->applied_at) ? $item->applied_at : $item->enrolled_at;
                            $status = strtolower($item->status);
                            $status_text = ($status === 'accepted') ? 'Accepted' : ucfirst($status);

                            // Mapping status ke class CSS baru
                            $status_class = 'status-reviewed'; // default
                            if (in_array($status, ['pending']))
                                $status_class = 'status-pending';
                            if (in_array($status, ['accepted']))
                                $status_class = 'status-accepted';
                            if (in_array($status, ['rejected']))
                                $status_class = 'status-rejected';
                            ?>
                    <div class="activity-date"><?= date('j M Y', strtotime($date)) ?></div>
                    <span class="status-badge <?= $status_class ?>"><?= esc($status_text) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>