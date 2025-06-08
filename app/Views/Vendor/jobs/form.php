<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Publikasikan Lowongan') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3><?= esc($title) ?></h3>
                        <p class="text-muted">Publikasikan Lowongan Pekerjaan</p>
                    </div>
                    <div class="card-body">
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

                        <form action="<?= isset($job) ? "/vendor/jobs/update/{$job->id}" : "/vendor/jobs/create" ?>"
                            method="post">
                            <?= csrf_field() ?>

                            <h5>Informasi Umum</h5>
                            <div class="form-group">
                                <label for="title">Judul Postingan (Pekerjaan)</label>
                                <input type="text" name="title" class="form-control"
                                    value="<?= old('title', $job->title ?? '') ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="location_id">Lokasi</label>
                                    <select name="location_id" class="form-control" required>
                                        <option value="">Pilih Lokasi</option>
                                        <?php foreach ($locations as $loc): ?>
                                        <option value="<?= $loc->id ?>"
                                            <?= (old('location_id', $job->location_id ?? '') == $loc->id) ? 'selected' : '' ?>>
                                            <?= esc($loc->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="category_id">Kategori</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>"
                                            <?= (old('category_id', $job->category_id ?? '') == $cat->id) ? 'selected' : '' ?>>
                                            <?= esc($cat->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Lengkap (Tanggung Jawab, Kualifikasi, dll.)</label>
                                <textarea name="description" class="form-control" rows="5"
                                    placeholder="Jelaskan secara detail mengenai lowongan yang Anda tawarkan..."><?= old('description', $job->description ?? '') ?></textarea>
                            </div>
                            <hr>

                            <h5>Detail Lowongan Pekerjaan</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="job_type">Tipe Pekerjaan</label>
                                    <select name="job_type" class="form-control">
                                        <?php $job_types = ['Full-time', 'Part-time', 'Contract', 'Internship', 'Freelance']; ?>
                                        <?php foreach ($job_types as $type): ?>
                                        <option value="<?= $type ?>"
                                            <?= (old('job_type', $job->job_type ?? 'Full-time') == $type) ? 'selected' : '' ?>>
                                            <?= $type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="salary_min">Gaji Minimum (Opsional)</label>
                                    <input type="number" name="salary_min" class="form-control" placeholder="5000000"
                                        value="<?= old('salary_min', $job->salary_min ?? '') ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="salary_max">Gaji Maksimum (Opsional)</label>
                                    <input type="number" name="salary_max" class="form-control" placeholder="7000000"
                                        value="<?= old('salary_max', $job->salary_max ?? '') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="application_deadline">Batas Waktu Lamaran</label>
                                <input type="datetime-local" name="application_deadline" class="form-control"
                                    value="<?= old('application_deadline', isset($job->application_deadline) ? date('Y-m-d\TH:i', strtotime($job->application_deadline)) : '') ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="quota">Kuota Pelamar (Opsional)</label>
                                <input type="number" name="quota" class="form-control" placeholder="Contoh: 10"
                                    value="<?= old('quota', $job->quota ?? '') ?>">
                                <small class="form-text text-muted">Kosongkan jika tidak ada batasan kuota.</small>
                            </div>
                            <!-- --- AKHIR PENAMBAHAN --- -->

                            <div class="form-group">
                                <label for="application_instructions">Cara Melamar</label>
                                <textarea name="application_instructions" class="form-control" rows="3"
                                    placeholder="Contoh: Kirim CV dan portofolio ke email@perusahaan.com dengan subjek 'Lamaran Backend Developer'"><?= old('application_instructions', $job->application_instructions ?? '') ?></textarea>
                            </div>
                            <hr>

                            <h5>Informasi Kontak</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="contact_email">Email Kontak</label>
                                    <input type="email" name="contact_email" class="form-control"
                                        placeholder="email.hrd@perusahaan.com"
                                        value="<?= old('contact_email', $job->contact_email ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="contact_phone">Nomor Telepon Kontak (Opsional)</label>
                                    <input type="text" name="contact_phone" class="form-control"
                                        placeholder="08xxxxxxxxxx"
                                        value="<?= old('contact_phone', $job->contact_phone ?? '') ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Publikasikan Sekarang</button>
                            <a href="/vendor/jobs" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>