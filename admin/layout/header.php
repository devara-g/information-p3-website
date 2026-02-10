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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="admin-logo">
                    <i class="fas fa-school"></i>
                </div>
                <h2>Admin Panel</h2>
            </div>

            <div class="sidebar-menu">
                <div class="menu-label">Main Menu</div>
                <ul>
                    <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="berita.php" class="<?= $current_page == 'berita.php' ? 'active' : ''; ?>"><i class="fas fa-newspaper"></i> Berita & Artikel</a></li>
                    <li><a href="agenda.php" class="<?= $current_page == 'agenda.php' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> Agenda Sekolah</a></li>
                    <li><a href="galeri.php" class="<?= $current_page == 'galeri.php' ? 'active' : ''; ?>"><i class="fas fa-images"></i> Galeri Kegiatan</a></li>
                    <li><a href="guru.php" class="<?= $current_page == 'guru.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Data Guru & Staff</a></li>
                </ul>

                <div class="menu-label">Settings</div>
                <ul>
                    <li><a href="pesan.php" class="<?= $current_page == 'pesan.php' ? 'active' : ''; ?>"><i class="fas fa-user-cog"></i> Pesan</a></li>
                    <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Lihat Website</a></li>
                </ul>
            </div>

            <div class="logout-btn">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content page-transition">
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
                });
            </script>