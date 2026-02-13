-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 13, 2026 at 12:14 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.27

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
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `password`) VALUES
(1, 'admin', 'smppgri3bogor321');

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
  `status` enum('hari ini','akan datang','selesai','di batalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agenda`
--

INSERT INTO `agenda` (`id`, `judul`, `foto`, `tanggal`, `waktu`, `lokasi`, `status`, `deskripsi`) VALUES
(3, 'aaa', 'upload/img/agenda_1770952628_698e97b4832f3.jpg', '2026-02-13', 'asas', 'ass', 'akan datang', 'ass'),
(5, 'Makalah Sejara', 'upload/img/agenda_1770979615_698f011f09de1.jpg', '2026-02-13', '08:00 -09:00', 'aula', 'akan datang', 'ksajjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `kategori` enum('kegiatan sekolah','prestasi siswa','kegiatan eskul') NOT NULL,
  `deskripsi` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `judul`, `foto`, `kategori`, `deskripsi`) VALUES
(1, 'a', 'upload/img/galeri_1770956559_698ea70f08c98.jpg', 'kegiatan sekolah', 'a'),
(2, 'a', 'upload/img/galeri_1770956599_698ea7374bf0a.jpg', 'kegiatan sekolah', 'a');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesan`
--

INSERT INTO `pesan` (`id`, `nama`, `email`, `subjek`, `pesan`, `dibaca`, `created_at`) VALUES
(24, 'devara hermawan', 'defarahermawan@gmail.com', 'hai', 'a', 1, '2026-02-12 15:25:58'),
(25, 'devara hermawan', 'defarahermawan@gmail.com', 'hai', 'aa', 0, '2026-02-12 16:45:09'),
(26, 'devara hermawan', 'defarahermawan@gmail.com', 'tes', 'tes', 1, '2026-02-13 09:44:46'),
(28, 'devara hermawan', 'defarahermawan@gmail.com', 'tes', 'tes', 0, '2026-02-13 09:53:14'),
(29, 'devara hermawan', 'defarahermawan@gmail.com', 'a', 'a', 0, '2026-02-13 11:17:59');

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
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
