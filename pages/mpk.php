<?php
include 'header.php';
include '../database/conn.php';

// Fetch data from mpk table
$result = $conn->query("SELECT * FROM mpk ORDER BY FIELD(category, 'ketua', 'waket', 'sekretaris', 'bendahara', 'anggota'), sort_order ASC");

// Group data by category
$mpkData = [
    'ketua' => [],
    'waket' => [],
    'sekretaris' => [],
    'bendahara' => [],
    'anggota' => []
];

while ($row = $result->fetch_assoc()) {
    $mpkData[$row['category']][] = $row;
}

// Section configuration
$sections = [
    'ketua' => ['title' => 'Ketua & Wakil Ketua MPK', 'positions' => ['ketua', 'waket'], 'icon' => 'fa-crown', 'color' => '#e74c3c'],
    'sekretaris' => ['title' => 'Sekretaris', 'positions' => ['sekretaris'], 'icon' => 'fa-clipboard', 'color' => '#9b59b6'],
    'bendahara' => ['title' => 'Bendahara', 'positions' => ['bendahara'], 'icon' => 'fa-wallet', 'color' => '#2ecc71'],
    'anggota' => ['title' => 'Anggota MPK', 'positions' => ['anggota'], 'icon' => 'fa-vote-yea', 'color' => '#3498db']
];

// Get total count for each section
function getSectionCount($data, $positions)
{
    $count = 0;
    foreach ($positions as $pos) {
        if (isset($data[$pos])) {
            $count += count($data[$pos]);
        }
    }
    return $count;
}
?>

<section class="structure-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
    </div>
    <h1>Majelis Perwakilan Kelas (MPK)</h1>
    <p>Kepengurusan MPK SMP PGRI 3 BOGOR Periode 2025/2026</p>
    <?php include 'wave.php'; ?>
</section>

<section class="guru-content">
    <?php
    $hasData = false;
    foreach ($sections as $sectionKey => $section):
        $sectionCount = getSectionCount($mpkData, $section['positions']);
        if ($sectionCount == 0) continue;
        $hasData = true;
    ?>
        <div class="guru-section">
            <div class="section-header">
                <div class="header-icon" style="background: <?php echo $section['color']; ?>">
                    <i class="fas <?php echo $section['icon']; ?>"></i>
                </div>
                <h2><?php echo $section['title']; ?></h2>
                <span class="teacher-count"><?php echo $sectionCount; ?> Orang</span>
            </div>
            <div class="guru-grid">
                <?php foreach ($section['positions'] as $cat): ?>
                    <?php if (isset($mpkData[$cat]) && count($mpkData[$cat]) > 0): ?>
                        <?php foreach ($mpkData[$cat] as $member): ?>
                            <?php
                            $photoPath = !empty($member['photo_filename']) ? '../upload/img/' . $member['photo_filename'] : '';
                            $defaultPhoto = 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=random&color=fff&size=200';
                            $photoSrc = !empty($photoPath) && file_exists($photoPath) ? $photoPath : $defaultPhoto;
                            ?>
                            <div class="guru-card">
                                <div class="card-image">
                                    <img src="<?php echo $photoSrc; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" onerror="this.src='<?php echo $defaultPhoto; ?>'">
                                    <div class="card-overlay">
                                        <a href="mailto:" class="contact-btn" title="Kirim Email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h3 class="guru-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                    <p class="guru-nip">
                                        <i class="fas fa-briefcase"></i>
                                        <?php echo htmlspecialchars($member['position']); ?>
                                    </p>
                                    <p class="guru-mapel">
                                        <i class="fas fa-school"></i>
                                        Kelas <?php echo htmlspecialchars($member['kelas']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (!$hasData): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <h3>Data MPK Belum Tersedia</h3>
            <p>Mohon maaf, data kepengurusan MPK sedang dalam proses pembaharuan. Silakan kembali lagi nanti.</p>
            <a href="../index.php" class="empty-state-btn">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    <?php endif; ?>
</section>

<?php include 'footer.php'; ?>

