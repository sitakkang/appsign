-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2021 at 08:15 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sign`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_surat_keluar`
--

CREATE TABLE `app_surat_keluar` (
  `id_surat_keluar` int(10) NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `perihal` tinytext NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `disetujui` varchar(100) DEFAULT NULL,
  `diusulkan` varchar(35) NOT NULL,
  `melalui` varchar(25) NOT NULL,
  `tgl_kirim` date DEFAULT NULL,
  `disposisi` varchar(100) NOT NULL,
  `catatan` mediumtext NOT NULL,
  `status` tinyint(3) NOT NULL,
  `flag` tinyint(2) NOT NULL,
  `attach1` varchar(100) DEFAULT NULL,
  `attach2` varchar(100) DEFAULT NULL,
  `attach3` varchar(100) DEFAULT NULL,
  `attach4` varchar(100) DEFAULT NULL,
  `attach5` varchar(100) DEFAULT NULL,
  `attach6` varchar(100) DEFAULT NULL,
  `attach7` varchar(100) DEFAULT NULL,
  `attach8` varchar(100) DEFAULT NULL,
  `attach9` varchar(100) DEFAULT NULL,
  `attach10` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_surat_masuk`
--

CREATE TABLE `app_surat_masuk` (
  `id_surat_masuk` int(10) NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `perihal` tinytext NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `ditujukan` varchar(100) DEFAULT NULL,
  `penerima` varchar(35) NOT NULL,
  `melalui` varchar(25) NOT NULL,
  `tgl_terima` date DEFAULT NULL,
  `disposisi` varchar(100) NOT NULL,
  `catatan` mediumtext NOT NULL,
  `status` tinyint(3) NOT NULL,
  `flag` tinyint(2) NOT NULL,
  `attach1` varchar(100) DEFAULT NULL,
  `attach2` varchar(100) DEFAULT NULL,
  `attach3` varchar(100) DEFAULT NULL,
  `attach4` varchar(100) DEFAULT NULL,
  `attach5` varchar(100) DEFAULT NULL,
  `attach6` varchar(100) DEFAULT NULL,
  `attach7` varchar(100) DEFAULT NULL,
  `attach8` varchar(100) DEFAULT NULL,
  `attach9` varchar(100) DEFAULT NULL,
  `attach10` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_surat_masuk`
--

INSERT INTO `app_surat_masuk` (`id_surat_masuk`, `no_surat`, `perihal`, `jenis`, `pengirim`, `ditujukan`, `penerima`, `melalui`, `tgl_terima`, `disposisi`, `catatan`, `status`, `flag`, `attach1`, `attach2`, `attach3`, `attach4`, `attach5`, `attach6`, `attach7`, `attach8`, `attach9`, `attach10`) VALUES
(2, '3123', 'Info', 'Pemberitahuan', 'Patar', NULL, 'Martina', 'Hardcopy', '2021-03-08', '', '', 0, 0, '2021/03/2kDqsVR3RH.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conf_level`
--

CREATE TABLE `conf_level` (
  `id_level` tinyint(2) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `conf_level`
--

INSERT INTO `conf_level` (`id_level`, `name`) VALUES
(1, 'Superadmin'),
(2, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `conf_menu`
--

CREATE TABLE `conf_menu` (
  `id_menu` int(10) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `icon2` varchar(150) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `link` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `akses` tinyint(1) NOT NULL,
  `sub` tinyint(1) NOT NULL,
  `level` text NOT NULL,
  `position` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `conf_menu`
--

INSERT INTO `conf_menu` (`id_menu`, `icon`, `icon2`, `name`, `link`, `status`, `akses`, `sub`, `level`, `position`) VALUES
(1, 'fa-desktop', '', 'Dashboard', 'home', 1, 1, 1, '\"1\",\"2\"', 1),
(2, 'fa-cogs', '', 'Configuration', 'admin/gen_modul', 1, 1, 1, '\"1\",\"2\"', 2),
(3, 'fa-inbox', NULL, 'Surat Masuk', 'admin/surat_masuk', 1, 1, 1, '\"1\",\"2\"', 3),
(4, 'fa-envelope-open-text', NULL, 'Surat Keluar', 'admin/surat_keluar', 1, 1, 1, '\"1\",\"2\"', 4),
(5, 'fa-file-signature', NULL, 'Signer', 'admin/signer', 1, 1, 1, '\"1\",\"2\"', 5);

-- --------------------------------------------------------

--
-- Table structure for table `conf_submenu`
--

CREATE TABLE `conf_submenu` (
  `id_submenu` int(5) NOT NULL,
  `id_menu` int(5) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `icon2` varchar(150) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `link` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `level` text NOT NULL,
  `position` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `conf_users`
--

CREATE TABLE `conf_users` (
  `id_user` int(10) NOT NULL,
  `fullname` varchar(60) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `salt` varchar(15) NOT NULL,
  `level` tinyint(2) NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `conf_users`
--

INSERT INTO `conf_users` (`id_user`, `fullname`, `avatar`, `username`, `password`, `salt`, `level`, `last_login`, `ip_address`, `status`) VALUES
(1, 'Superadmin', 'img/avatar/6U6lk2At.jpg', 'admin', '89a0c6ee2ad740022ce185004dd64cca98c04b51', 'Wb8e.?s5', 1, '2021-03-09 09:15:31', '::1', 1),
(2, 'Ardi', '', 'ardi', '00cc677ebf28c2788351082fe42ccc8982437a9c', '+qt_a0Wy', 1, '0000-00-00 00:00:00', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `temp_login`
--

CREATE TABLE `temp_login` (
  `id_temp` int(10) NOT NULL,
  `id_user` int(5) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  `nama_user` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_login`
--

INSERT INTO `temp_login` (`id_temp`, `id_user`, `tanggal`, `ip_address`, `nama_user`) VALUES
(1, 1, '2021-03-08 16:50:13', '::1', 'Superadmin'),
(2, 1, '2021-03-08 20:53:56', '::1', 'Superadmin'),
(3, 1, '2021-03-08 22:47:20', '::1', 'Superadmin'),
(4, 1, '2021-03-09 09:15:31', '::1', 'Superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `t_signer`
--

CREATE TABLE `t_signer` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_signer`
--

INSERT INTO `t_signer` (`id`, `name`, `email`) VALUES
(1, 'Scmaichel', 'Scmaichels@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_surat_keluar`
--
ALTER TABLE `app_surat_keluar`
  ADD PRIMARY KEY (`id_surat_keluar`);

--
-- Indexes for table `app_surat_masuk`
--
ALTER TABLE `app_surat_masuk`
  ADD PRIMARY KEY (`id_surat_masuk`);

--
-- Indexes for table `conf_level`
--
ALTER TABLE `conf_level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `conf_menu`
--
ALTER TABLE `conf_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `conf_submenu`
--
ALTER TABLE `conf_submenu`
  ADD PRIMARY KEY (`id_submenu`);

--
-- Indexes for table `conf_users`
--
ALTER TABLE `conf_users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `temp_login`
--
ALTER TABLE `temp_login`
  ADD PRIMARY KEY (`id_temp`);

--
-- Indexes for table `t_signer`
--
ALTER TABLE `t_signer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_index` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_surat_keluar`
--
ALTER TABLE `app_surat_keluar`
  MODIFY `id_surat_keluar` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `app_surat_masuk`
--
ALTER TABLE `app_surat_masuk`
  MODIFY `id_surat_masuk` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `conf_level`
--
ALTER TABLE `conf_level`
  MODIFY `id_level` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `conf_menu`
--
ALTER TABLE `conf_menu`
  MODIFY `id_menu` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `conf_submenu`
--
ALTER TABLE `conf_submenu`
  MODIFY `id_submenu` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conf_users`
--
ALTER TABLE `conf_users`
  MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `temp_login`
--
ALTER TABLE `temp_login`
  MODIFY `id_temp` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_signer`
--
ALTER TABLE `t_signer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
