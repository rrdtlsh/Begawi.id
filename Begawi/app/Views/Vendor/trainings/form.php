<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Publikasikan Pelatihan') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3><?= esc($title) ?></h3>
                        <p class="text-muted">Publikasikan Workshop atau Pelatihan</p>
                    </div>
                    <div class="card-body">
                        <?php if (session()->get('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session()->get('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <form
                            action="<?= isset($training) ? "/vendor/trainings/update/{$training->id}" : "/vendor/trainings/create" ?>"
                            method="post">
                            <?= csrf_field() ?>

                            <h5>Informasi Umum</h5>
                            <div class="form-group">
                                <label for="title">Judul Workshop/Pelatihan</label>
                                <input type="text" name="title" class="form-control"
                                    value="<?= old('title', $training->title ?? '') ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="location_id">Lokasi</label>
                                    <select name="location_id" class="form-control" required>
                                        <option value="">Pilih Lokasi</option>
                                        <?php foreach ($locations as $loc): ?>
                                        <option value="<?= $loc->id ?>"
                                            <?= (old('location_id', $training->location_id ?? '') == $loc->id) ? 'selected' : '' ?>>
                                            <?= esc($loc->name) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="category_id">Kategori</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>"
                                            <?= (old('category_id', $training->category_id ?? '') == $cat->id) ? 'selected' : '' ?>>
                                            <?= esc($cat->name) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi Lengkap</label>
                                <textarea name="description" class="form-control" rows="5"
                                    required><?= old('description', $training->description ?? '') ?></textarea>
                            </div>
                            <hr>

                            <h5>Detail Workshop/Pelatihan</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_date">Tanggal & Waktu Pelaksanaan</label>
                                    <input type="datetime-local" name="start_date" class="form-control"
                                        value="<?= old('start_date', isset($training->start_date) ? date('Y-m-d\TH:i', strtotime($training->start_date)) : '') ?>"
                                        required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="duration">Durasi</label>
                                    <input type="text" name="duration" class="form-control"
                                        placeholder="Contoh: 3 Jam / 2 Hari"
                                        value="<?= old('duration', $training->duration ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="platform">Tempat/Platform</label>
                                    <input type="text" name="platform" class="form-control"
                                        placeholder="Contoh: Zoom Meeting / Aula Gedung A"
                                        value="<?= old('platform', $training->platform ?? '') ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cost">Harga</label>
                                    <input type="number" name="cost" class="form-control"
                                        placeholder="Isi 0 jika gratis"
                                        value="<?= old('cost', $training->cost ?? '0') ?>" required>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-6"">
                                    <label for=" application_deadline">Batas Waktu Pendaftaran</label>
                                    <input type="datetime-local" name="application_deadline" class="form-control"
                                        value="<?= old('application_deadline', isset($training->application_deadline) ? date('Y-m-d\TH:i', strtotime($training->application_deadline)) : '') ?>"
                                        required>
                                    <small class="form-text text-muted">Peserta harus mendaftar sebelum tanggal
                                        ini.</small>
                                </div>

                                <div class="form-group col-md-6"">
                                    <label for=" quota">Kuota Peserta (Opsional)</label>
                                    <input type="number" name="quota" class="form-control" placeholder="Contoh: 50"
                                        value="<?= old('quota', $training->quota ?? '') ?>">
                                    <small class="form-text text-muted">Kosongkan jika tidak ada batasan kuota.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="registration_instructions">Cara Mendaftar</label>
                                <textarea name="registration_instructions" class="form-control" rows="3"
                                    placeholder="Contoh: Daftar melalui link bit.ly/daftarkelas atau hubungi CP..."><?= old('registration_instructions', $training->registration_instructions ?? '') ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="contact_email">Email Kontak</label>
                                    <input type="email" name="contact_email" class="form-control"
                                        placeholder="email.hrd@perusahaan.com"
                                        value="<?= old('contact_email', $training->contact_email ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="contact_phone">Nomor Telepon Kontak (Opsional)</label>
                                    <input type="text" name="contact_phone" class="form-control"
                                        placeholder="08xxxxxxxxxx"
                                        value="<?= old('contact_phone', $training->contact_phone ?? '') ?>">
                                </div>
                            </div>
                            <hr>

                            <button type="submit" class="btn btn-success">Publikasikan Sekarang</button>
                            <a href="/vendor/trainings" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>