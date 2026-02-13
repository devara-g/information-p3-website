<?php include 'header.php'; ?>

<section class="gallery-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <h1>Agenda Sekolah</h1>
    <p>Jadwal kegiatan SMP PGRI 3 BOGOR</p>
</section>

<section class="news-content">
    <div class="news-grid">
        <!-- data dummy agenda, nnti pake db ya jing -->
        <?php
        include '../database/conn.php';
        $data = mysqli_query($conn, "SELECT * FROM agenda");


        foreach ($data as $a) {
            $foto_path = (!empty($a['foto']) && file_exists('../' . $a['foto'])) ? '../' . $a['foto'] : 'assets/img/no-image.jpg';
            echo '
            <div class="news-card">
                <img src="' . $foto_path . '" alt="' . $a['judul'] . '" class="news-image">
                <div class="news-info">
                    <h3 class="news-title">' . $a['judul'] . '</h3>
                    <div class="news-meta" style="margin-bottom: 1rem; color: var(--gray);">
                        <span><i class="fas fa-calendar-alt"></i> ' . date('d M Y', strtotime($a['tanggal'])) . '</span><br>
                        <span><i class="fas fa-map-marker-alt"></i> ' . htmlspecialchars($a['lokasi']) . '</span>
                    </div>
                    <p class="news-excerpt">' . nl2br(htmlspecialchars($a['deskripsi'])) . '</p>
                </div>
            </div>
            ';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>