<?= $this->extend('layouts/vendor_dashboard_layout') ?>

<?= $this->section('content') ?>

<section class="mb-4">
    <a href="<?= site_url('vendor/dashboard') ?>" class="btn btn-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
    <h1 class="h3 mb-2">Daftar Peserta</h1>
    <p class="text-secondary">Untuk pelatihan: <strong><?= esc($training->title ?? 'Tidak Diketahui') ?></strong></p>
</section>

<section class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Peserta</th>
                        <th scope="col">Tanggal Mendaftar</th>
                        <th scope="col" style="min-width: 220px;">Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($participants)): ?>
                    <?php foreach ($participants as $index => $participant): ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td>
                            <?php // PERBAIKAN: Menambahkan pengecekan jika data peserta tidak ada ?>
                            <div><?= esc($participant->jobseeker_name ?? 'Data Peserta Hilang') ?></div>
                            <div class="small text-secondary"><?= esc($participant->jobseeker_email ?? 'N/A') ?></div>
                        </td>
                        <td><?= date('d M Y, H:i', strtotime($participant->enrolled_at ?? time())) ?></td>
                        <td>
                            <?php // Pastikan ID aplikasi ada sebelum membuat form ?>
                            <?php if (isset($participant->id)): ?>
                            <form
                                action="<?= site_url('vendor/trainings/participants/' . $participant->id . '/status') ?>"
                                method="post" class="d-flex gap-2">
                                <?= csrf_field() ?>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending"
                                        <?= ($participant->status ?? '') == 'pending' ? 'selected' : '' ?>>Menunggu
                                    </option>
                                    <option value="accepted"
                                        <?= ($participant->status ?? '') == 'accepted' ? 'selected' : '' ?>>Setujui
                                    </option>
                                    <option value="rejected"
                                        <?= ($participant->status ?? '') == 'rejected' ? 'selected' : '' ?>>Tolak
                                    </option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            </form>
                            <?php else: ?>
                            <span class="text-danger">ID Pendaftaran tidak valid</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-4">
                                Belum ada peserta yang mendaftar untuk pelatihan ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
