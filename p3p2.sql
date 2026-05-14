-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 20, 2026 at 07:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `p3p2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`, `foto`) VALUES
(1, 'admin', 'admin', 'smppgri3bogor321', 'admin_avatar_1772603303.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `id` int NOT NULL,
  `judul` varchar(250) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` varchar(50) NOT NULL,
  `lokasi` varchar(60) NOT NULL,
  `status` enum('hari ini','akan datang','selesai','di batalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `penulis` varchar(200) NOT NULL,
  `foto` varchar(300) NOT NULL,
  `tanggal` date NOT NULL,
  `kategori` enum('kegiatan','prestasi','pengumuman') NOT NULL,
  `deskripsi` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kepsek`
--

CREATE TABLE `kepsek` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `position` enum('kepsek dan wakasek','tata usaha','sekre','wakil ketua') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `photo_filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mpk`
--

CREATE TABLE `mpk` (
  `id` int NOT NULL,
  `category` enum('ketua','waket','sekretaris','bendahara','anggota') NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `photo_filename` varchar(100) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `osis`
--

CREATE TABLE `osis` (
  `id` int NOT NULL,
  `category` enum('ketua','waket','sekretaris','bendahara','seksi') NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `photo_filename` varchar(100) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id` int NOT NULL,
  `nama` varchar(200) NOT NULL,
  `email` varchar(80) NOT NULL,
  `subjek` varchar(30) NOT NULL,
  `pesan` longtext NOT NULL,
  `dibaca` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesan`
--

INSERT INTO `pesan` (`id`, `nama`, `email`, `subjek`, `pesan`, `dibaca`, `created_at`) VALUES
(24, 'devara hermawan', 'defarahermawan@gmail.com', 'hai', 'a', 1, '2026-02-12 15:25:58'),
(25, 'devara hermawan', 'defarahermawan@gmail.com', 'hai', 'aa', 1, '2026-02-12 16:45:09'),
(26, 'devara hermawan', 'defarahermawan@gmail.com', 'tes', 'tes', 1, '2026-02-13 09:44:46'),
(28, 'devara hermawan', 'defarahermawan@gmail.com', 'tes', 'tes', 0, '2026-02-13 09:53:14'),
(29, 'devara hermawan', 'defarahermawan@gmail.com', 'a', 'a', 0, '2026-02-13 11:17:59'),
(31, 'devara hermawan', 'defarahermawan@gmail.com', 'hai', 'halo admin', 0, '2026-02-16 14:56:24'),
(34, 'dema', 'dema@gmail.com', 'tet', 'tes', 0, '2026-03-04 05:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `category` enum('7','8','9','mapel') NOT NULL,
  `name` varchar(100) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `photo_filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sambutan`
--

CREATE TABLE `sambutan` (
  `id` int NOT NULL,
  `nama_kepsek` varchar(150) NOT NULL,
  `foto_kepsek` varchar(255) DEFAULT NULL,
  `pesan_sambutan` text NOT NULL,
  `jumlah_siswa` int NOT NULL DEFAULT 0,
  `jumlah_pengajar` int NOT NULL DEFAULT 0,
  `tanggal_sambutan` date NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sambutan`
--

INSERT INTO `sambutan` (`id`, `nama_kepsek`, `foto_kepsek`, `pesan_sambutan`, `jumlah_siswa`, `jumlah_pengajar`, `tanggal_sambutan`) VALUES
(1, 'Drs. Indra Robriandri, M.Si', NULL, 'Segala puji bagi Allah SWT yang telah memberikan rahmat dan hidayah-Nya. Dengan penuh kebanggaan, saya menyambut Anda di website resmi SMP PGRI 3 BOGOR.\n\nKami berkomitmen untuk memberikan pendidikan terbaik bagi putra-putri Anda — mencetak generasi yang tidak hanya cerdas secara intelektual, tetapi juga memiliki karakter yang kuat dan berakhlak mulia.\n\nDidukung oleh tenaga pengajar profesional dan fasilitas modern, kami yakin mampu menghasilkan lulusan yang kompeten, kreatif, dan siap menghadapi tantangan global.\n\nMari bersama kita wujudkan generasi yang cerdas, inovatif, dan berdaya saing tinggi untuk Indonesia yang lebih maju.', 800, 45, '2026-02-01');

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kepsek`
--
ALTER TABLE `kepsek`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mpk`
--
ALTER TABLE `mpk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `osis`
--
ALTER TABLE `osis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `sambutan`
--
ALTER TABLE `sambutan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kepsek`
--
ALTER TABLE `kepsek`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mpk`
--
ALTER TABLE `mpk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `osis`
--
ALTER TABLE `osis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `sambutan`
--
ALTER TABLE `sambutan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------

--
-- Table structure for table `visi`
--

CREATE TABLE `visi` (
  `id` int NOT NULL,
  `badge_text` varchar(100) NOT NULL DEFAULT 'Visi Sekolah',
  `badge_icon` varchar(50) NOT NULL DEFAULT 'fas fa-eye',
  `judul_section` varchar(150) NOT NULL DEFAULT 'Visi Kami',
  `deskripsi_section` varchar(255) NOT NULL DEFAULT 'Arah dan cita-cita besar SMP PGRI 3 BOGOR',
  `isi_visi` text NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visi`
--

INSERT INTO `visi` (`id`, `badge_text`, `badge_icon`, `judul_section`, `deskripsi_section`, `isi_visi`) VALUES
(1, 'Visi Sekolah', 'fas fa-eye', 'Visi Kami', 'Arah dan cita-cita besar SMP PGRI 3 BOGOR', 'Menjadi sekolah menengah pertama unggulan yang menghasilkan lulusan kompeten, kreatif, berakhlak mulia, cinta tanah air, dan mampu menghadapi tantangan global abad ke-21.');

-- --------------------------------------------------------

--
-- Table structure for table `misi`
--

CREATE TABLE `misi` (
  `id` int NOT NULL,
  `badge_text` varchar(100) NOT NULL DEFAULT 'Misi Sekolah',
  `badge_icon` varchar(50) NOT NULL DEFAULT 'fas fa-bullseye',
  `judul_section` varchar(150) NOT NULL DEFAULT '7 Misi Utama',
  `deskripsi_section` varchar(255) NOT NULL DEFAULT 'Langkah nyata mewujudkan visi sekolah',
  `nomor` int NOT NULL DEFAULT 0,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'fas fa-star',
  `icon_bg` varchar(20) NOT NULL DEFAULT '#eff6ff',
  `icon_color` varchar(20) NOT NULL DEFAULT '#3b82f6',
  `sort_order` int NOT NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `misi`
--

INSERT INTO `misi` (`id`, `badge_text`, `badge_icon`, `judul_section`, `deskripsi_section`, `nomor`, `judul`, `deskripsi`, `icon`, `icon_bg`, `icon_color`, `sort_order`) VALUES
(1, 'Misi Sekolah', 'fas fa-bullseye', '7 Misi Utama', 'Langkah nyata mewujudkan visi sekolah', 1, 'Akhlak & Ibadah', 'Menciptakan profil pelajar yang berakhlak mulia dan rajin beribadah dalam kehidupan sehari-hari.', 'fas fa-pray', '#eff6ff', '#3b82f6', 1),
(2, 'Misi Sekolah', 'fas fa-bullseye', '7 Misi Utama', 'Langkah nyata mewujudkan visi sekolah', 2, 'Pembelajaran Berkarakter', 'Menciptakan pembelajaran yang menarik, menyenangkan dan berkarakter yang mampu memfasilitasi pelajar sesuai bakat dan minatnya.', 'fas fa-lightbulb', '#fefce8', '#ca8a04', 2),
(3, 'Misi Sekolah', 'fas fa-bullseye', '7 Misi Utama', 'Langkah nyata mewujudkan visi sekolah', 3, 'Pembelajaran Berbasis Mutu', 'Melaksanakan proses pembelajaran berbasis mutu dan berinovatif untuk kemajuan bersama.', 'fas fa-chart-line', '#f0fdf4', '#16a34a', 3),
(4, 'Misi Sekolah', 'fas fa-bullseye', '7 Misi Utama', 'Langkah nyata mewujudkan visi sekolah', 4, 'Lingkungan Intelektual & Budaya', 'Menciptakan lingkungan sekolah sebagai tempat perkembangan intelektual, sosial, emosional, keterampilan, dan pengembangan budaya lokal dalam kebhinekaan global.', 'fas fa-globe', '#fdf4ff', '#9333ea', 4),
(5, 'Misi Sekolah', 'fas fa-bullseye', '7 Misi Utama', 'Langkah nyata mewujudkan visi sekolah', 5, 'Mandiri & Bernalar Kritis', 'Menciptakan profil pelajar yang berakhlak mulia, mandiri, bernalar kritis dan kreatif sehingga mampu mengkreasi ide dan keterampilan yang inovatif.', 'fas fa-brain', '#fff7ed', '#ea580c', 5),
(6, 'Misi Sekolah', 'fas fa-bullseye', '7 Misi Utama', 'Langkah nyata mewujudkan visi sekolah', 6, 'Pengembangan SDM Profesional', 'Mengembangkan sumber daya manusia yang profesional, kompeten, dan berdedikasi tinggi bagi kemajuan institusi.', 'fas fa-user-tie', '#f0f9ff', '#0284c7', 6),
(7, 'Misi Sekolah', 'fas fa-bullseye', '7 Misi Utama', 'Langkah nyata mewujudkan visi sekolah', 7, 'Pendidikan Inklusif & Gotong-Royong', 'Menjamin hak belajar setiap anak tanpa terkecuali, termasuk anak yang berkebutuhan khusus (inklusi), dalam proses pembelajaran yang menjunjung tinggi nilai gotong-royong.', 'fas fa-hands-helping', '#f0fdf4', '#059669', 7);

-- --------------------------------------------------------

--
-- Table structure for table `program_7k`
--

CREATE TABLE `program_7k` (
  `id` int NOT NULL,
  `badge_text` varchar(100) NOT NULL DEFAULT 'Program 7K',
  `badge_icon` varchar(50) NOT NULL DEFAULT 'fas fa-star',
  `judul_section` varchar(150) NOT NULL DEFAULT '7K Sekolah',
  `deskripsi_section` varchar(255) NOT NULL DEFAULT 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif',
  `nomor` int NOT NULL DEFAULT 0,
  `nama` varchar(200) NOT NULL,
  `icon` varchar(10) NOT NULL DEFAULT '⭐',
  `warna` varchar(20) NOT NULL DEFAULT '#3b82f6',
  `sort_order` int NOT NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_7k`
--

INSERT INTO `program_7k` (`id`, `badge_text`, `badge_icon`, `judul_section`, `deskripsi_section`, `nomor`, `nama`, `icon`, `warna`, `sort_order`) VALUES
(1, 'Program 7K', 'fas fa-star', '7K Sekolah', 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif', 1, 'Ke<strong>tertiban</strong>', '📋', '#3b82f6', 1),
(2, 'Program 7K', 'fas fa-star', '7K Sekolah', 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif', 2, 'Ke<strong>indahan</strong>', '🎨', '#f59e0b', 2),
(3, 'Program 7K', 'fas fa-star', '7K Sekolah', 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif', 3, 'Ke<strong>bersihan</strong>', '🧹', '#10b981', 3),
(4, 'Program 7K', 'fas fa-star', '7K Sekolah', 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif', 4, 'Ke<strong>amanan</strong>', '🛡️', '#ef4444', 4),
(5, 'Program 7K', 'fas fa-star', '7K Sekolah', 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif', 5, 'Ke<strong>keluargaan</strong>', '🤝', '#8b5cf6', 5),
(6, 'Program 7K', 'fas fa-star', '7K Sekolah', 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif', 6, 'Ke<strong>rindangan</strong>', '🌳', '#84cc16', 6),
(7, 'Program 7K', 'fas fa-star', '7K Sekolah', 'Tujuh pilar nilai yang membangun karakter dan lingkungan sekolah yang kondusif', 7, 'Ke<strong>disiplinan</strong>', '⏰', '#0ea5e9', 7);

-- --------------------------------------------------------

--
-- Indexes for table `visi`
--
ALTER TABLE `visi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `misi`
--
ALTER TABLE `misi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_7k`
--
ALTER TABLE `program_7k`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `visi`
--
ALTER TABLE `visi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `misi`
--
ALTER TABLE `misi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `program_7k`
--
ALTER TABLE `program_7k`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` int NOT NULL,
  `nama` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'fa-building',
  `color` varchar(20) NOT NULL DEFAULT '#3b82f6',
  `tag` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fasilitas`
--

INSERT INTO `fasilitas` (`id`, `nama`, `deskripsi`, `icon`, `color`, `tag`, `sort_order`, `is_active`) VALUES
(1, 'Ruang Kelas', 'Ruang belajar yang nyaman, bersih, dan dilengkapi dengan papan tulis, proyektor, dan kursi ergonomis untuk mendukung proses belajar mengajar yang optimal.', 'fa-chalkboard-teacher', '#3b82f6', 'Akademik', 1, 1),
(2, 'Laboratorium', 'Laboratorium IPA, Komputer, dan Bahasa yang lengkap untuk mendukung kegiatan praktikum dan riset ilmiah siswa secara langsung.', 'fa-flask', '#8b5cf6', 'Sains & IT', 2, 1),
(3, 'Perpustakaan', 'Perpustakaan modern dengan koleksi ribuan buku pelajaran, referensi, dan bacaan pengembangan diri dalam suasana tenang dan nyaman.', 'fa-book-open', '#f59e0b', 'Literasi', 3, 1),
(4, 'Lapangan Olahraga', 'Lapangan serbaguna untuk basket, voli, futsal, dan atletik. Tersedia pula area senam dan fasilitas olahraga indoor.', 'fa-running', '#10b981', 'Olahraga', 4, 1),
(5, 'Masjid Sekolah', 'Masjid sekolah yang representatif untuk mendukung kegiatan ibadah dan pembinaan karakter religius siswa sehari-hari.', 'fa-mosque', '#06b6d4', 'Ibadah', 5, 1),
(6, 'Kantin & UKS', 'Kantin bersih dengan berbagai pilihan makanan bergizi. Dilengkapi UKS dengan tenaga medis untuk menjaga kesehatan seluruh warga sekolah.', 'fa-utensils', '#f97316', 'Penunjang', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas_images`
--

CREATE TABLE `fasilitas_images` (
  `id` int NOT NULL,
  `fasilitas_id` int NOT NULL,
  `filename` varchar(255) NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fasilitas_images`
--
ALTER TABLE `fasilitas_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fasilitas_id` (`fasilitas_id`);

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fasilitas_images`
--
ALTER TABLE `fasilitas_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
