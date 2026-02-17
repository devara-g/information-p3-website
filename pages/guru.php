<?php include 'header.php'; ?>

<section class="structure-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <h1>Guru & Staff Pengajar</h1>
    <p>Tenaga Pendidik SMP PGRI 3 BOGOR</p>
</section>

<section class="structure-content">
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

    // Grouping data berdasarkan category
    $guru = [];
    while ($row = $result->fetch_assoc()) {
        $guru[$row['category']][] = $row;
    }

    // Mapping category ke nama display
    $categoryNames = [
        '7' => 'wali kelas 7',
        '8' => 'wali kelas 8', 
        '9' => 'wali kelas 9',
        'mapel' => 'Guru Mata Pelajaran'
    ];

    // Icon mapping untuk setiap kategori (hardcoded di PHP)
    $iconMap = [
        '7' => 'fa-chalkboard-teacher',
        '8' => 'fa-chalkboard-teacher',
        '9' => 'fa-chalkboard-teacher',
        'mapel' => 'fa-chalkboard-teacher'
    ];

    // Loop setiap kategori
    foreach ($categoryNames as $category => $displayName) {
        if (isset($guru[$category])) {
            $icon = $iconMap[$category];
            echo '
            <div class="structure-section">
                <h2><i class="fas ' . $icon . '"></i> ' . $displayName . '</h2>
                <div class="structure-cards">
            ';
            
            foreach ($guru[$category] as $guruData) {
                echo '
                    <div class="structure-card">
                        <img src="../upload/img/' . $guruData['photo_filename'] . '" alt="' . $guruData['name'] . '">
                        <h3>' . $guruData['name'] . '</h3>
                        <p class="nip">' . $guruData['nip'] . '</p>
                    </div>
                ';
            }
            
            echo '
                </div>
            </div>
            ';
        }
    }

    $conn->close();
    ?>
</section>

<?php include 'footer.php'; ?>