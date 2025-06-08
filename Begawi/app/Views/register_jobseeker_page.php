<?= $this->extend('layouts/regist_jobseeker_layout') ?>

<?= $this->section('content') ?>

<div class="registration-container">
    <div class="logo-begawi">Begaw<span>i</span></div>
    
    <h2><?= esc($title ?? 'Registrasi Pengguna') ?></h2>

    <?php if (session()->get('errors')): ?>
        <div class="alert alert-danger" style="margin-top: 1rem;">
            <strong>Gagal melakukan registrasi. Mohon periksa kembali isian Anda:</strong>
            <ul>
                <?php foreach (session()->get('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= form_open('register/process') ?>
        <input type="hidden" name="role" value="jobseeker">

        <div class="input-group">
            <label for="fullname">Nama Lengkap</label>
            <input type="text" id="fullname" name="fullname" placeholder="Masukkan nama lengkap Anda" value="<?= old('fullname') ?>" required>
        </div>

        <div class="input-group">
            <label for="js_location_id">Domisili</label>
            <select id="js_location_id" name="js_location_id" required>
                <option value="" disabled selected>Pilih Domisili</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= $loc->id ?>" <?= old('js_location_id') == $loc->id ? 'selected' : '' ?>>
                        <?= esc($loc->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="input-group">
            <label for="skill_id">Keahlian</label>
            <select id="skill_id" name="skill_id" required>
                <option value="" disabled selected>Pilih Keahlian Utama</option>
                <?php foreach ($skills as $skill): ?>
                     <option value="<?= $skill->id ?>" <?= old('skill_id') == $skill->id ? 'selected' : '' ?>>
                        <?= esc($skill->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="email@example.com" value="<?= old('email') ?>" required>
        </div>

        <div class="input-group">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Buat kata sandi (minimal 8 karakter)" required>
        </div>

         <div class="input-group">
            <label for="password_confirm">Konfirmasi Kata Sandi</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Ulangi kata sandi Anda" required>
        </div>

        <button type="submit" class="btn btn-primary">DAFTAR</button>
    <?= form_close() ?>

    <p class="login-link">
        Sudah mempunyai akun Begawi? <a href="<?= site_url('login') ?>">Masuk</a>
    </p>
</div>

<?= $this->endSection() ?>