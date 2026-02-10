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
    $jurusan = [
        [
            'nama' => 'Teknik Kendaraan Ringan',
            'guru' => [
                ['nama' => 'Ir. Joko Susilo', 'nip' => 'NIP. 197203041998031003', 'foto' => 'guru/tkr1'],
                ['nama' => 'Budi Hariyanto, S.T.', 'nip' => 'NIP. 197806152005011004', 'foto' => 'guru/tkr2'],
                ['nama' => 'Agus Setiawan', 'nip' => 'NIP. 198009122010011003', 'foto' => 'guru/tkr3'],
                ['nama' => 'Rahmat Pratama', 'nip' => 'NIP. 198505202015031003', 'foto' => 'guru/tkr4'],
            ]
        ],
        [
            'nama' => 'Teknik Sepeda Motor',
            'guru' => [
                ['nama' => 'Deni Kurniawan, S.T.', 'nip' => 'NIP. 197404102000121001', 'foto' => 'guru/tsm1'],
                ['nama' => 'Iwan Firmawan', 'nip' => 'NIP. 198112022008011002', 'foto' => 'guru/tsm2'],
                ['nama' => 'Sopian Hadi', 'nip' => 'NIP. 198807152012011002', 'foto' => 'guru/tsm3'],
            ]
        ],
        [
            'nama' => 'Teknik Instalasi Tenaga Listrik',
            'guru' => [
                ['nama' => 'Supriyanto, S.T.', 'nip' => 'NIP. 197505202000121001', 'foto' => 'guru/titl1'],
                ['nama' => 'Hari Sutrisno', 'nip' => 'NIP. 198303102008011003', 'foto' => 'guru/titl2'],
                ['nama' => 'Mamat Rahmat', 'nip' => 'NIP. 199005202015031002', 'foto' => 'guru/titl3'],
            ]
        ],
        [
            'nama' => 'Teknik Pemesinan',
            'guru' => [
                ['nama' => 'Rudi Hermawan, S.T.', 'nip' => 'NIP. 197608152000121001', 'foto' => 'guru/tp1'],
                ['nama' => 'Syarif Hidayat', 'nip' => 'NIP. 198404122005011003', 'foto' => 'guru/tp2'],
            ]
        ],
        [
            'nama' => 'Bisnis Digital',
            'guru' => [
                ['nama' => 'Dra. Ratna Dewi', 'nip' => 'NIP. 197203121994032004', 'foto' => 'guru/bd1'],
                ['nama' => 'Nina Kartika, S.E.', 'nip' => 'NIP. 198005202008012003', 'foto' => 'guru/bd2'],
                ['nama' => 'Lisa Amalia, S.Kom.', 'nip' => 'NIP. 199002152015032001', 'foto' => 'guru/bd3'],
            ]
        ],
        [
            'nama' => 'Guru Mata Pelajaran',
            'guru' => [
                ['nama' => 'Prof. Dr. Ahmad Fauzi', 'nip' => 'NIP. 196505121990031002', 'foto' => 'guru/mapel1'],
                ['nama' => 'Dra. Maria Ulfa', 'nip' => 'NIP. 197208151995122001', 'foto' => 'guru/mapel2'],
                ['nama' => 'Drs. Mahmud Syah', 'nip' => 'NIP. 196808121993031004', 'foto' => 'guru/mapel3'],
                ['nama' => 'Ibu Sumiyati, M.Pd.', 'nip' => 'NIP. 197506202000122001', 'foto' => 'guru/mapel4'],
            ]
        ]
    ];

    // Icon mapping for each department
    $iconMap = [
        'Teknik Kendaraan Ringan' => 'fa-car',
        'Teknik Sepeda Motor' => 'fa-motorcycle',
        'Teknik Instalasi Tenaga Listrik' => 'fa-bolt',
        'Teknik Pemesinan' => 'fa-cogs',
        'Bisnis Digital' => 'fa-laptop-code',
        'Guru Mata Pelajaran' => 'fa-chalkboard-teacher'
    ];

    foreach ($jurusan as $j) {
        $icon = isset($iconMap[$j['nama']]) ? $iconMap[$j['nama']] : 'fa-graduation-cap';
        echo '
        <div class="structure-section">
            <h2><i class="fas ' . $icon . '"></i> ' . $j['nama'] . '</h2>
            <div class="structure-cards">
        ';
        foreach ($j['guru'] as $g) {
            echo '
                <div class="structure-card">
                    <img src="../img/' . $g['foto'] . '.jpg" alt="' . $g['nama'] . '">
                    <h3>' . $g['nama'] . '</h3>
                    <p class="position">' . $j['nama'] . '</p>
                    <p class="nip">' . $g['nip'] . '</p>
                </div>
            ';
        }
        echo '
            </div>
        </div>
        ';
    }
    ?>
</section>

<?php include 'footer.php'; ?>