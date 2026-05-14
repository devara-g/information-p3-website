<?php
include '../database/conn.php';
include 'header.php';

// Fetch data from database
$visi = null;
$r = $conn->query("SELECT * FROM visi ORDER BY id LIMIT 1");
if ($r && $r->num_rows > 0) $visi = $r->fetch_assoc();

$misi_list = $conn->query("SELECT * FROM misi ORDER BY sort_order ASC");
$misi_first = null;
$r2 = $conn->query("SELECT badge_text, badge_icon, judul_section, deskripsi_section FROM misi ORDER BY id LIMIT 1");
if ($r2 && $r2->num_rows > 0) $misi_first = $r2->fetch_assoc();

$k7_list = $conn->query("SELECT * FROM program_7k ORDER BY sort_order ASC");
$k7_first = null;
$r3 = $conn->query("SELECT badge_text, badge_icon, judul_section, deskripsi_section FROM program_7k ORDER BY id LIMIT 1");
if ($r3 && $r3->num_rows > 0) $k7_first = $r3->fetch_assoc();

$animClasses = ['vm-slide-l','vm-slide-r','vm-slide-l','vm-slide-r','vm-slide-l','vm-slide-r','vm-fade-up'];
$delays = ['vm-d1','vm-d1','vm-d2','vm-d2','vm-d3','vm-d3','vm-d4'];
?>

<section class="about-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
    </div>
    <h1>Visi &amp; Misi</h1>
    <p>SMP PGRI 3 BOGOR</p>
    <?php include 'wave.php'; ?>
</section>

<!-- ============ VISI ============ -->
<section class="vm-visi-section">
    <div class="vm-wrap">
        <div class="vm-label-top vm-animate vm-fade-up">
            <span class="vm-badge"><i class="<?= htmlspecialchars($visi['badge_icon'] ?? 'fas fa-eye') ?>"></i> <?= htmlspecialchars($visi['badge_text'] ?? 'Visi Sekolah') ?></span>
            <h2><?= htmlspecialchars($visi['judul_section'] ?? 'Visi Kami') ?></h2>
            <p><?= htmlspecialchars($visi['deskripsi_section'] ?? 'Arah dan cita-cita besar SMP PGRI 3 BOGOR') ?></p>
        </div>
        <div class="vm-visi-card vm-animate vm-visi-reveal vm-d2">
            <div class="vm-visi-deco vm-deco-tl"><i class="fas fa-quote-left"></i></div>
            <p class="vm-visi-text">
                <?= htmlspecialchars($visi['isi_visi'] ?? 'Visi belum diatur.') ?>
            </p>
            <div class="vm-visi-deco vm-deco-br"><i class="fas fa-quote-right"></i></div>
        </div>
    </div>
</section>

<!-- ============ MISI ============ -->
<section class="vm-misi-section">
    <div class="vm-wrap">
        <div class="vm-label-top vm-animate vm-fade-up">
            <span class="vm-badge misi-badge"><i class="<?= htmlspecialchars($misi_first['badge_icon'] ?? 'fas fa-bullseye') ?>"></i> <?= htmlspecialchars($misi_first['badge_text'] ?? 'Misi Sekolah') ?></span>
            <h2><?= htmlspecialchars($misi_first['judul_section'] ?? '7 Misi Utama') ?></h2>
            <p><?= htmlspecialchars($misi_first['deskripsi_section'] ?? 'Langkah nyata mewujudkan visi sekolah') ?></p>
        </div>
        <div class="vm-misi-grid">
<?php
$i = 0;
if ($misi_list && $misi_list->num_rows > 0):
    $total = $misi_list->num_rows;
    while ($m = $misi_list->fetch_assoc()):
        $anim = $animClasses[$i] ?? 'vm-fade-up';
        $delay = $delays[$i] ?? 'vm-d4';
        $isLast = ($i == $total - 1 && $total % 2 == 1);
        $fullClass = $isLast ? ' vm-misi-card-full' : '';
?>
            <div class="vm-misi-card<?= $fullClass ?> vm-animate <?= $anim ?> <?= $delay ?>">
                <div class="vm-misi-num-wrap">
                    <div class="vm-misi-num"><?= (int)$m['nomor'] ?></div>
                    <div class="vm-misi-iconbox" style="background:<?= htmlspecialchars($m['icon_bg']) ?>; color:<?= htmlspecialchars($m['icon_color']) ?>">
                        <i class="<?= htmlspecialchars($m['icon']) ?>"></i>
                    </div>
                </div>
                <div class="vm-misi-body">
                    <h4><?= htmlspecialchars($m['judul']) ?></h4>
                    <p><?= htmlspecialchars($m['deskripsi']) ?></p>
                </div>
            </div>
<?php
        $i++;
    endwhile;
endif;
?>
        </div>
    </div>
</section>

<!-- ============ 7K ============ -->
<section class="vm-7k-section">
    <div class="vm-wrap">
        <div class="vm-label-top vm-animate vm-fade-up">
            <span class="vm-badge k7-badge"><i class="<?= htmlspecialchars($k7_first['badge_icon'] ?? 'fas fa-star') ?>"></i> <?= htmlspecialchars($k7_first['badge_text'] ?? 'Program 7K') ?></span>
            <h2><?= htmlspecialchars($k7_first['judul_section'] ?? '7K Sekolah') ?></h2>
            <p><?= htmlspecialchars($k7_first['deskripsi_section'] ?? 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif') ?></p>
        </div>
        <div class="vm-7k-grid">
<?php
$j = 0;
if ($k7_list && $k7_list->num_rows > 0):
    while ($k = $k7_list->fetch_assoc()):
        $d7 = 'vm-d' . ($j + 1);
?>
            <div class="vm-7k-card vm-animate vm-pop <?= $d7 ?>" style="--k-color:<?= htmlspecialchars($k['warna']) ?>">
                <div class="vm-7k-icon"><?= $k['icon'] ?></div>
                <div class="vm-7k-num"><?= (int)$k['nomor'] ?></div>
                <div class="vm-7k-label"><?= $k['nama'] ?></div>
            </div>
<?php
        $j++;
    endwhile;
endif;
?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
(function () {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('vm-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px'
    });
    document.querySelectorAll('.vm-animate').forEach(el => {
        observer.observe(el);
    });
})();
</script>
