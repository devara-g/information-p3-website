    <?php include 'header.php'; ?>

    <section class="gallery-hero">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
        </div>
        <h1>Galeri Kegiatan</h1>
        <p>Dokumentasi kegiatan SMP PGRI 3 BOGOR</p>
        <?php include 'wave.php'; ?>
    </section>

    <section class="gallery-content">
        <?php
        include '../database/conn.php';
        $categories = [
            'Kegiatan Sekolah' => 'Kegiatan Sekolah',
            'Prestasi Siswa' => 'Prestasi Siswa',
            'Kegiatan Ekskul' => 'Kegiatan Ekskul'
        ];

        foreach ($categories as $key => $title) :
            $query = mysqli_query($conn, "SELECT * FROM galeri WHERE kategori = '$key' ORDER BY id DESC");
        ?>
            <div class="gallery-section">
                <h2><?= $title ?></h2>
                <div class="gallery-grid">
                    <?php if (mysqli_num_rows($query) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) :
                            $img_src = (!empty($row['foto']) && file_exists('../' . $row['foto'])) ? '../' . $row['foto'] : 'assets/img/no-image.jpg';
                        ?>
                            <div class="gallery-item">
                                <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
                                <div class="gallery-overlay">
                                    <div class="overlay-content">
                                        <p class="overlay-title"><?= htmlspecialchars($row['judul']) ?></p>
                                        <?php if (!empty($row['deskripsi'])) : ?>
                                            <p class="overlay-desc"><?= htmlspecialchars($row['deskripsi']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; background: rgba(0,0,0,0.02); border-radius: 20px; margin: 20px 0;">
                            <i class="fas fa-images" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray); font-weight: 500;">Belum ada foto untuk kategori ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <?php include 'footer.php'; ?>

