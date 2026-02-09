<?php include 'header.php'; ?>

<section class="structure-hero">
    <h1>Majelis Perwakilan Kelas (MPK)</h1>
    <p>Kepengurusan MPK SMP PGRI 3 BOGOR Periode 2025/2026</p>
</section>

<section class="structure-content">
    <div class="structure-section">
        <h2><i class="fas fa-crown"></i> Ketua & Wakil Ketua MPK</h2>
        <div class="structure-cards">
            <div class="structure-card">
                <img src="../img/mpk/ketua.jpg" alt=" Ketua MPK">
                <h3>Bagus Khalif</h3>
                <p class="position"> Ketua MPK</p>
                <p class="nip">Kelas XII TP A</p>
            </div>
            <div class="structure-card">
                <img src="../img/mpk/wakil.jpg" alt=" Wakil MPK">
                <h3>Tiara Destyana</h3>
                <p class="position"> Wakil Ketua MPK</p>
                <p class="nip">Kelas XII BD B</p>
            </div>
        </div>
    </div>

    <div class="structure-section">
        <h2><i class="fas fa-clipboard"></i> Sekretaris</h2>
        <div class="structure-cards">
            <div class="structure-card">
                <img src="../img/mpk/sek1.jpg" alt="Sekretaris 1">
                <h3>Angelina Wijaya</h3>
                <p class="position">Sekretaris 1</p>
                <p class="nip">Kelas XI TKR A</p>
            </div>
            <div class="structure-card">
                <img src="../img/mpk/sek2.jpg" alt="Sekretaris 2">
                <h3>Rendy Kurniawan</h3>
                <p class="position">Sekretaris 2</p>
                <p class="nip">Kelas XI TITL A</p>
            </div>
        </div>
    </div>

    <div class="structure-section">
        <h2><i class="fas fa-wallet"></i> Bendahara</h2>
        <div class="structure-cards">
            <div class="structure-card">
                <img src="../img/mpk/bendahara1.jpg" alt="Bendahara 1">
                <h3>Citra Dewi</h3>
                <p class="position">Bendahara</p>
                <p class="nip">Kelas XI TSM A</p>
            </div>
        </div>
    </div>

    <div class="structure-section">
        <h2><i class="fas fa-vote-yea"></i> Anggota MPK</h2>
        <div class="structure-cards">
            <?php
            $anggota = [
                ['nama' => 'Andre Pratama', 'kelas' => 'XII TKR A', 'foto' => 'mpk/ang1'],
                ['nama' => 'Siti Nurhaliza', 'kelas' => 'XII TKR B', 'foto' => 'mpk/ang2'],
                ['nama' => 'Bayu Saputra', 'kelas' => 'XII TSM A', 'foto' => 'mpk/ang3'],
                ['nama' => 'Dinda Ayu', 'kelas' => 'XII TSM B', 'foto' => 'mpk/ang4'],
                ['nama' => 'Firman Maulana', 'kelas' => 'XII TITL A', 'foto' => 'mpk/ang5'],
                ['nama' => 'Eka Lestari', 'kelas' => 'XII TITL B', 'foto' => 'mpk/ang6'],
                ['nama' => 'Gilang Ramadan', 'kelas' => 'XII TP A', 'foto' => 'mpk/ang7'],
                ['nama' => 'Hana Putri', 'kelas' => 'XII TP B', 'foto' => 'mpk/ang8'],
                ['nama' => 'Ilham Fadli', 'kelas' => 'XII BD A', 'foto' => 'mpk/ang9'],
                ['nama' => 'Jasmine Salsa', 'kelas' => 'XII BD B', 'foto' => 'mpk/ang10'],
            ];

            foreach ($anggota as $a) {
                echo '
                <div class="structure-card">
                    <img src="../img/mpk/' . $a['foto'] . '.jpg" alt="' . $a['nama'] . '">
                    <h3>' . $a['nama'] . '</h3>
                    <p class="position">Anggota MPK</p>
                    <p class="nip">' . $a['kelas'] . '</p>
                </div>
                ';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>