<?php include 'header.php'; ?>

<style>
/* Fasilitas Specific Styles - Matching Global Theme */
.fs-main {
    padding: 3rem 1rem 6rem;
    background: #f8fafc; /* Matches site background layout */
}
.fs-wrap {
    max-width: 1100px;
    margin: 0 auto;
}
.fs-header {
    text-align: center;
    margin-bottom: 4rem;
}
.fs-header h2 {
    font-size: 2.2rem;
    color: var(--primary);
    font-weight: 800;
    margin-bottom: 0.5rem;
}
.fs-header p {
    color: var(--gray);
    font-size: 1.05rem;
}

.fs-showcase {
    display: flex;
    flex-direction: column;
    gap: 3rem;
}
.fs-facility-block {
    display: flex;
    gap: 3rem;
    align-items: center;
    background: #ffffff;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 2px 15px rgba(11,45,114,0.05);
    border: 1px solid rgba(226,232,240,0.8);
    opacity: 0; /* Base state for scroll animation */
    will-change: transform, opacity;
}
.fs-facility-block.is-visible {
    opacity: 1;
    transform: translateX(0);
    transition: opacity 0.8s cubic-bezier(0.2, 0.8, 0.2, 1), transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.3s ease;
}
.fs-facility-block.is-visible:hover {
    box-shadow: 0 15px 40px rgba(11,45,114,0.1);
    transform: translateY(-5px);
}
.fs-facility-block.fs-block-normal {
    transform: translateX(-50px); /* Slides in from left */
}
.fs-facility-block.fs-block-alt {
    flex-direction: row-reverse;
    transform: translateX(50px); /* Slides in from right */
}

.fs-img-side {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-width: 0;
}
.fs-img-main {
    position: relative;
    width: 100%;
    aspect-ratio: 16/10;
    border-radius: 12px;
    overflow: hidden;
    background: #f1f5f9;
}
.fs-img-main .fallback-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 4rem;
    opacity: 0.15;
    z-index: 0;
}
.fs-img-main img {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.3s ease;
}
.fs-img-main:hover img {
    transform: scale(1.08);
}
.fs-img-tag {
    position: absolute;
    bottom: 1rem;
    left: 1rem;
    z-index: 2;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.8rem;
    background: rgba(255,255,255,0.95);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.fs-img-thumbs {
    display: flex;
    gap: 0.75rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}
.fs-img-thumb {
    flex-shrink: 0;
    width: 70px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    background: #f1f5f9;
    position: relative;
    border: 2px solid transparent;
    transition: border-color 0.3s ease;
}
.fs-img-thumb:hover {
    border-color: var(--primary);
}
.fs-img-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: relative;
    z-index: 1;
}

.fs-info-side {
    flex: 1;
}
.fs-icon-badge {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 1.25rem;
}
.fs-name {
    font-size: 1.6rem;
    color: var(--dark);
    font-weight: 800;
    margin-bottom: 0.75rem;
}
.fs-desc {
    color: var(--gray);
    font-size: 0.95rem;
    line-height: 1.7;
    margin-bottom: 1.5rem;
}
.fs-divider {
    height: 3px;
    width: 50px;
    margin-bottom: 1.5rem;
    border-radius: 3px;
}
.fs-feature-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}
.fs-feature-list span {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    color: var(--dark);
    font-weight: 500;
    font-size: 0.9rem;
}
.fs-feature-list i {
    font-size: 1rem;
}

@media (max-width: 768px) {
    .fs-facility-block {
        flex-direction: column;
        padding: 1.5rem;
    }
    .fs-block-alt {
        flex-direction: column;
    }
}
</style>

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
    <p>Sarana modern berkualitas untuk mendukung kegiatan belajar mengajar.</p>
    <?php include 'wave.php'; ?>
</section>

<?php
// Facility data
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
                $delay = $i * 0.1;
            ?>
            <div class="fs-facility-block <?= $isEven ? 'fs-block-normal' : 'fs-block-alt' ?>">
                <!-- Image Side -->
                <div class="fs-img-side">
                    <div class="fs-img-main">
                        <i class="fas <?= $f['icon'] ?> fallback-icon" style="color: <?= $f['color'] ?>"></i>
                        <img src="<?= $imgBase . $f['images'][0] ?>"
                             alt="<?= htmlspecialchars($f['nama']) ?>"
                             onload="this.style.opacity='1'"
                             onerror="this.style.opacity='0'">
                        <div class="fs-img-overlay">
                            <span class="fs-img-tag" style="color:<?= $f['color'] ?>; border: 1px solid <?= $f['color'] ?>30;">
                                <i class="fas <?= $f['icon'] ?>" style="margin-right: 4px;"></i> <?= htmlspecialchars($f['tag']) ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($hasMultiImg): ?>
                    <div class="fs-img-thumbs">
                        <?php foreach (array_slice($f['images'], 1) as $tImg): ?>
                        <div class="fs-img-thumb" onclick="
                            const mainImg = this.closest('.fs-img-side').querySelector('.fs-img-main img');
                            mainImg.style.opacity = '0';
                            setTimeout(() => { mainImg.src='<?= $imgBase . $tImg ?>'; }, 150);
                        ">
                            <i class="fas fa-image" style="color: <?= $f['color'] ?>50; position: absolute; top:50%; left:50%; transform:translate(-50%,-50%); z-index: 0; font-size: 1.2rem;"></i>
                            <img src="<?= $imgBase . $tImg ?>" alt="<?= htmlspecialchars($f['nama']) ?>"
                                 onload="this.style.opacity='1'"
                                 onerror="this.style.opacity='0'">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Info Side -->
                <div class="fs-info-side">
                    <div class="fs-icon-badge" style="background: <?= $f['color'] ?>15; color: <?= $f['color'] ?>; border: 1px solid <?= $f['color'] ?>30;">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Beri sedikit delay antar item jika terlihat bersamaan
                setTimeout(() => {
                    entry.target.classList.add('is-visible');
                }, 100);
                observer.unobserve(entry.target); // Animate once
            }
        });
    }, { 
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    });

    document.querySelectorAll('.fs-facility-block').forEach((block) => {
        observer.observe(block);
    });
});
</script>

<?php include 'footer.php'; ?>