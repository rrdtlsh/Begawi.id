<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title', 'Admin Panel Begawi') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Orbitron:wght@700&display=swap"
        rel="stylesheet">

    <?= $this->renderSection('styles') ?>

    <style>
        /* Gaya Default */
        :root {
            --bs-body-bg: #f8f9fa;
            --main-content-bg: linear-gradient(135deg, #f0f4f3, #fdfcf7);
            --navbar-bg: #0c392c;
            --navbar-link-color: rgba(255, 255, 255, 0.9);
            --sidebar-bg: #ffffff;
            --sidebar-border-color: #dee2e6;
            --sidebar-link-color: #495057;
            --sidebar-link-hover-bg: #f0f4f3;
            --sidebar-link-hover-color: #215546;
            --sidebar-link-active-bg: rgba(33, 85, 70, 0.1);
            --sidebar-link-active-color: #215546;
            --card-bg: #fff;
            --card-border-color: #e3e6f0;
            --text-color-default: #5a5c69;
            --text-color-headings: #3a3b45;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: .9rem;
            background-color: var(--bs-body-bg);
            transition: background-color 0.4s ease;
        }

        .main-content {
            background: var(--main-content-bg);
            min-height: 100vh;
            padding-top: 24px;
        }

        .navbar-custom {
            background-color: var(--navbar-bg);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: var(--navbar-link-color);
            font-weight: 500;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 56px 0 0;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border-color);
            transition: background-color 0.4s ease, border-right-color 0.4s ease;
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 56px);
            padding-top: 1rem;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar .nav-link,
        .sidebar .dropdown-toggle {
            font-weight: 500;
            color: var(--sidebar-link-color);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .sidebar .nav-link:hover,
        .sidebar .dropdown-toggle:hover {
            background-color: var(--sidebar-link-hover-bg);
            color: var(--sidebar-link-hover-color);
        }

        .sidebar .nav-link.active {
            background-color: var(--sidebar-link-active-bg);
            color: var(--sidebar-link-active-color);
            font-weight: 600;
        }

        /* Styling Dropdown */
        .sidebar .dropdown-toggle::after {
            display: none;
        }

        .sidebar .dropdown-menu {
            border-radius: 0;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            background-color: var(--sidebar-bg);
            width: 100%;
            padding: 0;
            margin-top: 0 !important;
        }

        .sidebar .dropdown-item {
            color: var(--sidebar-link-color);
            padding: 10px 20px;
        }

        .sidebar .dropdown-item:hover {
            background-color: var(--sidebar-link-hover-bg);
            color: var(--sidebar-link-hover-color);
        }

        .sidebar .dropdown-item.active {
            background-color: var(--sidebar-link-active-bg);
            color: var(--sidebar-link-active-color);
            font-weight: 600;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border-color);
            color: var(--text-color-default);
            transition: background-color 0.4s ease, border-color 0.4s ease;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            color: var(--text-color-headings);
            transition: color 0.4s ease;
        }
    </style>

    <style id="dynamic-theme-styles"></style>

</head>

<body class="<?= (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'cyberpunk') ? 'cyberpunk-theme' : '' ?>">
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
                            <a class="nav-link" aria-current="page" href="/admin/dashboard">
                                <i class="fas fa-tachometer-alt fa-fw me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-paint-brush fa-fw me-2"></i>
                                    Pilih Tema
                                </a>
                                <ul class="dropdown-menu" id="theme-menu">
                                    <li><a class="dropdown-item" href="#" data-theme="default">Default</a></li>
                                    <li><a class="dropdown-item" href="#" data-theme="cyberpunk">Cyberpunk</a></li>
                                </ul>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/master-data') ?>">
                                <i class="bi bi-stack me-2"></i> Data Master
                            </a>
                        </li>
                </div>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // --- Penanda Link Aktif ---
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link:not(.dropdown-toggle)');
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // --- Logika Pengalih Tema ---
            const themeMenu = document.getElementById('theme-menu');
            const body = document.body;
            const themeStyleTag = document.getElementById('dynamic-theme-styles');

            const cyberpunkStyles = `
        @keyframes glitch {
            0% { text-shadow: -2px -2px 0 #ff00c1, 2px 2px 0 #00fff9; transform: translate(0, 0); }
            20% { text-shadow: 2px 2px 0 #ff00c1, -2px -2px 0 #00fff9; transform: translate(0, 0); }
            40% { text-shadow: 2px -2px 0 #ff00c1, -2px 2px 0 #00fff9; transform: translate(-1px, 1px); }
            60% { text-shadow: -2px 2px 0 #ff00c1, 2px -2px 0 #00fff9; transform: translate(1px, -1px); }
            80% { text-shadow: -2px -2px 0 #ff00c1, 2px 2px 0 #00fff9; transform: translate(0, 0); }
            100% { text-shadow: 2px 2px 0 #ff00c1, -2px -2px 0 #00fff9; transform: translate(0, 0); }
        }
        .cyberpunk-theme {
            --bs-body-bg: #0a0a1e;
            --main-content-bg: #0a0a1e;
            --navbar-bg: #1a1a3a;
            --navbar-link-color: #c0c0ff;
            --sidebar-bg: #11112b;
            --sidebar-border-color: #4a00e0;
            --sidebar-link-color: #c0c0ff;
            --sidebar-link-hover-bg: #f9f002;
            --sidebar-link-hover-color: #000;
            --sidebar-link-active-bg: #f9f002;
            --sidebar-link-active-color: #000;
            --card-bg: #1a1a3a;
            --card-border-color: #8e2de2;
            --text-color-default: #c0c0ff;
            --text-color-headings: #f9f002;
        }
        .cyberpunk-theme { font-family: 'Orbitron', sans-serif; }
        .cyberpunk-theme .navbar-brand { animation: glitch 2s linear infinite; color: #f9f002 !important; }
        .cyberpunk-theme .glitched-title { animation: glitch 2.5s linear infinite reverse; } /* Efek untuk judul dashboard */
        .cyberpunk-theme .sidebar { border-right: 2px solid var(--sidebar-border-color); }
        .cyberpunk-theme .sidebar .nav-link, .cyberpunk-theme .sidebar .dropdown-toggle { border-left: 4px solid transparent; }
        .cyberpunk-theme .sidebar .dropdown-item { color: var(--sidebar-link-color); }
        .cyberpunk-theme .sidebar .nav-link:hover, .cyberpunk-theme .sidebar .dropdown-toggle:hover, .cyberpunk-theme .sidebar .dropdown-item:hover {
            background-color: var(--sidebar-link-hover-bg); color: var(--sidebar-link-hover-color); text-shadow: 0 0 5px #000;
        }
        .cyberpunk-theme .sidebar .nav-link.active, .cyberpunk-theme .sidebar .dropdown-item.active {
            background-color: var(--sidebar-link-active-bg); color: var(--sidebar-link-active-color); box-shadow: inset 0 0 10px #000; text-shadow: 0 0 5px #000;
        }
        .cyberpunk-theme .card {
            border: 1px solid var(--card-border-color);
            box-shadow: 0 0 15px rgba(142, 45, 226, 0.5);
            clip-path: polygon(0 15px, 15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%);
        }
        .cyberpunk-theme .card .card-icon i { color: var(--card-border-color); opacity: 0.7; } /* Gaya untuk ikon kartu */
        .cyberpunk-theme .h1, .cyberpunk-theme .h2, .cyberpunk-theme .h6 { text-shadow: 0 0 8px #f9f002; }
        .cyberpunk-theme .border-bottom { border-bottom: 1px solid #f9f002 !important; }
        .cyberpunk-theme .text-primary { color: #00fff9 !important; }
        .cyberpunk-theme .text-success { color: #39ff14 !important; }
        .cyberpunk-theme .text-info { color: #ff00c1 !important; }
        .cyberpunk-theme .text-warning { color: #f9f002 !important; }
        .cyberpunk-theme .text-gray-800 { color: #c0c0ff !important; }
        
        .cyberpunk-theme .table { color: var(--text-color-default); border-color: var(--sidebar-border-color); }
        .cyberpunk-theme .table th { color: var(--text-color-headings); }
        .cyberpunk-theme .table > :not(caption) > * > * { background-color: transparent; }
        .cyberpunk-theme .table-hover > tbody > tr:hover > * {
            color: var(--sidebar-link-hover-color);
            background-color: var(--sidebar-link-hover-bg);
            text-shadow: 0 0 4px #000;
        }
        .cyberpunk-theme .badge.bg-success { background-color: #39ff14 !important; color: #000; }
        .cyberpunk-theme .badge.bg-warning { background-color: #f9f002 !important; color: #000; }
        .cyberpunk-theme #masterDataTabContent .table tbody td:nth-child(1),
            .cyberpunk-theme #masterDataTabContent .table tbody td:nth-child(2) {
                color: #00fff9; /* Warna biru neon / cyan */
                font-weight: bold;
                text-shadow: 0 0 6px rgba(0, 255, 249, 0.7);
            }
        
            .cyberpunk-theme #masterDataTab .nav-link {
                color: #c000ff; /* Warna ungu neon untuk tab tidak aktif */
                text-shadow: 0 0 5px rgba(192, 0, 255, 0.7);
                background-color: transparent;
                border-color: var(--sidebar-border-color);
            }

            .cyberpunk-theme #masterDataTab .nav-link.active {
                color: #f9f002; 
                background-color: var(--card-bg);
                border-color: var(--sidebar-border-color);
                border-bottom-color: var(--card-bg); /* Membuat border menyatu */
                text-shadow: 0 0 5px rgba(249, 240, 2, 0.7);
            }
        
            
            .cyberpunk-theme #masterDataTabContent .table td i {
                color: #f9f002; 
                text-shadow: 0 0 6px rgba(249, 240, 2, 0.7); 
                font-size: 1.2em; 
            }

            .cyberpunk-theme #masterDataTabContent .table .actions .btn {
                background: transparent;
                border-width: 1px;
                border-style: solid;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .cyberpunk-theme #masterDataTabContent .table .actions .btn.btn-warning {
                color: #f9f002;
                border-color: #f9f002;
                text-shadow: 0 0 5px rgba(249, 240, 2, 0.7);
            }
            .cyberpunk-theme #masterDataTabContent .table .actions .btn.btn-warning:hover {
                color: #000;
                background-color: #f9f002;
                box-shadow: 0 0 10px #f9f002;
            }

            .cyberpunk-theme #masterDataTabContent .table .actions .btn.btn-danger {
                color: #ff00c1;
                border-color: #ff00c1;
                text-shadow: 0 0 5px rgba(255, 0, 193, 0.7);
            }
            .cyberpunk-theme #masterDataTabContent .table .actions .btn.btn-danger:hover {
                color: #000;
                background-color: #ff00c1;
                box-shadow: 0 0 10px #ff00c1;
            }
            
        `;
            function setTheme(theme) {
                // Hapus status aktif dari semua item menu
                themeMenu.querySelectorAll('.dropdown-item').forEach(item => item.classList.remove('active'));

                if (theme === 'cyberpunk') {
                    body.classList.add('cyberpunk-theme');
                    themeStyleTag.innerHTML = cyberpunkStyles;
                    document.cookie = "theme=cyberpunk;path=/;max-age=31536000";
                    themeMenu.querySelector('[data-theme="cyberpunk"]').classList.add('active');
                } else { // Tema Default
                    body.classList.remove('cyberpunk-theme');
                    themeStyleTag.innerHTML = "";
                    document.cookie = "theme=default;path=/;max-age=31536000";
                    themeMenu.querySelector('[data-theme="default"]').classList.add('active');
                }

                // Perbarui chart jika ada di halaman ini
                if (typeof window.updateCharts === 'function') {
                    window.updateCharts();
                }
            }

            themeMenu.addEventListener('click', (e) => {
                e.preventDefault();
                const target = e.target;
                if (target.matches('.dropdown-item')) {
                    const selectedTheme = target.dataset.theme;
                    setTheme(selectedTheme);
                }
            });

            // Terapkan tema dari cookie saat memuat
            const savedTheme = body.classList.contains('cyberpunk-theme') ? 'cyberpunk' : 'default';
            setTheme(savedTheme);

        });
    </script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>