<?= $this->extend('layouts/regist_choice_layout') ?>

<?= $this->section('content') ?>

<div class="regist-choice-container">
    <div class="text-center">
        <h1 class="title">Selamat datang di Begawi!</h1>
        <p class="subtitle">Pertama-tama, apa tujuan Anda di sini?</p>
    </div>

    <div class="row justify-content-center align-items-stretch gx-5">
        <!-- Kotak Pilihan untuk Pencari Kerja -->
        <div class="col-md-5 mb-4">
            <div class="choice-card">
                <h2>Saya Pencari Kerja</h2>
                <p class="description">Temukan pekerjaan impian Anda, tingkatkan keahlian melalui pelatihan, dan jelajahi berbagai peluang karir yang sesuai dengan minat dan bakat Anda di Kalimantan Selatan.</p>
                <a href="<?= site_url('register/jobseeker') ?>" class="btn btn-register mt-auto">DAFTAR SEBAGAI PENCARI KERJA</a>
            </div>
        </div>

        <!-- Pemisah "ATAU" -->
        <div class="col-md-auto d-flex align-items-center justify-content-center">
            <div class="divider-text">Atau</div>
        </div>

        <!-- Kotak Pilihan untuk Penyedia Jasa -->
        <div class="col-md-5 mb-4">
            <div class="choice-card">
                <h2>Saya Penyedia Jasa</h2>
                <p class="description">Rekrut talenta terbaik untuk perusahaan Anda, tawarkan program pelatihan yang berkualitas, atau promosikan layanan dan jasa Anda kepada audiens yang lebih luas dan tepat sasaran.</p>
                <a href="<?= site_url('register/vendor') ?>" class="btn btn-register mt-auto">DAFTAR SEBAGAI PENYEDIA JASA</a>
            </div>
        </div>
    </div>

    <p class="login-link">
        Sudah mempunyai akun? <a href="<?= site_url('login') ?>">Masuk</a>
    </p>
    <p class="terms-link">
        Dengan membuat akun Begawi, Anda setuju dengan <a href="#">Syarat dan Ketentuan</a>, <a href="#">Kebijakan Privasi</a>, dan <a href="#">Penggunaan Pengguna Kami</a>.
    </p>
</div>

<?= $this->endSection() ?>

