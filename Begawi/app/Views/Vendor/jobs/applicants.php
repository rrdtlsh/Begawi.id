<?= $this->extend('layouts/vendor_dashboard_layout') ?>

<?= $this->section('content') ?>

<section class="mb-4">
    <a href="<?= site_url('vendor/dashboard') ?>" class="btn btn-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
    <h1 class="h3 mb-2">Daftar Pelamar</h1>
    <p class="text-secondary">Untuk lowongan: <strong><?= esc($job->title ?? 'Tidak Diketahui') ?></strong></p>
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
                        <!-- PERBAIKAN: Kolom Status dikembalikan -->
                        <th scope="col">Status</th>
                        <th scope="col" style="min-width: 340px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applicants)): ?>
                    <?php foreach ($applicants as $index => $applicant): ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td>
                            <div class="fw-bold"><?= esc($applicant->jobseeker_name ?? 'Data Peserta Hilang') ?></div>
                            <div class="small text-secondary"><?= esc($applicant->jobseeker_email ?? 'N/A') ?></div>
                        </td>
                        <td><?= date('d M Y, H:i', strtotime($applicant->applied_at)) ?></td>
                        <td>
                            <!-- PERBAIKAN: Badge status sekarang di kolomnya sendiri -->
                            <?php
                                    $status_class = [
                                        'pending' => 'bg-warning text-dark',
                                        'reviewed' => 'bg-info text-white',
                                        'accepted' => 'bg-success text-white',
                                        'rejected' => 'bg-danger text-white',
                                    ];
                                    ?>
                            <span
                                class="badge <?= $status_class[$applicant->status] ?? 'bg-secondary' ?> p-2"><?= ucfirst($applicant->status ?? 'N/A') ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <!-- Tombol Aksi Lainnya -->
                                <a href="<?= site_url('vendor/jobs/applicant/' . $applicant->application_id) ?>"
                                    class="btn btn-sm btn-light border" title="Lihat Detail Pelamar">
                                    Detail
                                </a>

                                <a href="<?= base_url('uploads/resumes/' . $applicant->resume_file_path) ?>"
                                    target="_blank" class="btn btn-sm btn-light border" title="Unduh CV Pelamar">
                                    Unduh CV
                                </a>

                                <!-- Form untuk mengubah status -->
                                <form
                                    action="<?= site_url('vendor/applicants/' . $applicant->application_id . '/status') ?>"
                                    method="post" class="d-flex gap-1 ms-auto">
                                    <?= csrf_field() ?>
                                    <select name="status" class="form-select form-select-sm" style="width: 110px;">
                                        <option value="pending"
                                            <?= ($applicant->status ?? '') == 'pending' ? 'selected' : '' ?>>Pending
                                        </option>
                                        <option value="accepted"
                                            <?= ($applicant->status ?? '') == 'accepted' ? 'selected' : '' ?>>Accept
                                        </option>
                                        <option value="rejected"
                                            <?= ($applicant->status ?? '') == 'rejected' ? 'selected' : '' ?>>Reject
                                        </option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Ubah</button>
                                </form>
                            </div>
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