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

                echo '
                <div class="news-card">
                    <img src="' . $foto_path . '" alt="' . $alt_text . '" class="news-image">
                    <div class="news-info">
                        <span class="news-date"><i class="fas fa-calendar-alt"></i> ' . date('d M Y', strtotime($row['tanggal'])) . ' • <i class="fas fa-tag"></i> ' . ucfirst($row['kategori']) . '</span>
                        <h3 class="news-title">' . $row['judul'] . '</h3>
                        <p class="news-excerpt">' . substr($row['deskripsi'], 0, 150) . '...</p>
                        <a href="detail-berita.php?id=' . $row['id'] . '" class="read-more">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                ';
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

