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
        /* ===== Sidebar Dropdown - Unified Accordion Style (All Screens) ===== */

        /* Arrow indicator on the toggle link */
        .sidebar-menu .dropdown>a {
            display: flex;
            align-items: center;
        }

        .sidebar-menu .dropdown>a .arrow {
            margin-left: auto;
            font-size: 11px;
            color: #64748b;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                color 0.3s ease;
            flex-shrink: 0;
        }

        .sidebar-menu .dropdown.show>a .arrow {
            transform: rotate(90deg);
            color: #38bdf8;
        }

        /* Active parent link highlight */
        .sidebar-menu .dropdown.show>a {
            color: #f8fafc;
            background: rgba(56, 189, 248, 0.08);
            border-radius: 14px;
        }

        /* ===== Accordion submenu - works on ALL screen sizes ===== */
        .sidebar-menu .dropdown .dropdown-menu {
            display: block;
            /* must be block for max-height animation */
            position: static;
            /* in-flow, never floating */
            left: unset;
            top: unset;
            transform: none;
            width: auto;

            /* Collapsed state */
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;

            /* Sidebar-matching colors */
            background: rgba(255, 255, 255, 0.03);
            border-left: 2px solid rgba(56, 189, 248, 0.2);
            border-radius: 0 0 10px 10px;
            margin: 0 8px 0 16px;
            padding: 0;
            list-style: none;
            box-shadow: none;
            z-index: auto;

            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.35s ease,
                visibility 0.35s ease,
                margin 0.35s ease,
                padding 0.35s ease;
        }

        /* Expanded state */
        .sidebar-menu .dropdown.show .dropdown-menu {
            max-height: 500px;
            opacity: 1;
            visibility: visible;
            margin-top: 6px;
            margin-bottom: 8px;
            padding: 4px 0;
        }

        /* Submenu items */
        .sidebar-menu .dropdown .dropdown-menu li {
            margin: 2px 0;
            padding: 0;
        }

        .sidebar-menu .dropdown .dropdown-menu li a {
            padding: 9px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            /* Slate 400 - matches sidebar inactive */
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            border-left: 2px solid transparent;
            border-radius: 8px;
            margin: 0 4px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-menu .dropdown .dropdown-menu li a i {
            font-size: 14px;
            width: 18px;
            text-align: center;
            color: #64748b;
            /* Slate 500 - matches sidebar icon color */
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .sidebar-menu .dropdown .dropdown-menu li a:hover {
            background: rgba(56, 189, 248, 0.06);
            color: #f8fafc;
            border-left-color: #38bdf8;
        }

        .sidebar-menu .dropdown .dropdown-menu li a:hover i {
            color: #38bdf8;
            transform: scale(1.1);
        }

        .sidebar-menu .dropdown .dropdown-menu li a.active {
            background: rgba(56, 189, 248, 0.12);
            color: #38bdf8;
            border-left-color: #38bdf8;
            font-weight: 700;
        }

        .sidebar-menu .dropdown .dropdown-menu li a.active i {
            color: #38bdf8;
            filter: drop-shadow(0 0 6px rgba(56, 189, 248, 0.4));
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
                    <li class="dropdown <?= in_array($current_page, ['guru.php', 'osis.php', 'mpk.php', 'kepsek.php']) ? 'active show' : ''; ?>">
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

                            // Close other dropdowns (Accordion logic)
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
                        if (!e.target.closest('.sidebar')) {
                            document.querySelectorAll('.dropdown.show').forEach(d => {
                                d.classList.remove('show');
                            });
                        }
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