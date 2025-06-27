<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Pelatihan</title>
    <link rel="stylesheet" href="/css/postinglowongan.css">
</head>

<body>

    <div class="main-container">
        <h3 class="page-title"><?= esc($title) ?></h3>
        <p class="text-muted" style="margin-top: -25px; margin-bottom: 20px;">Publikasikan Pelatihan Baru</p>

        <div class="form-publish-container">
            <?php if (session()->get('errors')): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
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
                        value="<?= old('title', $training->title ?? '') ?>" required>
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
                        placeholder="Jelaskan detail tentang pelatihan ini, silabus, dll."><?= old('description', $training->description ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="platform">Platform Pelatihan (Online/Offline)</label>
                    <input type="text" name="platform" class="form-control"
                        placeholder="Contoh: Zoom Meeting, Kantor Begawi, Google Meet"
                        value="<?= old('platform', $training->platform ?? '') ?>" required>
                    <small class="form-text text-muted">Contoh: Zoom Meeting, Kantor Begawi.</small>
                </div>
                <div class="form-group">
                    <label for="registration_instructions">Instruksi Pendaftaran</label>
                    <textarea name="registration_instructions" class="form-control" rows="3"
                        placeholder="Contoh: Kunjungi website xyz.com/daftar, Isi formulir."
                        required><?= old('registration_instructions', $training->registration_instructions ?? '') ?></textarea>
                </div>
                <hr>

                <h5 class="form-section-title">Jadwal & Kuota</h5>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="start_date">Tanggal & Waktu Mulai</label>
                        <input type="datetime-local" id="start_date" name="start_date" class="form-control" value="<?= old('start_date', isset($training->start_date) && $training->start_date ? date('Y-m-d\TH:i', strtotime($training->start_date)) : '') ?>"
                            required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_date">Tanggal & Waktu Selesai (Opsional)</label>
                        <input type="datetime-local" id="end_date" name="end_date" class="form-control"
                            value="<?= old('end_date', isset($training->end_date) && $training->end_date ? date('Y-m-d\TH:i', strtotime($training->end_date)) : '') ?>">
                        <small class="form-text text-muted">Kosongkan jika tidak ada tanggal selesai pasti.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="duration">Durasi Pelatihan (Jam)</label>
                    <input type="number" name="duration" class="form-control"
                        placeholder="Contoh: 10 (untuk 10 jam)"
                        value="<?= old('duration', $training->duration ?? '') ?>" min="0" required>
                    <small class="form-text text-muted">Masukkan durasi dalam jam.</small>
                </div>

                <div class="form-group">
                    <label for="quota">Kuota Peserta (Opsional)</label>
                    <input type="number" name="quota" class="form-control" placeholder="Contoh: 20"
                        value="<?= old('quota', $training->quota ?? '') ?>" min="0">
                    <small class="form-text text-muted">Kuota tidak boleh negatif. Kosongkan jika tidak ada batasan kuota.</small>
                </div>

                <hr>

                <h5 class="form-section-title">Informasi Kontak</h5>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="contact_email">Email Kontak</label>
                        <input type="email" name="contact_email" class="form-control"
                            placeholder="email.kontak@perusahaan.com"
                            value="<?= old('contact_email', $training->contact_email ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="contact_phone">Nomor Telepon Kontak (Opsional)</label>
                        <input type="text" name="contact_phone" class="form-control" placeholder="08xxxxxxxxxx"
                            value="<?= old('contact_phone', $training->contact_phone ?? '') ?>">
                    </div>
                </div>

                <div class="button-group">
                    <a href="/vendor/dashboard" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-publish">Publikasikan Sekarang</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function getMinDateTime() {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                return now.toISOString().slice(0, 16);
            }
            const minDateTime = getMinDateTime();
            startDateInput.min = minDateTime;
            endDateInput.min = minDateTime;

            startDateInput.addEventListener('change', function() {
                if (startDateInput.value) {
                    endDateInput.min = startDateInput.value;
                } else {
                    endDateInput.min = getMinDateTime();
                }
            });
        });
    </script>

</body>

</html>