<?= $this->extend('layouts/vendor_dashboard_layout') ?>

<?= $this->section('content') ?>

<section class="mb-4">
    <a href="javascript:history.back()" class="btn btn-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
    <h1 class="h3 mb-2"><?= esc($applicant->jobseeker_name) ?></h1>
    <p class="text-secondary">Melamar untuk posisi: <strong><?= esc($applicant->job_title) ?></strong></p>
</section>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5>Surat Lamaran (Cover Letter)</h5>
            </div>
            <div class="card-body">
                <p style="white-space: pre-wrap;"><?= esc($applicant->cover_letter ?? 'Tidak ada surat lamaran.') ?></p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Kontak</h5>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong><br><?= esc($applicant->jobseeker_email) ?></p>
                <p><strong>Telepon:</strong><br><?= esc($applicant->jobseeker_phone ?? 'N/A') ?></p>
                <p><strong>Domisili:</strong><br><?= esc($applicant->jobseeker_location ?? 'N/A') ?></p>
                <hr>
                <p><strong>Ringkasan Profil:</strong><br><?= esc($applicant->jobseeker_summary ?? 'Belum diisi.') ?></p>
                <hr>
                <div class="d-grid">
                    <a href="<?= base_url('uploads/resumes/' . $applicant->resume_file_path) ?>" target="_blank" class="btn btn-primary">
                        <i class="bi bi-download"></i> Unduh CV
                    </a>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <h5>Keahlian</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($applicant->skills)): ?>
                    <?php foreach($applicant->skills as $skill): ?>
                        <span class="badge bg-secondary text-white me-1 mb-1"><?= esc($skill->name) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-secondary">Tidak ada data keahlian.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
