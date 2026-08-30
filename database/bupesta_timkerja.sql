-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 28, 2026 at 07:25 AM
-- Server version: 8.0.46
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bupestaw_bupesta`
--

-- --------------------------------------------------------

--
-- Table structure for table `bupesta_timkerja`
--

CREATE TABLE `bupesta_timkerja` (
  `kode_tim_kerja` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_tim_kerja` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_tim_kerja` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip_ketua_tim` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1100` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1101` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1102` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1103` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1104` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1105` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1106` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1107` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1108` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1109` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1110` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1111` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1112` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1113` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1114` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1115` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1116` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1117` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1118` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1171` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1172` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1173` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1174` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1175` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bupesta_timkerja`
--

INSERT INTO `bupesta_timkerja` (`kode_tim_kerja`, `nama_tim_kerja`, `icon_tim_kerja`, `tahun`, `nip_ketua_tim`, `status`, `ketuatim_1100`, `ketuatim_1101`, `ketuatim_1102`, `ketuatim_1103`, `ketuatim_1104`, `ketuatim_1105`, `ketuatim_1106`, `ketuatim_1107`, `ketuatim_1108`, `ketuatim_1109`, `ketuatim_1110`, `ketuatim_1111`, `ketuatim_1112`, `ketuatim_1113`, `ketuatim_1114`, `ketuatim_1115`, `ketuatim_1116`, `ketuatim_1117`, `ketuatim_1118`, `ketuatim_1171`, `ketuatim_1172`, `ketuatim_1173`, `ketuatim_1174`, `ketuatim_1175`) VALUES
('2024s101', 'Umum', '<i class=\"fa-solid fa-briefcase fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2024s102', 'Statistik Sosial', '<i class=\"fa-solid fa-people-group fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2024s103', 'Statistik Harga, Distribusi dan Jasa', '<i class=\"fa-solid fa-truck-plane fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2024s104', 'Statistik Produksi', '<i class=\"fa-solid fa-seedling fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2024s105', 'Pengolahan dan TI', '<i class=\"fa-solid fa-computer fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2024s106', 'Neraca Wilayah dan Analisis', '<i class=\"fa-solid fa-scale-balanced fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2024s107', 'Diseminasi Statistik', '<i class=\"fa-solid fa-comments fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2024s108', 'Transformasi dan Budaya Organisasi', '<i class=\"fa-solid fa-shuffle fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2024s109', 'Pembinaan Statistik Sektoral', '<i class=\"fa-solid fa-handshake fa-xl\"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2025s101', 'Umum', '<i class=\"fa-solid fa-briefcase fa-xl\"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2025s102', 'Statistik Sosial', '<i class=\"fa-solid fa-people-group fa-xl\"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2025s103', 'Statistik Harga, Distribusi dan Jasa', '<i class=\"fa-solid fa-truck-plane fa-xl\"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2025s104', 'Statistik Produksi', '<i class=\"fa-solid fa-seedling fa-xl\"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2025s105', 'Metodologi, Pengolahan, Infrastruktur dan Inovasi Digital', '<i class=\"fa-solid fa-computer fa-xl\"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2025s106', 'Neraca Wilayah dan Analisis', '<i class=\"fa-solid fa-scale-balanced fa-xl\"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2025s107', 'Diseminasi dan Pelayanan Statistik', '<i class=\"fa-solid fa-comments fa-xl\"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s101', 'Umum', '<i class=\"fa-solid fa-briefcase fa-xl\"></i>', '2026', '197405171996121001', '1', NULL, '199302122017011001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '198005302011011007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '198211052011011007', NULL, NULL, '198902182010122008', NULL, NULL),
('2026s102', 'Statistik Sosial', '<i class=\"fa-solid fa-people-group fa-xl\"></i>', '2026', '196712291991121001', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '197611132002122004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s103', 'Statistik Harga, Distribusi dan Jasa', '<i class=\"fa-solid fa-truck-plane fa-xl\"></i>', '2026', '198410312008011005', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s104', 'Statistik Produksi', '<i class=\"fa-solid fa-seedling fa-xl\"></i>', '2026', '198306042006021003', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s105', 'Metodologi dan Sistem Informasi Statistik', '<i class=\"fa-solid fa-computer fa-xl\"></i>', '2026', '197307011995121001', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '199702262019121001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s106', 'Neraca Wilayah dan Analisis', '<i class=\"fa-solid fa-scale-balanced fa-xl\"></i>', '2026', '198605172008012002', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s107', 'Diseminasi, Pemberdayaan, dan Evaluasi Penyelenggaraan Statistik', '<i class=\"fa-solid fa-comments fa-xl\"></i>', '2026', '198504142009021005', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s108', 'Zona Integritas', NULL, '2026', '198308122006022001', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s109', 'Manajemen Resiko', NULL, '2026', '198505122009021003', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s110', 'Change Agen Network (CAN)', '', '2026', '198207102011012017', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2026s111', 'Sensus Ekonomi 2026', '', '2026', '197808032002121006', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '198310042011011011', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
