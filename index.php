<?php
include 'components/header.php';
include 'database/conn.php';

// Ambil data sambutan dari database
$sambutan = null;
$stmt = $conn->query("SELECT * FROM sambutan ORDER BY id DESC LIMIT 1");
if ($stmt && $stmt->num_rows > 0) {
    $sambutan = $stmt->fetch_assoc();
}

// Fallback values jika belum ada data di database
$kepsek_nama    = $sambutan ? htmlspecialchars($sambutan['nama_kepsek']) : 'Drs. Indra Robriandri, M.Si';
$kepsek_pesan   = $sambutan ? $sambutan['pesan_sambutan'] : '';
$jumlah_siswa   = $sambutan ? (int)$sambutan['jumlah_siswa'] : 800;
$jumlah_pengajar = $sambutan ? (int)$sambutan['jumlah_pengajar'] : 45;
$tanggal_sambutan = $sambutan ? $sambutan['tanggal_sambutan'] : '2026-02-01';

// Tentukan path foto
$foto_path = 'img/kepsek.jpg'; // default
if ($sambutan && $sambutan['foto_kepsek'] && file_exists('upload/img/' . $sambutan['foto_kepsek'])) {
    $foto_path = 'upload/img/' . htmlspecialchars($sambutan['foto_kepsek']);
}

// Format tanggal Indonesia
$bulan_indo = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$tgl_formatted = 'Bogor, ' . ($bulan_indo[date('m', strtotime($tanggal_sambutan))] ?? '') . ' ' . date('Y', strtotime($tanggal_sambutan));
?>

<section class="hero">

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>
    <div class="shape shape-5"></div>
    <div class="shape shape-6"></div>
    </div>
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="stagger-reveal">
                <span>Selamat Datang di</span>
                <span class="highlight">SMP PGRI 3 BOGOR</span>
            </h1>
            <p class="fade-up-delay">Membangun Generasi Cerdas, Kreatif, dan Berkarakter</p>

            <div class="principal-bubble">
                <i class="fas fa-quote-left quote-icon"></i>
                <h3>"Pendidikan adalah passport masa depan"</h3>
                <p>- <?= $kepsek_nama ?> -</p>
            </div>
        </div>

        <div class="hero-image">
            <div class="principal-photo">
                <img src="img/p3hd.jpg" alt="Logo Sekolah">
            </div>
        </div>
    </div>
    <?php include 'pages/wave.php'; ?>
</section>

<!-- ══ SAMBUTAN KEPALA SEKOLAH — PREMIUM REDESIGN (DYNAMIC) ══ -->
<section class="sm-section">
    <!-- Decorative background blobs -->
    <div class="sm-blob sm-blob-1"></div>
    <div class="sm-blob sm-blob-2"></div>

    <div class="sm-wrap">
        <!-- Section Header -->
        <div class="sm-header" id="smHeader">
            <span class="sm-eyebrow"><i class="fas fa-chalkboard-teacher"></i> Dari Pimpinan</span>
            <h2 class="sm-title">Sambutan <span>Kepala Sekolah</span></h2>
        </div>

        <div class="sm-inner">
            <!-- ── LEFT: Profile Card ── -->
            <div class="sm-profile" id="smProfile">
                <!-- Floating accent circles -->
                <div class="sm-profile-deco sm-deco-1"></div>
                <div class="sm-profile-deco sm-deco-2"></div>

                <div class="sm-photo-wrap">
                    <div class="sm-photo-ring"></div>
                    <img src="<?= $foto_path ?>" alt="Kepala Sekolah" class="sm-photo">
                    <div class="sm-photo-verified">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <div class="sm-profile-name">
                    <h3><?= $kepsek_nama ?></h3>
                    <p><i class="fas fa-graduation-cap"></i> Kepala Sekolah</p>
                </div>

                <!-- School Stats -->
                <div class="sm-stats">
                    <div class="sm-stat">
                        <span class="sm-stat-num" data-target=" 1972">0</span>
                        <span class="sm-stat-plus">+</span>
                        <span class="sm-stat-label">Tahun<br>Berdiri</span>
                    </div>
                    <div class="sm-stat-divider"></div>
                    <div class="sm-stat">
                        <span class="sm-stat-num" data-target="<?= $jumlah_siswa ?>">0</span>
                        <span class="sm-stat-plus">+</span>
                        <span class="sm-stat-label">Siswa<br>Aktif</span>
                    </div>
                    <div class="sm-stat-divider"></div>
                    <div class="sm-stat">
                        <span class="sm-stat-num" data-target="<?= $jumlah_pengajar ?>">0</span>
                        <span class="sm-stat-plus">+</span>
                        <span class="sm-stat-label">Tenaga<br>Pengajar</span>
                    </div>
                </div>

                <!-- Social badges -->
                <div class="sm-badges">
                    <span class="sm-badge-item"><i class="fas fa-award"></i> Akreditasi A</span>
                    <span class="sm-badge-item"><i class="fas fa-medal"></i> PGRI Terbaik</span>
                </div>
            </div>

            <!-- ── RIGHT: Message Panel ── -->
            <div class="sm-message" id="smMessage">
                <div class="sm-big-quote">&ldquo;</div>

                <p class="sm-greeting">Assalamu'alaikum Wr. Wb.</p>

                <div class="sm-body">
                    <?php if ($kepsek_pesan): ?>
                        <?php
                        // Pecah pesan per paragraf (baris baru)
                        $paragraphs = preg_split('/\n{1,}/', trim($kepsek_pesan));
                        foreach ($paragraphs as $p):
                            $p = trim($p);
                            if (!empty($p)):
                        ?>
                            <p><?= htmlspecialchars($p) ?></p>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    <?php else: ?>
                        <p>Segala puji bagi Allah SWT yang telah memberikan rahmat dan hidayah-Nya. Dengan penuh kebanggaan, saya menyambut Anda di website resmi <strong>SMP PGRI 3 BOGOR</strong>.</p>
                        <p>Kami berkomitmen untuk memberikan pendidikan terbaik bagi putra-putri Anda — mencetak generasi yang tidak hanya cerdas secara intelektual, tetapi juga memiliki <strong>karakter yang kuat dan berakhlak mulia</strong>.</p>
                        <p>Didukung oleh tenaga pengajar profesional dan fasilitas modern, kami yakin mampu menghasilkan lulusan yang kompeten, kreatif, dan siap menghadapi tantangan global.</p>
                        <p>Mari bersama kita wujudkan generasi yang cerdas, inovatif, dan berdaya saing tinggi untuk Indonesia yang lebih maju.</p>
                    <?php endif; ?>
                </div>

                <p class="sm-closing">Wassalamu'alaikum Wr. Wb.</p>

                <div class="sm-signature">
                    <div class="sm-sig-info">
                        <span class="sm-sig-date"><i class="fas fa-calendar-check"></i> <?= $tgl_formatted ?></span>
                        <strong class="sm-sig-name"><?= $kepsek_nama ?></strong>
                        <span class="sm-sig-pos">Kepala Sekolah SMP PGRI 3 BOGOR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // ── Sambutan: scroll reveal + counter animation ──
    (function() {
        const header = document.getElementById('smHeader');
        const profile = document.getElementById('smProfile');
        const message = document.getElementById('smMessage');

        function countUp(el) {
            const target = parseInt(el.dataset.target);
            const duration = 1800;
            const step = target / (duration / 16);
            let current = 0;
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    el.textContent = target;
                    clearInterval(timer);
                } else el.textContent = Math.floor(current);
            }, 16);
        }

        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const t = entry.target;
                t.classList.add('sm-in');
                // Trigger counters when profile card enters
                if (t === profile) {
                    setTimeout(() => {
                        document.querySelectorAll('.sm-stat-num').forEach(el => countUp(el));
                    }, 400);
                }
                io.unobserve(t);
            });
        }, {
            threshold: 0.15
        });

        [header, profile, message].forEach(el => {
            if (el) io.observe(el);
        });
    })();
</script>

<?php include 'components/footer.php'; ?>