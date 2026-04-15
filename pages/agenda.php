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
    <h1>Agenda Sekolah</h1>
    <p>Jadwal kegiatan dan acara SMP PGRI 3 BOGOR</p>
    <?php include 'wave.php'; ?>
</section>

<?php
include '../database/conn.php';

// Get all agenda sorted by date ASC (upcoming first)
$all = mysqli_query($conn, "SELECT * FROM agenda ORDER BY tanggal ASC");
$agenda_list = [];
while ($row = mysqli_fetch_assoc($all)) {
    $agenda_list[] = $row;
}

// Separate upcoming vs past
$today = date('Y-m-d');
$upcoming = [];
$past = [];
foreach ($agenda_list as $a) {
    if ($a['tanggal'] >= $today) {
        $upcoming[] = $a;
    } else {
        $past[] = $a;
    }
}
$all_sorted = array_merge($upcoming, array_reverse($past));

function agImg($row) {
    if (!empty($row['foto']) && file_exists('../' . $row['foto'])) return '../' . $row['foto'];
    return null;
}
?>

<?php if (!empty($all_sorted)): ?>

<!-- ======= SPOTLIGHT: NEXT UPCOMING EVENT ======= -->
<?php if (!empty($upcoming)): $spot = $upcoming[0]; $spotImg = agImg($spot); ?>
<section class="ag-spotlight">
    <div class="ag-wrap">
        <div class="ag-spot-inner">
            <div class="ag-spot-date-block">
                <span class="ag-spot-day"><?= date('d', strtotime($spot['tanggal'])) ?></span>
                <span class="ag-spot-month"><?= strtoupper(date('M', strtotime($spot['tanggal']))) ?></span>
                <span class="ag-spot-year"><?= date('Y', strtotime($spot['tanggal'])) ?></span>
            </div>
            <div class="ag-spot-info">
                <span class="ag-spot-label"><i class="fas fa-bolt"></i> Acara Berikutnya</span>
                <h2 class="ag-spot-title"><?= htmlspecialchars($spot['judul']) ?></h2>
                <div class="ag-spot-meta">
                    <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($spot['lokasi'] ?? 'Sekolah') ?></span>
                    <?php if (!empty($spot['waktu'])): ?>
                    <span><i class="fas fa-clock"></i> <?= htmlspecialchars($spot['waktu']) ?></span>
                    <?php endif; ?>
                </div>
                <p class="ag-spot-desc"><?= htmlspecialchars(substr(strip_tags($spot['deskripsi']), 0, 200)) ?>...</p>
            </div>
            <?php if ($spotImg): ?>
            <div class="ag-spot-img">
                <img src="<?= $spotImg ?>" alt="<?= htmlspecialchars($spot['judul']) ?>">
                <div class="ag-spot-img-overlay"></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ======= AGENDA GRID ======= -->
<section class="ag-main">
    <div class="ag-wrap">
        <div class="ag-section-header">
            <div>
                <h2>Semua Agenda</h2>
                <p><?= count($all_sorted) ?> kegiatan terjadwal</p>
            </div>
            <div class="ag-legend">
                <span class="ag-legend-item upcoming"><i class="fas fa-circle"></i> Akan Datang</span>
                <span class="ag-legend-item past"><i class="fas fa-circle"></i> Sudah Berlalu</span>
            </div>
        </div>

        <div class="ag-grid">
            <?php foreach ($all_sorted as $i => $a):
                $img = agImg($a);
                $isPast = ($a['tanggal'] < $today);
                $isUpcoming = !$isPast;
            ?>
            <div class="ag-card <?= $isPast ? 'ag-past' : 'ag-upcoming' ?>">
                <!-- Date Chip -->
                <div class="ag-date-chip">
                    <span class="ag-chip-day"><?= date('d', strtotime($a['tanggal'])) ?></span>
                    <span class="ag-chip-month"><?= strtoupper(date('M Y', strtotime($a['tanggal']))) ?></span>
                </div>

                <!-- Image -->
                <?php if ($img): ?>
                <div class="ag-card-img">
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($a['judul']) ?>">
                    <?php if ($isPast): ?>
                    <div class="ag-card-past-badge">Selesai</div>
                    <?php else: ?>
                    <div class="ag-card-upcoming-badge">Akan Datang</div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="ag-card-img ag-card-img-ph">
                    <div class="ag-ph-icon"><i class="fas fa-calendar-check"></i></div>
                    <?php if ($isPast): ?>
                    <div class="ag-card-past-badge">Selesai</div>
                    <?php else: ?>
                    <div class="ag-card-upcoming-badge">Akan Datang</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Info -->
                <div class="ag-card-body">
                    <h3 class="ag-card-title"><?= htmlspecialchars($a['judul']) ?></h3>
                    <div class="ag-card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($a['lokasi'] ?? 'Sekolah') ?></span>
                        <?php if (!empty($a['waktu'])): ?>
                        <span><i class="fas fa-clock"></i> <?= htmlspecialchars($a['waktu']) ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="ag-card-desc"><?= htmlspecialchars(substr(strip_tags($a['deskripsi']), 0, 120)) ?>...</p>
                </div>

                <!-- Status Bar -->
                <div class="ag-card-footer">
                    <?php if ($isUpcoming): ?>
                        <span class="ag-days-badge">
                            <?php
                            $diff = (new DateTime($a['tanggal']))->diff(new DateTime($today));
                            $days = $diff->days;
                            if ($days == 0) echo '<i class="fas fa-star"></i> Hari Ini!';
                            elseif ($days == 1) echo '<i class="fas fa-clock"></i> Besok';
                            else echo "<i class='fas fa-hourglass-half'></i> {$days} hari lagi";
                            ?>
                        </span>
                    <?php else: ?>
                        <span class="ag-passed-badge"><i class="fas fa-check-circle"></i> Telah Berlangsung</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php else: ?>
<section style="padding:5rem 2rem; max-width:1200px; margin:0 auto;">
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
        <h2>Belum Ada Agenda</h2>
        <p>Saat ini belum ada kegiatan yang dijadwalkan. Silakan periksa kembali nanti.</p>
        <a href="../index.php" class="empty-state-btn"><i class="fas fa-home"></i> Kembali ke Beranda</a>
    </div>
</section>
<?php endif; ?>

<?php include 'footer.php'; ?>
