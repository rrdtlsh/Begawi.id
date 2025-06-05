<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi - Begawi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Style untuk menyembunyikan field secara default */
        .hidden-fields {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header text-center">
                        <img src="/logo-begawi.png" alt="Begawi Logo" style="height: 40px; margin-bottom: 10px;"><br>
                        <h3 id="form-title">Registrasi Pencari Kerja</h3>
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
                        <div class="form-group text-center">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-primary active">
                                    <input type="radio" name="role" value="jobseeker" checked onchange="toggleFields()">
                                    Pencari Kerja
                                </label>
                                <label class="btn btn-outline-primary">
                                    <input type="radio" name="role" value="vendor" onchange="toggleFields()"> Penyedia
                                    Jasa
                                </label>
                            </div>
                        </div>
                        <hr>

                        <div id="jobseeker-fields">
                            <div class="form-group">
                                <label for="fullname">Nama Lengkap</label>
                                <input type="text" name="fullname" class="form-control"
                                    placeholder="Masukkan nama lengkap Anda" value="<?= old('fullname') ?>">
                            </div>
                            <div class="form-group">
                                <label for="js_location_id">Domisili</label>
                                <select name="js_location_id" class="form-control">
                                    <option value="">Pilih Domisili</option>
                                    <?php foreach ($locations as $loc): ?>
                                        <option value="<?= $loc->id ?>" <?= old('js_location_id') == $loc->id ? 'selected' : '' ?>>
                                            <?= esc($loc->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="skills">Kategori Keahlian</label>
                                <select name="skills[]" class="form-control">
                                    <option value="">Pilih Keahlian</option>
                                    <?php foreach ($skills as $skill): ?>
                                        <option valu="<?= $skill->id ?>" <?= old('skills') == $skill->id ? 'selected' : '' ?>>
                                            < ?=esc($skill->name) ?>
                                            <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div id="vendor-fields" class="hidden-fields">
                            <div class="form-group">
                                <label for="company_name">Nama Penyedia Jasa / Usaha</label>
                                <input type="text" name="company_name" class="form-control"
                                    placeholder="Contoh: PT. Jaya Abadi" value="<?= old('company_name') ?>">
                            </div>
                            <div class="form-group">
                                <label for="industry">Bidang Kategori Jasa</label>
                                <input type="text" name="industry" class="form-control"
                                    placeholder="Contoh: Teknologi Informasi" value="<?= old('industry') ?>">
                            </div>
                            <div class="form-group">
                                <label for="vendor_location_id">Domisili Usaha</label>
                                <select name="vendor_location_id" class="form-control">
                                    <option value="">Pilih Domisili Usaha</option>
                                    <?php foreach ($locations as $loc): ?>
                                        <option value="<?= $loc->id ?>" <?= old('vendor_location_id') == $loc->id ? 'selected' : '' ?>>
                                            <?= esc($loc->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="contact">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="contact" class="form-control"
                                    placeholder="Contoh: 081234567890" value="<?= old('contact') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Usaha</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com"
                                value="<?= old('email') ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Minimal 8 karakter">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirm" class="form-control"
                                placeholder="Ulangi kata sandi Anda">
                        </div>

                        <button type="submit" id="submit-button" class="btn btn-primary btn-block">DAFTAR</button>
                        <?= form_close() ?>
                    </div>
                    <div class="card-footer text-center">
                        <p>Sudah mempunyai akun? <a href="/login">Masuk</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const role = document.querySelector('input[name="role"]:checked').value;
            const jobseekerFields = document.getElementById('jobseeker-fields');
            const vendorFields = document.getElementById('vendor-fields');
            const formTitle = document.getElementById('form-title');
            const submitButton = document.getElementById('submit-button');
            const emailLabel = document.querySelector('label[for="email"]');

            if (role === 'jobseeker') {
                jobseekerFields.style.display = 'block';
                vendorFields.style.display = 'none';
                formTitle.innerText = 'Registrasi Pencari Kerja';
                submitButton.innerText = 'DAFTAR';
                emailLabel.innerText = 'Email';
            } else {
                jobseekerFields.style.display = 'none';
                vendorFields.style.display = 'block';
                formTitle.innerText = 'Registrasi Penyedia Jasa';
                submitButton.innerText = 'DAFTAR SEBAGAI PENYEDIA JASA';
                emailLabel.innerText = 'Email Usaha';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>

</body>

</html>