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
        $agenda_items = [
            [
                'judul' => 'Ujian Tengah Semester Ganjil',
                'tanggal' => '15 - 20 September 2026',
                'lokasi' => 'Gedung Sekolah',
                'gambar' => '../img/agenda/agenda1.jpg',
                'excerpt' => 'Pelaksanaan UTS Ganjil untuk siswa kelas VII, VIII, dan IX.'
            ],
            [
                'judul' => 'Rapat Orang Tua Murid Kelas VII',
                'tanggal' => '10 September 2026',
                'lokasi' => 'Aula Sekolah',
                'gambar' => '../img/agenda/agenda2.jpg',
                'excerpt' => 'Pertemuan awal tahun ajaran baru bersama orang tua siswa kelas VII.'
            ],
            [
                'judul' => 'Pentas Seni dan Budaya SMP',
                'tanggal' => '25 Agustus 2026',
                'lokasi' => 'Aula Terbuka',
                'gambar' => '../img/agenda/agenda3.jpg',
                'excerpt' => 'Pameran karya seni dan pertunjukan bakat siswa SMP PGRI 3 BOGOR.'
            ],
            [
                'judul' => 'Hari Sumpah Pemuda',
                'tanggal' => '28 Oktober 2026',
                'lokasi' => 'Halaman Sekolah',
                'gambar' => '../img/agenda/sumpahpemuda.jpg',
                'excerpt' => 'Perayaan hari sumpah pemuda dengan berbagai kegiatan kreatif siswa.'
            ],
            [
                'judul' => 'Ujian Akhir Semester Ganjil',
                'tanggal' => '15 - 19 Desember 2026',
                'lokasi' => 'Ruang Ujian',
                'gambar' => '../img/agenda/uas.jpg',
                'excerpt' => 'Evaluasi hasil belajar siswa pada akhir semester ganjil.'
            ],
            [
                'judul' => 'Libur Semester Ganjil',
                'tanggal' => '23 Des 2026 - 8 Jan 2027',
                'lokasi' => '-',
                'gambar' => '../img/agenda/libur.jpg',
                'excerpt' => 'Masa libur semester dan persiapan memasuki semester genap.'
            ]
        ];

        foreach ($agenda_items as $a) {
            echo '
            <div class="news-card">
                <img src="' . $a['gambar'] . '" alt="' . $a['judul'] . '" class="news-image">
                <div class="news-info">
                    <h3 class="news-title">' . $a['judul'] . '</h3>
                    <div class="news-meta" style="margin-bottom: 1rem; color: var(--gray);">
                        <span><i class="fas fa-calendar-alt"></i> ' . $a['tanggal'] . '</span><br>
                        <span><i class="fas fa-map-marker-alt"></i> ' . $a['lokasi'] . '</span>
                    </div>
                    <p class="news-excerpt">' . $a['excerpt'] . '</p>
                </div>
            </div>
            ';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>