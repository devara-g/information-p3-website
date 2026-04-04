<?php include 'header.php'; ?>

<section class="news-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
    </div>
    <h1>Berita Terbaru</h1>
    <p>Informasi terkini dari SMP PGRI 3 BOGOR</p>
    <?php include 'wave.php'; ?>
</section>

</style>

<section class="news-content">
    <div class="news-grid">
        <?php
        include '../database/conn.php';

        $sql = "SELECT * FROM berita ORDER BY id DESC LIMIT 6";
        $result = mysqli_query($conn, $sql);

        // Cek apakah ada berita
        if (mysqli_num_rows($result) > 0) {
            // Looping berita
            while ($row = mysqli_fetch_assoc($result)) {
                // Cek apakah foto ada atau tidak
                if (!empty($row['foto']) && file_exists('../' . $row['foto'])) {
                    $foto_path = '../' . $row['foto'];
                    $alt_text = $row['judul'];
                } else {
                    $foto_path = 'assets/img/no-image.jpg';
                    $alt_text = 'Tidak ada gambar';
                }

                $is_featured = (strtolower($row['kategori']) == 'sorotan khusus');
                
                // Set custom badge styling based on category
                $cat_lower = strtolower($row['kategori']);
                $badge_class = 'badge-default';
                if ($cat_lower == 'prestasi') $badge_class = 'badge-prestasi';
                else if ($cat_lower == 'kegiatan') $badge_class = 'badge-kegiatan';
                else if ($cat_lower == 'pengumuman') $badge_class = 'badge-pengumuman';
                else if ($cat_lower == 'ekstrakurikuler') $badge_class = 'badge-ekstra';
                else if ($is_featured) $badge_class = 'badge-sorotan';

                // Featured Card Layout (Sorotan Khusus)
                if ($is_featured) {
                    echo '
                    <div class="news-card featured-news-card">
                        <div class="news-info featured-info">
                            <div class="news-badge ' . $badge_class . '">' . strtoupper($row['kategori']) . '</div>
                            <h3 class="news-title text-white">' . $row['judul'] . '</h3>
                            <p class="news-excerpt text-light">' . substr(strip_tags($row['deskripsi']), 0, 200) . '...</p>
                            <a href="detail-berita.php?id=' . $row['id'] . '" class="btn-read-featured">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="featured-image-wrapper">
                            <img src="' . $foto_path . '" alt="' . $alt_text . '" class="featured-image">
                        </div>
                    </div>
                    ';
                } 
                // Normal Card Layout
                else {
                    echo '
                    <div class="news-card">
                        <div class="news-image-wrapper">
                            <div class="news-badge ' . $badge_class . '">' . ucfirst($row['kategori']) . '</div>
                            <img src="' . $foto_path . '" alt="' . $alt_text . '" class="news-image">
                        </div>
                        <div class="news-info">
                            <span class="news-date"><i class="fas fa-clock"></i> ' . date('d M Y', strtotime($row['tanggal'])) . '</span>
                            <h3 class="news-title">' . $row['judul'] . '</h3>
                            <p class="news-excerpt">' . substr(strip_tags($row['deskripsi']), 0, 110) . '...</p>
                            <a href="detail-berita.php?id=' . $row['id'] . '" class="read-more" style="margin-top: auto;">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    ';
                }
            }
        } else {
            // Tampilkan pesan jika tidak ada berita
            echo '
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h2>Belum Ada Berita</h2>
                <p>Saat ini belum ada berita yang dipublikasikan. Silakan kembali lagi nanti untuk mendapatkan informasi terbaru dari SMP PGRI 3 BOGOR.</p>
                <a href="../index.php" class="empty-state-btn">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>
            ';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

