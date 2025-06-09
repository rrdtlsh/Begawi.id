<?= $this->extend('layouts/login_layout') ?>

<?= $this->section('content') ?>

<div class="form-container">

    <div class="text-center mb-4">
        <a href="<?= site_url('/') ?>" class="text-decoration-none">
            <div class="logo-begawi">Begaw<span>i</span></div>
        </a>
        <h2 class="mt-2">Masuk ke Begawi</h2>
    </div>

    <!-- Menampilkan pesan sukses atau error dari session -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?= form_open('login/process') ?>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com"
            value="<?= old('email') ?>" required />
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi"
            required />
    </div>

    <button type="submit" class="btn btn-primary w-100">MASUK</button>
    <?= form_close() ?>

    <p class="signup-link">
        Belum punya akun Begawi? <a href="<?= site_url('register') ?>">Daftar</a>
    </p>
</div>

<?= $this->endSection() ?>