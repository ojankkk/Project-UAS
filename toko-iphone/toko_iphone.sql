-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 18, 2025 at 02:08 AM
-- Server version: 8.0.42
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_iphone`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(2, 'ojankk_', '$2y$10$5Awwox5n0JT/eGo9NnQP3OB77xxOlQUNx94vTvDJEQzGvplaUcqJC');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_saat_pesan` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `id_pesanan`, `id_produk`, `jumlah`, `harga_saat_pesan`) VALUES
(5, 5, 1, 1, 8500000.00),
(6, 6, 12, 2, 4000000.00),
(7, 7, 13, 1, 4000000.00),
(8, 8, 14, 1, 3500000.00),
(9, 9, 14, 1, 3500000.00),
(10, 10, 17, 10, 12000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int NOT NULL,
  `nomor_invoice` varchar(50) NOT NULL,
  `id_pesanan` int NOT NULL,
  `tanggal_cetak` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `nomor_invoice`, `id_pesanan`, `tanggal_cetak`) VALUES
(1, 'INV-20250718000522-7263', 4, '2025-07-18 06:05:22'),
(2, 'INV-20250718002012-9068', 5, '2025-07-18 06:20:12'),
(3, 'INV-20250718002410-6045', 6, '2025-07-18 06:24:10'),
(4, 'INV-20250718010204-7154', 7, '2025-07-18 07:02:04'),
(5, 'INV-20250718014454-6787', 8, '2025-07-18 07:44:54'),
(6, 'INV-20250718021531-5846', 9, '2025-07-18 08:15:31'),
(7, 'INV-20250718021611-1812', 10, '2025-07-18 08:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int NOT NULL,
  `id_member` int DEFAULT NULL,
  `aktivitas` text,
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `id_member`, `aktivitas`, `waktu`) VALUES
(1, 1, 'Melakukan pemesanan produk ID 14 sebanyak 1', '2025-07-18 07:44:54'),
(2, 2, 'Melakukan pemesanan produk ID 14 sebanyak 1', '2025-07-18 08:15:31'),
(3, 2, 'Melakukan pemesanan produk ID 17 sebanyak 10', '2025-07-18 08:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `log_login`
--

CREATE TABLE `log_login` (
  `id` int NOT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `waktu_login` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_login`
--

INSERT INTO `log_login` (`id`, `id_pelanggan`, `waktu_login`, `ip_address`) VALUES
(1, 1, '2025-07-18 06:47:37', '::1'),
(2, 1, '2025-07-18 07:03:08', '::1'),
(3, 2, '2025-07-18 08:15:04', '::1'),
(4, 1, '2025-07-18 09:56:35', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `log_stok`
--

CREATE TABLE `log_stok` (
  `id` int NOT NULL,
  `id_produk` int NOT NULL,
  `perubahan` int NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_stok`
--

INSERT INTO `log_stok` (`id`, `id_produk`, `perubahan`, `keterangan`, `tanggal`) VALUES
(1, 1, 10, 'Perubahan stok oleh admin', '2025-07-18 07:25:09'),
(2, 17, 0, 'Stok awal saat tambah produk', '2025-07-18 07:28:43');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `nama`, `email`, `password`) VALUES
(1, 'Fauzan Azhima Ardani', 'ardaninocounter@gmail.com', '$2y$10$5jkU05w5N7XKoJtNfXFDfu.YgzNeQ2Z9Sw7R3aSLdVc80oyFfb3FK'),
(2, 'islamiyah dinar kinanti', 'islamiyah.kinanti@gmail.com', '$2y$10$bOPqT/Uwga6InbV/vWWh/OU4CpVlbsNktQgpc6/dKINJFgDdZu4Bi');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int NOT NULL,
  `id_pelanggan` int NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `tanggal_pesan` datetime DEFAULT CURRENT_TIMESTAMP,
  `alamat` text NOT NULL,
  `nomor_invoice` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `id_pelanggan`, `total`, `tanggal_pesan`, `alamat`, `nomor_invoice`) VALUES
(1, 1, 8500000.00, '2025-07-18 00:03:04', 'Yayasan Yabis', NULL),
(2, 1, 8500000.00, '2025-07-18 00:10:37', 'yayasan Yabis', NULL),
(3, 1, 18000000.00, '2025-07-18 06:03:12', 'Surga', NULL),
(4, 1, 8500000.00, '2025-07-18 06:05:22', 'malaisia', NULL),
(5, 1, 8500000.00, '2025-07-18 06:20:12', 'Surga', NULL),
(6, 1, 8000000.00, '2025-07-18 06:24:10', 'Papua', NULL),
(7, 1, 4000000.00, '2025-07-18 07:02:04', 'Yabis', NULL),
(8, 1, 3500000.00, '2025-07-18 07:44:54', 'bandung', NULL),
(9, 2, 3500000.00, '2025-07-18 08:15:31', 'Gang Samping RS Amalia', NULL),
(10, 2, 120000000.00, '2025-07-18 08:16:11', 'Gang Samping RS Amalia', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `deskripsi` text,
  `harga` int NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `stok` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `kategori`, `deskripsi`, `harga`, `gambar`, `stok`) VALUES
(1, 'iPhone 11', 'iPhone', 'iPhone 11 64GB white saja', 8500000, 'download.jpeg', 10),
(2, 'iPhone 13 Pro', 'iPhone', 'iPhone 13 Pro 128GB Sierra Blue', 14500000, 'Iphone 13 ProMax Sierra Blue.jpeg', 0),
(3, 'iPhone SE 2022', 'iPhone', 'iPhone SE 3rd Gen 64GB', 6500000, 'iPhone SE 2022.jpeg', 0),
(4, 'IPhone 15 Pro', 'iPhone', 'good phone', 18000000, 'Win iPhone 15 Pro Max Giveaway - No Human Verification.jpeg', 0),
(5, 'Samsung A54 5G', 'Samsung', 'Samsung A54 5G Purple', 3500000, 'samsung galaxy a54 5g.jpeg', 0),
(12, 'Oppo reno 13', 'Oppo', 'Oppo reno 13 5G purple', 4000000, 'oppo reno 13.jpeg', 0),
(13, 'Samsung A55 5G', 'Samsung', 'Samsung A55 5G purple', 4000000, 'Samsung.jpeg', 0),
(14, 'POCO X7 Pro 5G', 'Xiaomi', 'POCO X7 Pro 5G Full Color', 3500000, 'Poco X7 Pro 5G.jpeg', 0),
(16, 'iPhone 13 Pro', 'iPhone', 'ip 13', 2000000, 'download.jpeg', 0),
(17, 'Vivo X200 Pro', 'Vivo', 'Vivo X200 Pro dengan Kamera super canggih', 12000000, 'download (1).jpeg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int NOT NULL,
  `id_produk` int DEFAULT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `komentar` text,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`id`, `id_produk`, `id_pelanggan`, `rating`, `komentar`, `tanggal`) VALUES
(1, 1, 1, 5, 'Sangat Bagus', '2025-07-18 00:03:58'),
(2, 14, 2, 5, 'Barangnya Sampai Dengan Aman', '2025-07-18 00:16:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pesanan` (`id_pesanan`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_member` (`id_member`);

--
-- Indexes for table `log_login`
--
ALTER TABLE `log_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `log_stok`
--
ALTER TABLE `log_stok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `log_login`
--
ALTER TABLE `log_login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log_stok`
--
ALTER TABLE `log_stok`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`),
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `member` (`id`);

--
-- Constraints for table `log_login`
--
ALTER TABLE `log_login`
  ADD CONSTRAINT `log_login_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `member` (`id`);

--
-- Constraints for table `log_stok`
--
ALTER TABLE `log_stok`
  ADD CONSTRAINT `log_stok_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `member` (`id`);

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`),
  ADD CONSTRAINT `ulasan_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `member` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
