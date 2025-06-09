<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Publikasikan Lowongan') ?></title>
    <link rel="stylesheet" href="/css/postinglowongan.css">
</head>
<body>
    <div class="main-container">
        <h1 class="page-title">Publikasikan Lowongan Pekerjaan</h1>
        
        <div class="form-publish-container">
            <?php if (session()->get('errors')): ?>
            <div class="alert alert-danger">
                <strong>Terjadi Kesalahan:</strong>
                <ul>
                    <?php foreach (session()->get('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form action="<?= isset($job) ? "/vendor/jobs/update/{$job->id}" : "/vendor/jobs/create" ?>" method="post">
                <?= csrf_field() ?>

                <h5 class="form-section-title">Informasi Umum</h5>
                
                <div class="form-group">
                    <label for="title">Judul Postingan (Pekerjaan)</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= old('title', $job->title ?? '') ?>" placeholder="Contoh: Web Developer Expert / Workshop Digital Marketing" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= (old('category_id', $job->category_id ?? '') == $cat->id) ? 'selected' : '' ?>>
                                    <?= esc($cat->name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location_id">Lokasi</label>
                            <select name="location_id" id="location_id" class="form-control" required>
                                <option value="">Pilih Lokasi</option>
                                <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc->id ?>" <?= (old('location_id', $job->location_id ?? '') == $loc->id) ? 'selected' : '' ?>>
                                    <?= esc($loc->name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Lengkap (Tanggung Jawab, Kualifikasi, Detail Workshop, dll.)</label>
                    <textarea name="description" id="description" class="form-control" rows="5" placeholder="Jelaskan secara detail mengenai lowongan atau workshop yang Anda tawarkan..."><?= old('description', $job->description ?? '') ?></textarea>
                </div>

                <h5 class="form-section-title">Detail Lowongan Pekerjaan</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="job_type">Tipe Pekerjaan</label>
                            <select name="job_type" id="job_type" class="form-control" required>
                                <?php
                                $job_types = [
                                    'Full-time'  => 'Penuh Waktu (Full-time)',
                                    'Part-time'  => 'Paruh Waktu (Part-time)',
                                    'Contract'   => 'Kontrak (Contract)',
                                    'Internship' => 'Magang (Internship)',
                                    'Freelance'  => 'Lepas (Freelance)'
                                ];
                                ?>
                                <option value="">Pilih Tipe Pekerjaan</option>
                                <?php foreach ($job_types as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= (old('job_type', $job->job_type ?? '') == $value) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="salary_min">Gaji Minimum (Opsional)</label>
                            <input type="text" name="salary_min" id="salary_min" class="form-control" placeholder="1.000.000" value="<?= old('salary_min', $job->salary_min ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="salary_max">Gaji Maksimum (Opsional)</label>
                            <input type="text" name="salary_max" id="salary_max" class="form-control" placeholder="5.000.000" value="<?= old('salary_max', $job->salary_max ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="application_deadline">Batas Waktu Lamaran</label>
                            <input type="date" name="application_deadline" id="application_deadline" class="form-control" value="<?= old('application_deadline', isset($job->application_deadline) ? date('Y-m-d', strtotime($job->application_deadline)) : '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quota">Kuota Peserta (Opsional)</label>
                            <input type="number" name="quota" id="quota" class="form-control" placeholder="Contoh: 20" value="<?= old('quota', $job->quota ?? '') ?>">
                            <small class="form-text text-muted">Kosongkan jika tidak ada batasan kuota.</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="application_instructions">Cara Melamar</label>
                    <textarea name="application_instructions" id="application_instructions" class="form-control" rows="3" placeholder="Contoh: Kirim CV dan portofolio ke email@perusahaan.com dengan subjek 'Lamaran Backend Developer' atau link ke form lamaran."><?= old('application_instructions', $job->application_instructions ?? '') ?></textarea>
                </div>

                <h5 class="form-section-title">Informasi Kontak</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_email">Email Kontak</label>
                            <input type="email" name="contact_email" id="contact_email" class="form-control" placeholder="alamat@emailanda.com" value="<?= old('contact_email', $job->contact_email ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_phone">Nomor Telepon Kontak (Opsional)</label>
                            <input type="text" name="contact_phone" id="contact_phone" class="form-control" placeholder="08xxxxxxxxxx" value="<?= old('contact_phone', $job->contact_phone ?? '') ?>">
                        </div>
                    </div>
                </div>
                
                <div class="button-group">
                    <a href="/vendor/dashboard" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-publish">
                        <span>Publikasikan Sekarang</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16"><path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                
                // Fungsi untuk format Rupiah
                function formatRupiah(angka) {
                    let number_string = angka.replace(/[^,\d]/g, '').toString(),
                        split = number_string.split(','),
                        sisa = split[0].length % 3,
                        rupiah = split[0].substr(0, sisa),
                        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                    return rupiah;
                }

                // Terapkan format Rupiah pada input gaji
                const salaryMinInput = document.getElementById('salary_min');
                const salaryMaxInput = document.getElementById('salary_max');

                salaryMinInput.addEventListener('keyup', function(e) {
                    salaryMinInput.value = formatRupiah(this.value);
                });

                salaryMaxInput.addEventListener('keyup', function(e) {
                    salaryMaxInput.value = formatRupiah(this.value);
                });

                // Validasi agar input angka tidak bisa di bawah 0
                const numberInputs = document.querySelectorAll('input[type="number"]');
                numberInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        if (this.value < 0) {
                            this.value = 0;
                        }
                    });
                });

            });
        </script>
    </body>
</html>