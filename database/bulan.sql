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

-- Dumping structure for table bupesta.bulan
CREATE TABLE IF NOT EXISTS `bulan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_bulan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bulan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `singkatan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.bulan: ~12 rows (approximately)
INSERT INTO `bulan` (`id`, `kode_bulan`, `nama_bulan`, `singkatan`) VALUES
	(1, '1', 'Januari', 'Jan'),
	(2, '2', 'Februari', 'Feb'),
	(3, '3', 'Maret', 'Mar'),
	(4, '4', 'April', 'Apr'),
	(5, '5', 'Mei', 'Mei'),
	(6, '6', 'Juni', 'Jun'),
	(7, '7', 'Juli', 'Jul'),
	(8, '8', 'Agustus', 'Agt'),
	(9, '9', 'September', 'Sep'),
	(10, '10', 'Oktober', 'Okt'),
	(11, '11', 'November', 'Nov'),
	(12, '12', 'Desember', 'Des');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
