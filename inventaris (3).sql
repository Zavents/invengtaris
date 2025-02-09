-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2025 at 03:12 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventaris`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(12) NOT NULL,
  `id_supplier` int(12) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga_barang` int(24) NOT NULL,
  `stok` int(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `id_supplier`, `nama_barang`, `harga_barang`, `stok`) VALUES
(6, 0, 'ikan', 10000, 21),
(7, 0, 'ayam', 20000, 20),
(8, 0, 'kucing', 90000, 10),
(9, 0, 'ikan', 10000, 123);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id_pembelian_detail` int(12) NOT NULL,
  `id_pembelian` int(12) NOT NULL,
  `id_barang` int(12) NOT NULL,
  `jumlah_barang` int(24) NOT NULL,
  `sub_total` int(24) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`id_pembelian_detail`, `id_pembelian`, `id_barang`, `jumlah_barang`, `sub_total`, `id_user`) VALUES
(22, 74, 7, 10, 0, 1),
(23, 75, 6, 1, 0, 2),
(27, 79, 8, 10, 0, 2),
(28, 80, 9, 123, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `laporan_inventaris`
--

CREATE TABLE `laporan_inventaris` (
  `id_laporan` int(12) NOT NULL,
  `id_barang` int(12) NOT NULL,
  `id_user` int(12) NOT NULL,
  `id_supplier` int(12) NOT NULL,
  `jumlah` int(24) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `laporan_inventaris`
--

INSERT INTO `laporan_inventaris` (`id_laporan`, `id_barang`, `id_user`, `id_supplier`, `jumlah`, `tanggal`) VALUES
(8, 6, 1, 8, 10, '2025-02-09'),
(9, 7, 1, 9, 10, '2025-02-09'),
(10, 6, 2, 7, 1, '2025-02-09'),
(11, 8, 2, 7, 10, '2025-02-09'),
(12, 9, 2, 7, 123, '2025-02-09');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(12) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_supplier` int(12) NOT NULL,
  `tanggal_pembelian` date NOT NULL,
  `total_harga` int(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `id_user`, `id_supplier`, `tanggal_pembelian`, `total_harga`) VALUES
(74, 1, 9, '2025-02-09', 200000),
(75, 2, 7, '2025-02-09', 10000),
(76, 2, 8, '2025-02-09', 900000),
(77, 2, 7, '2025-02-09', 90000),
(78, 2, 7, '2025-02-09', 90000),
(79, 2, 7, '2025-02-09', 900000),
(80, 2, 7, '2025-02-09', 1230000);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(12) NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nomor_telepon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat`, `nomor_telepon`) VALUES
(7, 'Mamad', 'Jalan Pasir Laut', '0819181716'),
(8, 'Usmad', 'Jalan Ikan Laut', '0812191813'),
(9, 'Ahmed', 'Jalan Ikan Pasir ', '0912131415');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(12) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('administrator','petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `name`, `password`, `level`) VALUES
(1, 'zavents', 'zavents', '$2y$10$zxyZXcZ.0RRkk8On2EkMcObU1mkb0qnI/JPnn.G/HP7U9e78pHRTi', 'administrator'),
(2, 'kusang', 'kusang', '$2y$10$J.8KdKmTwUsI8Eu8acaiR.gPxWzt3OEAQvvKffs41aJMSqHUdTYH2', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_supplier` (`id_supplier`) USING BTREE;

--
-- Indexes for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id_pembelian_detail`),
  ADD UNIQUE KEY `id_pembelian` (`id_pembelian`),
  ADD UNIQUE KEY `id_barang` (`id_barang`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `laporan_inventaris`
--
ALTER TABLE `laporan_inventaris`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_barang` (`id_barang`) USING BTREE,
  ADD KEY `id_user` (`id_user`) USING BTREE,
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_supplier` (`id_supplier`) USING BTREE,
  ADD KEY `id_user` (`id_user`) USING BTREE;

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id_pembelian_detail` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `laporan_inventaris`
--
ALTER TABLE `laporan_inventaris`
  MODIFY `id_laporan` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pembelian_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pembelian_ibfk_4` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `laporan_inventaris`
--
ALTER TABLE `laporan_inventaris`
  ADD CONSTRAINT `laporan_inventaris_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `laporan_inventaris_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `laporan_inventaris_ibfk_3` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
