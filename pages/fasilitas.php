<?php
include 'header.php';
include '../database/conn.php';

// Ensure tables exist
$chk = mysqli_query($conn, "SHOW TABLES LIKE 'fasilitas'");
if (mysqli_num_rows($chk) == 0) {
    $conn->query("CREATE TABLE `fasilitas` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nama` varchar(200) NOT NULL,
        `deskripsi` text NOT NULL,
        `icon` varchar(50) NOT NULL DEFAULT 'fa-building',
        `color` varchar(20) NOT NULL DEFAULT '#3b82f6',
        `tag` varchar(100) NOT NULL DEFAULT '',
        `sort_order` int NOT NULL DEFAULT 0,
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}
$chk2 = mysqli_query($conn, "SHOW TABLES LIKE 'fasilitas_images'");
if (mysqli_num_rows($chk2) == 0) {
    $conn->query("CREATE TABLE `fasilitas_images` (
        `id` int NOT NULL AUTO_INCREMENT,
        `fasilitas_id` int NOT NULL,
        `filename` varchar(255) NOT NULL,
        `sort_order` int NOT NULL DEFAULT 0,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_fasilitas_id` (`fasilitas_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

// Fetch active fasilitas
$fasilitas = [];
$result = $conn->query("SELECT * FROM fasilitas WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Fetch images for this facility
        $imgResult = $conn->query("SELECT filename FROM fasilitas_images WHERE fasilitas_id = {$row['id']} ORDER BY sort_order ASC");
        $row['images'] = [];
        while ($img = $imgResult->fetch_assoc()) {
            $row['images'][] = $img['filename'];
        }
        $fasilitas[] = $row;
    }
}
?>

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

<!-- ======= FACILITIES SHOWCASE ======= -->
<section class="fs-main">
    <div class="fs-wrap">
        <div class="fs-header">
            <h2>Fasilitas Unggulan</h2>
            <p>Setiap ruang dirancang untuk memaksimalkan potensi belajar siswa</p>
        </div>

        <div class="fs-showcase">
            <?php if (count($fasilitas) > 0): ?>
            <?php foreach ($fasilitas as $i => $f):
                $isEven = ($i % 2 === 0);
                $hasMultiImg = count($f['images']) > 1;
                $imgBase = '../upload/img/fasilitas/';
                $delay = $i * 0.1;
            ?>
            <div class="fs-facility-block <?= $isEven ? 'fs-block-normal' : 'fs-block-alt' ?>">
                <!-- Image Side -->
                <div class="fs-img-side">
                    <div class="fs-img-main">
                        <?php if (count($f['images']) > 0): ?>
                        <i class="fas <?= htmlspecialchars($f['icon']) ?> fallback-icon" style="color: <?= htmlspecialchars($f['color']) ?>"></i>
                        <img src="<?= $imgBase . htmlspecialchars($f['images'][0]) ?>"
                             alt="<?= htmlspecialchars($f['nama']) ?>"
                             onload="this.style.opacity='1'"
                             onerror="this.style.opacity='0'">
                        <?php else: ?>
                        <div style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:linear-gradient(135deg, <?= htmlspecialchars($f['color']) ?>20, <?= htmlspecialchars($f['color']) ?>40)">
                            <i class="fas <?= htmlspecialchars($f['icon']) ?>" style="font-size:4rem;color:<?= htmlspecialchars($f['color']) ?>;opacity:0.5"></i>
                        </div>
                        <?php endif; ?>
                        <div class="fs-img-overlay">
                            <span class="fs-img-tag" style="color:<?= htmlspecialchars($f['color']) ?>; border: 1px solid <?= htmlspecialchars($f['color']) ?>30;">
                                <i class="fas <?= htmlspecialchars($f['icon']) ?>" style="margin-right: 4px;"></i> <?= htmlspecialchars($f['tag']) ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($hasMultiImg): ?>
                    <div class="fs-img-thumbs">
                        <?php foreach (array_slice($f['images'], 1) as $tImg): ?>
                        <div class="fs-img-thumb" onclick="
                            const mainImg = this.closest('.fs-img-side').querySelector('.fs-img-main img');
                            mainImg.style.opacity = '0';
                            setTimeout(() => { mainImg.src='<?= $imgBase . htmlspecialchars($tImg) ?>'; }, 150);
                        ">
                            <i class="fas fa-image" style="color: <?= htmlspecialchars($f['color']) ?>50; position: absolute; top:50%; left:50%; transform:translate(-50%,-50%); z-index: 0; font-size: 1.2rem;"></i>
                            <img src="<?= $imgBase . htmlspecialchars($tImg) ?>" alt="<?= htmlspecialchars($f['nama']) ?>"
                                 onload="this.style.opacity='1'"
                                 onerror="this.style.opacity='0'">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Info Side -->
                <div class="fs-info-side">
                    <div class="fs-icon-badge" style="background: <?= htmlspecialchars($f['color']) ?>15; color: <?= htmlspecialchars($f['color']) ?>; border: 1px solid <?= htmlspecialchars($f['color']) ?>30;">
                        <i class="fas <?= htmlspecialchars($f['icon']) ?>"></i>
                    </div>
                    <h3 class="fs-name"><?= htmlspecialchars($f['nama']) ?></h3>
                    <p class="fs-desc"><?= htmlspecialchars($f['deskripsi']) ?></p>
                    <div class="fs-divider" style="background: linear-gradient(to right, <?= htmlspecialchars($f['color']) ?>, transparent)"></div>
                    <div class="fs-feature-list">
                        <span><i class="fas fa-check-circle" style="color:<?= htmlspecialchars($f['color']) ?>"></i> Fasilitas Lengkap</span>
                        <span><i class="fas fa-check-circle" style="color:<?= htmlspecialchars($f['color']) ?>"></i> Terawat & Bersih</span>
                        <span><i class="fas fa-check-circle" style="color:<?= htmlspecialchars($f['color']) ?>"></i> Akses 24/7</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <!-- Empty state when no fasilitas data -->
            <div style="text-align:center;padding:4rem 2rem;color:#94a3b8">
                <i class="fas fa-building" style="font-size:4rem;margin-bottom:1.5rem;opacity:0.3;display:block"></i>
                <h3 style="font-size:1.4rem;font-weight:700;color:var(--dark);margin-bottom:0.75rem">Data Fasilitas Belum Tersedia</h3>
                <p style="font-size:0.95rem;max-width:500px;margin:0 auto">Fasilitas sekolah sedang dalam proses pembaruan. Silakan kunjungi kembali nanti.</p>
            </div>
            <?php endif; ?>
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