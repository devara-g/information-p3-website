<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

// Get current page name for active menu
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Dashboard Admin'; ?> - SMP PGRI 3 BOGOR</title>
    <link rel="stylesheet" href="../css/style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="css/admin.css?v=<?= time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Dropdown Menu Styles - Fixed */
        .sidebar-menu .dropdown {
            position: relative;
        }

        .sidebar-menu .dropdown > a {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-menu .dropdown > a::after {
            content: '\f105';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .sidebar-menu .dropdown:hover > a::after,
        .sidebar-menu .dropdown.active > a::after,
        .sidebar-menu .dropdown.show > a::after {
            transform: rotate(90deg);
        }

        /* Fix dropdown positioning - ini yang utama */
        .sidebar-menu .dropdown .dropdown-menu {
            display: none;
            position: fixed;
            left: 260px; /* Lebar sidebar */
            margin-top: -45px; /* Sesuaikan posisi vertikal */
            min-width: 220px;
            background: #1e293b;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            list-style: none;
            padding: 10px 0;
            z-index: 99999; /* Z-index sangat tinggi */
            border: 1px solid rgba(255,255,255,0.1);
        }

        /* Sesuaikan posisi untuk setiap item dropdown */
        .sidebar-menu li:nth-child(5) .dropdown-menu { /* Data Guru & Staff */
            top: 220px; /* Sesuaikan dengan posisi menu */
        }

        .sidebar-menu .dropdown:hover > .dropdown-menu,
        .sidebar-menu .dropdown.active > .dropdown-menu,
        .sidebar-menu .dropdown.show > .dropdown-menu {
            display: block !important;
        }

        .sidebar-menu .dropdown .dropdown-menu li {
            padding: 0;
            margin: 0;
        }

        .sidebar-menu .dropdown .dropdown-menu li a {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .sidebar-menu .dropdown .dropdown-menu li a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: var(--accent, #4f46e5);
        }

        .sidebar-menu .dropdown .dropdown-menu li a i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* Hapus transform dari main content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            /* Hapus transform property */
        }

        /* Fix untuk mobile */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-menu .dropdown .dropdown-menu {
                position: static !important;
                left: 0;
                margin-top: 0;
                margin-left: 20px;
                box-shadow: none;
                background: rgba(0,0,0,0.2);
                width: calc(100% - 20px);
            }
            
            .sidebar-menu .dropdown .dropdown-menu li a {
                padding-left: 40px;
            }
        }

        /* Menu label styling */
        .menu-label {
            padding: 20px 20px 8px;
            font-size: 12px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            letter-spacing: 1px;
        }

        /* Fix untuk modal */
        .logout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 100000;
        }

        .logout-overlay.active {
            display: flex;
        }

        .logout-modal {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            position: relative;
            z-index: 100001;
        }

        /* Sidebar fixed */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 260px;
            z-index: 1000;
        }
    </style>
</head>

<body>

    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="admin-logo-container">
                    <img src="../img/pgri3-photoroom.png" alt="Logo" class="sidebar-logo">
                </div>
                <div class="sidebar-brand">
                    <h2>SMP PGRI 3 BGR</h2>
                    <span>ADMINISTRATOR PANEL</span>
                </div>
            </div>

            <div class="sidebar-menu">
                <div class="menu-label">Main Menu</div>
                <ul>
                    <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : ''; ?>"><i class="bx bxs-dashboard"></i> Dashboard</a></li>
                    <li><a href="berita.php" class="<?= $current_page == 'berita.php' ? 'active' : ''; ?>"><i class="bx bxs-news"></i> Berita & Artikel</a></li>
                    <li><a href="agenda.php" class="<?= $current_page == 'agenda.php' ? 'active' : ''; ?>"><i class="bx bxs-calendar-event"></i> Agenda Sekolah</a></li>
                    <li><a href="galeri.php" class="<?= $current_page == 'galeri.php' ? 'active' : ''; ?>"><i class="bx bxs-camera"></i> Galeri Kegiatan</a></li>
                    <li class="dropdown <?= in_array($current_page, ['guru.php', 'osis.php', 'mpk.php', 'kepsek.php']) ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" class="dropdown-toggle"><i class="bx bxs-user-pin"></i> Data Guru & Staff <i class="fas fa-chevron-right arrow"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="guru.php" class="<?= $current_page == 'guru.php' ? 'active' : ''; ?>"><i class="fas fa-chalkboard-teacher"></i> Data Guru</a></li>
                            <li><a href="osis.php" class="<?= $current_page == 'osis.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Data OSIS</a></li>
                            <li><a href="mpk.php" class="<?= $current_page == 'mpk.php' ? 'active' : ''; ?>"><i class="fas fa-user-tie"></i> Data MPK</a></li>
                            <li><a href="kepsek.php" class="<?= $current_page == 'kepsek.php' ? 'active' : ''; ?>"><i class="fas fa-school"></i> Data Kepsek</a></li>
                        </ul>
                    </li>
                </ul>

                <div class="menu-label">External</div>
                <ul>
                    <li><a href="pesan.php" class="<?= $current_page == 'pesan.php' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Pesan Masuk</a></li>
                    <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Lihat Website</a></li>
                </ul>
            </div>

            <div class="logout-btn">
                <a href="javascript:void(0)" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <!-- Main Content - Hapus class page-transition -->
        <main class="main-content">
            <!-- Mobile Sidebar Overlay -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <!-- Top Bar -->
            <div class="top-bar">
                <button class="toggle-sidebar" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="search-box">
                    <i class="fas fa-search" style="color: var(--gray);"></i>
                    <input type="text" placeholder="Cari data...">
                </div>

                <div class="user-profile">
                    <div class="user-info">
                        <h4><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Administrator'; ?></h4>
                        <p>Super Admin</p>
                    </div>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const sidebar = document.querySelector('.sidebar');
                    const sidebarToggle = document.getElementById('sidebarToggle');
                    const sidebarOverlay = document.getElementById('sidebarOverlay');

                    if (sidebarToggle && sidebar && sidebarOverlay) {
                        sidebarToggle.addEventListener('click', function() {
                            sidebar.classList.toggle('active');
                            sidebarOverlay.classList.toggle('active');
                            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
                        });

                        sidebarOverlay.addEventListener('click', function() {
                            sidebar.classList.remove('active');
                            sidebarOverlay.classList.remove('active');
                            document.body.style.overflow = '';
                        });
                    }

                    // Logout Modal Logic
                    const logoutBtn = document.getElementById('logoutBtn');
                    const logoutModal = document.getElementById('logoutModal');
                    const btnCancelLogout = document.getElementById('btnCancelLogout');

                    if (logoutBtn && logoutModal && btnCancelLogout) {
                        logoutBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            logoutModal.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        });

                        btnCancelLogout.addEventListener('click', function() {
                            logoutModal.classList.remove('active');
                            document.body.style.overflow = '';
                        });

                        logoutModal.addEventListener('click', function(e) {
                            if (e.target === logoutModal) {
                                logoutModal.classList.remove('active');
                                document.body.style.overflow = '';
                            }
                        });
                    }

                    // Dropdown Toggle Logic - Improved
                    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
                    
                    dropdownToggles.forEach(toggle => {
                        toggle.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            const dropdown = this.closest('.dropdown');
                            
                            // Close other dropdowns
                            document.querySelectorAll('.dropdown.show').forEach(d => {
                                if (d !== dropdown) {
                                    d.classList.remove('show');
                                }
                            });
                            
                            // Toggle current dropdown
                            dropdown.classList.toggle('show');
                        });
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!e.target.closest('.dropdown')) {
                            document.querySelectorAll('.dropdown.show').forEach(d => {
                                d.classList.remove('show');
                            });
                        }
                    });

                    // Adjust dropdown position based on scroll
                    function adjustDropdownPosition() {
                        const activeDropdown = document.querySelector('.dropdown.show');
                        if (activeDropdown) {
                            const dropdownMenu = activeDropdown.querySelector('.dropdown-menu');
                            const rect = activeDropdown.getBoundingClientRect();
                            
                            if (dropdownMenu) {
                                // Pastikan dropdown tidak keluar dari viewport
                                if (rect.top + dropdownMenu.offsetHeight > window.innerHeight) {
                                    dropdownMenu.style.top = 'auto';
                                    dropdownMenu.style.bottom = '0';
                                } else {
                                    dropdownMenu.style.top = rect.top + 'px';
                                    dropdownMenu.style.bottom = 'auto';
                                }
                            }
                        }
                    }

                    window.addEventListener('scroll', function() {
                        // Sembunyikan dropdown saat scroll
                        document.querySelectorAll('.dropdown.show').forEach(d => {
                            d.classList.remove('show');
                        });
                    });

                    // File Upload Logic
                    const fileInputs = document.querySelectorAll('.file-upload-input');
                    fileInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            const fileName = this.files[0] ? this.files[0].name : '';
                            const wrapper = this.closest('.file-upload-wrapper');
                            if (wrapper) {
                                const nameDisplay = wrapper.querySelector('.file-name');
                                const labelSpan = wrapper.querySelector('span:not(.file-name)');
                                const labelIcon = wrapper.querySelector('i');

                                if (fileName) {
                                    if (nameDisplay) {
                                        nameDisplay.textContent = fileName;
                                        nameDisplay.style.display = 'block';
                                    }
                                    if (labelSpan) labelSpan.textContent = 'File terpilih:';
                                    if (labelIcon) labelIcon.style.color = '#15803d';
                                    const label = wrapper.querySelector('.file-upload-label');
                                    if (label) label.style.borderColor = '#15803d';
                                } else {
                                    if (nameDisplay) nameDisplay.style.display = 'none';
                                    if (labelSpan) labelSpan.textContent = 'Klik atau seret file ke sini';
                                    if (labelIcon) labelIcon.style.color = '';
                                    const label = wrapper.querySelector('.file-upload-label');
                                    if (label) label.style.borderColor = '';
                                }
                            }
                        });
                    });
                });

                // Pindahkan modal ke body
                document.addEventListener('DOMContentLoaded', function() {
                    const logoutModal = document.getElementById('logoutModal');
                    if (logoutModal) {
                        document.body.appendChild(logoutModal);
                    }
                });
            </script>

            <!-- Logout Confirmation Modal -->
            <div class="logout-overlay" id="logoutModal">
                <div class="logout-modal">
                    <div class="logout-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h2>Konfirmasi Logout</h2>
                    <p>Apakah Anda yakin ingin keluar dari halaman panel admin? Sesi Anda akan berakhir.</p>
                    <div class="logout-btns">
                        <button class="btn-cancel" id="btnCancelLogout">Batal</button>
                        <a href="logout.php" class="btn-confirm-logout">Ya, Keluar</a>
                    </div>
                </div>
            </div>