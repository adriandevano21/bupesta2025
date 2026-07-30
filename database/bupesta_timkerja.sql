-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for bupesta
CREATE DATABASE IF NOT EXISTS `bupestaw_bupesta` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bupestaw_bupesta`;

-- Dumping structure for table bupesta.bupesta_timkerja
CREATE TABLE IF NOT EXISTS `bupesta_timkerja` (
  `kode_tim_kerja` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_tim_kerja` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_tim_kerja` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip_ketua_tim` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1100` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1101` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1102` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1103` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1104` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1105` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1106` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1107` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1108` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1109` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1110` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1111` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1112` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1113` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1114` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1115` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1116` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1117` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1118` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1171` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1172` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1173` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1174` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketuatim_1175` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.bupesta_timkerja: ~27 rows (approximately)
INSERT INTO `bupesta_timkerja` (`kode_tim_kerja`, `nama_tim_kerja`, `icon_tim_kerja`, `tahun`, `nip_ketua_tim`, `status`, `ketuatim_1100`, `ketuatim_1101`, `ketuatim_1102`, `ketuatim_1103`, `ketuatim_1104`, `ketuatim_1105`, `ketuatim_1106`, `ketuatim_1107`, `ketuatim_1108`, `ketuatim_1109`, `ketuatim_1110`, `ketuatim_1111`, `ketuatim_1112`, `ketuatim_1113`, `ketuatim_1114`, `ketuatim_1115`, `ketuatim_1116`, `ketuatim_1117`, `ketuatim_1118`, `ketuatim_1171`, `ketuatim_1172`, `ketuatim_1173`, `ketuatim_1174`, `ketuatim_1175`) VALUES
	('2024s101', 'Umum', '<i class="fa-solid fa-briefcase fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2024s102', 'Statistik Sosial', '<i class="fa-solid fa-people-group fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2024s103', 'Statistik Harga, Distribusi dan Jasa', '<i class="fa-solid fa-truck-plane fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2024s104', 'Statistik Produksi', '<i class="fa-solid fa-seedling fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2024s105', 'Pengolahan dan TI', '<i class="fa-solid fa-computer fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2024s106', 'Neraca Wilayah dan Analisis', '<i class="fa-solid fa-scale-balanced fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2024s107', 'Diseminasi Statistik', '<i class="fa-solid fa-comments fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2024s108', 'Transformasi dan Budaya Organisasi', '<i class="fa-solid fa-shuffle fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2024s109', 'Pembinaan Statistik Sektoral', '<i class="fa-solid fa-handshake fa-xl"></i>', '2024', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2025s101', 'Umum', '<i class="fa-solid fa-briefcase fa-xl"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2025s102', 'Statistik Sosial', '<i class="fa-solid fa-people-group fa-xl"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2025s103', 'Statistik Harga, Distribusi dan Jasa', '<i class="fa-solid fa-truck-plane fa-xl"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2025s104', 'Statistik Produksi', '<i class="fa-solid fa-seedling fa-xl"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2025s105', 'Metodologi, Pengolahan, Infrastruktur dan Inovasi Digital', '<i class="fa-solid fa-computer fa-xl"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2025s106', 'Neraca Wilayah dan Analisis', '<i class="fa-solid fa-scale-balanced fa-xl"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2025s107', 'Diseminasi dan Pelayanan Statistik', '<i class="fa-solid fa-comments fa-xl"></i>', '2025', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s101', 'Umum', '<i class="fa-solid fa-briefcase fa-xl"></i>', '2026', '197405171996121001', '1', NULL, '199302122017011001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '198005302011011007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '198211052011011007', NULL, NULL, NULL, NULL, NULL),
	('2026s102', 'Statistik Sosial', '<i class="fa-solid fa-people-group fa-xl"></i>', '2026', '196712291991121001', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '197611132002122004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s103', 'Statistik Harga, Distribusi dan Jasa', '<i class="fa-solid fa-truck-plane fa-xl"></i>', '2026', '198410312008011005', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s104', 'Statistik Produksi', '<i class="fa-solid fa-seedling fa-xl"></i>', '2026', '198306042006021003', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s105', 'Metodologi dan Sistem Informasi Statistik', '<i class="fa-solid fa-computer fa-xl"></i>', '2026', '197307011995121001', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s106', 'Neraca Wilayah dan Analisis', '<i class="fa-solid fa-scale-balanced fa-xl"></i>', '2026', '198605172008012002', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s107', 'Diseminasi, Pemberdayaan, dan Evaluasi Penyelenggaraan Statistik', '<i class="fa-solid fa-comments fa-xl"></i>', '2026', '198504142009021005', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s108', 'Zona Integritas', NULL, '2026', '198308122006022001', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s109', 'Manajemen Resiko', NULL, '2026', '198505122009021003', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s110', 'Change Agen Network (CAN)', '', '2026', '198207102011012017', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	('2026s111', 'Sensus Ekonomi 2026', '', '2026', '197808032002121006', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
