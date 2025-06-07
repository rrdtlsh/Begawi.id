<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>">
            <img src="<?= base_url('images/Logo_Begawi.png') ?>" alt="Logo Begawi" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/') ?>">Beranda</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="workDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Lowongan Kerja
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="workDropdown">
                        <li><a class="dropdown-item" href="/lowongan">Pekerjaan</a></li>
                        <li><a class="dropdown-item" href="/pelatihan">Workshop & Training</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Perusahaan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/about') ?>">Tentang Kami</a>
                </li>
            </ul>
            <div class="d-flex">
                <?php if (session()->get('isLoggedIn')): ?>
                    <!-- Ini bisa diganti dengan ikon user jika sudah login -->
                    <a href="<?= site_url((session()->get('role') === 'vendor') ? 'vendor/dashboard' : 'jobseeker/dashboard') ?>"
                        class="btn btn-outline-success me-2">Dashboard</a>
                    <a href="<?= site_url('logout') ?>" class="btn btn-success">Logout</a>
                <?php else: ?>
                    <a href="<?= site_url('register') ?>" class="btn btn-outline-success me-2">Daftar</a>
                    <a href="<?= site_url('login') ?>" class="btn btn-success">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>