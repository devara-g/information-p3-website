    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SMP PGRI 3 BGR - Sekolah Hebat Indonesia</title>
        <link rel="stylesheet" href="../css/style.css?v=<?= time(); ?>">
        <link rel="icon" href="../img/p3hd.jpg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>

    <body>
        <header>
            <nav>
                <a href="../index.php" class="logo">
                    <img src="../img/p3hd.jpg?v=<?= time(); ?>" alt="Logo Sekolah">
                    <span>SMP PGRI 3 BGR</span>
                </a>

                <div class="mobile-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <ul class="nav-links">
                    <li><a href="../index.php"><i class="fas fa-home"></i> Beranda</a></li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-info-circle"></i> Tentang <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 4px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="about.php"><i class="fas fa-school"></i> Profil Sekolah</a></li>
                            <li><a href="visi-misi.php"><i class="fas fa-bullseye"></i> Visi & Misi</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-newspaper"></i> Berita <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 4px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="berita.php"><i class="fas fa-rss"></i> Berita Terbaru</a></li>
                            <li><a href="agenda.php"><i class="fas fa-calendar-alt"></i> Agenda Sekolah</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-images"></i> Galeri <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 4px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="galeri.php"><i class="fas fa-camera"></i> Galeri Kegiatan</a></li>
                            <li><a href="fasilitas.php"><i class="fas fa-building"></i> Fasilitas</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-sitemap"></i> Struktur <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 4px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="kesiswaan.php"><i class="fas fa-child"></i> Kesiswaan</a></li>
                            <li><a href="struktur.php"><i class="fas fa-user-tie"></i> Kepsek & TU</a></li>
                            <li><a href="osis.php"><i class="fas fa-users"></i> OSIS</a></li>
                            <li><a href="mpk.php"><i class="fas fa-user-friends"></i> MPK</a></li>
                            <li><a href="guru.php"><i class="fas fa-chalkboard-teacher"></i> Guru & Staff</a></li>
                        </ul>
                    </li>
                    <li><a href="kontak.php"><i class="fas fa-envelope"></i> Kontak</a></li>
                </ul>
            </nav>
        </header>

        <a href="../admin/login.php" class="admin-fixed" title="Admin Panel">
            <i class="fas fa-user-cog"></i>
        </a>

        <script src="../js/main.js?v=<?= time(); ?>"></script>