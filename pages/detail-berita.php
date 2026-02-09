<?php include 'header.php'; ?>

<section class="news-hero" style="padding-top: 6rem;">
    <h1>Detail Berita</h1>
    <p>Informasi lengkap seputar sekolah</p>
</section>

<section class="news-detail">
    <?php
    $id = isset($_GET['id']) ? $_GET['id'] : 1;

    $berita_list = [
        1 => [
            'judul' => 'Prestasi Gemilang Siswa SMP PGRI 3 BOGOR di Olimpiade Nasional',
            'tanggal' => '15 Mei 2026',
            'kategori' => 'Prestasi',
            'gambar' => '../img/berita/berita1.jpg',
            'penulis' => 'Admin Humas',
            'isi' => '
                <p>SMP PGRI 3 BOGOR kembali menorehkan prestasi gemilang di tingkat nasional. Delegasi siswa yang mengikuti Olimpiade Sains Nasional (OSN) berhasil meraih medali emas.</p>
                <p>Pencapaian ini merupakan hasil kerja keras siswa dan bimbingan intensif dari para guru. Sekolah berkomitmen untuk terus mendukung pengembangan bakat dan minat siswa di bidang akademik maupun non-akademik.</p>
                <p>"Kami sangat bangga dengan pencapaian ini. Ini membuktikan bahwa siswa SMP PGRI 3 BOGOR mampu bersaing di tingkat nasional," ujar Kepala Sekolah.</p>
            '
        ],
        2 => [
            'judul' => 'Penerimaan Peserta Didik Baru (PPDB) 2026 Telah Dibuka',
            'tanggal' => '10 Mei 2026',
            'kategori' => 'Pengumuman',
            'gambar' => '../img/berita/berita2.jpg',
            'penulis' => 'Panitia PPDB',
            'isi' => '
                <p>PPDB (Penerimaan Peserta Didik Baru) SMP PGRI 3 BOGOR tahun ajaran 2026/2027 telah dibuka! Bagi kalian lulusan SD/MI, kesempatan emas untuk bergabung dengan sekolah terbaik ada di tangan kalian.</p>
                <p>Pendaftaran dapat dilakukan secara online melalui website resmi atau datang langsung ke gedung sekolah. Kami menyediakan fasilitas modern dan tenaga pengajar profesional untuk mencetak generasi unggul.</p>
                <p>SMP PGRI 3 BOGOR mengedepankan pendidikan karakter dan penguasaan teknologi untuk menghadapi tantangan masa depan.</p>
            '
        ],
        3 => [
            'judul' => 'Workshop Digital Literacy untuk Siswa Kelas VII',
            'tanggal' => '05 Mei 2026',
            'kategori' => 'Kegiatan',
            'gambar' => '../img/berita/berita3.jpg',
            'penulis' => 'Wakasek Kesiswaan',
            'isi' => '
                <p>Dalam rangka meningkatkan literasi digital siswa di era teknologi, SMP PGRI 3 BOGOR mengadakan workshop Digital Literacy untuk siswa kelas VII. Workshop ini menghadirkan praktisi IT yang berpengalaman.</p>
                <p>Siswa diajarkan cara menggunakan media sosial dengan bijak, mengenali berita hoax, dan dasar-dasar keamanan siber. Kegiatan ini diharapkan dapat membentuk karakter siswa yang cerdas bermedia sosial.</p>
                <p>SMP PGRI 3 BOGOR berkomitmen untuk terus mengadakan kegiatan yang mendukung pengembangan kompetensi siswa sesuai tuntutan zaman.</p>
            '
        ],
        4 => [
            'judul' => 'Tim Basket SMP PGRI 3 BOGOR Raih Juara Umum',
            'tanggal' => '01 Mei 2026',
            'kategori' => 'Prestasi',
            'gambar' => '../img/berita/berita1.jpg',
            'penulis' => 'Pembina OSIS',
            'isi' => '
                <p>Tim basket SMP PGRI 3 BOGOR berhasil meraih juara umum dalam kompetisi antar-sekolah se-Bogor. Turnamen ini diikuti oleh puluhan sekolah dari berbagai daerah.</p>
                <p>Kemenangan ini menjadi motivasi bagi tim untuk terus berlatih dan meraih prestasi yang lebih tinggi. Sekolah memberikan apresiasi yang tinggi atas dedikasi para atlet dan pelatih.</p>
            '
        ],
        5 => [
            'judul' => 'Kunjungan Edukasi ke Museum Nasional',
            'tanggal' => '25 April 2026',
            'kategori' => 'Kegiatan',
            'gambar' => '../img/berita/berita2.jpg',
            'penulis' => 'Guru Sejarah',
            'isi' => '
                <p>Siswa kelas VIII SMP PGRI 3 BOGOR melakukan kunjungan edukasi ke Museum Nasional di Jakarta. Kegiatan ini bertujuan untuk memperdalam pengetahuan siswa tentang sejarah dan budaya Indonesia.</p>
                <p>Selama kunjungan, siswa mengamati berbagai artefak bersejarah dan mendapatkan penjelasan dari pemandu museum. Kunjungan ini merupakan bagian dari kurikulum pembelajaran luar kelas.</p>
                <p>SMP PGRI 3 BOGOR terus mengembangkan metode pembelajaran yang variatif dan menyenangkan bagi siswa.</p>
            '
        ],
        6 => [
            'judul' => 'Program Beasiswa Siswa Berprestasi 2026',
            'tanggal' => '20 April 2026',
            'kategori' => 'Pengumuman',
            'gambar' => '../img/berita/berita3.jpg',
            'penulis' => 'Admin Sekolah',
            'isi' => '
                <p>SMP PGRI 3 BOGOR memberikan apresiasi kepada siswa berprestasi melalui program beasiswa. Sebanyak 25 siswa menerima beasiswa dari berbagai sumber, baik dari pemerintah maupun yayasan.</p>
                <p>Program beasiswa ini diharapkan dapat membantu siswa untuk terus berprestasi dan menginspirasi siswa lainnya. Kriteria penilaian meliputi prestasi akademik, non-academic, dan kedisiplinan.</p>
            '
        ]
    ];

    $berita = isset($berita_list[$id]) ? $berita_list[$id] : $berita_list[1];
    ?>

    <a href="berita.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Berita</a>

    <div class="news-detail-header">
        <span class="news-date" style="color: var(--accent);"><?php echo $berita['kategori']; ?></span>
        <h1><?php echo $berita['judul']; ?></h1>
        <div class="news-detail-meta">
            <span><i class="fas fa-calendar-alt"></i> <?php echo $berita['tanggal']; ?></span>
            <span><i class="fas fa-user"></i> <?php echo $berita['penulis']; ?></span>
        </div>
    </div>

    <img src="<?php echo $berita['gambar']; ?>" alt="<?php echo $berita['judul']; ?>" class="news-detail-image">

    <div class="news-detail-content">
        <?php echo $berita['isi']; ?>
    </div>
</section>

<?php include 'footer.php'; ?>