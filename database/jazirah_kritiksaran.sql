-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table bupesta.jazirah_kritiksaran
CREATE TABLE IF NOT EXISTS `jazirah_kritiksaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nip_pegawai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('kritik','saran','masukan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'masukan',
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.jazirah_kritiksaran: ~2 rows (approximately)
REPLACE INTO `jazirah_kritiksaran` (`id`, `nip_pegawai`, `jenis`, `pesan`, `created_at`, `updated_at`) VALUES
	(1, '199503132019032001', 'saran', 'Tambahkan fitur sdadasdasdas', '2026-09-28 05:09:38', '2026-09-28 05:09:38'),
	(2, '199503132019032001', 'saran', 'agsu', '2026-09-28 05:09:56', '2026-09-28 05:09:56');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
