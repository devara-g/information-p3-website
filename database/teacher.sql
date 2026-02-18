

-- Table for OSIS organization data
CREATE TABLE IF NOT EXISTS osis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('ketua', 'waket', 'sekretaris', 'bendahara', 'seksi') NOT NULL,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    kelas VARCHAR(50) NOT NULL,
    photo_filename VARCHAR(100),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert OSIS data
INSERT INTO osis (category, name, position, kelas, photo_filename, sort_order) VALUES
('ketua', 'Rizky Maulana', 'Ketua OSIS', 'XII TKR A', 'ketua', 1),
('waket', 'Aurel Putri Dinda', 'Wakil Ketua OSIS', 'XII BD A', 'waket', 1),
('sekretaris', 'Nadia Safitri', 'Sekretaris 1', 'XII TITL A', 'sek1', 1),
('sekretaris', 'Kevin Aryo Wibowo', 'Sekretaris 2', 'XII TP A', 'sek2', 1),
('bendahara', 'Salsabila Rahma', 'Bendahara 1', 'XII TSM A', 'bendahara1', 1),
('bendahara', 'Dimas Prasetyo', 'Bendahara 2', 'XII BD A', 'bendahara2', 1),
('seksi', 'Fajar Nugroho', 'Sie Kebugaran & Kesehatan', 'XII TKR B', 'kes1', 1),
('seksi', 'Putri Melati', 'Sie Kebugaran & Kesehatan', 'XII TITL B', 'kes2', 1),
('seksi', 'Revalina Gita', 'Sie Seni & Budaya', 'XII TP B', 'seni1', 1),
('seksi', 'Yoga Permana', 'Sie Seni & Budaya', 'XII TSM B', 'seni2', 1);

-- Table for MPK organization data
CREATE TABLE IF NOT EXISTS mpk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('ketua', 'waket', 'sekretaris', 'bendahara', 'anggota') NOT NULL,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    kelas VARCHAR(50) NOT NULL,
    photo_filename VARCHAR(100),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert MPK data
INSERT INTO mpk (category, name, position, kelas, photo_filename, sort_order) VALUES
('ketua', 'Bagus Khalif', 'Ketua MPK', 'XII TP A', 'ketua', 1),
('waket', 'Tiara Destyana', 'Wakil Ketua MPK', 'XII BD B', 'waket', 1),
('sekretaris', 'Angelina Wijaya', 'Sekretaris 1', 'XII TKR A', 'sek1', 1),
('sekretaris', 'Rendy Kurniawan', 'Sekretaris 2', 'XII TITL A', 'sek2', 1),
('bendahara', 'Citra Dewi', 'Bendahara', 'XII TSM A', 'bendahara1', 1),
('anggota', 'Andre Pratama', 'Anggota MPK', 'XII TKR A', 'ang1', 1),
('anggota', 'Siti Nurhaliza', 'Anggota MPK', 'XII TKR B', 'ang2', 2),
('anggota', 'Bayu Saputra', 'Anggota MPK', 'XII TSM A', 'ang3', 3),
('anggota', 'Dinda Ayu', 'Anggota MPK', 'XII TSM B', 'ang4', 4),
('anggota', 'Firman Maulana', 'Anggota MPK', 'XII TITL A', 'ang5', 5),
('anggota', 'Eka Lestari', 'Anggota MPK', 'XII TITL B', 'ang6', 6),
('anggota', 'Gilang Ramadan', 'Anggota MPK', 'XII TP A', 'ang7', 7),
('anggota', 'Hana Putri', 'Anggota MPK', 'XII TP B', 'ang8', 8),
('anggota', 'Ilham Fadli', 'Anggota MPK', 'XII BD A', 'ang9', 9),
('anggota', 'Jasmine Salsa', 'Anggota MPK', 'XII BD B', 'ang10', 10);

-- Table for Kepala Sekolah data
CREATE TABLE IF NOT EXISTS kepsek (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    nip VARCHAR(50) NOT NULL,
    position VARCHAR(100) NOT NULL DEFAULT 'Kepala Sekolah',
    photo_filename VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Kepsek data
INSERT INTO kepsek (name, nip, position, photo_filename) VALUES
('Dr. H. Ahmad Suharto, M.Pd.', 'NIP. 196708151992031002', 'Kepala Sekolah', 'kepsek');
