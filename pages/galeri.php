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
    <p>Dokumentasi momen berharga SMP PGRI 3 BOGOR</p>
    <?php include 'wave.php'; ?>
</section>

<?php
include '../database/conn.php';

$cat_meta = [
    'Kegiatan Sekolah' => ['icon' => 'fa-school',  'color' => '#3b82f6', 'bg' => '#eff6ff'],
    'Prestasi Siswa'   => ['icon' => 'fa-trophy',  'color' => '#f59e0b', 'bg' => '#fefce8'],
    'Kegiatan Ekskul'  => ['icon' => 'fa-futbol',  'color' => '#10b981', 'bg' => '#f0fdf4'],
];

// Fetch all photos
$all_photos = [];
foreach ($cat_meta as $cat => $m) {
    $q = mysqli_query($conn, "SELECT * FROM galeri WHERE kategori = '" . mysqli_real_escape_string($conn, $cat) . "' ORDER BY id DESC");
    while ($row = mysqli_fetch_assoc($q)) {
        $row['_cat_meta'] = $m;
        $all_photos[] = $row;
    }
}

// Counts
$counts = ['all' => 0];
foreach ($cat_meta as $cat => $m) {
    $r = mysqli_query($conn, "SELECT COUNT(*) AS c FROM galeri WHERE kategori = '" . mysqli_real_escape_string($conn, $cat) . "'");
    $n = (int) mysqli_fetch_assoc($r)['c'];
    $counts[$cat] = $n;
    $counts['all'] += $n;
}
?>

<!-- ════ STICKY FILTER + CONTROLS ════ -->
<div class="gl-filter-bar">
    <div class="gl-wrap">
        <div class="gl-filter-inner">
            <div class="gl-tabs" id="glTabs">
                <button class="gl-tab active" data-filter="all">
                    <i class="fas fa-images"></i> Semua
                    <span class="gl-count"><?= $counts['all'] ?></span>
                </button>
                <?php foreach ($cat_meta as $cat => $m): ?>
                <button class="gl-tab" data-filter="<?= htmlspecialchars($cat) ?>">
                    <i class="fas <?= $m['icon'] ?>"></i> <?= $cat ?>
                    <span class="gl-count"><?= $counts[$cat] ?></span>
                </button>
                <?php endforeach; ?>
            </div>
            <div class="gl-controls">
                <div class="gl-view-toggle">
                    <button class="gl-view-btn active" id="glViewMasonry" title="Masonry">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="gl-view-btn" id="glViewGrid" title="Grid Rata">
                        <i class="fas fa-th"></i>
                    </button>
                </div>
                <span class="gl-showing" id="glShowing"><?= $counts['all'] ?> foto</span>
            </div>
        </div>
    </div>
</div>

<!-- ════ PHOTO GRID ════ -->
<section class="gl-main">
    <div class="gl-wrap">

        <?php if (!empty($all_photos)): ?>
        <div class="gl-masonry" id="glGrid">
            <?php
            $idx = 0;
            foreach ($all_photos as $photo):
                $img_src = (!empty($photo['foto']) && file_exists('../' . $photo['foto']))
                    ? '../' . $photo['foto']
                    : 'https://placehold.co/600x400/e2e8f0/475569?text=Tidak+Ada+Foto';
                

                $cat     = htmlspecialchars($photo['kategori']);
                $m       = $photo['_cat_meta'];
                $judul   = htmlspecialchars($photo['judul']);
                $desc    = htmlspecialchars($photo['deskripsi'] ?? '');
                // Vary heights: every 5th & 7th item in view is "tall"
                $tall    = ($idx % 5 === 0 || $idx % 7 === 0) ? 'gl-item-tall' : '';
                $idx++;
            ?>
            <div class="gl-item <?= $tall ?>"
                 data-cat="<?= $cat ?>"
                 data-src="<?= $img_src ?>"
                 data-title="<?= $judul ?>"
                 data-desc="<?= $desc ?>"
                 data-color="<?= $m['color'] ?>"
                 onclick="glOpen(this)">

                <img src="<?= $img_src ?>" alt="<?= $judul ?>" loading="lazy">

                <!-- Overlay on hover -->
                <div class="gl-overlay">
                    <div class="gl-overlay-top">
                        <span class="gl-cat-pill"
                              style="background:<?= $m['color'] ?>20;color:<?= $m['color'] ?>;border:1px solid <?= $m['color'] ?>50">
                            <i class="fas <?= $m['icon'] ?>"></i> <?= $cat ?>
                        </span>
                        <button class="gl-zoom-icon" onclick="glOpen(this.closest('.gl-item'))">
                            <i class="fas fa-expand-alt"></i>
                        </button>
                    </div>
                    <div class="gl-overlay-bot">
                        <p class="gl-item-title"><?= $judul ?></p>
                        <?php if ($desc): ?>
                        <p class="gl-item-desc"><?= mb_substr($desc, 0, 60) ?>...</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="gl-empty-state">
            <div class="gl-empty-icon"><i class="fas fa-images"></i></div>
            <h3>Galeri Masih Kosong</h3>
            <p>Dokumentasi kegiatan sekolah akan segera ditampilkan di sini.</p>
            <a href="../index.php" class="gl-empty-btn"><i class="fas fa-home"></i> Kembali ke Beranda</a>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- ════ LIGHTBOX MODAL ════ -->
<div class="gl-modal" id="glModal" onclick="if(event.target===this)glClose()">
    <button class="gl-modal-close" onclick="glClose()"><i class="fas fa-times"></i></button>
    <button class="gl-modal-prev" id="glPrev" onclick="glNav(-1)"><i class="fas fa-chevron-left"></i></button>
    <button class="gl-modal-next" id="glNext" onclick="glNav(1)"><i class="fas fa-chevron-right"></i></button>

    <div class="gl-modal-content">
        <div class="gl-modal-img-wrap">
            <img id="glModalImg" src="" alt="">
            <div class="gl-modal-loader"><i class="fas fa-circle-notch fa-spin"></i></div>
        </div>
        <div class="gl-modal-info">
            <span id="glModalCat" class="gl-modal-cat-label"></span>
            <h3 id="glModalTitle"></h3>
            <p id="glModalDesc"></p>
            <div class="gl-modal-counter" id="glCounter"></div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
(function () {
    let visible = [], idx = 0;

    /* ── Filter ── */
    document.querySelectorAll('.gl-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.gl-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const f = this.dataset.filter;
            let shown = 0;
            document.querySelectorAll('#glGrid .gl-item').forEach(el => {
                const show = f === 'all' || el.dataset.cat === f;
                el.style.display = show ? '' : 'none';
                if (show) shown++;
            });
            document.getElementById('glShowing').textContent = shown + ' foto';
        });
    });

    /* ── View toggle ── */
    document.getElementById('glViewMasonry').addEventListener('click', function () {
        setView(this, 'gl-masonry');
    });
    document.getElementById('glViewGrid').addEventListener('click', function () {
        setView(this, 'gl-uniform-grid');
    });

    function setView(btn, cls) {
        document.querySelectorAll('.gl-view-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('glGrid').className = cls;
    }

    /* ── Lightbox ── */
    function getVisible() {
        return [...document.querySelectorAll('#glGrid .gl-item')].filter(el => el.style.display !== 'none');
    }

    window.glOpen = function (el) {
        visible = getVisible();
        idx = visible.indexOf(el);
        render(el);
        document.getElementById('glModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.glClose = function () {
        document.getElementById('glModal').classList.remove('active');
        document.body.style.overflow = '';
    };

    window.glNav = function (dir) {
        idx = (idx + dir + visible.length) % visible.length;
        render(visible[idx]);
    };

    function render(el) {
        const img = document.getElementById('glModalImg');
        const loader = document.querySelector('.gl-modal-loader');
        img.style.opacity = '0';
        loader.style.display = 'flex';

        img.onload = () => {
            img.style.opacity = '1';
            loader.style.display = 'none';
        };

        img.src   = el.dataset.src;
        img.alt   = el.dataset.title;

        document.getElementById('glModalTitle').textContent = el.dataset.title;
        document.getElementById('glModalDesc').textContent  = el.dataset.desc || '';
        document.getElementById('glCounter').textContent    = (idx + 1) + ' / ' + visible.length;

        const catEl = document.getElementById('glModalCat');
        catEl.textContent   = el.dataset.cat;
        catEl.style.color   = el.dataset.color;
        catEl.style.borderColor = el.dataset.color + '40';
        catEl.style.background  = el.dataset.color + '15';

        // hide nav if only 1 item
        document.getElementById('glPrev').style.display = visible.length > 1 ? '' : 'none';
        document.getElementById('glNext').style.display = visible.length > 1 ? '' : 'none';
    }

    /* ── Keyboard ── */
    document.addEventListener('keydown', e => {
        const m = document.getElementById('glModal');
        if (!m.classList.contains('active')) return;
        if (e.key === 'Escape')      glClose();
        if (e.key === 'ArrowLeft')   glNav(-1);
        if (e.key === 'ArrowRight')  glNav(1);
    });

    /* ── Animate items on scroll (progressive enhancement) ── */
    const items = document.querySelectorAll('.gl-item');
    if (items.length > 0) {
        const GAP_MS = 55;

        // Mark items as "will animate" — hides them only if JS works
        items.forEach(el => el.classList.add('gl-will-animate'));

        const io = new IntersectionObserver(entries => {
            const toShow = entries.filter(e => e.isIntersecting).map(e => e.target);
            toShow.forEach((el, i) => {
                el.style.transitionDelay = (i * GAP_MS) + 'ms';
                el.classList.add('gl-visible');
                setTimeout(() => { el.style.transitionDelay = '0ms'; }, (i * GAP_MS) + 650);
                io.unobserve(el);
            });
        }, { threshold: 0.05, rootMargin: '0px 0px -20px 0px' });

        items.forEach(el => io.observe(el));
    }
})();
</script>