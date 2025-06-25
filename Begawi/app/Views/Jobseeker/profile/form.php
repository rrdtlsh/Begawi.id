<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Edit Profil Saya') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="/css/editprofil.css">

</head>
<body>

    <div class="edit-profile-container">
        <div class="form-container">
            <h2 class="form-title"><?= esc($title ?? 'Edit Profil Saya') ?></h2>

            <form action="/jobseeker/profile/update" method="post" enctype="multipart/form-data">
                
                <?= csrf_field() ?>

                <?php if (session()->get('errors')): ?>
                    <div class="alert alert-danger">
                        <strong>Terjadi Kesalahan:</strong>
                        <ul class="mb-0">
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
                <div class="form-group">
                    <label for="profile_picture">Foto Profil</label>
                    <?php if (!empty($profile->profile_picture_path)): ?>
                        <img src="<?= base_url('uploads/avatars/' . esc($profile->profile_picture_path)) ?>"
                            alt="Foto saat ini" class="img-thumbnail mb-2" width="120">
                    <?php endif; ?>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control-file">
                    <small class="form-text">Format: JPG, PNG. Maks: 1MB.</small>
                </div>

                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" class="form-control"
                        value="<?= old('fullname', $profile->user_fullname ?? session()->get('fullname') ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" class="form-control"
                        value="<?= old('phone', $profile->phone ?? '') ?>" placeholder="Contoh: 08123456789">
                </div>

                <div class="form-group">
                    <label for="location_id">Domisili</label>
                    <select id="location_id" name="location_id" class="form-control" required>
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
                    <select name="skills[]" id="skills-select" class="form-control" multiple="multiple">
                        <?php foreach ($skills as $skill): ?>
                            <option value="<?= $skill->id ?>" <?= in_array($skill->id, old('skills', $userSkillIds ?? [])) ? 'selected' : '' ?>>
                                <?= esc($skill->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text">Pilih satu atau lebih keahlian.</small>
                </div>

                <div class="form-group">
                    <label for="summary">Ringkasan Profil (Tentang Saya)</label>
                    <textarea id="summary" name="summary" class="form-control" rows="4" placeholder="Tuliskan ringkasan singkat tentang diri Anda..."><?= old('summary', $profile->summary ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="resume">Upload CV Baru (Opsional)</label>
                    <?php if (!empty($profile->resume_path)): ?>
                        <p class="form-text mb-1">CV saat ini: 
                            <a href="<?= base_url('uploads/resumes/' . esc($profile->resume_path)) ?>" target="_blank">Lihat CV</a>
                        </p>
                    <?php endif; ?>
                    <input type="file" name="resume" id="resume" class="form-control-file">
                    <small class="form-text">Tipe: PDF, DOC, DOCX. Maks: 2MB.</small>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                    <a href="/jobseeker/dashboard" class="btn btn-cancel">Batal</a>
                </div>
            </form>

            <hr class="my-4">

            <div class="danger-zone" style="border: 1px solid #f5c6cb; border-radius: 8px; padding: 20px; background-color: #f8d7da;">
                <h5 class="font-weight-bold" style="color: #721c24;"><i class="bi bi-exclamation-triangle-fill"></i> Hapus Akun !</h5>
                <p class="small" style="color: #721c24;">Tindakan ini tidak dapat diurungkan. Seluruh data Anda, termasuk riwayat lamaran dan pendaftaran pelatihan, akan dihapus secara permanen.</p>
        
                <form action="<?= site_url('jobseeker/profile/delete') ?>" method="post" onsubmit="return confirm('PERINGATAN: Apakah Anda benar-benar yakin ingin menghapus akun ini secara permanen?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger w-100 font-weight-bold"><i class="bi bi-trash-fill"></i> Hapus Akun Saya</button>
            </form>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#skills-select').select2({
                placeholder: "Pilih atau ketik keahlian Anda",
                allowClear: true
            });
        });
    </script>
</body>
</html>