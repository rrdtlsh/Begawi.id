<?= $this->extend('layouts/regist_vendor_layout') ?>

<?= $this->section('content') ?>

<div class="registration-container">
    <div class="logo-begawi">Begaw<span>i</span></div>

    <h2><?= esc($title ?? 'Registrasi Penyedia Jasa') ?></h2>

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
        <input type="hidden" name="role" value="vendor">

        <div class="input-group">
            <label for="company_name">Nama Penyedia Jasa / Usaha</label>
            <input type="text" id="company_name" name="company_name" placeholder="Contoh: PT. Jaya Abadi" value="<?= old('company_name') ?>" required>
        </div>

        <div class="input-group">
            <label for="industry">Bidang Kategori Jasa</label>
            <input type="text" id="industry" name="industry" placeholder="Contoh: Teknologi Informasi" value="<?= old('industry') ?>" required>
        </div>

        <div class="input-group">
            <label for="vendor_location_id">Domisili Usaha</label>
            <select id="vendor_location_id" name="vendor_location_id" required>
                <option value="" disabled selected>Pilih Domisili Usaha</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= $loc->id ?>" <?= old('vendor_location_id') == $loc->id ? 'selected' : '' ?>>
                        <?= esc($loc->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="input-group">
            <label for="company_address">Alamat Lengkap Usaha</label>
            <textarea id="company_address" name="company_address" placeholder="Contoh: Jl. A. Yani Km. 5, Gedung ABC Lt. 2" required><?= old('company_address') ?></textarea>
        </div>

        <div class="input-group">
            <label for="contact">Nomor Telepon / WhatsApp</label>
            <input type="text" id="contact" name="contact" placeholder="Contoh: 081234567890" value="<?= old('contact') ?>" required>
        </div>

        <div class="input-group">
            <label for="email">Email Usaha</label>
            <input type="email" id="email" name="email" placeholder="email@example.com" value="<?= old('email') ?>" required>
        </div>

        <div class="input-group">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
        </div>

        <div class="input-group">
            <label for="password_confirm">Konfirmasi Kata Sandi</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Ulangi kata sandi Anda" required>
        </div>

        <button type="submit" class="btn btn-primary">DAFTAR SEBAGAI PENYEDIA JASA</button>
    <?= form_close() ?>

    <p class="login-link">
        Sudah punya akun penyedia jasa? <a href="<?= site_url('login') ?>">Masuk</a>
    </p>
</div>

<?= $this->endSection() ?>