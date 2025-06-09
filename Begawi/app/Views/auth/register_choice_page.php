<?= $this->extend('layouts/regist_choice_layout') ?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center">
                <h1 class="display-5 fw-bold">Selamat datang di Begawi!</h1>
                <p class="lead text-muted mb-5">Pertama-tama, apa tujuan Anda di sini?</p>

                <div class="row">
                    <div class="col-md-5 mb-4">
                        <div class="p-5 border rounded-3 h-100 d-flex flex-column">
                            <h2>Saya Pencari Kerja</h2>
                            <p class="flex-grow-1">Temukan pekerjaan impian Anda, tingkatkan keahlian melalui pelatihan, dan jelajahi berbagai peluang karir yang sesuai dengan minat dan bakat Anda di Kalimantan Selatan.</p>
                            <a href="<?= site_url('register/jobseeker') ?>" class="btn btn-success btn-lg mt-auto">DAFTAR SEBAGAI PENCARI KERJA</a>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                        <div class="divider-container w-100">ATAU</div>
                    </div>

                    <div class="col-md-5 mb-4">
                        <div class="p-5 border rounded-3 h-100 d-flex flex-column">
                            <h2>Saya Penyedia Jasa</h2>
                            <p class="flex-grow-1">Rekrut talenta terbaik untuk perusahaan Anda, tawarkan program pelatihan yang berkualitas, atau promosikan layanan dan jasa Anda kepada audiens yang lebih luas dan tepat sasaran.</p>
                            <a href="<?= site_url('register/vendor') ?>" class="btn btn-success btn-lg mt-auto">DAFTAR SEBAGAI PENYEDIA JASA</a>
                        </div>
                    </div>
                </div>

                <p class="mt-4">
                    Sudah mempunyai akun? <a href="<?= site_url('login') ?>">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
