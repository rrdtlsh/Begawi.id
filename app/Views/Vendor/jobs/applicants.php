<?= $this->extend('layouts/vendor_dashboard_layout') ?>

<?= $this->section('content') ?>

<section class="mb-4">
    <a href="<?= site_url('vendor/dashboard') ?>" class="btn btn-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
    <h1 class="h3 mb-2">Daftar Pelamar</h1>
    <p class="text-secondary">Untuk lowongan: <strong><?= esc($job->title) ?></strong></p>
</section>

<section class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Pelamar</th>
                        <th scope="col">Tanggal Melamar</th>
                        <th scope="col">CV</th>
                        <th scope="col" style="min-width: 200px;">Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applicants)): ?>
                        <?php foreach ($applicants as $index => $applicant): ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td>
                                    <div><?= esc($applicant->jobseeker_name) ?></div>
                                    <div class="small text-secondary"><?= esc($applicant->jobseeker_email) ?></div>
                                </td>
                                <td><?= date('d M Y, H:i', strtotime($applicant->applied_at)) ?></td>
                                <td>
                                    <?php
                                        // Logika untuk tombol Lihat CV
                                        $resume_url = !empty($applicant->resume_file_path) ? base_url('uploads/resumes/' . $applicant->resume_file_path) : '#!';
                                        $disabled_class = empty($applicant->resume_file_path) ? 'disabled' : '';
                                    ?>
                                    <a href="<?= $resume_url ?>" target="_blank" class="btn btn-sm btn-outline-secondary <?= $disabled_class ?>" title="Lihat CV">
                                        <i class="bi bi-file-earmark-person-fill"></i> Lihat
                                    </a>
                                </td>
                                <td>
                                    <!-- Formulir untuk mengubah status lamaran -->
                                    <form action="<?= site_url('vendor/applicants/' . $applicant->application_id . '/status') ?>" method="post" class="d-flex gap-2">
                                        <?= csrf_field() ?>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="pending" <?= $applicant->status == 'pending' ? 'selected' : '' ?>>Menunggu</option>
                                            <option value="reviewed" <?= $applicant->status == 'reviewed' ? 'selected' : '' ?>>Ditinjau</option>
                                            <option value="accepted" <?= $applicant->status == 'accepted' ? 'selected' : '' ?>>Terima</option>
                                            <option value="rejected" <?= $applicant->status == 'rejected' ? 'selected' : '' ?>>Tolak</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">
                                Belum ada pelamar untuk lowongan ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
