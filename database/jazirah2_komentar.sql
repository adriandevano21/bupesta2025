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

-- Dumping structure for table bupesta.jazirah2_komentar
CREATE TABLE IF NOT EXISTS `jazirah2_komentar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_jazirah2_hasil` bigint unsigned NOT NULL,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `komentar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.jazirah2_komentar: ~2 rows (approximately)
REPLACE INTO `jazirah2_komentar` (`id`, `id_jazirah2_hasil`, `nip`, `komentar`, `created_at`, `updated_at`) VALUES
	(6, 4706, '198207142007011001', 'Mohon diperhatikan relevansi periode pada sertifikat kepesertaan SAKIP. Akan lebih tepat apabila ditampilkan sertifikat tahun 2026 sehingga bukti dukung yang disajikan mencerminkan kondisi terbaru dan sesuai dengan periode penilaian', '2026-09-24 06:18:20', '2026-09-24 06:18:20'),
	(9, 4706, '198207102011012017', 'Dokumen yang diminta adalah akumulasi kompetensi hingga tahun 2026 (artinya sertifikat sebelum 2026 juga dilampirkan jika ada), maka urutan pada sertifikat per individu kami urutkan dari 2026 2025 2024 dst.', '2026-09-24 06:24:24', '2026-09-24 06:24:24'),
	(10, 4706, '198207142007011001', 'Dokumen masih belum sesuai, tolong lengkapi tanda tangannya lagi', '2026-09-24 06:25:35', '2026-09-24 06:25:35'),
	(11, 4706, '198207102011012017', 'Oke baik sudah dilengkapi', '2026-09-24 06:27:52', '2026-09-24 06:27:52'),
	(12, 4706, '198207142007011001', 'Tolong sedikit lagi gabungkan menjadi 1 file', '2026-09-24 06:29:12', '2026-09-24 06:29:12'),
	(13, 4706, '198207142007011001', '99', '2026-09-24 06:33:22', '2026-09-24 06:33:22'),
	(14, 4706, '198306042006021003', 'Oke selesai ya', '2026-09-24 06:35:39', '2026-09-24 06:35:39'),
	(15, 4706, '198207142007011001', 'Belum masih harus diperiksa dalamnya', '2026-09-24 06:44:32', '2026-09-24 06:44:32'),
	(16, 4706, '198306042006021003', 'Oke sudah ya', '2026-09-24 06:52:20', '2026-09-24 06:52:20'),
	(17, 4706, '198207142007011001', 'Belum', '2026-09-24 06:57:29', '2026-09-24 06:57:29'),
	(18, 4706, '198306042006021003', 'Sudah', '2026-09-24 06:57:46', '2026-09-24 06:57:46'),
	(19, 4706, '198306042006021003', 'Masih beum', '2026-09-24 07:26:11', '2026-09-24 07:26:11'),
	(20, 4706, '198207142007011001', 'Belum bg', '2026-09-24 07:26:47', '2026-09-24 07:26:47'),
	(21, 4706, '198306042006021003', 'Sudah?', '2026-09-24 07:29:04', '2026-09-24 07:29:04'),
	(22, 4706, '198306042006021003', 'Oke Terimakasih', '2026-09-24 07:31:53', '2026-09-24 07:31:53'),
	(23, 4706, '199906212022011001', 'Validasi Dibatalkan', '2026-09-24 08:43:36', '2026-09-24 08:43:36'),
	(24, 4706, '199906212022011001', 'Validasi Dibatalkan', '2026-09-24 09:45:08', '2026-09-24 09:45:08'),
	(25, 4706, '199906212022011001', 'Validasi Dibatalkan', '2026-09-25 01:26:49', '2026-09-25 01:26:49'),
	(26, 4706, '199906212022011001', 'Validasi Dibatalkan', '2026-09-25 01:27:18', '2026-09-25 01:27:18');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
