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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Pelamar</h5>
        <div>
            <a href="<?= site_url('vendor/jobs/' . $job->id . '/download-excel') ?>" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel-fill"></i> Download Excel
            </a>
            <a href="<?= site_url('vendor/jobs/' . $job->id . '/download-pdf') ?>" class="btn btn-danger btn-sm">
                <i class="bi bi-file-pdf-fill"></i> Download PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Pelamar</th>
                        <th scope="col">Tanggal Melamar</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end" style="min-width: 380px;">Aksi</th>
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
                                <td>
                                    <?= \CodeIgniter\I18n\Time::parse($applicant->applied_at, 'UTC')->setTimezone('Asia/Makassar')->format('d M Y, H:i') ?>
                                </td>
                                <td>
                                    <?php
                                    $status_class = [
                                        'pending' => 'bg-warning text-dark',
                                        'accepted' => 'bg-success text-white',
                                        'rejected' => 'bg-danger text-white',
                                    ];
                                    ?>
                                    <span class="badge <?= $status_class[$applicant->status] ?? 'bg-secondary' ?> p-2"><?= ucfirst($applicant->status ?? 'N/A') ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <a href="<?= site_url('vendor/jobs/applicant/' . $applicant->application_id) ?>"
                                            class="btn btn-sm btn-light border" title="Lihat Detail Pelamar">
                                            <i class="bi bi-person-lines-fill"></i> Detail
                                        </a>

                                        <a href="<?= base_url('uploads/resumes/' . $applicant->resume_file_path) ?>"
                                            target="_blank" class="btn btn-sm btn-secondary" title="Unduh CV Pelamar">
                                            <i class="bi bi-download"></i> CV
                                        </a>

                                        <form
                                            action="<?= site_url('vendor/applicants/' . $applicant->application_id . '/status') ?>"
                                            method="post" class="d-flex gap-1 form-update-status">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="rejection_reason" class="rejection-reason-input">
                                            <select name="status" class="form-select form-select-sm status-select" style="width: auto;">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusForms = document.querySelectorAll('.form-update-status');
        statusForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const selectElement = form.querySelector('.status-select');
                const selectedValue = selectElement.value;
                const selectedText = selectElement.options[selectElement.selectedIndex].text;

                if (selectedValue === 'rejected') {
                    Swal.fire({
                        title: 'Tolak Lamaran Ini?',
                        input: 'textarea',
                        inputLabel: 'Alasan Penolakan',
                        inputPlaceholder: 'Tuliskan alasan penolakan di sini (opsional)...',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Tolak Lamaran!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const reasonInput = form.querySelector('.rejection-reason-input');
                            reasonInput.value = result.value || '';
                            form.submit();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Anda akan mengubah status pelamar ini menjadi "${selectedText}".`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, ubah status!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>