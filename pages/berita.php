<?php include 'header.php'; ?>

<section class="news-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
    </div>
    <h1>Berita Terbaru</h1>
    <p>Informasi terkini dari SMP PGRI 3 BOGOR</p>
    <?php include 'wave.php'; ?>
</section>

<?php
include '../database/conn.php';

$sql_all = "SELECT * FROM berita ORDER BY id DESC LIMIT 10";
$result_all = mysqli_query($conn, $sql_all);
$all_berita = [];
while ($row = mysqli_fetch_assoc($result_all)) {
    $all_berita[] = $row;
}

function getImgPath(array $row): ?string {
    if (!empty($row['foto']) && file_exists('../' . $row['foto'])) {
        return '../' . $row['foto'];
    }
    return null;
}

function getBadgeClass(string $cat): string {
    switch (strtolower(trim($cat))) {
        case 'prestasi':      return 'nb-bdg-prestasi';
        case 'kegiatan':      return 'nb-bdg-kegiatan';
        case 'pengumuman':    return 'nb-bdg-pengumuman';
        case 'akademik':      return 'nb-bdg-akademik';
        case 'ekstrakurikuler': return 'nb-bdg-ekstra';
        case 'sorotan khusus': return 'nb-bdg-sorotan';
        default:              return 'nb-bdg-default';
    }
}
?>

<?php if (!empty($all_berita)): ?>

<!-- ========== FEATURED HERO ARTICLE ========== -->
<?php $hero = $all_berita[0]; $heroImg = getImgPath($hero); ?>
<section class="nb-hero-feature">
    <div class="nb-wrap">
        <div class="nb-hero-top">
            <div class="nb-hero-left">
                <span class="nb-hero-label">⚡ Berita Utama</span>
                <h2 class="nb-hero-title"><?= htmlspecialchars($hero['judul']) ?></h2>
                <div class="nb-hero-meta">
                    <span><i class="fas fa-calendar-alt"></i> <?= date('d M Y', strtotime($hero['tanggal'])) ?></span>
                    <span><i class="fas fa-user"></i> Admin Sekolah</span>
                </div>
            </div>
            <div class="nb-hero-right">
                <p><?= htmlspecialchars(substr(strip_tags($hero['deskripsi']), 0, 240)) ?>...</p>
            </div>
        </div>

        <a href="detail-berita.php?id=<?= $hero['id'] ?>" class="nb-hero-img-wrap">
            <?php if ($heroImg): ?>
                <img src="<?= $heroImg ?>" alt="<?= htmlspecialchars($hero['judul']) ?>" class="nb-hero-img">
            <?php else: ?>
                <div class="nb-hero-img-ph"><i class="fas fa-newspaper"></i></div>
            <?php endif; ?>
            <div class="nb-hero-overlay"></div>
            <div class="nb-hero-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
    </div>
</section>

<!-- ========== FILTER BAR ========== -->
<div class="nb-filter-bar">
    <div class="nb-wrap">
        <div class="nb-filter-inner">
            <div class="nb-tabs" id="nbTabs">
                <button class="nb-tab active" data-cat="semua">Semua</button>
                <button class="nb-tab" data-cat="akademik">Akademik</button>
                <button class="nb-tab" data-cat="prestasi">Prestasi</button>
                <button class="nb-tab" data-cat="kegiatan">Kegiatan</button>
                <button class="nb-tab" data-cat="pengumuman">Pengumuman</button>
            </div>
            <div class="nb-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="nbSearch" placeholder="Cari berita, artikel...">
            </div>
        </div>
    </div>
</div>

<!-- ========== NEWS GRID ========== -->
<section class="nb-grid-section">
    <div class="nb-wrap">
        <div class="nb-grid-header">
            <h2>Kabar Terbaru</h2>
            <a href="berita.php" class="nb-view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>

        <?php $remaining = array_slice($all_berita, 1); ?>

        <?php if (!empty($remaining)): ?>
        <div class="nb-grid" id="nbGrid">
            <?php foreach ($remaining as $article):
                $img     = getImgPath($article);
                $cat     = strtolower(trim($article['kategori'] ?? ''));
                $isFeat  = ($cat === 'sorotan khusus');
                $bdgCls  = getBadgeClass($article['kategori']);
            ?>

            <?php if ($isFeat): ?>
            <!-- ─── FEATURED WIDE CARD ─── -->
            <div class="nb-card nb-card-feat" data-cat="<?= htmlspecialchars($cat) ?>">
                <div class="nb-fc-text">
                    <span class="nb-card-badge <?= $bdgCls ?>">Sorotan Khusus</span>
                    <h3><?= htmlspecialchars($article['judul']) ?></h3>
                    <p><?= htmlspecialchars(substr(strip_tags($article['deskripsi']), 0, 200)) ?>...</p>
                    <a href="detail-berita.php?id=<?= $article['id'] ?>" class="nb-fc-btn">
                        Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <?php if ($img): ?>
                <div class="nb-fc-img">
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($article['judul']) ?>">
                </div>
                <?php endif; ?>
            </div>

            <?php else: ?>
            <!-- ─── STANDARD CARD ─── -->
            <a href="detail-berita.php?id=<?= $article['id'] ?>" class="nb-card nb-card-std" data-cat="<?= htmlspecialchars($cat) ?>">
                <div class="nb-sc-img-wrap">
                    <?php if ($img): ?>
                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($article['judul']) ?>" class="nb-sc-img">
                    <?php else: ?>
                        <div class="nb-sc-img-ph"><i class="fas fa-newspaper"></i></div>
                    <?php endif; ?>
                    <span class="nb-badge-overlay <?= $bdgCls ?>"><?= ucfirst($article['kategori']) ?></span>
                </div>
                <div class="nb-sc-body">
                    <span class="nb-sc-date"><i class="fas fa-clock"></i> <?= date('d M Y', strtotime($article['tanggal'])) ?></span>
                    <h3 class="nb-sc-title"><?= htmlspecialchars($article['judul']) ?></h3>
                    <p class="nb-sc-excerpt"><?= htmlspecialchars(substr(strip_tags($article['deskripsi']), 0, 100)) ?>...</p>
                </div>
            </a>
            <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <!-- No results message (hidden by default) -->
        <div class="nb-no-results" id="nbNoResults" style="display:none;">
            <i class="fas fa-search"></i>
            <p>Tidak ada berita yang cocok dengan pencarian Anda.</p>
        </div>

        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-newspaper"></i></div>
            <h2>Belum Ada Berita Lainnya</h2>
            <p>Hanya ada satu artikel saat ini. Silakan periksa kembali nanti.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========== NEWSLETTER STRIP ========== -->
<section class="nb-newsletter">
    <div class="nb-wrap">
        <div class="nb-nl-inner">
            <div class="nb-nl-text">
                <h2>Jangan Lewatkan Kabar Pendidikan Kami</h2>
                <p>Dapatkan update berita, prestasi, dan pengumuman sekolah<br>langsung di email Anda setiap minggu.</p>
            </div>
            <form class="nb-nl-form" onsubmit="event.preventDefault(); this.querySelector('button').innerHTML='<i class=\'fas fa-check\'></i> Terkirim!';">
                <input type="email" placeholder="Alamat email Anda" required>
                <button type="submit">Langganan</button>
            </form>
        </div>
    </div>
</section>

<?php else: ?>
<!-- Empty State -->
<section style="padding: 5rem 2rem; max-width: 1400px; margin: 0 auto;">
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-newspaper"></i></div>
        <h2>Belum Ada Berita</h2>
        <p>Saat ini belum ada berita yang dipublikasikan. Silakan kembali lagi nanti untuk mendapatkan informasi terbaru dari SMP PGRI 3 BOGOR.</p>
        <a href="../index.php" class="empty-state-btn"><i class="fas fa-home"></i> Kembali ke Beranda</a>
    </div>
</section>
<?php endif; ?>

<?php include 'footer.php'; ?>

<script>
(function () {
    const tabs   = document.querySelectorAll('.nb-tab');
    const search = document.getElementById('nbSearch');
    const grid   = document.getElementById('nbGrid');
    const noRes  = document.getElementById('nbNoResults');

    if (!grid) return;

    function filterCards() {
        const activeCat = (document.querySelector('.nb-tab.active') || {}).dataset?.cat || 'semua';
        const term = search ? search.value.toLowerCase().trim() : '';
        const cards = grid.querySelectorAll('.nb-card');
        let visible = 0;

        cards.forEach(card => {
            const cardCat = (card.dataset.cat || '').toLowerCase();
            const title   = (card.querySelector('h3')?.textContent || '').toLowerCase();
            const excerpt = (card.querySelector('p')?.textContent  || '').toLowerCase();

            const catOk    = activeCat === 'semua' || cardCat === activeCat || cardCat.includes(activeCat);
            const searchOk = !term || title.includes(term) || excerpt.includes(term);
            const show     = catOk && searchOk;

            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noRes) noRes.style.display = visible === 0 ? 'flex' : 'none';
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            filterCards();
        });
    });

    if (search) search.addEventListener('input', filterCards);
})();
</script>
