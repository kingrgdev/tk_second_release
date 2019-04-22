-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2019 at 05:28 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timekeeping_csi`
--

-- --------------------------------------------------------

--
-- Table structure for table `alteration_records`
--

CREATE TABLE `alteration_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_applied` date NOT NULL,
  `sched_date` date NOT NULL,
  `cur_time_in` datetime DEFAULT NULL,
  `cur_time_out` datetime DEFAULT NULL,
  `new_time_in` datetime NOT NULL,
  `new_time_out` datetime NOT NULL,
  `total_hrs` decimal(8,2) NOT NULL,
  `undertime` decimal(8,2) NOT NULL,
  `late` decimal(8,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved_1_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_2_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_1` int(11) NOT NULL DEFAULT '0',
  `approved_2` int(11) NOT NULL DEFAULT '0',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `payroll_register_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alteration_records`
--

INSERT INTO `alteration_records` (`id`, `company_id`, `date_applied`, `sched_date`, `cur_time_in`, `cur_time_out`, `new_time_in`, `new_time_out`, `total_hrs`, `undertime`, `late`, `reason`, `approved_1_id`, `approved_2_id`, `approved_1`, `approved_2`, `status`, `deleted`, `payroll_register_number`, `lu_by`, `created_at`, `updated_at`) VALUES
(1, '2018-004', '2019-04-12', '2019-04-11', NULL, NULL, '2019-04-11 09:00:00', '2019-04-11 20:00:00', '10.50', '0.00', '0.00', 'First', '2018-004', '2018-004', 1, 1, 'APPROVED', 0, NULL, 'Admin', '2019-04-11 22:39:21', '2019-04-11 22:52:00'),
(2, '2018-003', '2019-04-12', '2019-04-11', NULL, NULL, '2019-04-11 09:00:00', '2019-04-11 20:00:00', '10.50', '0.00', '0.00', 'Second', '2018-004', '2018-004', 1, 1, 'APPROVED', 0, NULL, 'Admin', '2019-04-11 22:39:32', '2019-04-11 22:51:57'),
(3, '2018-035', '2019-04-12', '2019-04-11', NULL, NULL, '2019-04-11 09:00:00', '2019-04-11 20:00:00', '10.50', '0.00', '0.00', 'Third', '2018-004', '2018-004', 1, 1, 'APPROVED', 0, NULL, 'Admin', '2019-04-11 22:39:39', '2019-04-11 22:51:53'),
(4, '2018-004', '2019-04-17', '2019-04-16', NULL, NULL, '2019-04-16 13:32:00', '2019-04-17 14:32:00', '25.00', '0.00', '4.53', 'sad', '2018-004', '2018-004', 1, 1, 'APPROVED', 0, NULL, 'Admin', '2019-04-16 21:33:01', '2019-04-16 21:33:01'),
(5, '2018-004', '2019-04-22', '2019-04-08', NULL, NULL, '2019-04-08 09:30:00', '2019-04-08 18:50:00', '9.33', '0.00', '1.50', 'DTR', NULL, NULL, 0, 0, 'PENDING', 0, NULL, 'Admin', '2019-04-22 02:32:54', '2019-04-22 02:32:54'),
(6, '2018-004', '2019-04-22', '2019-04-18', NULL, NULL, '2019-04-18 09:30:00', '2019-04-18 18:30:00', '8.50', '0.50', '0.00', 'Absent', NULL, NULL, 0, 0, 'PENDING', 0, NULL, 'Admin', '2019-04-22 02:52:29', '2019-04-22 02:52:29'),
(7, '2018-004', '2019-04-22', '2019-04-17', NULL, NULL, '2019-04-17 06:00:00', '2019-04-17 18:00:00', '11.00', '0.00', '0.00', 'Absent', NULL, NULL, 0, 0, 'PENDING', 0, NULL, 'Admin', '2019-04-22 02:58:12', '2019-04-22 02:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

CREATE TABLE `attendance_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `biometric_id` int(11) DEFAULT NULL,
  `sched_date` date DEFAULT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`id`, `biometric_id`, `sched_date`, `time_in`, `time_out`, `lu_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, '2019-04-05', '2019-04-05 06:41:00', '2019-04-05 18:30:00', 'Admin', 'Admin', '2019-04-16 01:50:55', '2019-04-16 01:50:55'),
(2, 5, '2019-04-08', '2019-04-08 08:26:00', '2019-04-08 18:50:00', 'Admin', 'Admin', '2019-04-16 01:50:55', '2019-04-16 01:50:55'),
(3, 7, '2019-04-08', '2019-04-08 08:03:00', '2019-04-08 09:16:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(4, 9, '2019-04-05', '2019-04-05 07:56:00', '2019-04-05 19:00:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(5, 9, '2019-04-06', '2019-04-06 07:41:00', '2019-04-06 18:40:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(6, 9, '2019-04-08', '2019-04-08 08:03:00', '2019-04-08 18:47:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(7, 11, '2019-04-05', '2019-04-05 07:24:00', '2019-04-05 14:24:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(8, 13, '2019-04-08', '2019-04-08 08:16:00', '2019-04-08 19:00:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(9, 19, '2019-04-05', '2019-04-05 08:06:00', '2019-04-05 18:44:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(10, 19, '2019-04-08', '2019-04-08 07:42:00', '2019-04-08 19:48:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(11, 26, '2019-04-05', '2019-04-05 08:05:00', '2019-04-05 19:21:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(12, 26, '2019-04-08', '2019-04-08 08:29:00', '2019-04-08 19:16:00', 'Admin', 'Admin', '2019-04-16 01:50:56', '2019-04-16 01:50:56'),
(13, 38, '2019-04-05', '2019-04-05 08:31:00', '2019-04-05 19:18:00', 'Admin', 'Admin', '2019-04-16 01:50:57', '2019-04-16 01:50:57'),
(14, 38, '2019-04-08', '2019-04-08 11:57:00', '2019-04-08 19:08:00', 'Admin', 'Admin', '2019-04-16 01:50:57', '2019-04-16 01:50:57'),
(15, 46, '2019-04-05', '2019-04-05 08:25:00', '2019-04-05 09:05:00', 'Admin', 'Admin', '2019-04-16 01:50:57', '2019-04-16 01:50:57'),
(16, 46, '2019-04-06', '2019-04-06 09:44:00', '2019-04-06 18:42:00', 'Admin', 'Admin', '2019-04-16 01:50:57', '2019-04-16 01:50:57'),
(17, 46, '2019-04-08', '2019-04-08 06:17:00', '2019-04-08 12:45:00', 'Admin', 'Admin', '2019-04-16 01:50:57', '2019-04-16 01:50:57'),
(18, 59, '2019-04-05', '2019-04-05 07:58:00', '2019-04-05 19:01:00', 'Admin', 'Admin', '2019-04-16 01:50:57', '2019-04-16 01:50:57'),
(19, 59, '2019-04-08', '2019-04-08 08:05:00', '2019-04-08 18:37:00', 'Admin', 'Admin', '2019-04-16 01:50:57', '2019-04-16 01:50:57'),
(20, 62, '2019-04-05', '2019-04-05 08:30:00', '2019-04-05 19:01:00', 'Admin', 'Admin', '2019-04-16 01:50:57', '2019-04-16 01:50:58'),
(21, 62, '2019-04-08', '2019-04-08 08:29:00', '2019-04-08 18:38:00', 'Admin', 'Admin', '2019-04-16 01:50:58', '2019-04-16 01:50:58');

-- --------------------------------------------------------

--
-- Table structure for table `leave_records`
--

CREATE TABLE `leave_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_applied` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` decimal(8,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `approved_1_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_2_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_1` int(11) NOT NULL DEFAULT '0',
  `approved_2` int(11) NOT NULL DEFAULT '0',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `payroll_register_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_records`
--

INSERT INTO `leave_records` (`id`, `company_id`, `leave_id`, `date_applied`, `reason`, `duration`, `start_date`, `end_date`, `approved_1_id`, `approved_2_id`, `approved_1`, `approved_2`, `status`, `deleted`, `payroll_register_number`, `lu_by`, `created_at`, `updated_at`) VALUES
(1, '2018-004', '9', '2019-04-12', 'sad', '5.00', '2019-04-15', '2019-04-20', '2018-004', '2018-004', 1, 1, 'CANCELLED', 0, NULL, 'Admin', '2019-04-12 03:07:58', '2019-04-12 03:11:01'),
(2, '2018-003', '9', '2019-04-12', 'sad', '3.00', '2019-04-12', '2019-04-16', '2018-004', '2018-004', 1, 1, 'APPROVED', 0, NULL, 'Admin', '2019-04-12 03:09:52', '2019-04-12 03:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(13, '2014_10_12_000000_create_users_table', 1),
(14, '2014_10_12_100000_create_password_resets_table', 1),
(15, '2019_02_20_031706_user_type', 2),
(16, '2019_02_27_020527_alteration_records', 3),
(17, '2019_03_01_030648_overtime_records', 4),
(18, '2019_03_08_021344_leave_reacords', 5),
(19, '2019_03_11_014630_sub_leave_records', 6),
(20, '2019_04_04_112346_user_modules', 7),
(21, '2019_04_08_031444_user_module_access', 8),
(22, '2019_04_15_022543_attendace_records', 9),
(23, '2019_04_15_023224_attendance_records', 10);

-- --------------------------------------------------------

--
-- Table structure for table `overtime_records`
--

CREATE TABLE `overtime_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_applied` date NOT NULL,
  `sched_date` date NOT NULL,
  `shift_applied` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_timein` datetime NOT NULL,
  `date_timeout` datetime NOT NULL,
  `total_hrs` decimal(8,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved_1_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_2_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_1` int(11) NOT NULL DEFAULT '0',
  `approved_2` int(11) NOT NULL DEFAULT '0',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `payroll_register_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `overtime_records`
--

INSERT INTO `overtime_records` (`id`, `company_id`, `date_applied`, `sched_date`, `shift_applied`, `date_timein`, `date_timeout`, `total_hrs`, `reason`, `approved_1_id`, `approved_2_id`, `approved_1`, `approved_2`, `status`, `deleted`, `payroll_register_number`, `lu_by`, `created_at`, `updated_at`) VALUES
(1, '2018-004', '2019-04-12', '2019-04-12', 'Post-Shift', '2019-04-12 19:30:00', '2019-04-12 22:30:00', '3.00', 'First', '2018-004', '2018-004', 1, 1, 'APPROVED', 0, NULL, 'Admin', '2019-04-12 01:31:58', '2019-04-12 01:31:58'),
(2, '2018-003', '2019-04-12', '2019-04-12', 'Post-Shift', '2019-04-12 19:30:00', '2019-04-12 22:30:00', '3.00', 'First', '2018-004', '2018-004', 1, 1, 'APPROVED', 0, NULL, 'Admin', '2019-04-12 01:32:03', '2019-04-12 01:32:03'),
(3, '2018-035', '2019-04-12', '2019-04-12', 'Post-Shift', '2019-04-12 19:30:00', '2019-04-12 22:30:00', '3.00', 'First', '2018-004', '2018-004', 1, 1, 'CANCELLED', 0, NULL, 'Admin', '2019-04-12 01:32:08', '2019-04-12 01:32:20'),
(4, '2018-004', '2019-04-15', '2019-04-14', 'Post-Shift', '2019-04-14 19:30:00', '2019-04-14 22:30:00', '3.00', 'Sad', '2018-004', '2018-004', 1, 1, 'APPROVED', 0, NULL, 'Admin', '2019-04-15 02:55:21', '2019-04-15 02:55:21');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_leave_records`
--

CREATE TABLE `sub_leave_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `leave_record_id` bigint(20) NOT NULL,
  `company_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sched_date` date NOT NULL,
  `payroll_register_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_leave_records`
--

INSERT INTO `sub_leave_records` (`id`, `leave_record_id`, `company_id`, `sched_date`, `payroll_register_number`, `deleted`, `lu_by`, `created_at`, `updated_at`) VALUES
(6, 10, '2018-004', '2019-03-11', NULL, 0, 'Admin', '2019-03-11 01:16:07', '2019-03-11 01:16:07'),
(7, 11, '2018-004', '2019-04-02', NULL, 0, 'Admin', '2019-04-02 00:26:15', '2019-04-02 00:26:15'),
(8, 12, '2018-004', '2019-04-18', NULL, 0, 'Admin', '2019-04-02 00:27:02', '2019-04-02 00:27:02'),
(9, 13, '2018-004', '2019-04-24', NULL, 0, 'Admin', '2019-04-02 00:37:42', '2019-04-02 00:37:42'),
(10, 1, '2018-004', '2019-04-15', NULL, 0, 'Admin', '2019-04-12 03:07:58', '2019-04-12 03:07:58'),
(11, 1, '2018-004', '2019-04-16', NULL, 0, 'Admin', '2019-04-12 03:07:58', '2019-04-12 03:07:58'),
(12, 1, '2018-004', '2019-04-17', NULL, 0, 'Admin', '2019-04-12 03:07:58', '2019-04-12 03:07:58'),
(13, 1, '2018-004', '2019-04-18', NULL, 0, 'Admin', '2019-04-12 03:07:58', '2019-04-12 03:07:58'),
(14, 1, '2018-004', '2019-04-20', NULL, 0, 'Admin', '2019-04-12 03:07:58', '2019-04-12 03:07:58'),
(15, 2, '2018-003', '2019-04-13', NULL, 0, 'Admin', '2019-04-12 03:09:52', '2019-04-12 03:09:52'),
(16, 2, '2018-003', '2019-04-15', NULL, 0, 'Admin', '2019-04-12 03:09:52', '2019-04-12 03:09:52'),
(17, 2, '2018-003', '2019-04-16', NULL, 0, 'Admin', '2019-04-12 03:09:52', '2019-04-12 03:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `user_type_id`, `name`, `email`, `email_verified_at`, `password`, `active`, `remember_token`, `lu_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2018-004', 1, 'Admin', 'admin@admin.admin', NULL, '$2y$10$vYb9ZjgeA2nUCi2CAPuODOl4FrEpkgrdLePYlec7.9zqin8b.mYgS', 'yes', 'LQmC41Hifzq2WdgKqLllJeQMj2q9ELkoaFr5MWv9V2gKrqSgWfWZcxwL6FdG', NULL, NULL, '2019-02-19 18:56:39', '2019-02-19 18:56:39'),
(2, '2018-003', 1, 'Agoncillo, Judy Ann', 'sample@yahoo.com', NULL, '$2y$10$J7X/NIIy3xJbS7r68jqZ8ux6gSUioC2HkcyAotjZdwaAJcJ8GcfSS', 'yes', 'cHK9exdldv672RuDyLOPYxCSP53MiVvETNqGo8t6DSVDYI0H09rVGVCo1xnn', 'Alandy, Adrian', 'Admin', '2019-02-21 19:33:56', '2019-02-22 02:23:48'),
(3, '2018-035', 1, 'Alandy, Adrian', 'sample123@yahoo.com', NULL, '$2y$10$L8BddWVkQQiH1OuFW3streeQQN2PNbSI7JQ2iWKeT50fDoaAGIqZW', 'yes', NULL, 'Admin', 'Admin', '2019-02-21 19:42:09', '2019-04-10 00:41:21'),
(4, '2018-044', 2, 'Angelo, Michael', 'sample222@yahoo.com', NULL, '$2y$10$wL4q3pN/llNsM46./HAfIOgvHju7/wg/7S1L9E3wWg1Js/l9EDImW', 'yes', NULL, 'Admin', 'Admin', '2019-02-21 19:43:03', '2019-04-07 21:56:05'),
(5, '2010-1234', 13, 'Sample, Beverly', 'bev@yahoo.com', NULL, '$2y$10$RAX5OBR208WJsfKoxcx9CONom8dHrcbTX3PVXDARV3UYrZroLvK/i', 'yes', 'LI08AiHvmpftpCL13tnrN6SWObeK5SSYvDdoE79RJlSPL2UJpPtUqwhHDkjO', 'Admin', 'Admin', '2019-04-10 00:33:38', '2019-04-10 00:41:43'),
(6, '2018-616', 1, 'Beckham, David', 'beck123@yahoo.com', NULL, '$2y$10$LJrTGQ21KlR.YVpR7kqwIuhotMElQNaQXmgQ8YKhfu/kqq1cjz7.6', 'yes', 'K0U1B6QuoxPZfTuD4k0rmuSbqPuRoMEHEX6yJpGCTvCag3ACleon0w2GHtrA', 'Admin', 'Admin', '2019-04-21 18:20:41', '2019-04-21 18:20:41');

-- --------------------------------------------------------

--
-- Table structure for table `user_modules`
--

CREATE TABLE `user_modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `module_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_modules`
--

INSERT INTO `user_modules` (`id`, `module_code`, `module_name`, `module_type`, `lu_by`, `created_by`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 'time_records', 'Time Records', 'module', 'admin', 'admin', NULL, NULL, 0),
(2, 'overtime_records', 'Overtime Records', 'module', 'admin', 'admin', NULL, NULL, 0),
(3, 'leave_records', 'Leave Record', 'module', 'admin', 'admin', NULL, NULL, 0),
(4, 'work_schedules', 'Work Schedules', 'module', 'admin', 'admin', NULL, NULL, 0),
(5, 'team_status', 'Team Status', 'module', 'admin', 'admin', NULL, NULL, 0),
(6, 'payslips', 'Payslips', 'module', 'admin', 'admin', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_module_access`
--

CREATE TABLE `user_module_access` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `time_records` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `overtime_records` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `leave_records` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `work_schedules` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `team_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `payslips` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_module_access`
--

INSERT INTO `user_module_access` (`id`, `user_type_id`, `time_records`, `overtime_records`, `leave_records`, `work_schedules`, `team_status`, `payslips`, `lu_by`, `created_by`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 1, 'all', 'all', 'all', 'all', 'all', 'all', 'admin', 'admin', NULL, NULL, 0),
(2, 13, 'all', 'all', 'all', 'all', 'all', 'all', 'Admin', 'Admin', '2019-04-08 00:48:53', '2019-04-08 00:48:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lu_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `type_name`, `type_description`, `created_by`, `lu_by`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Sad', 'Admin', 'Admin', 0, '2019-02-21 17:54:52', '2019-03-28 23:26:17'),
(13, 'PM', NULL, 'Admin', NULL, 0, '2019-04-08 00:48:53', '2019-04-08 00:48:53');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_alteration_records`
-- (See below for the actual view)
--
CREATE TABLE `view_alteration_records` (
`id` int(10) unsigned
,`emp_name` varchar(93)
,`company_id` varchar(20)
,`sched_date` date
,`date_applied` date
,`cur_time_in` datetime
,`cur_time_out` datetime
,`new_time_in` datetime
,`new_time_out` datetime
,`total_hrs` decimal(8,2)
,`undertime` decimal(8,2)
,`late` decimal(8,2)
,`reason` varchar(255)
,`approved_1_id` varchar(191)
,`approved_2_id` varchar(191)
,`approved_1` int(11)
,`approved_2` int(11)
,`status` varchar(191)
,`deleted` int(11)
,`payroll_register_number` varchar(191)
,`lu_by` varchar(191)
,`created_at` timestamp
,`updated_at` timestamp
,`emp_no` bigint(20)
,`fullname` varchar(93)
,`lname` varchar(30)
,`fname` varchar(30)
,`company_ind` bigint(20)
,`company_name` varchar(100)
,`department` varchar(50)
,`position` varchar(50)
,`team` varchar(50)
,`employment_status` varchar(16)
,`birtdate` date
,`active` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employee_information`
-- (See below for the actual view)
--
CREATE TABLE `view_employee_information` (
`emp_no` bigint(20)
,`company_id` varchar(20)
,`fullname` varchar(93)
,`lname` varchar(30)
,`fname` varchar(30)
,`company_ind` bigint(20)
,`company_name` varchar(100)
,`department` varchar(50)
,`position` varchar(50)
,`team` varchar(50)
,`employment_status` varchar(16)
,`active` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employee_schedule`
-- (See below for the actual view)
--
CREATE TABLE `view_employee_schedule` (
`emp_no` bigint(20)
,`company_id` varchar(20)
,`fullname` varchar(93)
,`lname` varchar(30)
,`fname` varchar(30)
,`company_ind` bigint(20)
,`company_name` varchar(100)
,`department` varchar(50)
,`position` varchar(50)
,`team` varchar(50)
,`employment_status` varchar(16)
,`active` varchar(20)
,`template_id` bigint(20)
,`type` varchar(200)
,`reg_in` time
,`reg_out` time
,`mon_in` time
,`mon_out` time
,`mon` int(2)
,`tue_in` time
,`tue_out` time
,`tue` int(2)
,`wed_in` time
,`wed_out` time
,`wed` int(11)
,`thu_in` time
,`thu_out` time
,`thu` int(2)
,`fri_in` time
,`fri_out` time
,`fri` int(2)
,`sat_in` time
,`sat_out` time
,`sat` int(2)
,`sun_in` time
,`sun_out` time
,`sun` int(2)
,`flexihrs` decimal(8,2)
,`lunch_out` time
,`lunch_in` time
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employee_schedule_request`
-- (See below for the actual view)
--
CREATE TABLE `view_employee_schedule_request` (
`id` bigint(20)
,`emp_no` bigint(20)
,`company_id` varchar(20)
,`fullname` varchar(93)
,`lname` varchar(30)
,`fname` varchar(30)
,`company_ind` bigint(20)
,`company_name` varchar(100)
,`department` varchar(50)
,`position` varchar(50)
,`team` varchar(50)
,`employment_status` varchar(16)
,`active` varchar(20)
,`request_status` varchar(191)
,`type` varchar(200)
,`start_date` date
,`end_date` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_leave_records`
-- (See below for the actual view)
--
CREATE TABLE `view_leave_records` (
`id` int(10) unsigned
,`emp_name` varchar(93)
,`company_id` varchar(20)
,`date_applied` date
,`reason` varchar(255)
,`duration` decimal(8,2)
,`start_date` date
,`end_date` date
,`leave_record_id` int(10) unsigned
,`leave_name` varchar(100)
,`approved_1_id` varchar(191)
,`approved_2_id` varchar(191)
,`approved_1` int(11)
,`approved_2` int(11)
,`status` varchar(191)
,`deleted` int(11)
,`payroll_register_number` varchar(191)
,`lu_by` varchar(191)
,`created_at` timestamp
,`updated_at` timestamp
,`emp_no` bigint(20)
,`fullname` varchar(93)
,`lname` varchar(30)
,`fname` varchar(30)
,`company_ind` bigint(20)
,`company_name` varchar(100)
,`department` varchar(50)
,`position` varchar(50)
,`team` varchar(50)
,`employment_status` varchar(16)
,`birtdate` date
,`active` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_overtime_records`
-- (See below for the actual view)
--
CREATE TABLE `view_overtime_records` (
`id` int(10) unsigned
,`emp_name` varchar(93)
,`company_id` varchar(20)
,`sched_date` date
,`date_applied` date
,`shift_applied` varchar(191)
,`date_timein` datetime
,`date_timeout` datetime
,`total_hrs` decimal(8,2)
,`reason` varchar(255)
,`approved_1_id` varchar(191)
,`approved_2_id` varchar(191)
,`approved_1` int(11)
,`approved_2` int(11)
,`status` varchar(191)
,`deleted` int(11)
,`payroll_register_number` varchar(191)
,`lu_by` varchar(191)
,`created_at` timestamp
,`updated_at` timestamp
,`emp_no` bigint(20)
,`fullname` varchar(93)
,`lname` varchar(30)
,`fname` varchar(30)
,`company_ind` bigint(20)
,`company_name` varchar(100)
,`department` varchar(50)
,`position` varchar(50)
,`team` varchar(50)
,`employment_status` varchar(16)
,`birtdate` date
,`active` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_punch_alteration_records`
-- (See below for the actual view)
--
CREATE TABLE `view_punch_alteration_records` (
`id` int(10) unsigned
,`company_id` varchar(20)
,`emp_name` varchar(93)
,`sched_date` date
,`date_applied` date
,`cur_time_in` datetime
,`cur_time_out` datetime
,`new_time_in` datetime
,`new_time_out` datetime
,`total_hrs` decimal(8,2)
,`undertime` decimal(8,2)
,`late` decimal(8,2)
,`reason` varchar(255)
,`approved_1_id` varchar(191)
,`approved_2_id` varchar(191)
,`approved_1` int(11)
,`approved_2` int(11)
,`status` varchar(191)
,`deleted` int(11)
,`payroll_register_number` varchar(191)
,`lu_by` varchar(191)
,`created_at` timestamp
,`updated_at` timestamp
,`level1name` varchar(93)
,`level2name` varchar(93)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user`
-- (See below for the actual view)
--
CREATE TABLE `view_user` (
`id` int(10) unsigned
,`company_id` varchar(191)
,`fullname` varchar(93)
,`email` varchar(191)
,`user_type_id` int(11)
,`lu_by` varchar(191)
,`type_name` varchar(191)
);

-- --------------------------------------------------------

--
-- Structure for view `view_alteration_records`
--
DROP TABLE IF EXISTS `view_alteration_records`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_alteration_records`  AS  select `e`.`id` AS `id`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `emp_name`,`e`.`company_id` AS `company_id`,`e`.`sched_date` AS `sched_date`,`e`.`date_applied` AS `date_applied`,`e`.`cur_time_in` AS `cur_time_in`,`e`.`cur_time_out` AS `cur_time_out`,`e`.`new_time_in` AS `new_time_in`,`e`.`new_time_out` AS `new_time_out`,`e`.`total_hrs` AS `total_hrs`,`e`.`undertime` AS `undertime`,`e`.`late` AS `late`,`e`.`reason` AS `reason`,`e`.`approved_1_id` AS `approved_1_id`,`e`.`approved_2_id` AS `approved_2_id`,`e`.`approved_1` AS `approved_1`,`e`.`approved_2` AS `approved_2`,`e`.`status` AS `status`,`e`.`deleted` AS `deleted`,`e`.`payroll_register_number` AS `payroll_register_number`,`e`.`lu_by` AS `lu_by`,`e`.`created_at` AS `created_at`,`e`.`updated_at` AS `updated_at`,`a`.`emp_no` AS `emp_no`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `fullname`,`a`.`lname` AS `lname`,`a`.`fname` AS `fname`,`b`.`company_ind` AS `company_ind`,`d`.`company_name` AS `company_name`,`b`.`department` AS `department`,`b`.`position` AS `position`,`b`.`team` AS `team`,`b`.`employment_status` AS `employment_status`,`a`.`birtdate` AS `birtdate`,`a`.`active` AS `active` from (((`alteration_records` `e` left join `hris_csi`.`personal_information` `a` on((`e`.`company_id` = `a`.`company_id`))) left join `hris_csi`.`employee_information` `b` on((`a`.`company_id` = `b`.`company_id`))) left join `hris_csi`.`company` `d` on((`b`.`company_ind` = `d`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_employee_information`
--
DROP TABLE IF EXISTS `view_employee_information`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_employee_information`  AS  select `a`.`emp_no` AS `emp_no`,`a`.`company_id` AS `company_id`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `fullname`,`a`.`lname` AS `lname`,`a`.`fname` AS `fname`,`b`.`company_ind` AS `company_ind`,`d`.`company_name` AS `company_name`,`b`.`department` AS `department`,`b`.`position` AS `position`,`b`.`team` AS `team`,`b`.`employment_status` AS `employment_status`,`a`.`active` AS `active` from ((`hris_csi`.`personal_information` `a` left join `hris_csi`.`employee_information` `b` on((`a`.`company_id` = `b`.`company_id`))) left join `hris_csi`.`company` `d` on((`b`.`company_ind` = `d`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_employee_schedule`
--
DROP TABLE IF EXISTS `view_employee_schedule`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_employee_schedule`  AS  select `a`.`emp_no` AS `emp_no`,`a`.`company_id` AS `company_id`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `fullname`,`a`.`lname` AS `lname`,`a`.`fname` AS `fname`,`b`.`company_ind` AS `company_ind`,`d`.`company_name` AS `company_name`,`b`.`department` AS `department`,`b`.`position` AS `position`,`b`.`team` AS `team`,`b`.`employment_status` AS `employment_status`,`a`.`active` AS `active`,`e`.`template_id` AS `template_id`,`f`.`type` AS `type`,`f`.`reg_in` AS `reg_in`,`f`.`reg_out` AS `reg_out`,`f`.`mon_in` AS `mon_in`,`f`.`mon_out` AS `mon_out`,`f`.`mon` AS `mon`,`f`.`tue_in` AS `tue_in`,`f`.`tue_out` AS `tue_out`,`f`.`tue` AS `tue`,`f`.`wed_in` AS `wed_in`,`f`.`wed_out` AS `wed_out`,`f`.`wed` AS `wed`,`f`.`thu_in` AS `thu_in`,`f`.`thu_out` AS `thu_out`,`f`.`thu` AS `thu`,`f`.`fri_in` AS `fri_in`,`f`.`fri_out` AS `fri_out`,`f`.`fri` AS `fri`,`f`.`sat_in` AS `sat_in`,`f`.`sat_out` AS `sat_out`,`f`.`sat` AS `sat`,`f`.`sun_in` AS `sun_in`,`f`.`sun_out` AS `sun_out`,`f`.`sun` AS `sun`,`f`.`flexihrs` AS `flexihrs`,`f`.`lunch_out` AS `lunch_out`,`f`.`lunch_in` AS `lunch_in` from ((((`hris_csi`.`personal_information` `a` left join `hris_csi`.`employee_information` `b` on((`a`.`company_id` = `b`.`company_id`))) left join `hris_csi`.`company` `d` on((`b`.`company_ind` = `d`.`id`))) left join `hris_csi`.`employee_schedule` `e` on((`a`.`company_id` = `e`.`company_id`))) left join `hris_csi`.`schedule_template` `f` on((`e`.`template_id` = `f`.`ind`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_employee_schedule_request`
--
DROP TABLE IF EXISTS `view_employee_schedule_request`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_employee_schedule_request`  AS  select `f`.`id` AS `id`,`a`.`emp_no` AS `emp_no`,`a`.`company_id` AS `company_id`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `fullname`,`a`.`lname` AS `lname`,`a`.`fname` AS `fname`,`b`.`company_ind` AS `company_ind`,`d`.`company_name` AS `company_name`,`b`.`department` AS `department`,`b`.`position` AS `position`,`b`.`team` AS `team`,`b`.`employment_status` AS `employment_status`,`a`.`active` AS `active`,`f`.`request_status` AS `request_status`,`e`.`type` AS `type`,`f`.`start_date` AS `start_date`,`f`.`end_date` AS `end_date` from ((((`hris_csi`.`employee_schedule_request` `f` left join `hris_csi`.`schedule_template` `e` on((`f`.`template_id` = `e`.`ind`))) left join `hris_csi`.`personal_information` `a` on((`f`.`company_id` = `a`.`company_id`))) left join `hris_csi`.`employee_information` `b` on((`f`.`company_id` = `b`.`company_id`))) left join `hris_csi`.`company` `d` on((`b`.`company_ind` = `d`.`id`))) order by `f`.`id` desc ;

-- --------------------------------------------------------

--
-- Structure for view `view_leave_records`
--
DROP TABLE IF EXISTS `view_leave_records`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_leave_records`  AS  select `e`.`id` AS `id`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `emp_name`,`e`.`company_id` AS `company_id`,`e`.`date_applied` AS `date_applied`,`e`.`reason` AS `reason`,`e`.`duration` AS `duration`,`e`.`start_date` AS `start_date`,`e`.`end_date` AS `end_date`,`e`.`id` AS `leave_record_id`,(select `a`.`leave_name` from `hris_csi`.`leave_template` `a` where (`a`.`id` = `e`.`leave_id`)) AS `leave_name`,`e`.`approved_1_id` AS `approved_1_id`,`e`.`approved_2_id` AS `approved_2_id`,`e`.`approved_1` AS `approved_1`,`e`.`approved_2` AS `approved_2`,`e`.`status` AS `status`,`e`.`deleted` AS `deleted`,`e`.`payroll_register_number` AS `payroll_register_number`,`e`.`lu_by` AS `lu_by`,`e`.`created_at` AS `created_at`,`e`.`updated_at` AS `updated_at`,`a`.`emp_no` AS `emp_no`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `fullname`,`a`.`lname` AS `lname`,`a`.`fname` AS `fname`,`b`.`company_ind` AS `company_ind`,`d`.`company_name` AS `company_name`,`b`.`department` AS `department`,`b`.`position` AS `position`,`b`.`team` AS `team`,`b`.`employment_status` AS `employment_status`,`a`.`birtdate` AS `birtdate`,`a`.`active` AS `active` from (((`leave_records` `e` left join `hris_csi`.`personal_information` `a` on((`e`.`company_id` = `a`.`company_id`))) left join `hris_csi`.`employee_information` `b` on((`a`.`company_id` = `b`.`company_id`))) left join `hris_csi`.`company` `d` on((`b`.`company_ind` = `d`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_overtime_records`
--
DROP TABLE IF EXISTS `view_overtime_records`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_overtime_records`  AS  select `e`.`id` AS `id`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `emp_name`,`e`.`company_id` AS `company_id`,`e`.`sched_date` AS `sched_date`,`e`.`date_applied` AS `date_applied`,`e`.`shift_applied` AS `shift_applied`,`e`.`date_timein` AS `date_timein`,`e`.`date_timeout` AS `date_timeout`,`e`.`total_hrs` AS `total_hrs`,`e`.`reason` AS `reason`,`e`.`approved_1_id` AS `approved_1_id`,`e`.`approved_2_id` AS `approved_2_id`,`e`.`approved_1` AS `approved_1`,`e`.`approved_2` AS `approved_2`,`e`.`status` AS `status`,`e`.`deleted` AS `deleted`,`e`.`payroll_register_number` AS `payroll_register_number`,`e`.`lu_by` AS `lu_by`,`e`.`created_at` AS `created_at`,`e`.`updated_at` AS `updated_at`,`a`.`emp_no` AS `emp_no`,concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `fullname`,`a`.`lname` AS `lname`,`a`.`fname` AS `fname`,`b`.`company_ind` AS `company_ind`,`d`.`company_name` AS `company_name`,`b`.`department` AS `department`,`b`.`position` AS `position`,`b`.`team` AS `team`,`b`.`employment_status` AS `employment_status`,`a`.`birtdate` AS `birtdate`,`a`.`active` AS `active` from (((`overtime_records` `e` left join `hris_csi`.`personal_information` `a` on((`e`.`company_id` = `a`.`company_id`))) left join `hris_csi`.`employee_information` `b` on((`a`.`company_id` = `b`.`company_id`))) left join `hris_csi`.`company` `d` on((`b`.`company_ind` = `d`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_punch_alteration_records`
--
DROP TABLE IF EXISTS `view_punch_alteration_records`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_punch_alteration_records`  AS  select `b`.`id` AS `id`,`b`.`company_id` AS `company_id`,concat(`q`.`lname`,', ',`q`.`fname`,' ',`q`.`mname`) AS `emp_name`,`b`.`sched_date` AS `sched_date`,`b`.`date_applied` AS `date_applied`,`b`.`cur_time_in` AS `cur_time_in`,`b`.`cur_time_out` AS `cur_time_out`,`b`.`new_time_in` AS `new_time_in`,`b`.`new_time_out` AS `new_time_out`,`b`.`total_hrs` AS `total_hrs`,`b`.`undertime` AS `undertime`,`b`.`late` AS `late`,`b`.`reason` AS `reason`,`b`.`approved_1_id` AS `approved_1_id`,`b`.`approved_2_id` AS `approved_2_id`,`b`.`approved_1` AS `approved_1`,`b`.`approved_2` AS `approved_2`,`b`.`status` AS `status`,`b`.`deleted` AS `deleted`,`b`.`payroll_register_number` AS `payroll_register_number`,`b`.`lu_by` AS `lu_by`,`b`.`created_at` AS `created_at`,`b`.`updated_at` AS `updated_at`,(select concat(`a`.`lname`,', ',`a`.`fname`,' ',`a`.`mname`) AS `fullname` from `hris_csi`.`personal_information` `a` where (`a`.`company_id` = `b`.`approved_1_id`)) AS `level1name`,(select concat(`c`.`lname`,', ',`c`.`fname`,' ',`c`.`mname`) AS `fullname` from `hris_csi`.`personal_information` `c` where (`c`.`company_id` = `b`.`approved_2_id`)) AS `level2name` from (`alteration_records` `b` left join `hris_csi`.`personal_information` `q` on((`b`.`company_id` = `q`.`company_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_user`
--
DROP TABLE IF EXISTS `view_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user`  AS  select `a`.`id` AS `id`,`a`.`company_id` AS `company_id`,concat(`b`.`lname`,', ',`b`.`fname`,' ',`b`.`mname`) AS `fullname`,`a`.`email` AS `email`,`a`.`user_type_id` AS `user_type_id`,`a`.`lu_by` AS `lu_by`,`c`.`type_name` AS `type_name` from ((`users` `a` left join `hris_csi`.`personal_information` `b` on((`a`.`company_id` = `b`.`company_id`))) left join `user_type` `c` on((`a`.`user_type_id` = `c`.`id`))) where (`a`.`active` = 'yes') ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alteration_records`
--
ALTER TABLE `alteration_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_records`
--
ALTER TABLE `leave_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `overtime_records`
--
ALTER TABLE `overtime_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `sub_leave_records`
--
ALTER TABLE `sub_leave_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_modules`
--
ALTER TABLE `user_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_module_access`
--
ALTER TABLE `user_module_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alteration_records`
--
ALTER TABLE `alteration_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `leave_records`
--
ALTER TABLE `leave_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `overtime_records`
--
ALTER TABLE `overtime_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_leave_records`
--
ALTER TABLE `sub_leave_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_modules`
--
ALTER TABLE `user_modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_module_access`
--
ALTER TABLE `user_module_access`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
