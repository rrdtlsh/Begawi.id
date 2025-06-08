<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Registrasi Penyedia Jasa') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header text-center">
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

                        <?= form_open('register/process') ?>
                        <input type="hidden" name="role" value="vendor">

                        <div class="form-group">
                            <label for="company_name">Nama Penyedia Jasa / Usaha</label>
                            <input type="text" name="company_name" class="form-control"
                                placeholder="Contoh: PT. Jaya Abadi" value="<?= old('company_name') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="industry">Bidang Kategori Jasa</label>
                            <input type="text" name="industry" class="form-control"
                                placeholder="Contoh: Teknologi Informasi" value="<?= old('industry') ?>">
                        </div>
                        <div class="form-group">
                            <label for="vendor_location_id">Domisili Usaha</label>
                            <select name="vendor_location_id" class="form-control" required>
                                <option value="">Pilih Domisili Usaha</option>
                                <?php if (!empty($locations)):
                                    foreach ($locations as $loc): ?>
                                <option value="<?= $loc->id ?>"
                                    <?= old('vendor_location_id') == $loc->id ? 'selected' : '' ?>>
                                    <?= esc($loc->name) ?>
                                </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="company_address">Alamat Lengkap Usaha</label>
                            <textarea name="company_address" class="form-control" rows="3"
                                placeholder="Contoh: Jl. A. Yani Km. 5, Gedung ABC Lt. 2"
                                required><?= old('company_address') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="contact">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="contact" class="form-control" placeholder="Contoh: 081234567890"
                                value="<?= old('contact') ?>" required>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="email">Email Usaha</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com"
                                value="<?= old('email') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirm" class="form-control"
                                placeholder="Ulangi kata sandi Anda" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">DAFTAR SEBAGAI PENYEDIA JASA</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>