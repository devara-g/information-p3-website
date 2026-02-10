<?php include 'header.php'; ?>

<section class="news-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <h1>Berita Terbaru</h1>
    <p>Informasi terkini dari SMP PGRI 3 BOGOR</p>
</section>

<section class="news-content">
    <div class="news-grid">
        <?php
        $berita_items = [
            [
                'id' => 1,
                'judul' => 'Prestasi Gemilang Siswa SMP PGRI 3 BOGOR di Olimpiade Nasional',
                'tanggal' => '15 Mei 2026',
                'kategori' => 'Prestasi',
                'gambar' => '../img/berita/berita1.jpg',
                'excerpt' => 'Siswa SMP PGRI 3 BOGOR berhasil meraih medali emas dalam Olimpiade Sains Nasional bidang Matematika.'
            ],
            [
                'id' => 2,
                'judul' => 'Penerimaan Peserta Didik Baru (PPDB) 2026 Telah Dibuka',
                'tanggal' => '10 Mei 2026',
                'kategori' => 'Pengumuman',
                'gambar' => '../img/berita/berita2.jpg',
                'excerpt' => 'PPDB SMP PGRI 3 BOGOR dibuka! Segera daftarkan diri Anda melalui jalur prestasi atau zonasi.'
            ],
            [
                'id' => 3,
                'judul' => 'Workshop Digital Literacy untuk Siswa Kelas VII',
                'tanggal' => '05 Mei 2026',
                'kategori' => 'Kegiatan',
                'gambar' => '../img/berita/berita3.jpg',
                'excerpt' => 'SMP PGRI 3 BOGOR mengadakan workshop Digital Literacy untuk membekali siswa dengan pemahaman teknologi yang bijak.'
            ],
            [
                'id' => 4,
                'judul' => 'Tim Basket SMP PGRI 3 BOGOR Raih Juara Umum',
                'tanggal' => '01 Mei 2026',
                'kategori' => 'Prestasi',
                'gambar' => '../img/berita/berita1.jpg',
                'excerpt' => 'Semangat juang tim basket SMP PGRI 3 BOGOR membuahkan hasil dengan meraih juara umum kompetisi antar-SMP.'
            ],
            [
                'id' => 5,
                'judul' => 'Kunjungan Edukasi ke Museum Nasional',
                'tanggal' => '25 April 2026',
                'kategori' => 'Kegiatan',
                'gambar' => '../img/berita/berita2.jpg',
                'excerpt' => 'Siswa kelas VIII melakukan kunjungan edukasi untuk mempelajari sejarah bangsa di Museum Nasional.'
            ],
            [
                'id' => 6,
                'judul' => 'Program Beasiswa Siswa Berprestasi 2026',
                'tanggal' => '20 April 2026',
                'kategori' => 'Pengumuman',
                'gambar' => '../img/berita/berita3.jpg',
                'excerpt' => 'Pemberian apresiasi kepada para siswa berprestasi melalui program beasiswa sekolah.'
            ]
        ];

        foreach ($berita_items as $b) {
            echo '
            <div class="news-card">
                <img src="' . $b['gambar'] . '" alt="' . $b['judul'] . '" class="news-image">
                <div class="news-info">
                    <span class="news-date"><i class="fas fa-calendar-alt"></i> ' . $b['tanggal'] . ' • <i class="fas fa-tag"></i> ' . $b['kategori'] . '</span>
                    <h3 class="news-title">' . $b['judul'] . '</h3>
                    <p class="news-excerpt">' . $b['excerpt'] . '</p>
                    <a href="detail-berita.php?id=' . $b['id'] . '" class="read-more">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            ';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>