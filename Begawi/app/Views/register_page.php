<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi - Begawi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Registrasi Akun Baru</h3>
                    </div>
                    <div class="card-body">
                        <?php if (session()->get('errors')): ?>
                            <div class="alert alert-danger">
                                <?php foreach (session()->get('errors') as $error): ?>
                                    <p><?= esc($error) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?= form_open('register/process') ?>
                        <div class="form-group">
                            <label for="fullname">Nama Lengkap</label>
                            <input type="text" name="fullname" class="form-control" value="<?= old('fullname') ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Daftar Sebagai:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="jobseeker"
                                    value="jobseeker" <?= old('role', 'jobseeker') == 'jobseeker' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="jobseeker">Pencari Kerja</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="vendor" value="vendor"
                                    <?= old('role') == 'vendor' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="vendor">Perusahaan</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                        <?= form_close() ?>
                    </div>
                    <div class="card-footer text-center">
                        <p>Sudah punya akun? <a href="<?= site_url('login') ?>">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>