-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2019 at 04:42 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tugas_webprogram`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `kd_brg` char(30) NOT NULL,
  `nm_brg` varchar(100) NOT NULL,
  `hrg_brg` int(11) NOT NULL,
  `kd_kat` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`kd_brg`, `nm_brg`, `hrg_brg`, `kd_kat`) VALUES
('brg_5dd6bdcb9b281', 'Good Day', 6700, 'kat_5dd6bdcb9bc45');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `kd_kat` char(30) NOT NULL,
  `nm_kat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`kd_kat`, `nm_kat`) VALUES
('kat_5dd6bdcb9bc45', 'Minuman');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `kd_pel` char(30) NOT NULL,
  `nm_pel` varchar(100) NOT NULL,
  `almt_pel` varchar(256) NOT NULL,
  `no_telp` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`kd_pel`, `nm_pel`, `almt_pel`, `no_telp`) VALUES
('pel_5dd6bdf2a2043', 'aziz ruri suparman', 'Kelapa Gading', '085891800396');

-- --------------------------------------------------------

--
-- Table structure for table `tb_order`
--

CREATE TABLE `tb_order` (
  `kd_tran` char(30) NOT NULL,
  `kd_brg` varchar(1000) NOT NULL,
  `kd_pel` char(30) NOT NULL,
  `jml` varchar(100) NOT NULL,
  `hrg_byr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_order`
--

INSERT INTO `tb_order` (`kd_tran`, `kd_brg`, `kd_pel`, `jml`, `hrg_byr`) VALUES
('tran_5dd6bdf2a2a07', 'brg_5dd6bdcb9b281', 'pel_5dd6bdf2a2043', '10', 67000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` char(30) NOT NULL,
  `username` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `password` varchar(365) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `name`, `password`) VALUES
('usr_ddd38f3e6e5a4', 'ueukhi', 'Universitas Esa Unggul', '$2y$10$74x4zApqWu2YyQK/XKTMO.NtQRYFiBBYUmvMLx1JXfZuQ43z4fpxW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`kd_brg`),
  ADD KEY `category` (`kd_kat`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`kd_kat`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`kd_pel`);

--
-- Indexes for table `tb_order`
--
ALTER TABLE `tb_order`
  ADD PRIMARY KEY (`kd_tran`),
  ADD KEY `barang` (`kd_brg`),
  ADD KEY `pelanggan` (`kd_pel`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `category` FOREIGN KEY (`kd_kat`) REFERENCES `category` (`kd_kat`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
