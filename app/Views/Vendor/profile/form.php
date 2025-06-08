<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Edit Profil Usaha') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

                        <form action="/vendor/profile/update" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="form-group">
                                <label for="company_logo">Logo Perusahaan</label><br>
                                <?php if (!empty($vendor->company_logo_path)): ?>
                                <img src="/uploads/logos/<?= esc($vendor->company_logo_path) ?>" alt="Logo saat ini"
                                    class="img-thumbnail mb-2" width="150">
                                <?php endif; ?>
                                <input type="file" name="company_logo" class="form-control-file">
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah logo. Tipe: jpg,
                                    png. Maks: 1MB.</small>
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="company_name">Nama Usaha</label>
                                <input type="text" name="company_name" class="form-control"
                                    value="<?= old('company_name', $vendor->company_name ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="industry">Bidang Jasa</label>
                                <input type="text" name="industry" class="form-control"
                                    value="<?= old('industry', $vendor->industry ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label for="location_id">Domisili Usaha</label>
                                <select name="location_id" class="form-control" required>
                                    <option value="">Pilih Domisili</option>
                                    <?php if (!empty($locations)): foreach ($locations as $loc): ?>
                                    <option value="<?= $loc->id ?>"
                                        <?= (old('location_id', $vendor->location_id ?? '') == $loc->id) ? 'selected' : '' ?>>
                                        <?= esc($loc->name) ?>
                                    </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="company_address">Alamat Lengkap Usaha</label>
                                <textarea name="company_address" class="form-control"
                                    rows="3"><?= old('company_address', $vendor->company_address ?? '') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="contact">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="contact" class="form-control"
                                    value="<?= old('contact', $vendor->contact ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="website">Website (Opsional)</label>
                                <input type="text" name="website" class="form-control" placeholder="https://..."
                                    value="<?= old('website', $vendor->website ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label for="company_profile">Deskripsi Singkat Perusahaan</label>
                                <textarea name="company_profile" class="form-control"
                                    rows="5"><?= old('company_profile', $vendor->company_profile ?? '') ?></textarea>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="/vendor/dashboard" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>