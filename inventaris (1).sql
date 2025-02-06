-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 01:50 AM
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
  `nama_barang` varchar(255) NOT NULL,
  `harga_barang` int(24) NOT NULL,
  `stok` int(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `harga_barang`, `stok`) VALUES
(1, 'Smartphone', 599000, 25),
(2, 'Laptop', 999000, 12);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id_pembelian_detail` int(12) NOT NULL,
  `id_pembelian` int(12) NOT NULL,
  `id_barang` int(12) NOT NULL,
  `jumlah_barang` int(24) NOT NULL,
  `sub_total` int(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_inventaris`
--

CREATE TABLE `laporan_inventaris` (
  `id_laporan` int(12) NOT NULL,
  `id_barang` int(12) NOT NULL,
  `id_user` int(12) NOT NULL,
  `tipe_aktivitas` enum('pembelian','penjualan') NOT NULL,
  `jumlah` int(24) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(5, 'asd', 'as123', '12');

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
(9, 'kusang', 'kusang', '$2y$10$QX/gZ9/AftrLBY0ET5QDR.GH6FZ286eSJKqRP.SgwfEvqxQDylvl.', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id_pembelian_detail`),
  ADD UNIQUE KEY `id_pembelian` (`id_pembelian`),
  ADD UNIQUE KEY `id_barang` (`id_barang`);

--
-- Indexes for table `laporan_inventaris`
--
ALTER TABLE `laporan_inventaris`
  ADD PRIMARY KEY (`id_laporan`),
  ADD UNIQUE KEY `id_barang` (`id_barang`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `id_supplier` (`id_supplier`);

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
  MODIFY `id_barang` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id_pembelian_detail` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_inventaris`
--
ALTER TABLE `laporan_inventaris`
  MODIFY `id_laporan` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pembelian_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `laporan_inventaris`
--
ALTER TABLE `laporan_inventaris`
  ADD CONSTRAINT `laporan_inventaris_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `laporan_inventaris_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

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
