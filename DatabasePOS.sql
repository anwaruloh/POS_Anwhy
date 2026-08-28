-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               11.8.8-MariaDB - MariaDB Server
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


-- Dumping database structure for pos_bang_rulz
CREATE DATABASE IF NOT EXISTS `pos_bang_rulz` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */;
USE `pos_bang_rulz`;

-- Dumping structure for table pos_bang_rulz.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.cache: ~0 rows (approximately)

-- Dumping structure for table pos_bang_rulz.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.cache_locks: ~0 rows (approximately)

-- Dumping structure for table pos_bang_rulz.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table pos_bang_rulz.item_penjualan
CREATE TABLE IF NOT EXISTS `item_penjualan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_id` bigint(20) unsigned NOT NULL,
  `produk_id` bigint(20) unsigned NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_penjualan_penjualan_id_foreign` (`penjualan_id`),
  KEY `item_penjualan_produk_id_foreign` (`produk_id`),
  CONSTRAINT `item_penjualan_penjualan_id_foreign` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`),
  CONSTRAINT `item_penjualan_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.item_penjualan: ~4 rows (approximately)
INSERT INTO `item_penjualan` (`id`, `penjualan_id`, `produk_id`, `kuantitas`, `harga_satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
	(8, 5, 3, 1, 9000000, 9000000, '2026-08-10 23:22:36', '2026-08-10 23:22:36'),
	(15, 9, 1, 1, 4500000, 4500000, '2026-08-11 00:31:53', '2026-08-11 00:31:53'),
	(16, 10, 1, 1, 4500000, 4500000, '2026-08-17 21:16:48', '2026-08-17 21:16:48'),
	(21, 11, 12, 1, 130000, 130000, '2026-08-27 20:00:45', '2026-08-27 20:00:45');

-- Dumping structure for table pos_bang_rulz.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.jobs: ~0 rows (approximately)

-- Dumping structure for table pos_bang_rulz.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.job_batches: ~0 rows (approximately)

-- Dumping structure for table pos_bang_rulz.kategoris
CREATE TABLE IF NOT EXISTS `kategoris` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.kategoris: ~8 rows (approximately)
INSERT INTO `kategoris` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
	(1, 'Blok Seher', NULL, NULL),
	(2, 'Ecu', NULL, NULL),
	(3, 'Velg', NULL, NULL),
	(4, 'Oli', NULL, NULL),
	(5, 'Ban', '2026-08-10 20:40:58', '2026-08-10 20:40:58'),
	(6, 'Sensor TPS', '2026-08-10 23:59:38', '2026-08-10 23:59:38'),
	(8, 'V-Belt', '2026-08-11 00:30:12', '2026-08-11 00:30:12'),
	(9, 'gas spontan', '2026-08-11 00:55:43', '2026-08-11 00:55:43');

-- Dumping structure for table pos_bang_rulz.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.migrations: ~9 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_roles_table', 1),
	(2, '0001_01_01_000000_create_users_table', 1),
	(3, '0001_01_01_000001_create_cache_table', 1),
	(4, '0001_01_01_000002_create_jobs_table', 1),
	(5, '2026_07_22_065020_create_produk_table', 1),
	(6, '2026_07_22_065233_create_penjualan_table', 1),
	(7, '2026_07_22_065416_create_item_penjualan_table', 1),
	(8, '2026_07_29_053152_update_status_enum_in_penjualan_table', 1),
	(9, '2026_08_05_071235_create_supliers_table', 1),
	(10, '2026_08_11_025833_create_kategoris_table', 2);

-- Dumping structure for table pos_bang_rulz.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table pos_bang_rulz.penjualan
CREATE TABLE IF NOT EXISTS `penjualan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `total_pembayaran` int(11) NOT NULL,
  `metode_pembayaran` varchar(255) NOT NULL,
  `status` enum('OPEN','DRAFT','COMPLETED') DEFAULT 'OPEN',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_user_id_foreign` (`user_id`),
  CONSTRAINT `penjualan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.penjualan: ~4 rows (approximately)
INSERT INTO `penjualan` (`id`, `user_id`, `total_pembayaran`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES
	(5, 1, 9000000, 'TRANSFER', 'COMPLETED', '2026-08-10 23:22:36', '2026-08-10 23:22:41'),
	(9, 1, 4500000, 'QRIS', 'COMPLETED', '2026-08-11 00:31:53', '2026-08-11 00:32:14'),
	(10, 1, 4500000, 'CASH', 'COMPLETED', '2026-08-17 21:16:47', '2026-08-17 21:16:56'),
	(11, 1, 130000, 'CASH', 'COMPLETED', '2026-08-17 21:17:26', '2026-08-27 20:00:48');

-- Dumping structure for table pos_bang_rulz.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Kategori_id` bigint(20) unsigned DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL,
  `foto` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produk_user_id_foreign` (`user_id`),
  KEY `produk_nama_index` (`nama`),
  CONSTRAINT `produk_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.produk: ~11 rows (approximately)
INSERT INTO `produk` (`id`, `Kategori_id`, `user_id`, `foto`, `nama`, `harga_beli`, `harga_jual`, `stok`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'products/mHt6rktI2qKKGXdecNdNXSzYLyzT5P9Ov4noWy8I.jpg', 'Blok seher Uma Racing', 3900000, 4500000, 98, '2026-08-10 19:16:06', '2026-08-27 20:00:42'),
	(2, 1, 1, 'products/yIEVrjZK8d8ZQmPAaK9hMdLxgq4PJYsF3GIbT3Q5.jpg', 'Blok seher BRT', 900000, 1200000, 100, '2026-08-10 19:17:42', '2026-08-27 20:00:32'),
	(3, 2, 1, 'products/b3F4FVPIUjFH0XHSy7eh6jkR9bUFpbOKKlz7cvJH.jpg', 'Aracer super x', 8500000, 9000000, 49, '2026-08-10 19:21:23', '2026-08-27 20:00:40'),
	(4, 2, 1, 'products/w7IeqP9YZlSbmztK3eHjumClAx1dEIi3RwZhbXRp.jpg', 'Juken 5+', 750000, 1550000, 80, '2026-08-10 19:23:04', '2026-08-10 19:23:04'),
	(5, 3, 1, 'products/3hlUS0TOP39sJLeeSxH7Unp6BqyGpbnsy7bJCqnU.jpg', 'King Speed forged premium', 12000000, 13500000, 10, '2026-08-10 20:19:09', '2026-08-10 20:19:09'),
	(6, 3, 1, 'products/eDJJbKLau4iaf4QypE25z15il9rxRibHj8LMU7qc.jpg', 'VND Premium', 1600000, 2000000, 60, '2026-08-10 20:20:36', '2026-08-10 23:52:56'),
	(7, 4, 1, 'products/GsVyGgzuw8wir9Zw3R3wdBhG1zSSP8DOhw0sJTbe.jpg', 'Oli Deltalube', 70000, 100000, 80, '2026-08-10 20:22:06', '2026-08-10 20:22:06'),
	(8, 4, 1, 'products/wy9pQhoTsBF7xtWkIKaSzaqGT0xfOLyATqgsv4fL.jpg', 'Oli Shell', 55000, 95000, 90, '2026-08-10 20:22:43', '2026-08-10 20:22:43'),
	(10, 5, 1, 'products/tUbvGWW7iFAdzLBLmsIm2p3oaSMyQr7O5GCvyXNv.jpg', 'Sensor TPS Uma Racing', 450000, 500000, 100, '2026-08-11 00:01:13', '2026-08-11 00:01:13'),
	(11, NULL, 1, 'products/50D0OdzSrT9ZTJ20FgYgPr35dx7gj124I4nYf003.png', 'V-Belt YSP', 45000, 55000, 100, '2026-08-11 00:31:04', '2026-08-11 00:54:48'),
	(12, 9, 1, 'products/2vy4Yfemlh6T11CBapDFFLiRs3JloUQPjpPtGXBs.jpg', 'gas spontan daytona', 100000, 130000, 89, '2026-08-11 00:56:17', '2026-08-27 20:00:45'),
	(13, 8, 1, 'products/wfGb4whxi41zStAPcq2L6DC6HudJRlUyHRHJcr6n.jpg', 'v bely kw', 1345, 2345, 34, '2026-08-11 00:56:57', '2026-08-11 00:56:57');

-- Dumping structure for table pos_bang_rulz.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.roles: ~2 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', '2026-08-10 19:07:23', '2026-08-10 19:07:23'),
	(2, 'Kasir', '2026-08-10 19:07:23', '2026-08-10 19:07:23');

-- Dumping structure for table pos_bang_rulz.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.sessions: ~2 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('nWzQXnXTetGiiRI02YSZ8TKfAvSoYPC9yGJECYwj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJOMTU2b0JKSzZRSkpjM1lQQXVCVEc5aXl2THl1dHZLRVN2N3I5SVlNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAxXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1787885846),
	('SfD4mDZRl96JULHXpddyEycXl67LtOFJTg7iAGPg', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJZZkNSbkFPeGJhcXlaQlRjeFpVRDVSWUJ3UDBVelBYcjh2MHBHNnVSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAxXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1787886056);

-- Dumping structure for table pos_bang_rulz.supliers
CREATE TABLE IF NOT EXISTS `supliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_suplier` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.supliers: ~0 rows (approximately)

-- Dumping structure for table pos_bang_rulz.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  FULLTEXT KEY `users_name_email_fulltext` (`name`,`email`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_bang_rulz.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Anwar Admin', 'admin@gmail.com', NULL, '$2y$12$bP3i.DHf9NrqlMmyFWIflu0jmVdLCC7wrfn0Qn.FtMzyqxBN493f.', NULL, '2026-08-10 19:07:23', '2026-08-10 19:07:23'),
	(7, 1, 'Test User', 'test@example.com', '2026-08-10 19:07:24', '$2y$12$Kyuav0/ajNeZ8odpu060.eexL7TSIVtHv/8cNhcdhL9BQ9/lfJOLW', 's3VGh5E6vr', '2026-08-10 19:07:24', '2026-08-10 19:07:24'),
	(8, 2, 'Cathrine', 'cathrine@gmail.com', NULL, '$2y$12$De1AWi4/PhWqTFpaapONwOSAZH6zczIVMC72rPTQeW2Bb7V5pv8wC', NULL, '2026-08-10 19:10:01', '2026-08-10 19:10:01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
