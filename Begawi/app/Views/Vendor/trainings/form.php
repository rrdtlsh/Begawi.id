<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="/css/postinglowongan.css">
</head>

<body>

    <div class="main-container">
        <h3 class="page-title"><?= esc($title) ?></h3>
        <p class="text-muted" style="margin-top: -25px; margin-bottom: 20px;">Publikasikan Pelatihan Baru</p>

        <div class="form-publish-container">
            <?php if (session()->get('errors')): ?>
                <div style="background-color: #f8d7da; ...">
                    <strong>Terjadi Kesalahan:</strong>
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

                <h5 class="form-section-title">Informasi Umum Pelatihan</h5>
                <div class="form-group">
                    <label for="title">Judul Pelatihan</label>
                    <input type="text" name="title" class="form-control"
                        placeholder="Contoh: Pelatihan Web Developer Fundamental"
                        value="<?= esc(old('title', $training->title ?? '')) ?>" required>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="category_id">Kategori Pelatihan</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= (old('category_id', $training->category_id ?? '') == $cat->id) ? 'selected' : '' ?>>
                                    <?= esc($cat->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="location_id">Lokasi Pelatihan</label>
                        <select name="location_id" class="form-control" required>
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc->id ?>" <?= (old('location_id', $training->location_id ?? '') == $loc->id) ? 'selected' : '' ?>>
                                    <?= esc($loc->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Lengkap Pelatihan</label>
                    <textarea name="description" class="form-control" rows="5"
                        placeholder="Jelaskan detail tentang pelatihan ini, silabus, dll."><?= esc(old('description', $training->description ?? '')) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="platform">Platform Pelatihan (Online/Offline)</label>
                    <input type="text" name="platform" class="form-control"
                        placeholder="Contoh: Zoom Meeting, Kantor Begawi"
                        value="<?= esc(old('platform', $training->platform ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label for="registration_instructions">Instruksi Pendaftaran</label>
                    <textarea name="registration_instructions" class="form-control" rows="3"
                        placeholder="Contoh: Kunjungi website xyz.com/daftar"
                        required><?= esc(old('registration_instructions', $training->registration_instructions ?? '')) ?></textarea>
                </div>
                <hr>

                <h5 class="form-section-title">Jadwal & Kuota</h5>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="start_date">Tanggal & Waktu Mulai</label>
                        <input type="datetime-local" name="start_date" class="form-control"
                            value="<?= esc(old('start_date', isset($training->start_date) && $training->start_date ? date('Y-m-d\TH:i', strtotime($training->start_date)) : '')) ?>"
                            required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_date">Tanggal & Waktu Selesai (Opsional)</label>
                        <input type="datetime-local" name="end_date" class="form-control"
                            value="<?= esc(old('end_date', isset($training->end_date) && $training->end_date ? date('Y-m-d\TH:i', strtotime($training->end_date)) : '')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="duration">Durasi Pelatihan (Jam)</label>
                    <input type="number" name="duration" class="form-control" placeholder="Contoh: 10 (untuk 10 jam)"
                        value="<?= esc(old('duration', $training->duration ?? '')) ?>" min="0" required>
                </div>

                <div class="form-group">
                    <label for="quota">Kuota Peserta (Opsional)</label>
                    <input type="number" name="quota" class="form-control" placeholder="Contoh: 20"
                        value="<?= esc(old('quota', $training->quota ?? '')) ?>" min="0">
                </div>
                <hr>

                <h5 class="form-section-title">Informasi Kontak</h5>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="contact_email">Email Kontak</label>
                        <input type="email" name="contact_email" class="form-control"
                            placeholder="email.kontak@perusahaan.com"
                            value="<?= esc(old('contact_email', $training->contact_email ?? '')) ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="contact_phone">Nomor Telepon Kontak (Opsional)</label>
                        <input type="text" name="contact_phone" class="form-control" placeholder="08xxxxxxxxxx"
                            value="<?= esc(old('contact_phone', $training->contact_phone ?? '')) ?>">
                    </div>
                </div>

                <div class="button-group">
                    <a href="/vendor/dashboard" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-publish">Publikasikan Sekarang</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>