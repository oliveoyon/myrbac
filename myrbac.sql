-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 17, 2025 at 08:48 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myrbac`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:5:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"d\";s:8:\"category\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:31:{i:0;a:5:{s:1:\"a\";i:1;s:1:\"b\";s:15:\"View Categories\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Category Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:5:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"Add Category\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Category Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:5:{s:1:\"a\";i:3;s:1:\"b\";s:13:\"Edit Category\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Category Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"Delete Category\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Category Management\";}i:4;a:5:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"View Districts\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"District Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"Add District\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"District Management\";}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:13:\"Edit District\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"District Management\";}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"Delete District\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"District Management\";}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"View PNGOs\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"PNGO Management\";}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:8:\"Add PNGO\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"PNGO Management\";}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:9:\"Edit PNGO\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"PNGO Management\";}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:11:\"Delete PNGO\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"PNGO Management\";}i:12;a:5:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"View Users\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"User Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:5:{s:1:\"a\";i:14;s:1:\"b\";s:8:\"Add User\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"User Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:5:{s:1:\"a\";i:15;s:1:\"b\";s:17:\"View User Details\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"User Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:5:{s:1:\"a\";i:16;s:1:\"b\";s:9:\"Edit User\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"User Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:5:{s:1:\"a\";i:17;s:1:\"b\";s:21:\"View User Permissions\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"User Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:5:{s:1:\"a\";i:18;s:1:\"b\";s:21:\"Edit User Permissions\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"User Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:5:{s:1:\"a\";i:19;s:1:\"b\";s:23:\"Update User Permissions\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"User Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:5:{s:1:\"a\";i:20;s:1:\"b\";s:10:\"View Roles\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Role Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:5:{s:1:\"a\";i:21;s:1:\"b\";s:8:\"Add Role\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Role Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:5:{s:1:\"a\";i:22;s:1:\"b\";s:9:\"Edit Role\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Role Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:5:{s:1:\"a\";i:23;s:1:\"b\";s:11:\"Delete Role\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Role Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:5:{s:1:\"a\";i:24;s:1:\"b\";s:16:\"View Permissions\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:21:\"Permission Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:5:{s:1:\"a\";i:25;s:1:\"b\";s:14:\"Add Permission\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:21:\"Permission Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:5:{s:1:\"a\";i:26;s:1:\"b\";s:15:\"Edit Permission\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:21:\"Permission Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:5:{s:1:\"a\";i:27;s:1:\"b\";s:17:\"Delete Permission\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:21:\"Permission Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:5:{s:1:\"a\";i:28;s:1:\"b\";s:23:\"Manage Role Permissions\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:26:\"Role Permission Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:5:{s:1:\"a\";i:29;s:1:\"b\";s:21:\"View Role Permissions\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:26:\"Role Permission Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:5:{s:1:\"a\";i:30;s:1:\"b\";s:21:\"Edit Role Permissions\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:26:\"Role Permission Management\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:5:{s:1:\"a\";i:31;s:1:\"b\";s:23:\"Update Role Permissions\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:26:\"Role Permission Management\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1742278451);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard Panel', NULL, '2025-03-12 21:20:20'),
(2, 'Category Management', NULL, NULL),
(3, 'District Management', NULL, NULL),
(4, 'PNGO Management', NULL, NULL),
(5, 'User Management', NULL, NULL),
(6, 'Role Management', NULL, NULL),
(7, 'Permission Management', NULL, NULL),
(8, 'Role Permission Management', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
CREATE TABLE IF NOT EXISTS `districts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Barishal', '2025-03-12 21:13:32', '2025-03-12 21:13:32'),
(2, 'Khulna', '2025-03-12 21:13:39', '2025-03-12 21:13:39'),
(3, 'Narsingdi', '2025-03-12 21:13:45', '2025-03-12 21:13:45'),
(4, 'Kumilla', '2025-03-12 21:13:50', '2025-03-12 21:13:50'),
(5, 'Rangpur', '2025-03-12 21:13:58', '2025-03-12 21:13:58');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_02_24_065012_create_districts_table', 1),
(5, '2025_02_24_065041_create_pngos_table', 1),
(6, '2025_03_06_070238_create_permission_tables', 1),
(7, '2025_03_12_152003_create_categories_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 1),
(13, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 1),
(15, 'App\\Models\\User', 1),
(16, 'App\\Models\\User', 1),
(17, 'App\\Models\\User', 1),
(18, 'App\\Models\\User', 1),
(19, 'App\\Models\\User', 1),
(20, 'App\\Models\\User', 1),
(21, 'App\\Models\\User', 1),
(22, 'App\\Models\\User', 1),
(23, 'App\\Models\\User', 1),
(24, 'App\\Models\\User', 1),
(25, 'App\\Models\\User', 1),
(26, 'App\\Models\\User', 1),
(27, 'App\\Models\\User', 1),
(28, 'App\\Models\\User', 1),
(29, 'App\\Models\\User', 1),
(30, 'App\\Models\\User', 1),
(31, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `category`, `created_at`, `updated_at`) VALUES
(1, 'View Categories', 'web', 'Category Management', '2025-03-12 21:34:04', '2025-03-12 21:34:04'),
(2, 'Add Category', 'web', 'Category Management', '2025-03-12 21:34:16', '2025-03-12 21:34:16'),
(3, 'Edit Category', 'web', 'Category Management', '2025-03-12 21:34:27', '2025-03-12 21:34:27'),
(4, 'Delete Category', 'web', 'Category Management', '2025-03-12 21:34:36', '2025-03-12 21:34:36'),
(5, 'View Districts', 'web', 'District Management', '2025-03-12 21:34:46', '2025-03-12 21:34:54'),
(6, 'Add District', 'web', 'District Management', '2025-03-12 21:35:07', '2025-03-12 21:35:07'),
(7, 'Edit District', 'web', 'District Management', '2025-03-12 21:35:16', '2025-03-12 21:35:16'),
(8, 'Delete District', 'web', 'District Management', '2025-03-12 21:35:25', '2025-03-12 21:35:25'),
(9, 'View PNGOs', 'web', 'PNGO Management', '2025-03-12 21:35:35', '2025-03-12 21:35:35'),
(10, 'Add PNGO', 'web', 'PNGO Management', '2025-03-12 21:35:47', '2025-03-12 21:35:47'),
(11, 'Edit PNGO', 'web', 'PNGO Management', '2025-03-12 21:35:56', '2025-03-12 21:35:56'),
(12, 'Delete PNGO', 'web', 'PNGO Management', '2025-03-12 21:36:05', '2025-03-12 21:36:05'),
(13, 'View Users', 'web', 'User Management', '2025-03-12 21:36:14', '2025-03-12 21:36:14'),
(14, 'Add User', 'web', 'User Management', '2025-03-12 21:36:24', '2025-03-12 21:36:24'),
(15, 'View User Details', 'web', 'User Management', '2025-03-12 21:36:33', '2025-03-12 21:36:33'),
(16, 'Edit User', 'web', 'User Management', '2025-03-12 21:36:43', '2025-03-12 21:36:43'),
(17, 'View User Permissions', 'web', 'User Management', '2025-03-12 21:36:53', '2025-03-12 21:36:53'),
(18, 'Edit User Permissions', 'web', 'User Management', '2025-03-12 21:37:02', '2025-03-12 21:37:02'),
(19, 'Update User Permissions', 'web', 'User Management', '2025-03-12 21:37:11', '2025-03-12 21:37:11'),
(20, 'View Roles', 'web', 'Role Management', '2025-03-12 21:37:19', '2025-03-12 21:37:19'),
(21, 'Add Role', 'web', 'Role Management', '2025-03-12 21:37:30', '2025-03-12 21:37:30'),
(22, 'Edit Role', 'web', 'Role Management', '2025-03-12 21:37:37', '2025-03-12 21:37:37'),
(23, 'Delete Role', 'web', 'Role Management', '2025-03-12 21:37:46', '2025-03-12 21:37:46'),
(24, 'View Permissions', 'web', 'Permission Management', '2025-03-12 21:37:59', '2025-03-12 21:37:59'),
(25, 'Add Permission', 'web', 'Permission Management', '2025-03-12 21:38:11', '2025-03-12 21:38:11'),
(26, 'Edit Permission', 'web', 'Permission Management', '2025-03-12 21:38:18', '2025-03-12 21:38:18'),
(27, 'Delete Permission', 'web', 'Permission Management', '2025-03-12 21:38:26', '2025-03-12 21:38:26'),
(28, 'Manage Role Permissions', 'web', 'Role Permission Management', '2025-03-12 21:38:34', '2025-03-12 21:38:34'),
(29, 'View Role Permissions', 'web', 'Role Permission Management', '2025-03-12 21:38:43', '2025-03-12 21:38:43'),
(30, 'Edit Role Permissions', 'web', 'Role Permission Management', '2025-03-12 21:38:52', '2025-03-12 21:38:52'),
(31, 'Update Role Permissions', 'web', 'Role Permission Management', '2025-03-12 21:39:00', '2025-03-12 21:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `pngos`
--

DROP TABLE IF EXISTS `pngos`;
CREATE TABLE IF NOT EXISTS `pngos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pngos`
--

INSERT INTO `pngos` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'BLAST', '2025-03-12 21:14:09', '2025-03-12 21:14:09'),
(2, 'RDRS', '2025-03-12 21:14:14', '2025-03-12 21:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2025-03-12 21:21:11', '2025-03-12 21:21:11');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(5, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9WcSfEJMjFbXdWkktlOIanJZvaqqELoL2KLSl6VG', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoialVIT1pocHpFOWt4THEyV2xlM1FyRDBOb2xrUnpsYlgyWXF4WFhvdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tbmUvY291cnQtcG9saWNlLXByaXNvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1742198424);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district_id` bigint UNSIGNED DEFAULT NULL,
  `pngo_id` bigint UNSIGNED DEFAULT NULL,
  `status` int DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_district_id_foreign` (`district_id`),
  KEY `users_pngo_id_foreign` (`pngo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `district_id`, `pngo_id`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@email.com', NULL, '$2y$12$Oh9YraXlRcPHwMq9NB.VqOPJDY9rUwYqJpLa5gL/8lIZ29IDfA9pm', NULL, NULL, 1, NULL, '2025-03-13 05:14:12', '2025-03-13 05:14:12');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
