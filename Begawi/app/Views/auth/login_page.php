<?= $this->extend('layouts/login_layout') ?>

<?= $this->section('content') ?>

<div class="form-container">

    <div class="text-center mb-4">
        <a href="<?= site_url('/') ?>" class="text-decoration-none">
            <div class="logo-begawi">Begaw<span>i</span></div>
        </a>
        <h2 class="mt-2">Masuk ke Begawi</h2>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" id="errorMessage"><?= session()->getFlashdata('error') ?></div>
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

    <button type="submit" id="loginButton" class="btn btn-primary w-100">MASUK</button>
    <?= form_close() ?>

    <p class="signup-link">
        Belum punya akun Begawi? <a href="<?= site_url('register') ?>">Daftar</a>
    </p>
</div>


<?php if (session()->getFlashdata('lockout_active')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginButton = document.getElementById('loginButton');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const errorMessageDiv = document.getElementById('errorMessage');

        let timeLeft = 30;

        // Nonaktifkan form segera saat halaman dimuat
        loginButton.disabled = true;
        emailInput.disabled = true;
        passwordInput.disabled = true;

        const countdownTimer = setInterval(function() {
            if (timeLeft <= 0) {
                // Jika waktu habis
                clearInterval(countdownTimer);
                loginButton.disabled = false;
                emailInput.disabled = false;
                passwordInput.disabled = false;
                loginButton.innerText = 'MASUK';
                if (errorMessageDiv) {
                    errorMessageDiv.style.display = 'none'; 
                }
            } else {
                // Selama waktu berjalan
                const message = `Anda telah 4 kali gagal melakukan login. Coba lagi dalam ${timeLeft} detik.`;
                loginButton.innerText = `Tunggu (${timeLeft}s)`;
                if (errorMessageDiv) {
                    errorMessageDiv.innerText = message;
                }
            }
            timeLeft -= 1;
        }, 1000); // Update setiap 1 detik
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>