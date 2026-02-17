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
                    <li><a href="guru.php" class="<?= $current_page == 'guru.php' ? 'active' : ''; ?>"><i class="bx bxs-user-pin"></i> Data Guru & Staff</a></li>
                </ul>

                <div class="menu-label">External</div>
                <ul>
                    <li><a href="pesan.php" class="<?= $current_page == 'pesan.php' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Pesan Masuk</a></li>
                    <li class="mt-4"><a href="../index.php" target="_blank" class="sidebar-action-btn"><i class="fas fa-external-link-alt"></i> Lihat Website</a></li>
                </ul>
            </div>

            <div class="logout-btn">
                <a href="javascript:void(0)" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
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

                    // Logout Modal Logic
                    const logoutBtn = document.getElementById('logoutBtn');
                    const logoutModal = document.getElementById('logoutModal');
                    const btnCancelLogout = document.getElementById('btnCancelLogout');

                    if (logoutBtn && logoutModal && btnCancelLogout) {
                        logoutBtn.addEventListener('click', function(e) {
                            if (window.innerWidth <= 768) {
                                // Mobile: Direct logout for faster experience
                                window.location.href = 'logout.php';
                            } else {
                                // Desktop: Show premium modal
                                logoutModal.classList.add('active');
                                document.body.style.overflow = 'hidden';
                            }
                        });

                        btnCancelLogout.addEventListener('click', function() {
                            logoutModal.classList.remove('active');
                            document.body.style.overflow = '';
                        });

                        // Close on overlay click
                        logoutModal.addEventListener('click', function(e) {
                            if (e.target === logoutModal) {
                                logoutModal.classList.remove('active');
                                document.body.style.overflow = '';
                            }
                        });
                    }

                    // File Upload Logic
                    const fileInputs = document.querySelectorAll('.file-upload-input');
                    fileInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            const fileName = this.files[0] ? this.files[0].name : '';
                            const wrapper = this.closest('.file-upload-wrapper');
                            const nameDisplay = wrapper.querySelector('.file-name');
                            const labelSpan = wrapper.querySelector('span:not(.file-name)');
                            const labelIcon = wrapper.querySelector('i');

                            if (fileName) {
                                nameDisplay.textContent = fileName;
                                nameDisplay.style.display = 'block';
                                labelSpan.textContent = 'File terpilih:';
                                labelIcon.style.color = '#15803d'; // Success color
                                wrapper.querySelector('.file-upload-label').style.borderColor = '#15803d';
                            } else {
                                nameDisplay.style.display = 'none';
                                labelSpan.textContent = 'Klik atau seret file ke sini';
                                labelIcon.style.color = '';
                                wrapper.querySelector('.file-upload-label').style.borderColor = '';
                            }
                        });
                    });
                });
                    // Pindahkan modal ke body agar fixed relative ke viewport (bukan main-content yg punya transform)
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editModal');
        const msgModal = document.getElementById('msgModal');
        const deleteModal = document.getElementById('deleteModal');
        if (editModal) document.body.appendChild(editModal);
        if (msgModal) document.body.appendChild(msgModal);
        if (deleteModal) document.body.appendChild(deleteModal);
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