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
    <h1>Fasilitas Sekolah</h1>
    <p>Sarana modern untuk mendukung kegiatan belajar mengajar</p>
    <?php include 'wave.php'; ?>
</section>

<?php
// Facility data: each category has an icon, color accent, and static images
$fasilitas = [
    [
        'nama'  => 'Ruang Kelas',
        'icon'  => 'fa-chalkboard-teacher',
        'color' => '#3b82f6',
        'desc'  => 'Ruang belajar yang nyaman, bersih, dan dilengkapi dengan papan tulis, proyektor, dan kursi ergonomis untuk mendukung proses belajar mengajar yang optimal.',
        'images' => ['kelas1.jpg','kelas2.jpg','kelas3.jpg','kelas4.jpg'],
        'tag'   => 'Akademik',
    ],
    [
        'nama'  => 'Laboratorium',
        'icon'  => 'fa-flask',
        'color' => '#8b5cf6',
        'desc'  => 'Laboratorium IPA, Komputer, dan Bahasa yang lengkap untuk mendukung kegiatan praktikum dan riset ilmiah siswa secara langsung.',
        'images' => ['lab1.jpg','lab2.jpg','lab3.jpg','lab4.jpg'],
        'tag'   => 'Sains & IT',
    ],
    [
        'nama'  => 'Perpustakaan',
        'icon'  => 'fa-book-open',
        'color' => '#f59e0b',
        'desc'  => 'Perpustakaan modern dengan koleksi ribuan buku pelajaran, referensi, dan bacaan pengembangan diri dalam suasana tenang dan nyaman.',
        'images' => ['perpus.jpg','perpus.jpg'],
        'tag'   => 'Literasi',
    ],
    [
        'nama'  => 'Lapangan Olahraga',
        'icon'  => 'fa-running',
        'color' => '#10b981',
        'desc'  => 'Lapangan serbaguna untuk basket, voli, futsal, dan atletik. Tersedia pula area senam dan fasilitas olahraga indoor.',
        'images' => ['lapangan.jpg','futsal.jpg','gym.jpg'],
        'tag'   => 'Olahraga',
    ],
    [
        'nama'  => 'Masjid Sekolah',
        'icon'  => 'fa-mosque',
        'color' => '#06b6d4',
        'desc'  => 'Masjid sekolah yang representatif untuk mendukung kegiatan ibadah dan pembinaan karakter religius siswa sehari-hari.',
        'images' => ['masjid.jpg'],
        'tag'   => 'Ibadah',
    ],
    [
        'nama'  => 'Kantin & UKS',
        'icon'  => 'fa-utensils',
        'color' => '#f97316',
        'desc'  => 'Kantin bersih dengan berbagai pilihan makanan bergizi. Dilengkapi UKS dengan tenaga medis untuk menjaga kesehatan seluruh warga sekolah.',
        'images' => ['kantin.jpg','uk.jpg'],
        'tag'   => 'Penunjang',
    ],
];
?>

<!-- ======= STATS BANNER ======= -->
<section class="fs-stats-bar">
    <div class="fs-wrap">
        <div class="fs-stats-grid">
            <div class="fs-stat-item">
                <i class="fas fa-building"></i>
                <span class="fs-stat-num">6</span>
                <span class="fs-stat-label">Jenis Fasilitas</span>
            </div>
            <div class="fs-stat-item">
                <i class="fas fa-door-open"></i>
                <span class="fs-stat-num">24</span>
                <span class="fs-stat-label">Ruang Kelas</span>
            </div>
            <div class="fs-stat-item">
                <i class="fas fa-flask"></i>
                <span class="fs-stat-num">3</span>
                <span class="fs-stat-label">Laboratorium</span>
            </div>
            <div class="fs-stat-item">
                <i class="fas fa-wifi"></i>
                <span class="fs-stat-num">100%</span>
                <span class="fs-stat-label">Area Wi-Fi</span>
            </div>
        </div>
    </div>
</section>

<!-- ======= FACILITIES SHOWCASE ======= -->
<section class="fs-main">
    <div class="fs-wrap">
        <div class="fs-header">
            <h2>Fasilitas Unggulan</h2>
            <p>Setiap ruang dirancang untuk memaksimalkan potensi belajar siswa</p>
        </div>

        <div class="fs-showcase">
            <?php foreach ($fasilitas as $i => $f):
                $isEven = ($i % 2 === 0);
                $hasMultiImg = count($f['images']) > 1;
                $imgBase = '../img/fasilitas/';
            ?>
            <div class="fs-facility-block <?= $isEven ? 'fs-block-normal' : 'fs-block-alt' ?>">
                <!-- Image Side -->
                <div class="fs-img-side">
                    <div class="fs-img-main">
                        <img src="<?= $imgBase . $f['images'][0] ?>"
                             alt="<?= htmlspecialchars($f['nama']) ?>"
                             onerror="this.parentElement.innerHTML='<div class=\'fs-img-ph\'><i class=\'fas <?= $f['icon'] ?>\'></i></div>'">
                        <div class="fs-img-overlay">
                            <span class="fs-img-tag" style="background:<?= $f['color'] ?>20;color:<?= $f['color'] ?>;border:1px solid <?= $f['color'] ?>40">
                                <?= htmlspecialchars($f['tag']) ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($hasMultiImg): ?>
                    <div class="fs-img-thumbs">
                        <?php foreach (array_slice($f['images'], 1) as $tImg): ?>
                        <div class="fs-img-thumb" onclick="this.closest('.fs-img-side').querySelector('.fs-img-main img').src='<?= $imgBase . $tImg ?>'">
                            <img src="<?= $imgBase . $tImg ?>" alt="<?= htmlspecialchars($f['nama']) ?>"
                                 onerror="this.style.display='none'">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Info Side -->
                <div class="fs-info-side">
                    <div class="fs-icon-badge" style="background: <?= $f['color'] ?>15; color: <?= $f['color'] ?>;">
                        <i class="fas <?= $f['icon'] ?>"></i>
                    </div>
                    <h3 class="fs-name"><?= htmlspecialchars($f['nama']) ?></h3>
                    <p class="fs-desc"><?= htmlspecialchars($f['desc']) ?></p>
                    <div class="fs-divider" style="background: linear-gradient(to right, <?= $f['color'] ?>, transparent)"></div>
                    <div class="fs-feature-list">
                        <span><i class="fas fa-check-circle" style="color:<?= $f['color'] ?>"></i> Fasilitas Lengkap</span>
                        <span><i class="fas fa-check-circle" style="color:<?= $f['color'] ?>"></i> Terawat & Bersih</span>
                        <span><i class="fas fa-check-circle" style="color:<?= $f['color'] ?>"></i> Akses 24/7</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
