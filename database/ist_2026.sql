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

-- Dumping structure for table bupesta.ist_kandidat_tahap2
CREATE TABLE IF NOT EXISTS `ist_kandidat_tahap2` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `periode_id` bigint unsigned NOT NULL,
  `nip_kandidat` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.ist_kandidat_tahap2: ~0 rows (approximately)

-- Dumping structure for table bupesta.ist_penilaian_tahap1
CREATE TABLE IF NOT EXISTS `ist_penilaian_tahap1` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `periode_id` bigint unsigned NOT NULL,
  `nip_pemilih` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_kandidat` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skor_kuesioner` int NOT NULL,
  `detail_jawaban` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.ist_penilaian_tahap1: ~0 rows (approximately)

-- Dumping structure for table bupesta.ist_periode
CREATE TABLE IF NOT EXISTS `ist_periode` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tahun` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `informasi_persiapan` text COLLATE utf8mb4_unicode_ci,
  `mulai_tahap1_1` date DEFAULT NULL,
  `akhir_tahap1_1` date DEFAULT NULL,
  `mulai_tahap1_2` date DEFAULT NULL,
  `akhir_tahap1_2` date DEFAULT NULL,
  `mulai_tahap2_1` date DEFAULT NULL,
  `akhir_tahap2_1` date DEFAULT NULL,
  `mulai_tahap2_2` date DEFAULT NULL,
  `akhir_tahap2_2` date DEFAULT NULL,
  `tanggal_pengumuman` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.ist_periode: ~1 rows (approximately)

-- Dumping structure for table bupesta.ist_pertanyaan
CREATE TABLE IF NOT EXISTS `ist_pertanyaan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pertanyaan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.ist_pertanyaan: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
