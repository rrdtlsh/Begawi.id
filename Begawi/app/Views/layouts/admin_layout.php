<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title', 'Admin Panel Begawi') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <?= $this->renderSection('styles') ?>

    <style>
        /* =================================
           Gaya Kustom untuk Admin Layout
           ================================= */
        body {
            font-family: 'Poppins', sans-serif;
            font-size: .9rem;
            background-color: #f8f9fa; /* Warna dasar body */
        }

        /* --- Header --- */
        .navbar-custom {
            background-color: #0c392c; /* Hijau Tua dari referensi */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        
        .navbar-custom .nav-link:hover {
            color: #ffffff;
        }

        .navbar-custom .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* --- Sidebar --- */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 56px 0 0; /* Tinggi navbar */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            background-color: #ffffff; /* Latar putih bersih */
            border-right: 1px solid #dee2e6;
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 56px);
            padding-top: 1rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        /* --- Menu Navigasi di Sidebar --- */
        .sidebar .nav-link {
            font-weight: 500;
            color: #495057; /* Warna teks menu */
            padding: 12px 20px;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent; /* Indikator aktif tersembunyi */
            transition: all 0.3s ease;
        }

        .sidebar .nav-link .fa-fw {
            width: 20px;
            margin-right: 1rem;
            color: #6c757d;
        }

        .sidebar .nav-link:hover {
            background-color: #f0f4f3; /* Latar hover hijau sangat lembut */
            color: #215546;
            border-left-color: #b3a522; /* Aksen emas saat hover */
        }

        .sidebar .nav-link.active {
            background-color: rgba(33, 85, 70, 0.1); /* Latar aktif hijau transparan */
            border-left-color: #215546; /* Indikator aktif hijau tua */
            color: #215546; /* Teks aktif lebih gelap */
            font-weight: 600;
        }

        .sidebar .nav-link.active .fa-fw {
            color: #215546;
        }

        /* --- Area Konten Utama --- */
        .main-content {
            padding-top: 24px;
            /* Gradient background yang sama dengan dashboard untuk konsistensi */
            background: linear-gradient(135deg, #f0f4f3, #fdfcf7);
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <header class="navbar navbar-dark sticky-top navbar-custom flex-md-nowrap p-0 shadow-sm">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="/admin/dashboard">Admin Panel Begawi</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="/logout">
                    <i class="fas fa-sign-out-alt fa-fw me-1"></i> Log Out
                </a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/admin/dashboard">
                                <i class="fas fa-tachometer-alt fa-fw me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-briefcase fa-fw me-2"></i>
                                Kelola Lowongan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-users fa-fw me-2"></i>
                                Kelola Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-tags fa-fw me-2"></i>
                                Kelola Kategori
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <?= $this->renderSection('scripts') ?>

    <script>
        // Script sederhana untuk mengatur class 'active' pada menu sidebar
        // berdasarkan URL saat ini.
        document.addEventListener("DOMContentLoaded", function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');

            navLinks.forEach(link => {
                // Hapus 'active' dari semua link terlebih dahulu
                link.classList.remove('active');
                
                // Tambahkan 'active' jika href link cocok dengan path URL
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>