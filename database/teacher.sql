CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('7', '8', '9', 'mapel') NOT NULL,
    name VARCHAR(100) NOT NULL,
    nip VARCHAR(50) UNIQUE NOT NULL,
    photo_filename VARCHAR(100),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert semua data guru
INSERT INTO teachers (category, name, nip, photo_filename, sort_order) VALUES
-- Kelas 7
('7', 'Ir. Joko Susilo', 'NIP. 197203041998031003', 'tkr1', 1),
('7', 'Budi Hariyanto, S.T.', 'NIP. 197806152005011004', 'tkr2', 2),
('7', 'Agus Setiawan', 'NIP. 198009122010011003', 'tkr3', 3),
('7', 'Rahmat Pratama', 'NIP. 198505202015031003', 'tkr4', 4),

-- Kelas 8
('8', 'Deni Kurniawan, S.T.', 'NIP. 197404102000121001', 'tsm1', 1),
('8', 'Iwan Firmawan', 'NIP. 198112022008011002', 'tsm2', 2),
('8', 'Sopian Hadi', 'NIP. 198807152012011002', 'tsm3', 3),

-- Kelas 9
('9', 'Supriyanto, S.T.', 'NIP. 197505202000121001', 'titl1', 1),
('9', 'Hari Sutrisno', 'NIP. 198303102008011003', 'titl2', 2),
('9', 'Mamat Rahmat', 'NIP. 199005202015031002', 'titl3', 3),

-- Guru Mata Pelajaran (mapel)
('mapel', 'Prof. Dr. Ahmad Fauzi', 'NIP. 196505121990031002', 'mapel1', 1),
('mapel', 'Dra. Maria Ulfa', 'NIP. 197208151995122001', 'mapel2', 2),
('mapel', 'Drs. Mahmud Syah', 'NIP. 196808121993031004', 'mapel3', 3),
('mapel', 'Ibu Sumiyati, M.Pd.', 'NIP. 197506202000122001', 'mapel4', 4);