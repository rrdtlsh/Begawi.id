<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-0">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/images/logo_begawi.png" alt="Begawi Logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Beranda</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="jobsDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Lowongan Kerja
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="jobsDropdown">
                        <li><a class="dropdown-item" href="/jobs">Pekerjaan</a></li>
                        <li><a class="dropdown-item" href="/trainings">Workshop & Training</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/companies">Perusahaan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">Tentang Kami</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">


                <?php if (session()->get('isLoggedIn')): ?>
                    <?php
                    $role = session()->get('role');
                    ?>
                    <?php switch ($role):
                        case 'admin': ?>
                            <a href="/admin/dashboard" class="nav-icon-link">
                                <i class="bi bi-speedometer2 nav-icon-item"></i>
                                <span class="nav-icon-text">Admin Panel</span>
                            </a>
                            <?php break; ?>

                        <?php case 'vendor': ?>
                            <a href="/vendor/dashboard" class="nav-icon-link">
                                <i class="bi bi-building nav-icon-item"></i>
                                <span class="nav-icon-text">Dashboard Vendor</span>
                            </a>
                            <?php break; ?>

                        <?php default: ?>
                            <a href="/jobseeker/chatbot" class="nav-icon-link">
                                <i class="bi bi-robot nav-icon-item"></i>
                                <span class="nav-icon-text">ChatBot AI</span>
                            </a>
                            <a href="/jobseeker/dashboard" class="nav-icon-link">
                                <i class="bi bi-person-circle nav-icon-item"></i>
                                <span class="nav-icon-text">Profile</span>
                            </a>
                            <?php break; ?>
                    <?php endswitch; ?>
                    <a href="/logout" class="btn btn-logout-nav">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Log Out</span>
                    </a>
                <?php else: ?>
                    <a href="/login" class="btn btn-login">Masuk</a>
                    <a href="/register" class="btn btn-register">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>