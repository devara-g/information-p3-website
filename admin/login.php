<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMP PGRI 3 BGR - Sekolah Hebat Indonesia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <header>
        <nav>
            <a href="../index.php" class="logo">
                <img src="../img/pgri3-photoroom.png" alt="Logo Sekolah">
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
                        <li><a href="../pages/about.php"><i class="fas fa-school"></i> Profil Sekolah</a></li>
                        <li><a href="../pages/visi-misi.php"><i class="fas fa-bullseye"></i> Visi & Misi</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-newspaper"></i> Berita <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 4px;"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="../pages/berita.php"><i class="fas fa-rss"></i> Berita Terbaru</a></li>
                        <li><a href="../pages/agenda.php"><i class="fas fa-calendar-alt"></i> Agenda Sekolah</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-images"></i> Galeri <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 4px;"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="../pages/galeri.php"><i class="fas fa-camera"></i> Galeri Kegiatan</a></li>
                        <li><a href="../pages/fasilitas.php"><i class="fas fa-building"></i> Fasilitas</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-sitemap"></i> Struktur <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 4px;"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="../pages/struktur.php"><i class="fas fa-user-tie"></i> Kepsek & TU</a></li>
                        <li><a href="../pages/osis.php"><i class="fas fa-users"></i> OSIS</a></li>
                        <li><a href="../pages/mpk.php"><i class="fas fa-user-friends"></i> MPK</a></li>
                        <li><a href="../pages/guru.php"><i class="fas fa-chalkboard-teacher"></i> Guru & Staff</a></li>
                    </ul>
                </li>
                <li><a href="../pages/kontak.php"><i class="fas fa-envelope"></i> Kontak</a></li>
                <li><a href="login.php" class="admin-btn"><i class="fas fa-user-shield"></i> Admin</a></li>
            </ul>
        </nav>
    </header>

    <a href="../admin/login.php" class="admin-fixed" title="Admin Panel">
        <i class="fas fa-cog"></i>
    </a>

    <script src="../js/main.js"></script>

    <section style="padding: 8rem 2rem 4rem; min-height: 100vh; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; align-items: center; justify-content: center;">
        <div style="background: var(--white); padding: 3rem; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); width: 100%; max-width: 400px;">
            <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem;"><i class="fas fa-user-shield"></i></div>
                    <h2 style="color: var(--primary); margin-bottom: 0.5rem;">Admin Panel</h2>
                <p style="color: #666;">SMP PGRI 3 BGR</p>
            </div>

            <form method="POST" action="login-process.php">
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" required style="width: 100%; padding: 0.8rem 1rem; border: 2px solid #eee; border-radius: 10px; font-size: 1rem; transition: border-color 0.3s;">
                </div>
                <div style="margin-bottom: 1.5rem; position: relative;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required style="width: 100%; padding: 0.8rem 3rem 0.8rem 1rem; border: 2px solid #eee; border-radius: 10px; font-size: 1rem; transition: border-color 0.3s;">
                    <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 1rem; top: 2.7rem; cursor: pointer; color: var(--gray); font-size: 1.1rem; transition: 0.3s;"></i>
                </div>
                <button type="submit" name="login" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: var(--white); border: none; padding: 1rem; border-radius: 10px; cursor: pointer; font-size: 1rem; font-weight: 600; width: 100%; transition: transform 0.3s;">Masuk</button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="../index.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">← Kembali ke Beranda</a>
            </div>
        </div>
    </section>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });

        togglePassword.addEventListener('mouseover', function() {
            this.style.color = 'var(--primary)';
        });

        togglePassword.addEventListener('mouseout', function() {
            this.style.color = 'var(--gray)';
        });
    </script>

    <style>
        input:focus {
            outline: none;
            border-color: var(--primary) !important;
        }

        button:hover {
            transform: scale(1.02);
        }

        #togglePassword:hover {
            transform: scale(1.1);
        }
    </style>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>SMP PGRI 3 BOGOR</h3>
                <p>Sekolah Menengah Pertama yang berkomitmen menghasilkan lulusan kompeten, kreatif, dan berakhlak mulia.</p>
            </div>
            <div class="footer-section">
                <h3>Menu Cepat</h3>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="pages/about.php">Profil Sekolah</a></li>
                    <li><a href="pages/berita.php">Berita</a></li>
                    <li><a href="pages/galeri.php">Galeri</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Kontak Kami</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Jl. Raya Tajur No. 12B, Bogor</li>
                    <li><i class="fas fa-phone"></i> (0251) 1234-5678</li>
                    <li><i class="fas fa-envelope"></i> info@smppgri3bogor.sch.id</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 SMP PGRI 3 BOGOR. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>