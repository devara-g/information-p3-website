<?php include 'header.php'; ?>

<section class="structure-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
    </div>
    <h1>Guru & Staff Pengajar</h1>
    <p>Tenaga Pendidik SMP PGRI 3 BOGOR</p>
    <?php include 'wave.php'; ?>
</section>

<section class="guru-content">
    <?php
    // Koneksi ke database
    include '../database/conn.php';

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari database, urut berdasarkan category dan sort_order
    $query = "SELECT * FROM teachers ORDER BY 
        FIELD(category, '7', '8', '9', 'mapel'),
        sort_order ASC";
    $result = $conn->query($query);

    // Check if there are any teachers
    if ($result->num_rows === 0) {
        echo '
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h3>Data Guru Belum Tersedia</h3>
            <p>Mohon maaf, data guru dan staff pengajar sedang dalam proses pembaharuan. Silakan periksa kembali nanti.</p>
            <a href="../index.php" class="empty-state-btn">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
        ';
    } else {
        // Grouping data berdasarkan category
        $guru = [];
        while ($row = $result->fetch_assoc()) {
            $guru[$row['category']][] = $row;
        }

        // Mapping category ke nama display dengan icon
        $categoryInfo = [
            '7' => ['name' => 'Wali Kelas 7', 'icon' => 'fa-user-graduate', 'color' => '#e74c3c'],
            '8' => ['name' => 'Wali Kelas 8', 'icon' => 'fa-user-graduate', 'color' => '#3498db'],
            '9' => ['name' => 'Wali Kelas 9', 'icon' => 'fa-user-graduate', 'color' => '#2ecc71'],
            'mapel' => ['name' => 'Guru Mata Pelajaran', 'icon' => 'fa-chalkboard-teacher', 'color' => '#9b59b6']
        ];

        // Loop setiap kategori
        foreach ($categoryInfo as $category => $info) {
            if (isset($guru[$category])) {
                echo '
                <div class="guru-section">
                    <div class="section-header">
                        <div class="header-icon" style="background: ' . $info['color'] . '">
                            <i class="fas ' . $info['icon'] . '"></i>
                        </div>
                        <h2>' . $info['name'] . '</h2>
                        <span class="teacher-count">' . count($guru[$category]) . ' Orang</span>
                    </div>
                    <div class="guru-grid">
                ';

                foreach ($guru[$category] as $guruData) {
                    // Handle photo - use default if not exists
                    $photoPath = '../upload/img/' . $guruData['photo_filename'];
                    $defaultPhoto = 'https://ui-avatars.com/api/?name=' . urlencode($guruData['name']) . '&background=random&color=fff&size=200';
                    $photoSrc = file_exists($photoPath) ? $photoPath : $defaultPhoto;

                    echo '
                        <div class="guru-card">
                            <div class="card-image">
                                <img src="' . $photoSrc . '" alt="' . htmlspecialchars($guruData['name']) . '" onerror="this.src=\'' . $defaultPhoto . '\'">
                                <div class="card-overlay">
                                    <a href="mailto:' . (isset($guruData['email']) ? $guruData['email'] : '') . '" class="contact-btn" title="Kirim Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-info">
                                <h3 class="guru-name">' . htmlspecialchars($guruData['name']) . '</h3>
                                <p class="guru-nip">
                                    <i class="fas fa-id-card"></i>
                                    ' . (!empty($guruData['nip']) ? htmlspecialchars($guruData['nip']) : '-') . '
                                </p>
                                ' . (isset($guruData['subject']) && !empty($guruData['subject']) ? '
                                <p class="guru-mapel">
                                    <i class="fas fa-book"></i>
                                    ' . htmlspecialchars($guruData['subject']) . '
                                </p>
                                ' : '') . '
                            </div>
                        </div>
                    ';
                }

                echo '
                    </div>
                </div>
                ';
            }
        }
    }

    $conn->close();
    ?>
</section>
<?php include 'footer.php'; ?>

