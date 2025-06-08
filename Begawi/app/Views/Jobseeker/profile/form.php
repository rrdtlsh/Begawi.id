<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Edit Profil') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Perbaikan tampilan Select2 agar menyatu dengan Bootstrap */
        .select2-container .select2-selection--multiple {
            min-height: 38px;
            border: 1px solid #ced4da !important;
            padding: .2rem .4rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0c392c;
            /* Warna latar belakang tag */
            color: white;
            /* Warna teks tag */
            border: 1px solid #0c392c;
            /* Border tag */
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            /* Warna tombol close tag */
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3><?= esc($title) ?></h3>
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

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="/jobseeker/profile/update" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="form-group">
                                <label for="profile_picture">Foto Profil</label><br>
                                <?php if (!empty($profile->profile_picture_path)): ?>
                                    <img src="<?= base_url('uploads/avatars/' . esc($profile->profile_picture_path)) ?>"
                                        alt="Foto saat ini" class="img-thumbnail mb-2" width="150">
                                <?php else: ?>
                                    <p class="text-muted small">Belum ada foto profil.</p>
                                <?php endif; ?>
                                <input type="file" name="profile_picture" class="form-control-file">
                                <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maks: 1MB.</small>
                            </div>

                            <div class="form-group">
                                <label for="fullname">Nama Lengkap</label>
                                <input type="text" name="fullname" class="form-control"
                                    value="<?= old('fullname', $profile->user_fullname ?? session()->get('fullname') ?? '') ?>"
                                    required>
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control"
                            value="<?= old('phone', $profile->phone ?? '') ?>">
                        <small class="form-text text-muted">Nomor telepon Anda.</small>
                    </div>
                    <div class="form-group">
                        <label for="location_id">Domisili</label>
                        <select name="location_id" class="form-control" required>
                            <option value="">Pilih Domisili</option>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc->id ?>" <?= (old('location_id', $profile->location_id ?? '') == $loc->id) ? 'selected' : '' ?>>
                                    <?= esc($loc->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="skills-select">Keahlian (Pilih keahlian Anda)</label>
                        <select name="skills[]" id="skills-select" class="form-control" multiple="multiple" required>
                            <!-- Menjadikan required jika ingin user selalu memilih skill -->
                            <?php foreach ($skills as $skill): ?>
                                <option value="<?= $skill->id ?>" <?= in_array($skill->id, old('skills', $userSkillIds ?? [])) ? 'selected' : '' ?>>
                                    <?= esc($skill->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Pilih satu atau lebih keahlian.</small>
                    </div>
                    <div class="form-group">
                        <label for="summary">Ringkasan Profil (Tentang Saya)</label>
                        <textarea name="summary" class="form-control"
                            rows="5"><?= old('summary', $profile->summary ?? '') ?></textarea>
                        <small class="form-text text-muted">Jelaskan diri Anda secara singkat.</small>
                    </div>
                    <div class="form-group">
                        <label for="resume">Upload CV Baru (Opsional)</label>
                        <?php if (!empty($profile->resume_path)): ?>
                            <p class="text-muted">CV saat ini: <a
                                    href="<?= base_url('uploads/resumes/' . esc($profile->resume_path)) ?>"
                                    target="_blank">Lihat
                                    CV</a></p>
                        <?php endif; ?>
                        <input type="file" name="resume" class="form-control-file">
                        <small class="form-text text-muted">Tipe: PDF, DOC, DOCX. Maks: 2MB.</small>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="/jobseeker/dashboard" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#skills-select').select2({
                placeholder: "Pilih atau ketik keahlian Anda",
                allowClear: true // Memungkinkan pengguna untuk menghapus semua pilihan
            });
        });
    </script>
</body>

</html>