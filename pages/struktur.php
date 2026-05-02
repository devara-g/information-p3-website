<?php
include 'header.php';
include '../database/conn.php';

// Fetch data from kepsek table
$result = $conn->query("SELECT * FROM kepsek ORDER BY FIELD(position, 'kepsek dan wakasek', 'wakil ketua', 'sekre', 'tata usaha'), id ASC");

// Define position options (Labels)
$positionOptions = [
    'kepsek dan wakasek' => 'Kepala Sekolah & Wakasek',
    'wakil ketua'        => 'Wakil Ketua',
    'sekre'              => 'Sekretaris',
    'tata usaha'         => 'Tata Usaha',
];

// Initialize groupings based on NEW enum
$kepsekData = [
    'kepsek dan wakasek' => [],
    'wakil ketua'        => [],
    'sekre'              => [],
    'tata usaha'         => []
];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (isset($kepsekData[$row['position']])) {
            $kepsekData[$row['position']][] = $row;
        }
    }
}

// Section configuration
$sections = [
    'pimpinan' => [
        'title' => 'Pimpinan Sekolah', 
        'positions' => ['kepsek dan wakasek', 'wakil ketua'], 
        'icon' => 'fa-user-tie', 
        'color' => '#e74c3c'
    ],
    'sekretariat' => [
        'title' => 'Sekretariat', 
        'positions' => ['sekre'], 
        'icon' => 'fa-user-pen', 
        'color' => '#9b59b6'
    ],
    'tatausaha' => [
        'title' => 'Tata Usaha', 
        'positions' => ['tata usaha'], 
        'icon' => 'fa-users-cog', 
        'color' => '#3498db'
    ]
];

// Get total count for each section
function getSectionCount(array $data, array $positions): int
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
    <h1>Struktur Organisasi</h1>
    <p>Kepala Sekolah & Tata Usaha SMP PGRI 3 BOGOR</p>
    <?php include 'wave.php'; ?>
</section>

<section class="guru-content">
    <?php
    $hasData = false;
    foreach ($sections as $sectionKey => $section):
        $sectionCount = getSectionCount($kepsekData, $section['positions']);
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
                <?php foreach ($section['positions'] as $pos): ?>
                    <?php if (isset($kepsekData[$pos]) && count($kepsekData[$pos]) > 0): ?>
                        <?php foreach ($kepsekData[$pos] as $member): ?>
                            <?php
                            $photoPath = !empty($member['photo_filename']) ? '../upload/img/' . $member['photo_filename'] : '';
                            $defaultPhoto = 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=random&color=fff&size=200';
                            $photoSrc = !empty($photoPath) && file_exists($photoPath) ? $photoPath : $defaultPhoto;
                            ?>
                            <div class="guru-card">
                                <div class="card-image">
                                    <img src="<?php echo $photoSrc; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" onerror="this.src='<?php echo $defaultPhoto; ?>'">
                                    <div class="card-overlay">
                                        <a href="mailto:<?php echo isset($member['email']) ? $member['email'] : ''; ?>" class="contact-btn" title="Kirim Email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h3 class="guru-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                                    <p class="guru-nip">
                                        <i class="fas fa-id-card"></i>
                                        <?php echo !empty($member['nip']) ? htmlspecialchars($member['nip']) : '-'; ?>
                                    </p>
                                    <p class="guru-mapel">
                                        <i class="fas fa-briefcase"></i>
                                        <?php echo isset($positionOptions[$member['position']]) ? htmlspecialchars($positionOptions[$member['position']]) : htmlspecialchars($member['position']); ?>
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
            <i class="fas fa-folder-open"></i>
            <h3>Data Belum Tersedia</h3>
            <p>Data struktur organisasi belum tersedia.</p>
        </div>
    <?php endif; ?>
</section>

<?php include 'footer.php'; ?>

