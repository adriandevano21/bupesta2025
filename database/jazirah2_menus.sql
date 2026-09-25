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

-- Dumping structure for table bupesta.jazirah_menus
CREATE TABLE IF NOT EXISTS `jazirah_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.jazirah_menus: ~12 rows (approximately)
REPLACE INTO `jazirah_menus` (`id`, `title`, `url`, `bg`, `icon`, `urutan`, `created_at`, `updated_at`) VALUES
	(1, 'New Lembar Kerja', 'http://localhost/jazirah-lembarkerja', 'linear-gradient(135deg, #3b82f6, #06b6d4)', '<i class="fa-solid fa-file-lines"></i>', 1, NULL, NULL),
	(2, 'Seulanga', 'http://localhost/seulanga', 'linear-gradient(135deg, #fbbf24, #eab308)', '<i class="fa-solid fa-star"></i>', 2, NULL, NULL),
	(3, 'Rangkuman', 'http://localhost/rangkuman', 'linear-gradient(135deg, #6366f1, #2563eb)', '<i class="fa-solid fa-chart-pie"></i>', 3, NULL, NULL),
	(4, 'Pengisian Matriks Aksi', 'http://localhost/matriks-aksi', 'linear-gradient(135deg, #a855f7, #6366f1)', '<i class="fa-solid fa-pen-to-square"></i>', 4, NULL, NULL),
	(5, 'Pedoman ZI', 'http://localhost/pedoman-zi', 'linear-gradient(135deg, #34d399, #14b8a6)', '<i class="fa-solid fa-book"></i>', 5, NULL, NULL),
	(6, 'SOP', 'http://localhost/sop', 'linear-gradient(135deg, #fb7185, #ef4444)', '<i class="fa-solid fa-file-shield"></i>', 6, NULL, NULL),
	(7, 'LHE TPP ZI 2024', 'http://localhost/lhe-tpp-2024', 'linear-gradient(135deg, #d946ef, #9333ea)', '<i class="fa-solid fa-award"></i>', 7, NULL, NULL),
	(8, 'LKE Satker 2024', 'http://localhost/lke-satker-2024', 'linear-gradient(135deg, #fb923c, #c2410c)', '<i class="fa-solid fa-clipboard-check"></i>', 8, NULL, NULL),
	(9, 'Event Jazirah', 'http://localhost/jazirah/event', 'linear-gradient(135deg, #22d3ee, #3b82f6)', '<i class="fa-solid fa-calendar-days"></i>', 9, NULL, NULL),
	(10, 'Satker Lolos TPI', 'http://localhost/satker-tpi', 'linear-gradient(135deg, #4ade80, #059669)', '<i class="fa-solid fa-circle-check"></i>', 10, NULL, NULL),
	(11, 'QNA', 'http://localhost/qna', 'linear-gradient(135deg, #38bdf8, #06b6d4)', '<i class="fa-solid fa-circle-question"></i>', 11, NULL, NULL),
	(12, 'Narahubung', 'http://localhost/narahubung', 'linear-gradient(135deg, #2dd4bf, #10b981)', '<i class="fa-solid fa-headset"></i>', 12, NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
