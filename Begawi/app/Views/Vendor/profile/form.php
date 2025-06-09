<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Edit Profil Usaha') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/edit-profil.css">
</head>

<body>
    <div class="page-wrapper">
        <div class="form-container">
            <div class="text-center mb-4">
                <h2 class="form-title"><?= esc($title) ?></h2>
            </div>

            <?php if (session()->get('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session()->get('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/vendor/profile/update" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="company_logo">Logo Perusahaan</label><br>
                    <?php if (!empty($vendor->company_logo_path)): ?>
                        <img src="/uploads/logos/<?= esc($vendor->company_logo_path) ?>" alt="Logo saat ini"
                            class="img-thumbnail mb-2" width="120">
                    <?php endif; ?>
                    <div class="custom-file">
                        <input type="file" name="company_logo" class="custom-file-input" id="company_logo">
                        <label class="custom-file-label" for="company_logo">Pilih File</label>
                    </div>
                    <small class="form-text text-muted">Tipe: jpg, png. Maks: 1MB.</small>
                </div>

                <div class="form-group">
                    <label for="company_name">Nama Usaha</label>
                    <input type="text" id="company_name" name="company_name" class="form-control"
                        value="<?= esc(old('company_name', $vendor->company_name ?? '')) ?>" required>
                </div>

                <div class="form-group">
                    <label for="industry">Bidang Jasa</label>
                    <input type="text" id="industry" name="industry" class="form-control"
                        value="<?= esc(old('industry', $vendor->industry ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label for="location_id">Domisili Usaha</label>
                    <select id="location_id" name="location_id" class="form-control custom-select" required>
                        <option value="">Pilih Domisili</option>
                        <?php if (!empty($locations)):
                            foreach ($locations as $loc): ?>
                                <option value="<?= $loc->id ?>" <?= (old('location_id', $vendor->location_id ?? '') == $loc->id) ? 'selected' : '' ?>>
                                    <?= esc($loc->name) ?>
                                </option>
                            <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="company_address">Alamat Lengkap Usaha</label>
                    <textarea id="company_address" name="company_address" class="form-control"
                        rows="3"><?= esc(old('company_address', $vendor->company_address ?? '')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="contact">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="contact" name="contact" class="form-control"
                        value="<?= esc(old('contact', $vendor->contact ?? '')) ?>" required>
                </div>

                <div class="form-group">
                    <label for="website">Website (Opsional)</label>
                    <input type="text" id="website" name="website" class="form-control" placeholder="https://..."
                        value="<?= esc(old('website', $vendor->website ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label for="company_profile">Deskripsi Singkat Perusahaan</label>
                    <textarea id="company_profile" name="company_profile" class="form-control"
                        rows="5"><?= esc(old('company_profile', $vendor->company_profile ?? '')) ?></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="/vendor/dashboard" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function (e) {
                let fileName = e.target.files[0].name;
                let nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        });
    </script>
</body>

</html>