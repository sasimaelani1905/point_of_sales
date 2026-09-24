-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 24, 2026 at 06:56 AM
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
-- Database: `point`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang`
--

CREATE TABLE `tbl_barang` (
  `kode_brg` varchar(10) NOT NULL,
  `kode_supplier` varchar(10) NOT NULL,
  `nama_brg` varchar(20) NOT NULL,
  `merk` varchar(10) NOT NULL,
  `stok` int NOT NULL,
  `rata_harga_beli` int NOT NULL,
  `harga_jual` int NOT NULL,
  `foto_barang` text,
  `barcode_barang` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_barang`
--

INSERT INTO `tbl_barang` (`kode_brg`, `kode_supplier`, `nama_brg`, `merk`, `stok`, `rata_harga_beli`, `harga_jual`, `foto_barang`, `barcode_barang`) VALUES
('4', '0003', 'Suncreen', 'Wardah', 17, 40000, 47000, '4_1789373024.jpg', '100000004'),
('5', '0002', 'Shampo', 'Serasoft', 49, 50000, 80000, '5_1789377233.jpg', '100000005');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_konsinyasi`
--

CREATE TABLE `tbl_barang_konsinyasi` (
  `kode_brg_konsinyasi` int NOT NULL,
  `kode_supplier` varchar(20) NOT NULL,
  `nama_brg` varchar(100) NOT NULL,
  `merk` varchar(100) NOT NULL,
  `stok` int NOT NULL,
  `harga_supplier` int NOT NULL,
  `harga_jual` int NOT NULL,
  `barcode_barang` varchar(100) DEFAULT NULL,
  `foto_barang` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_barang_konsinyasi`
--

INSERT INTO `tbl_barang_konsinyasi` (`kode_brg_konsinyasi`, `kode_supplier`, `nama_brg`, `merk`, `stok`, `harga_supplier`, `harga_jual`, `barcode_barang`, `foto_barang`) VALUES
(1, '0001', 'Buku Gambar', 'Sidu', 95, 35000, 39000, '100000001', '1_1789530216.jpg'),
(2, '0002', 'Penggaris', 'Kenko', 160, 6000, 10000, '100000002', '2_1789531518.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detail_nota_beli`
--

CREATE TABLE `tbl_detail_nota_beli` (
  `urut` int NOT NULL,
  `kode_nota` varchar(10) NOT NULL,
  `kode_brg` varchar(10) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_beli` int NOT NULL,
  `total_harga_beli` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_detail_nota_beli`
--

INSERT INTO `tbl_detail_nota_beli` (`urut`, `kode_nota`, `kode_brg`, `jumlah`, `harga_beli`, `total_harga_beli`) VALUES
(1, '01', '08', 23, 20, 200),
(2, '3', '001', 3, 2000, 6000),
(3, '9', '002', 9, 4000, 36000),
(4, '3', '002', 5, 5000, 25000),
(6, '4', '4', 2, 70000, 140000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detail_nota_jual`
--

CREATE TABLE `tbl_detail_nota_jual` (
  `urut` int NOT NULL,
  `kode_nota` varchar(10) NOT NULL,
  `kode_brg` varchar(10) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_jual` int NOT NULL,
  `total_harga_jual` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_detail_nota_jual`
--

INSERT INTO `tbl_detail_nota_jual` (`urut`, `kode_nota`, `kode_brg`, `jumlah`, `harga_jual`, `total_harga_jual`) VALUES
(3, '2', '001', 9, 6000, 54000),
(4, '2', '002', 9, 4000, 36000),
(5, '2', '003', 1, 80000, 80000),
(6, '4', '4', 2, 47000, 94000),
(7, '5', '4', 2, 3, 6),
(8, '4', '5', 8, 8000, 64000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detail_nota_konsinyasi`
--

CREATE TABLE `tbl_detail_nota_konsinyasi` (
  `id_detail` int NOT NULL,
  `no_nota_konsinyasi` varchar(20) NOT NULL,
  `kode_brg_konsinyasi` int NOT NULL,
  `jumlah_titip` int NOT NULL DEFAULT '1',
  `harga_satuan` int NOT NULL DEFAULT '0',
  `subtotal` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_detail_nota_konsinyasi`
--

INSERT INTO `tbl_detail_nota_konsinyasi` (`id_detail`, `no_nota_konsinyasi`, `kode_brg_konsinyasi`, `jumlah_titip`, `harga_satuan`, `subtotal`, `created_at`) VALUES
(1, 'NK-20260916001', 1, 90, 80000, 7200000, '2026-09-16 04:31:55'),
(3, 'NK-20260916001', 2, 80, 8000, 640000, '2026-09-16 04:34:17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nota_beli`
--

CREATE TABLE `tbl_nota_beli` (
  `kode_nota` varchar(10) NOT NULL,
  `kode_supplier` varchar(10) NOT NULL,
  `tgl_pembelian` date NOT NULL,
  `total_pembelian` int NOT NULL,
  `status` enum('L','2','3','4') NOT NULL COMMENT 'L=Lunas, 2=25%, 3=50%, 4=75%',
  `keterangan` text,
  `metode_pembayaran` enum('Cash','QRIS','Transfer') NOT NULL DEFAULT 'Cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_nota_beli`
--

INSERT INTO `tbl_nota_beli` (`kode_nota`, `kode_supplier`, `tgl_pembelian`, `total_pembelian`, `status`, `keterangan`, `metode_pembayaran`) VALUES
('4', '0003', '2026-09-10', 142000, '2', 'Belum Lunas', 'Cash'),
('9', '0002', '2026-09-10', 36000, 'L', 'Lunas', 'Transfer');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nota_jual`
--

CREATE TABLE `tbl_nota_jual` (
  `kode_nota` varchar(10) NOT NULL,
  `kode_supplier` varchar(10) NOT NULL,
  `tgl_penjualan` date NOT NULL,
  `total_penjualan` int NOT NULL,
  `status` enum('L','2','3','4') NOT NULL COMMENT 'Lunas, 2 dibayar 25%, 3 dibayar 50%, 4 dibayar 75%',
  `keterangan` text,
  `metode_pembayaran` enum('Cash','QRIS','Transfer') NOT NULL DEFAULT 'Cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_nota_jual`
--

INSERT INTO `tbl_nota_jual` (`kode_nota`, `kode_supplier`, `tgl_penjualan`, `total_penjualan`, `status`, `keterangan`, `metode_pembayaran`) VALUES
('4', '0002', '2026-09-10', 158000, 'L', 'Lunas', 'Cash'),
('5', '0001', '2026-09-16', 60000, 'L', 'Lunas', 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nota_konsinyasi`
--

CREATE TABLE `tbl_nota_konsinyasi` (
  `no_nota_konsinyasi` varchar(20) NOT NULL,
  `kode_brg_konsinyasi` int NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `tgl_titip` date NOT NULL,
  `total_item` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_nota_konsinyasi`
--

INSERT INTO `tbl_nota_konsinyasi` (`no_nota_konsinyasi`, `kode_brg_konsinyasi`, `nama_supplier`, `tgl_titip`, `total_item`, `created_at`) VALUES
('NK-20260916001', 1, 'Cahaya', '2026-09-16', 170, '2026-09-16 04:03:42');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `kode_supplier` varchar(10) NOT NULL,
  `nama_supplier` varchar(15) NOT NULL,
  `nama_pic` varchar(15) NOT NULL,
  `kontak_pic` varchar(15) NOT NULL,
  `alamat_supplier` text NOT NULL,
  `website` text,
  `akun_ig` text,
  `akun_tiktok` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`kode_supplier`, `nama_supplier`, `nama_pic`, `kontak_pic`, `alamat_supplier`, `website`, `akun_ig`, `akun_tiktok`) VALUES
('0001', 'Sasi', 'Cenda', '0893735', 'ds.majuuu', 'bismilahh.my.id', 'cenda', 'cendaniii'),
('0002', 'Cahaya', 'Ardila', '0976', 'dk.bahagia', 'cahaya.my.id', '_cahayaardila', 'yayuttt'),
('0003', 'Indah', 'Indah Gemilang', '0986463', 'ds.kaler', 'indah.my.id', 'indhhhh', 'indaaa');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `peran` enum('K','S') NOT NULL,
  `nama_panggilan` varchar(15) NOT NULL,
  `pin` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`username`, `password`, `peran`, `nama_panggilan`, `pin`) VALUES
('090909', '090909', 'S', 'Yayut', '909090'),
('121212', '121212', 'K', 'admin', '121212'),
('42423044', '42423044', 'K', 'Sasi', '121212'),
('676767', '676767', 'S', 'Melani', '989898');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  ADD UNIQUE KEY `kode_brg` (`kode_brg`);

--
-- Indexes for table `tbl_barang_konsinyasi`
--
ALTER TABLE `tbl_barang_konsinyasi`
  ADD PRIMARY KEY (`kode_brg_konsinyasi`);

--
-- Indexes for table `tbl_detail_nota_beli`
--
ALTER TABLE `tbl_detail_nota_beli`
  ADD PRIMARY KEY (`urut`);

--
-- Indexes for table `tbl_detail_nota_jual`
--
ALTER TABLE `tbl_detail_nota_jual`
  ADD PRIMARY KEY (`urut`);

--
-- Indexes for table `tbl_detail_nota_konsinyasi`
--
ALTER TABLE `tbl_detail_nota_konsinyasi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_nota` (`no_nota_konsinyasi`),
  ADD KEY `fk_detail_barang` (`kode_brg_konsinyasi`);

--
-- Indexes for table `tbl_nota_beli`
--
ALTER TABLE `tbl_nota_beli`
  ADD PRIMARY KEY (`kode_nota`);

--
-- Indexes for table `tbl_nota_jual`
--
ALTER TABLE `tbl_nota_jual`
  ADD PRIMARY KEY (`kode_nota`);

--
-- Indexes for table `tbl_nota_konsinyasi`
--
ALTER TABLE `tbl_nota_konsinyasi`
  ADD PRIMARY KEY (`no_nota_konsinyasi`),
  ADD KEY `idx_kode_brg` (`kode_brg_konsinyasi`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`kode_supplier`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_barang_konsinyasi`
--
ALTER TABLE `tbl_barang_konsinyasi`
  MODIFY `kode_brg_konsinyasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_detail_nota_beli`
--
ALTER TABLE `tbl_detail_nota_beli`
  MODIFY `urut` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_detail_nota_jual`
--
ALTER TABLE `tbl_detail_nota_jual`
  MODIFY `urut` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_detail_nota_konsinyasi`
--
ALTER TABLE `tbl_detail_nota_konsinyasi`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_detail_nota_konsinyasi`
--
ALTER TABLE `tbl_detail_nota_konsinyasi`
  ADD CONSTRAINT `fk_detail_barang` FOREIGN KEY (`kode_brg_konsinyasi`) REFERENCES `tbl_barang_konsinyasi` (`kode_brg_konsinyasi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_nota` FOREIGN KEY (`no_nota_konsinyasi`) REFERENCES `tbl_nota_konsinyasi` (`no_nota_konsinyasi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_nota_konsinyasi`
--
ALTER TABLE `tbl_nota_konsinyasi`
  ADD CONSTRAINT `fk_nota_barang_konsinyasi` FOREIGN KEY (`kode_brg_konsinyasi`) REFERENCES `tbl_barang_konsinyasi` (`kode_brg_konsinyasi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
