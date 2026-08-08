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

-- Dumping structure for table bupesta.bupesta_hukdis
CREATE TABLE IF NOT EXISTS `bupesta_hukdis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nip_bps` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satker` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hukuman` text COLLATE utf8mb4_unicode_ci,
  `tmt_mulai` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bupesta.bupesta_hukdis: ~70 rows (approximately)
INSERT INTO `bupesta_hukdis` (`id`, `nip_bps`, `nama`, `satker`, `jenis`, `hukuman`, `tmt_mulai`, `created_at`, `updated_at`) VALUES
	(1, '060113060', 'Bambang Wahyudi, SE', 'BPS Provinsi Aceh', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(2, '169999999', 'Saiful Bahri, S.Sos.', 'BPS Kabupaten Pidie', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2021-10-05', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(3, '340012636', 'Jarlian', 'BPS Kabupaten Simeulue', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-07-25', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(4, '340012968', 'Adli', 'BPS Kabupaten Aceh Besar', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2015-12-07', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(5, '340012968', 'Adli', 'BPS Kabupaten Aceh Besar', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2021-06-14', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(6, '340012968', 'Adli', 'BPS Kabupaten Aceh Besar', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-03-13', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(7, '340013022', 'Nurzakiah, SE', 'BPS Provinsi Aceh', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(8, '340013023', 'Zahniar Abdullah Aqsa', 'BPS Kabupaten Aceh Timur', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-06-05', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(9, '340013023', 'Zahniar Abdullah Aqsa', 'BPS Kabupaten Aceh Timur', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2025-04-30', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(10, '340013026', 'Yusmadi', 'BPS Kota Lhokseumawe', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-09', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(11, '340013026', 'Yusmadi', 'BPS Kota Lhokseumawe', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-24', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(12, '340014049', 'Zulkarnain', '', 'Hukuman Disiplin Sedang', 'Penundaan Kenaikan Pangkat Selama 1 Tahun (PP 53/06-06-2010)', '2025-03-21', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(13, '340014490', 'Djamaluddin', 'BPS Kabupaten Simeulue', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-07-25', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(14, '340014873', 'Tedi Herdiawan, S.Sos, M.A.P', 'BPS Kota Banda Aceh', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2010-07-01', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(15, '340014873', 'Tedi Herdiawan, S.Sos, M.A.P', 'BPS Kota Banda Aceh', 'Sanksi Administratif Ringan', 'Teguran Lisan', '2020-12-01', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(16, '340015213', 'Musliyadi', 'BPS Kabupaten Aceh Timur', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2025-04-30', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(17, '340017101', 'Ikhwan, SE', 'BPS Kabupaten Aceh Utara', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-10', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(18, '340018216', 'Raiski Ramadhoni, S.E., M.AP.', 'BPS Provinsi Aceh', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(19, '340018217', 'Hafni Zahra, S.E', 'BPS Kabupaten Pidie', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-04-21', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(20, '340018902', 'Darwin Dani', '', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2014-09-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(21, '340018969', 'Rais Fahmi, S.P', '', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2020-12-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(22, '340019110', 'Rizal Rahmad, SST, M.Stat', 'BPS Kabupaten Pidie', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-04-21', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(23, '340019110', 'Rizal Rahmad, SST, M.Stat', 'BPS Kabupaten Pidie', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-03-19', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(24, '340019578', 'Muzakkir, S.E.', 'BPS Kabupaten Aceh Timur', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2025-04-30', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(25, '340019581', 'Muhar Fuadhi Zaima', 'BPS Kabupaten Aceh Utara', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-06-01', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(26, '340019582', 'Riswan Amin', 'BPS Kabupaten Aceh Simeulue', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-12', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(27, '340019582', 'Riswan Amin', 'BPS Kabupaten Aceh Simeulue', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2025-08-22', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(28, '340019582', 'Riswan Amin', 'BPS Kabupaten Aceh Simeulue', 'Hukuman Disiplin Sedang', 'Penundaan Kenaikan gaji berkala  paling lama 1 tahun (PP 30)', '2025-10-10', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(29, '340019710', 'Hervan Syachri', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2019-09-05', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(30, '340019874', 'Armia', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2012-09-05', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(31, '340019888', 'Jamaluddin', '', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2014-02-07', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(32, '340019962', 'Aang Saputra, SST, M.Si.', 'BPS Kabupaten Aceh Jaya', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-12', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(33, '340019964', 'Fitral Pratomo, SST', 'BPS Provinsi Aceh', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(34, '340019966', 'M. Alimuddin, SST, M.T', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2012-09-05', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(35, '340019970', 'Darwis Abubakar, SE, M.Si', 'BPS Provinsi Aceh', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(36, '340019984', 'Muhammad Nasyiruddin, S.M.', 'BPS Kabupaten Singkil', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-24', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(37, '340019984', 'Muhammad Nasyiruddin, S.M.', 'BPS Kabupaten Singkil', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2025-10-27', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(38, '340019984', 'Muhammad Nasyiruddin, S.M.', 'BPS Kabupaten Singkil', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-09-30', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(39, '340019984', 'Muhammad Nasyiruddin, S.M.', 'BPS Kabupaten Singkil', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-08-15', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(40, '340019989', 'Nensi Fitrah', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2015-12-07', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(41, '340020483', 'Fitriani', 'BPS Kabupaten Pidie', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-04-24', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(42, '340050109', 'Hendra Dharmawan, SST, M.Si.', 'BPS Provinsi Aceh', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(43, '340052095', 'Hamdani', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2020-12-02', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(44, '340019532', 'Hamdani', 'BPS Kabupaten Aceh Utara', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-10', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(45, '340052126', 'Ibrahim', 'BPS Kota Subulussalam', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(46, '340052321', 'Yusrizal', 'BPS Kabupaten Aceh Timur', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-30', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(47, '340053002', 'Ari Hermawan', '', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2019-10-03', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(48, '340053002', 'Ari Hermawan', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2021-03-26', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(49, '340053005', 'Muhammad Ikhwani', 'BPS Kota Lhokseumawe', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-09', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(50, '340053005', 'Muhammad Ikhwani', 'BPS Kota Lhokseumawe', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-24', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(51, '340054455', 'Nur Asiah, SP', '', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2016-06-24', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(52, '340054479', 'Zulfiadi, SE', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2017-07-04', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(53, '340056959', 'Haris Noprianto, SST', 'BPS Kota Langsa', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2025-07-15', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(54, '340056990', 'Andy Syarifuddin, SST, M.Ak.', 'BPS Kabupaten Nagan Raya', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-26', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(55, '340057446', 'Kamil Aulia, SST', '', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2017-02-21', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(56, '340058032', 'Adelin Namira, SST', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2017-03-24', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(57, '340058710', 'Erisya Desi Deria, S.Tr.Stat.', 'BPS Kabupaten Aceh Singkil', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-03-24', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(58, '340058850', 'Muhammad Agus Mauliza, S.Tr.Stat.', '', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2019-01-01', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(59, '340059384', 'Ahmad Kamal, S.Tr.Stat.', '', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2023-12-29', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(60, '340059712', 'Qori Nur Laeli, S.Tr.Stat.', 'BPS Kabupaten Aceh Singkil', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-24', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(61, '340060670', 'Hajrul Multazam, S.Tr.Stat.', 'BPS Kabupaten Pidie', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-21', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(62, '340061064', 'Andi Arif Ginting, A.Md.Kb.N.', 'BPS Provinsi Aceh', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(63, '340061065', 'Andini Anastasya Iskandar, A.Md.Kb.N.', '', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2023-12-29', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(64, '340061065', 'Andini Anastasya Iskandar, A.Md.Kb.N.', '', 'Hukuman Disiplin Ringan', 'Pernyataan  tidak puas secara tertulis (PP30/PP53/PP94)', '2025-03-04', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(65, '340061440', 'Muhammad Yusuf, A.Md', 'BPS Kabupaten Nagan Raya', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-26', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(66, '340061461', 'Nurhasanah, S.E.', 'BPS Kabupaten Aceh Utara', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-06-01', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(67, '340061947', 'Mutia Soraya Adila, S.Tr.Stat.', 'BPS Kota Subulussalam', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-17', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(68, '340061988', 'Pinky Aulia Viradina, S.Tr.Stat.', 'BPS Kabupaten Aceh Utara', 'Hukuman Disiplin Ringan', 'Teguran Tertulis (PP30/PP53/PP94)', '2025-04-10', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(69, '340062317', 'Alfajri Hidayat, S.Tr.Stat.', 'BPS Kabupaten Aceh Timur', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-04-30', '2026-08-08 03:25:25', '2026-08-08 03:25:25'),
	(70, '340062320', 'Alicia Dwi Syafitri, S.Tr.Stat.', 'BPS Kabupaten Pidie Jaya', 'Hukuman Disiplin Ringan', 'Teguran Lisan (PP30/PP53/PP94)', '2025-03-18', '2026-08-08 03:25:25', '2026-08-08 03:25:25');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
