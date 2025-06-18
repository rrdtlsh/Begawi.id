Tentu, saya akan merapikan file `app/Views/Vendor/trainings/participants.php` agar memiliki struktur dan tampilan yang konsisten dengan file referensi `applicants.php` yang Anda berikan.

Perubahan yang saya lakukan meliputi:
1.  Membuat struktur `card-header` untuk menampung judul dan tombol **Download Laporan (Excel & PDF)** agar lebih rapi.
2.  Menambahkan kolom **Status** dengan *badge* berwarna agar status setiap peserta mudah terlihat, sama seperti pada halaman daftar pelamar.
3.  Mengubah nama kolom terakhir menjadi **Aksi** dan menatanya agar form perubahan status berada di sisi kanan tabel.
4.  Memperbaiki posisi tombol download yang sebelumnya salah tempat.

Berikut adalah kode `participants.php` yang telah dirapikan:

```php
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Peserta</h5>
        <div>
            <a href="<?= site_url('vendor/trainings/'.$training->id.'/download-excel') ?>" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel-fill"></i> Download Excel
            </a>
            <a href="<?= site_url('vendor/trainings/'.$training->id.'/download-pdf') ?>" class="btn btn-danger btn-sm">
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
                        <th scope="col">Nama Peserta</th>
                        <th scope="col">Tanggal Mendaftar</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end" style="min-width: 240px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($participants)): ?>
                        <?php foreach ($participants as $index => $participant): ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td>
                                <div class="fw-bold"><?= esc($participant->jobseeker_name ?? 'Data Peserta Hilang') ?></div>
                                <div class="small text-secondary"><?= esc($participant->jobseeker_email ?? 'N/A') ?></div>
                            </td>
                            <td><?= date('d M Y, H:i', strtotime($participant->enrolled_at ?? time())) ?></td>
                            <td>
                                <?php
                                $status_class = [
                                    'pending'  => 'bg-warning text-dark',
                                    'accepted' => 'bg-success text-white',
                                    'rejected' => 'bg-danger text-white',
                                ];
                                ?>
                                <span class="badge <?= $status_class[$participant->status] ?? 'bg-secondary' ?> p-2"><?= ucfirst($participant->status ?? 'N/A') ?></span>
                            </td>
                            <td class="text-end">
                                <?php if (isset($participant->id)): ?>
                                    <form action="<?= site_url('vendor/trainings/participants/' . $participant->id . '/status') ?>" method="post" class="d-flex justify-content-end gap-2">
                                        <?= csrf_field() ?>
                                        <select name="status" class="form-select form-select-sm" style="width: auto;">
                                            <option value="pending" <?= ($participant->status ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="accepted" <?= ($participant->status ?? '') == 'accepted' ? 'selected' : '' ?>>Accept</option>
                                            <option value="rejected" <?= ($participant->status ?? '') == 'rejected' ? 'selected' : '' ?>>Reject</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Ubah</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-danger small">ID Pendaftaran tidak valid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">
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