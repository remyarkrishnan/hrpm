-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 07, 2025 at 05:50 AM
-- Server version: 8.0.31
-- PHP Version: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`),
  KEY `activity_log_event_index` (`event`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `event`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\Project', '1', NULL, NULL, '{\"attributes\": {\"name\": \"Office Complex Construction\", \"status\": \"active\", \"completion_percentage\": 35}}', NULL, 'created', '2025-10-06 03:10:07', '2025-10-06 03:10:07'),
(2, 'default', 'created', 'App\\Models\\Project', '2', NULL, NULL, '{\"attributes\": {\"name\": \"Residential Villa Project\", \"status\": \"active\", \"completion_percentage\": 20}}', NULL, 'created', '2025-10-06 03:10:07', '2025-10-06 03:10:07'),
(3, 'default', 'created', 'App\\Models\\Project', '3', NULL, NULL, '{\"attributes\": {\"name\": \"Shopping Mall Renovation\", \"status\": \"active\", \"completion_percentage\": 10}}', NULL, 'created', '2025-10-06 03:10:07', '2025-10-06 03:10:07'),
(4, 'default', 'created', 'App\\Models\\Project', '4', NULL, NULL, '{\"attributes\": {\"name\": \"Infrastructure Development\", \"status\": \"planning\", \"completion_percentage\": 5}}', NULL, 'created', '2025-10-06 03:10:07', '2025-10-06 03:10:07'),
(5, 'default', 'created', 'App\\Models\\Project', '5', NULL, NULL, '{\"attributes\": {\"name\": \"Hospital Extension Project\", \"status\": \"planning\", \"completion_percentage\": 0}}', NULL, 'created', '2025-10-06 03:10:07', '2025-10-06 03:10:07'),
(6, 'default', 'User logged in', 'App\\Models\\User', '1', 'App\\Models\\User', '1', '[]', NULL, NULL, '2025-10-06 03:11:09', '2025-10-06 03:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` timestamp NULL DEFAULT NULL,
  `check_out` timestamp NULL DEFAULT NULL,
  `check_in_location` json DEFAULT NULL,
  `check_out_location` json DEFAULT NULL,
  `work_hours` decimal(5,2) NOT NULL DEFAULT '0.00',
  `overtime_hours` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` enum('present','absent','late','half_day') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_user_id_date_unique` (`user_id`,`date`),
  KEY `attendances_approved_by_foreign` (`approved_by`),
  KEY `attendances_date_status_index` (`date`,`status`),
  KEY `attendances_user_id_date_index` (`user_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_cache_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_08_19_000000_create_jobs_table', 1),
(6, '2019_08_19_000000_create_sessions_table', 1),
(7, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(8, '2024_01_01_000000_create_permission_tables', 1),
(9, '2024_01_01_000001_create_attendances_table', 1),
(10, '2024_01_01_000002_create_projects_table', 1),
(11, '2024_01_01_000003_create_activity_log_table', 1),
(12, '2024_01_01_000004_create_project_approval_steps_table', 2),
(18, '2024_01_01_000005_create_project_employee_table', 3),
(19, '2024_01_01_000006_update_projects_table_for_construction_management', 3),
(20, 'create_project_milestones_table', 4),
(21, 'create_project_sub_plans_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view-users', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(2, 'create-users', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(3, 'edit-users', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(4, 'delete-users', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(5, 'manage-user-roles', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(6, 'view-user-profile', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(7, 'export-users', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(8, 'view-roles', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(9, 'create-roles', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(10, 'edit-roles', 'web', '2025-10-06 03:10:05', '2025-10-06 03:10:05'),
(11, 'delete-roles', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(12, 'manage-permissions', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(13, 'view-projects', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(14, 'create-projects', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(15, 'edit-projects', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(16, 'delete-projects', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(17, 'manage-project-members', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(18, 'view-project-reports', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(19, 'export-projects', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(20, 'view-attendance', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(21, 'mark-attendance', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(22, 'edit-attendance', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(23, 'approve-attendance', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(24, 'view-attendance-reports', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(25, 'export-attendance', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(26, 'view-leaves', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(27, 'apply-leave', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(28, 'approve-leaves', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(29, 'reject-leaves', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(30, 'view-leave-reports', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(31, 'export-leaves', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(32, 'view-dashboard', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(33, 'view-analytics', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(34, 'generate-reports', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(35, 'export-reports', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(36, 'view-system-logs', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(37, 'view-settings', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(38, 'edit-settings', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(39, 'backup-system', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(40, 'view-activity-logs', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(41, 'manage-system', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `project_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('residential','commercial','industrial','infrastructure','renovation') COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `expected_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `project_status` enum('draft','planning','approval_pending','approved','in_progress','on_hold','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `end_date` date NOT NULL,
  `budget` decimal(12,2) DEFAULT NULL,
  `status` enum('draft','planning','approval_pending','approved','in_progress','on_hold','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `progress_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `documents` json DEFAULT NULL,
  `completion_percentage` int NOT NULL DEFAULT '0',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `project_manager_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_project_code_unique` (`project_code`),
  KEY `projects_status_priority_index` (`status`,`priority`),
  KEY `projects_manager_id_status_index` (`manager_id`,`status`),
  KEY `projects_start_date_end_date_index` (`start_date`,`end_date`),
  KEY `projects_created_by_foreign` (`created_by`),
  KEY `projects_status_created_at_index` (`status`,`created_at`),
  KEY `projects_project_manager_id_status_index` (`project_manager_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `project_code`, `type`, `client_name`, `client_contact`, `manager_id`, `start_date`, `expected_end_date`, `actual_end_date`, `project_status`, `end_date`, `budget`, `status`, `progress_percentage`, `priority`, `documents`, `completion_percentage`, `location`, `address`, `created_at`, `updated_at`, `project_manager_id`, `created_by`, `deleted_at`) VALUES
(1, 'Office Complex Construction', 'Construction of a modern 5-story office complex with parking facilities and landscaping.', 'PROJ-1-2025', 'residential', 'ABC Corporation', '+91 9876543100', 2, '2025-07-06', '2026-01-04', NULL, 'draft', '2026-07-06', 50000000.00, 'in_progress', 27.00, 'high', NULL, 35, 'Mumbai', 'Plot No. 123, Sector 5, Navi Mumbai, Maharashtra 400614', '2025-10-06 03:10:07', '2025-10-06 13:51:52', 3, NULL, NULL),
(2, 'Residential Villa Project', 'Luxury residential villas with modern amenities and smart home features.', 'PROJ-2-2025', 'residential', 'XYZ Developers', '+91 9876543101', 2, '2025-08-06', '2026-01-04', NULL, 'draft', '2026-08-06', 75000000.00, 'in_progress', 0.00, 'medium', NULL, 20, 'Pune', 'Survey No. 456, Wakad, Pune, Maharashtra 411057', '2025-10-06 03:10:07', '2025-10-06 03:10:07', NULL, NULL, NULL),
(3, 'Shopping Mall Renovation', 'Complete renovation of existing shopping mall including modernization of facilities.', 'PROJ-3-2025', 'residential', 'Mall Management Ltd', '+91 9876543102', 2, '2025-09-06', '2026-01-04', NULL, 'draft', '2026-04-06', 25000000.00, 'in_progress', 0.00, 'urgent', NULL, 10, 'Delhi', 'Connaught Place, New Delhi, Delhi 110001', '2025-10-06 03:10:07', '2025-10-06 03:10:07', NULL, NULL, NULL),
(4, 'Infrastructure Development', 'Road construction and infrastructure development for new residential area.', 'PROJ-4-2025', 'residential', 'Municipal Corporation', '+91 9876543103', 1, '2025-09-29', '2026-01-04', NULL, 'draft', '2026-02-06', 15000000.00, 'draft', 0.00, 'medium', NULL, 5, 'Bangalore', 'Electronic City Phase 2, Bangalore, Karnataka 560100', '2025-10-06 03:10:07', '2025-10-06 03:10:07', NULL, NULL, NULL),
(5, 'Hospital Extension Project', 'Extension of existing hospital with new OPD block and parking facility.', 'PROJ-5-2025', 'residential', 'City Hospital Trust', '+91 9876543104', 2, '2025-10-16', '2026-01-04', NULL, 'draft', '2026-06-06', 35000000.00, 'draft', 0.00, 'high', NULL, 0, 'Chennai', 'Anna Nagar, Chennai, Tamil Nadu 600040', '2025-10-06 03:10:07', '2025-10-06 12:13:46', NULL, NULL, '2025-10-06 12:13:46');

-- --------------------------------------------------------

--
-- Table structure for table `project_approval_steps`
--

DROP TABLE IF EXISTS `project_approval_steps`;
CREATE TABLE IF NOT EXISTS `project_approval_steps` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `step_order` int NOT NULL,
  `step_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consultancy_type` enum('design','environment','safety','structural','electrical','plumbing','finance','legal','municipal','fire_safety','quality','final_approval') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `responsible_person_id` bigint UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','approved','rejected','on_hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `documents` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_approval_steps_responsible_person_id_foreign` (`responsible_person_id`),
  KEY `project_approval_steps_approved_by_foreign` (`approved_by`),
  KEY `project_approval_steps_project_id_step_order_index` (`project_id`,`step_order`),
  KEY `project_approval_steps_status_due_date_index` (`status`,`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_employee`
--

DROP TABLE IF EXISTS `project_employee`;
CREATE TABLE IF NOT EXISTS `project_employee` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `allocation_percentage` decimal(5,2) NOT NULL DEFAULT '100.00',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_employee_project_id_user_id_unique` (`project_id`,`user_id`),
  KEY `project_employee_project_id_index` (`project_id`),
  KEY `project_employee_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_milestones`
--

DROP TABLE IF EXISTS `project_milestones`;
CREATE TABLE IF NOT EXISTS `project_milestones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `due_date` date NOT NULL,
  `completion_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `progress_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `responsible_person_id` bigint UNSIGNED DEFAULT NULL,
  `priority` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_milestones_responsible_person_id_foreign` (`responsible_person_id`),
  KEY `project_milestones_project_id_status_index` (`project_id`,`status`),
  KEY `project_milestones_due_date_index` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_sub_plans`
--

DROP TABLE IF EXISTS `project_sub_plans`;
CREATE TABLE IF NOT EXISTS `project_sub_plans` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('planned','in_progress','completed','on_hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `budget` decimal(15,2) DEFAULT NULL,
  `responsible_person_id` bigint UNSIGNED DEFAULT NULL,
  `dependencies` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_sub_plans_responsible_person_id_foreign` (`responsible_person_id`),
  KEY `project_sub_plans_project_id_status_index` (`project_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(2, 'admin', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(3, 'project-manager', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(4, 'employee', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06'),
(5, 'consultant', 'web', '2025-10-06 03:10:06', '2025-10-06 03:10:06');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
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
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(1, 2),
(2, 2),
(3, 2),
(5, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(13, 2),
(14, 2),
(15, 2),
(17, 2),
(19, 2),
(20, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 2),
(37, 2),
(38, 2),
(40, 2),
(1, 3),
(6, 3),
(13, 3),
(14, 3),
(15, 3),
(17, 3),
(18, 3),
(20, 3),
(23, 3),
(24, 3),
(26, 3),
(28, 3),
(29, 3),
(30, 3),
(32, 3),
(33, 3),
(34, 3),
(6, 4),
(13, 4),
(20, 4),
(21, 4),
(26, 4),
(27, 4),
(32, 4),
(6, 5),
(13, 5),
(20, 5),
(21, 5),
(26, 5),
(27, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aadhar_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_employee_id_unique` (`employee_id`),
  KEY `users_status_department_index` (`status`,`department`),
  KEY `users_email_status_index` (`email`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `employee_id`, `department`, `designation`, `joining_date`, `status`, `profile_image`, `address`, `emergency_contact`, `emergency_phone`, `date_of_birth`, `gender`, `blood_group`, `salary`, `bank_account`, `pan_number`, `aadhar_number`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@teqinvalley.in', '2025-10-06 03:10:06', '$2y$12$vU8akcLP52S5.3Rx/gk9UO5x9xPwM7Z.52JHyIIVKICQRgmAP4w1K', '+91 9876543210', 'SA001', 'Administration', 'Super Administrator', '2025-10-06', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 11:54:58', 'KP5rEhn8ID7AccoeQBL2vGmnqJgaa7gAAVd5eHE0nOrgm383WoRjEQYlcIIJ', '2025-10-06 03:10:06', '2025-10-06 11:54:58'),
(2, 'Admin User', 'admin@teqinvalley.in', '2025-10-06 03:10:07', '$2y$12$dhvi3.61otJ6chbMFANoEu17pd2reyBguVSetPOV5Mn0DPgR/MQLi', '+91 9876543211', 'AD001', 'Administration', 'Administrator', '2025-10-06', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 03:10:07', '2025-10-06 03:10:07'),
(3, 'John Manager', 'manager@teqinvalley.in', '2025-10-06 03:10:07', '$2y$12$R1R/BfhkBjFztCR4UGl4oe/n4BnmEHNHJmOxrP1rhGzSJtNyg.vNi', '+91 9876543212', 'PM001', 'Project Management', 'Senior Project Manager', '2025-04-06', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 75000.00, NULL, NULL, NULL, NULL, NULL, '2025-10-06 03:10:07', '2025-10-06 06:53:48'),
(4, 'Jane Employee', 'employee@teqinvalley.in', '2025-10-06 03:10:07', '$2y$12$FPI4r/8oRIiCBBknOr/lceNuucojAQ7SaVXc/ILGU57QvCCrN4iiC', '+91 9876543213', 'EMP001', 'Engineering', 'Senior Engineer', '2024-10-06', 1, NULL, '123 Main Street, City, State 12345', NULL, NULL, '1990-05-15', 'female', NULL, 50000.00, NULL, NULL, NULL, NULL, NULL, '2025-10-06 03:10:07', '2025-10-06 06:53:55'),
(5, 'Mike Consultant', 'consultant@teqinvalley.in', '2025-10-06 03:10:07', '$2y$12$67CnH9puCqsRtAz/TvplXuPn4h5Zuc3o9JN45x3CN83T/XIvWGVDK', '+91 9876543214', 'CON001', 'Project Management', 'Senior Consultant', '2025-07-06', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 60000.00, NULL, NULL, NULL, NULL, NULL, '2025-10-06 03:10:07', '2025-10-06 06:54:14');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `projects_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_approval_steps`
--
ALTER TABLE `project_approval_steps`
  ADD CONSTRAINT `project_approval_steps_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `project_approval_steps_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_approval_steps_responsible_person_id_foreign` FOREIGN KEY (`responsible_person_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_employee`
--
ALTER TABLE `project_employee`
  ADD CONSTRAINT `project_employee_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_employee_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_milestones`
--
ALTER TABLE `project_milestones`
  ADD CONSTRAINT `project_milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_milestones_responsible_person_id_foreign` FOREIGN KEY (`responsible_person_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_sub_plans`
--
ALTER TABLE `project_sub_plans`
  ADD CONSTRAINT `project_sub_plans_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_sub_plans_responsible_person_id_foreign` FOREIGN KEY (`responsible_person_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
