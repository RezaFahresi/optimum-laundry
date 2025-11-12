-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2025 at 11:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Satuan', '2025-05-22 08:30:23', '2025-05-22 08:30:23'),
(2, 'Kiloan', '2025-05-22 08:30:23', '2025-05-22 08:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_suggestions`
--

CREATE TABLE `complaint_suggestions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `type` char(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reply` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fotopengembalian`
--

CREATE TABLE `fotopengembalian` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fotopengembalian`
--

INSERT INTO `fotopengembalian` (`id`, `transaction_id`, `foto`) VALUES
(3, 28, 'uploads/pengembalian/1762830274_6912a7c20b5fa.png'),
(4, 28, 'uploads/pengembalian/1762830274_6912a7c20ca22.png'),
(5, 30, 'uploads/pengembalian/1762855509_69130a55c99c3.png'),
(6, 30, 'uploads/pengembalian/1762855509_69130a55dc29b.png');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Baju', '2025-05-22 08:30:23', '2025-05-22 08:30:23'),
(2, 'Celana', '2025-05-22 08:30:23', '2025-05-22 08:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2022_04_26_214509_create_items_table', 1),
(4, '2022_04_26_220421_create_categories_table', 1),
(5, '2022_04_26_220542_create_complaint_suggestions_table', 1),
(6, '2022_04_26_221116_create_services_table', 1),
(7, '2022_04_26_221214_create_statuses_table', 1),
(8, '2022_04_26_221346_create_transactions_table', 1),
(9, '2022_04_26_222040_create_price_lists_table', 1),
(10, '2022_04_26_222156_create_vouchers_table', 1),
(11, '2022_04_26_222157_create_user_vouchers_table', 1),
(12, '2022_04_26_222913_create_transaction_details_table', 1),
(13, '2022_08_16_090618_create_service_type_table', 1),
(14, '2022_08_16_091616_add_service_type_to_transactions', 1),
(15, '2022_08_19_140133_add_payment_amount_transaction', 1),
(16, '2022_10_18_211852_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `idpengeluaran` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `jumlah` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`idpengeluaran`, `judul`, `jumlah`, `tanggal`, `deskripsi`) VALUES
(1, 'bayar listrik', '10000', '2025-10-21', 'adsad'),
(2, 'bayar air', '1000', '2025-10-21', 'bayar air');

-- --------------------------------------------------------

--
-- Table structure for table `price_lists`
--

CREATE TABLE `price_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_lists`
--

INSERT INTO `price_lists` (`id`, `item_id`, `category_id`, `service_id`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 10000, '2025-05-26 05:45:55', '2025-05-26 05:45:55'),
(2, 1, 1, 1, 10000, '2025-05-26 05:45:55', '2025-05-26 05:45:55'),
(3, 2, 2, 1, 10000, '2025-05-26 05:45:55', '2025-05-26 05:45:55'),
(4, 2, 1, 1, 10000, '2025-05-26 05:45:55', '2025-05-26 05:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('daily','weekly','monthly','custom') NOT NULL,
  `rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rules`)),
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `last_proccess` date DEFAULT NULL,
  `time` time NOT NULL,
  `order_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`order_details`)),
  `total_amount` int(11) NOT NULL,
  `service_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pickup_option` varchar(100) NOT NULL,
  `status` enum('active','paused','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `user_id`, `type`, `rules`, `start_date`, `end_date`, `last_run`, `last_proccess`, `time`, `order_details`, `total_amount`, `service_type_id`, `voucher_id`, `pickup_option`, `status`, `created_at`, `updated_at`) VALUES
(12, 2, 'weekly', '{\"weekly\":[\"senin\",\"selasa\",\"rabu\",\"kamis\",\"jumat\"],\"monthly\":[],\"custom\":[]}', '2025-10-21', NULL, '2025-11-11 09:17:19', NULL, '17:04:00', '[{\"item_id\":1,\"item_name\":\"Baju\",\"service_id\":1,\"service_name\":\"Cuci\",\"category_id\":1,\"category_name\":\"Satuan\",\"quantity\":1,\"amount\":10000,\"price\":10000,\"subtotal\":10000,\"total\":20000}]', 20000, 2, NULL, 'app', 'active', '2025-10-21 10:05:08', '2025-11-11 02:17:19'),
(13, 2, 'monthly', '{\"weekly\":[],\"monthly\":[\"21\"],\"custom\":[]}', '2025-10-21', NULL, '2025-10-23 13:48:02', NULL, '17:08:00', '[{\"item_id\":1,\"item_name\":\"Baju\",\"service_id\":1,\"service_name\":\"Cuci\",\"category_id\":1,\"category_name\":\"Satuan\",\"quantity\":1,\"amount\":10000,\"price\":0,\"subtotal\":10000,\"total\":10000}]', 10000, 1, NULL, 'none', 'active', '2025-10-21 10:08:53', '2025-10-23 06:54:08');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_logs`
--

CREATE TABLE `schedule_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `status` enum('email sent','failed','skipped') NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `recipients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`recipients`)),
  `error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_logs`
--

INSERT INTO `schedule_logs` (`id`, `schedule_id`, `user_id`, `user_email`, `status`, `reason`, `recipients`, `error`, `created_at`, `updated_at`) VALUES
(83, 12, 2, 'fahruladib9@gmail.com', 'email sent', 'weekly match (selasa)', '[\"fahruladib9@gmail.com\",\"admin@gmail.com\"]', NULL, '2025-10-21 10:05:16', '2025-10-21 10:05:16'),
(84, 13, 2, 'fahruladib9@gmail.com', 'email sent', 'monthly match (day 21)', '[\"fahruladib9@gmail.com\",\"admin@gmail.com\"]', NULL, '2025-10-21 10:09:02', '2025-10-21 10:09:02'),
(85, 12, 2, 'fahruladib9@gmail.com', 'email sent', 'weekly match (rabu)', '[\"fahruladib9@gmail.com\",\"admin@gmail.com\"]', NULL, '2025-10-22 09:20:10', '2025-10-22 09:20:10'),
(86, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:20:10', '2025-10-22 09:20:10'),
(87, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:20:18', '2025-10-22 09:20:18'),
(88, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:20:23', '2025-10-22 09:20:23'),
(89, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:26:06', '2025-10-22 09:26:06'),
(90, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:28:32', '2025-10-22 09:28:32'),
(91, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:28:49', '2025-10-22 09:28:49'),
(92, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:32:30', '2025-10-22 09:32:30'),
(93, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:36:17', '2025-10-22 09:36:17'),
(94, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-22 09:36:31', '2025-10-22 09:36:31'),
(95, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (kamis)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-10-23 06:44:58', '2025-10-23 06:44:58'),
(96, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-23 06:44:58', '2025-10-23 06:44:58'),
(97, 12, 2, 'fahruladib9@gmail.com', 'email sent', 'weekly match (kamis)', '[\"fahruladib9@gmail.com\",\"admin@gmail.com\"]', NULL, '2025-10-23 06:45:10', '2025-10-23 06:45:10'),
(98, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-23 06:45:10', '2025-10-23 06:45:10'),
(99, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-23 06:45:17', '2025-10-23 06:45:17'),
(100, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-23 06:45:20', '2025-10-23 06:45:20'),
(101, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-23 06:45:27', '2025-10-23 06:45:27'),
(102, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-10-23 06:45:32', '2025-10-23 06:45:32'),
(103, 13, 2, 'fahruladib9@gmail.com', 'email sent', 'daily schedule', '[\"fahruladib9@gmail.com\",\"admin@gmail.com\"]', NULL, '2025-10-23 06:48:02', '2025-10-23 06:48:02'),
(104, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:28:19', '2025-11-11 01:28:19'),
(105, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:28:19', '2025-11-11 01:28:19'),
(106, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:29:36', '2025-11-11 01:29:36'),
(107, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:29:36', '2025-11-11 01:29:36'),
(108, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:29:41', '2025-11-11 01:29:41'),
(109, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:29:41', '2025-11-11 01:29:41'),
(110, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:29:50', '2025-11-11 01:29:50'),
(111, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:29:50', '2025-11-11 01:29:50'),
(112, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:29:57', '2025-11-11 01:29:57'),
(113, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:29:57', '2025-11-11 01:29:57'),
(114, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:30:29', '2025-11-11 01:30:29'),
(115, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:30:29', '2025-11-11 01:30:29'),
(116, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:33:41', '2025-11-11 01:33:41'),
(117, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:33:41', '2025-11-11 01:33:41'),
(118, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:36:01', '2025-11-11 01:36:01'),
(119, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:36:01', '2025-11-11 01:36:01'),
(120, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:36:16', '2025-11-11 01:36:16'),
(121, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:36:16', '2025-11-11 01:36:16'),
(122, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:36:23', '2025-11-11 01:36:23'),
(123, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:36:23', '2025-11-11 01:36:23'),
(124, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:55:29', '2025-11-11 01:55:29'),
(125, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:55:29', '2025-11-11 01:55:29'),
(126, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 01:55:51', '2025-11-11 01:55:51'),
(127, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 01:55:52', '2025-11-11 01:55:52'),
(128, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 02:01:11', '2025-11-11 02:01:11'),
(129, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:01:11', '2025-11-11 02:01:11'),
(130, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 02:03:09', '2025-11-11 02:03:09'),
(131, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:03:10', '2025-11-11 02:03:10'),
(132, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 02:04:00', '2025-11-11 02:04:00'),
(133, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:04:01', '2025-11-11 02:04:01'),
(134, 12, 2, 'fahruladib9@gmail.com', 'failed', 'weekly match (selasa)', NULL, 'Connection could not be established with host \"ssl://smtp.gmail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.gmail.com failed: No such host is known. ', '2025-11-11 02:04:14', '2025-11-11 02:04:14'),
(135, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:04:14', '2025-11-11 02:04:14'),
(136, 12, 2, 'fahruladib9@gmail.com', 'email sent', 'weekly match (selasa)', '[\"fahruladib9@gmail.com\",\"admin@gmail.com\"]', NULL, '2025-11-11 02:17:19', '2025-11-11 02:17:19'),
(137, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:17:19', '2025-11-11 02:17:19'),
(138, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:17:25', '2025-11-11 02:17:25'),
(139, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:17:50', '2025-11-11 02:17:50'),
(140, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:28:24', '2025-11-11 02:28:24'),
(141, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:28:29', '2025-11-11 02:28:29'),
(142, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:28:36', '2025-11-11 02:28:36'),
(143, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:28:42', '2025-11-11 02:28:42'),
(144, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:28:52', '2025-11-11 02:28:52'),
(145, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:29:07', '2025-11-11 02:29:07'),
(146, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:29:21', '2025-11-11 02:29:21'),
(147, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:29:59', '2025-11-11 02:29:59'),
(148, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:30:09', '2025-11-11 02:30:09'),
(149, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:31:49', '2025-11-11 02:31:49'),
(150, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:31:52', '2025-11-11 02:31:52'),
(151, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:31:55', '2025-11-11 02:31:55'),
(152, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:32:02', '2025-11-11 02:32:02'),
(153, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:32:06', '2025-11-11 02:32:06'),
(154, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:32:37', '2025-11-11 02:32:37'),
(155, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:32:41', '2025-11-11 02:32:41'),
(156, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:33:19', '2025-11-11 02:33:19'),
(157, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:33:27', '2025-11-11 02:33:27'),
(158, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:33:30', '2025-11-11 02:33:30'),
(159, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:33:45', '2025-11-11 02:33:45'),
(160, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:34:27', '2025-11-11 02:34:27'),
(161, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:34:29', '2025-11-11 02:34:29'),
(162, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:34:33', '2025-11-11 02:34:33'),
(163, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:42:08', '2025-11-11 02:42:08'),
(164, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:43:35', '2025-11-11 02:43:35'),
(165, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:43:43', '2025-11-11 02:43:43'),
(166, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 02:57:31', '2025-11-11 02:57:31'),
(167, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:01:54', '2025-11-11 03:01:54'),
(168, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:02:08', '2025-11-11 03:02:08'),
(169, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:02:18', '2025-11-11 03:02:18'),
(170, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:02:46', '2025-11-11 03:02:46'),
(171, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:02:57', '2025-11-11 03:02:57'),
(172, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:04:09', '2025-11-11 03:04:09'),
(173, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:04:23', '2025-11-11 03:04:23'),
(174, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:04:36', '2025-11-11 03:04:36'),
(175, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:04:58', '2025-11-11 03:04:58'),
(176, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:05:08', '2025-11-11 03:05:08'),
(177, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:05:48', '2025-11-11 03:05:48'),
(178, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:05:56', '2025-11-11 03:05:56'),
(179, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:06:26', '2025-11-11 03:06:26'),
(180, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:06:31', '2025-11-11 03:06:31'),
(181, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:06:34', '2025-11-11 03:06:34'),
(182, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:06:37', '2025-11-11 03:06:37'),
(183, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:08:48', '2025-11-11 03:08:48'),
(184, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:09:06', '2025-11-11 03:09:06'),
(185, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:09:44', '2025-11-11 03:09:44'),
(186, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:10:07', '2025-11-11 03:10:07'),
(187, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:13:37', '2025-11-11 03:13:37'),
(188, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:14:06', '2025-11-11 03:14:06'),
(189, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:14:32', '2025-11-11 03:14:32'),
(190, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:15:07', '2025-11-11 03:15:07'),
(191, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:15:58', '2025-11-11 03:15:58'),
(192, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:16:11', '2025-11-11 03:16:11'),
(193, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:17:08', '2025-11-11 03:17:08'),
(194, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:20:13', '2025-11-11 03:20:13'),
(195, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:20:17', '2025-11-11 03:20:17'),
(196, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:21:35', '2025-11-11 03:21:35'),
(197, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:22:02', '2025-11-11 03:22:02'),
(198, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:22:34', '2025-11-11 03:22:34'),
(199, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:23:00', '2025-11-11 03:23:00'),
(200, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:23:51', '2025-11-11 03:23:51'),
(201, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:24:58', '2025-11-11 03:24:58'),
(202, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:26:09', '2025-11-11 03:26:09'),
(203, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:26:49', '2025-11-11 03:26:49'),
(204, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:26:53', '2025-11-11 03:26:53'),
(205, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:27:02', '2025-11-11 03:27:02'),
(206, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:28:47', '2025-11-11 03:28:47'),
(207, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:28:54', '2025-11-11 03:28:54'),
(208, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 03:28:58', '2025-11-11 03:28:58'),
(209, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:02:56', '2025-11-11 10:02:56'),
(210, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:03:18', '2025-11-11 10:03:18'),
(211, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:03:23', '2025-11-11 10:03:23'),
(212, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:03:32', '2025-11-11 10:03:32'),
(213, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:03:44', '2025-11-11 10:03:44'),
(214, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:03:58', '2025-11-11 10:03:58'),
(215, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:04:07', '2025-11-11 10:04:07'),
(216, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:04:12', '2025-11-11 10:04:12'),
(217, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:04:32', '2025-11-11 10:04:32'),
(218, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:04:45', '2025-11-11 10:04:45'),
(219, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:04:48', '2025-11-11 10:04:48'),
(220, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:04:52', '2025-11-11 10:04:52'),
(221, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:05:12', '2025-11-11 10:05:12'),
(222, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:05:25', '2025-11-11 10:05:25'),
(223, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:05:29', '2025-11-11 10:05:29'),
(224, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:05:53', '2025-11-11 10:05:53'),
(225, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:06:05', '2025-11-11 10:06:05'),
(226, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:06:08', '2025-11-11 10:06:08'),
(227, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:06:11', '2025-11-11 10:06:11'),
(228, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:06:47', '2025-11-11 10:06:47'),
(229, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:06:51', '2025-11-11 10:06:51'),
(230, 13, 2, 'fahruladib9@gmail.com', 'skipped', 'monthly not match', NULL, NULL, '2025-11-11 10:07:46', '2025-11-11 10:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Cuci', '2025-05-22 08:30:23', '2025-05-22 08:30:23'),
(2, 'Setrika', '2025-05-22 08:30:23', '2025-05-22 08:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

CREATE TABLE `service_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `cost` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`id`, `name`, `description`, `cost`, `created_at`, `updated_at`) VALUES
(1, 'Regular Service', 'Layanan reguler dengan lama waktu pengerjaan yang tidak tentu', 0, '2025-05-22 08:30:23', '2025-05-22 08:30:23'),
(2, 'Priority Service', 'Layanan prioritas dengan waktu pengerjaan satu hari', 10000, '2025-05-22 08:30:23', '2025-05-22 08:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Belum Dikerjakan', '2025-05-22 08:30:23', '2025-05-22 08:30:23'),
(2, 'Sedang Dikerjakan', '2025-05-22 08:30:23', '2025-05-22 08:30:23'),
(3, 'Selesai', '2025-05-22 08:30:23', '2025-05-22 08:30:23'),
(4, 'Proses Pengembalian', NULL, NULL),
(5, 'Pengembalian Diterima', NULL, NULL),
(6, 'Pengembalian Ditolak', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `idstok` int(11) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`idstok`, `nama`, `stok`) VALUES
(1, 'Deterjen', 54);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL,
  `service_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `finish_date` timestamp NULL DEFAULT NULL,
  `service_cost` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `discount` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_amount` int(11) NOT NULL DEFAULT 0,
  `bukti_pembayaran` text DEFAULT NULL,
  `metodepembayaran` varchar(250) NOT NULL,
  `pickup_option` varchar(255) NOT NULL DEFAULT 'none',
  `keluhan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `status_id`, `service_type_id`, `admin_id`, `member_id`, `finish_date`, `service_cost`, `discount`, `total`, `created_at`, `updated_at`, `payment_amount`, `bukti_pembayaran`, `metodepembayaran`, `pickup_option`, `keluhan`) VALUES
(9, 3, 2, 1, 2, '2025-05-27 11:18:43', 10000, 0, 20000, '2025-05-27 11:18:22', '2025-05-27 11:18:43', 20000, 'uploads/1748341102_Capture.JPG', 'transfer', 'none', NULL),
(10, 3, 2, 2, 2, '2025-05-27 11:23:42', 10000, 0, 20000, '2025-05-27 11:23:04', '2025-05-27 11:23:42', 20000, 'uploads/1748341384_Capture.JPG', 'transfer', 'none', NULL),
(16, 2, 2, 1, 2, NULL, 10000, 0, 30000, '2025-09-14 09:31:26', '2025-09-15 08:51:13', 30000, 'uploads/1757838686_Untitled-1.png', 'transfer', 'none', NULL),
(17, 3, 2, 1, 2, '2025-09-15 08:46:32', 10000, 0, 20000, '2025-09-14 13:04:00', '2025-09-15 08:46:32', 20000, 'uploads/1757851440_Untitled-1.png', 'transfer', 'none', NULL),
(18, 2, 2, 1, 2, NULL, 10000, 0, 40000, '2025-09-15 08:30:31', '2025-09-15 08:54:30', 40000, NULL, 'transfer', 'none', NULL),
(19, 3, 2, 1, 2, '2025-09-15 10:47:15', 10000, 0, 30000, '2025-09-15 10:46:27', '2025-09-15 10:47:15', 30000, NULL, 'transfer', 'none', NULL),
(24, 3, 2, 1, 2, '2025-10-21 10:06:00', 10000, 0, 20000, '2025-10-21 10:06:00', '2025-10-21 10:06:00', 20000, NULL, 'cash', 'none', NULL),
(25, 3, 1, 1, 2, '2025-10-21 10:09:49', 0, 0, 10000, '2025-10-21 10:09:49', '2025-10-21 10:09:49', 10000, NULL, 'cash', 'none', NULL),
(28, 5, 1, 1, 2, NULL, 0, 0, 10000, '2025-11-11 02:29:18', '2025-11-11 03:21:48', 10000, 'uploads/1762828158_2.png', 'transfer', 'wa', 'asdadsa'),
(29, 1, 1, 1, 2, NULL, 0, 0, 10000, '2025-11-11 02:30:06', '2025-11-11 02:30:06', 10000, 'uploads/1762828206_2.png', 'transfer', 'wa', NULL),
(30, 5, 1, 1, 2, NULL, 0, 0, 10000, '2025-11-11 10:03:41', '2025-11-11 10:05:46', 10000, 'uploads/1762855421_logo bukutamudigital.png', 'transfer', 'wa', 'masih kotor');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `price_list_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `price_list_id`, `quantity`, `price`, `sub_total`, `created_at`, `updated_at`) VALUES
(7, 9, 1, 1, 10000, 10000, '2025-05-27 11:18:22', '2025-05-27 11:18:22'),
(8, 10, 1, 1, 10000, 10000, '2025-05-27 11:23:04', '2025-05-27 11:23:04'),
(10, 16, 1, 2, 10000, 20000, '2025-09-14 09:31:26', '2025-09-14 09:31:26'),
(11, 17, 2, 1, 10000, 10000, '2025-09-14 13:04:00', '2025-09-14 13:04:00'),
(12, 18, 1, 3, 10000, 30000, '2025-09-15 08:30:31', '2025-09-15 08:30:31'),
(13, 19, 2, 2, 10000, 20000, '2025-09-15 10:46:27', '2025-09-15 10:46:27'),
(18, 24, 2, 1, 10000, 10000, '2025-10-21 10:06:00', '2025-10-21 10:06:00'),
(19, 25, 2, 1, 0, 10000, '2025-10-21 10:09:49', '2025-10-21 10:09:49'),
(22, 28, 2, 1, 10000, 10000, '2025-11-11 02:29:18', '2025-11-11 02:29:18'),
(23, 29, 2, 1, 10000, 10000, '2025-11-11 02:30:06', '2025-11-11 02:30:06'),
(24, 30, 2, 1, 10000, 10000, '2025-11-11 10:03:41', '2025-11-11 10:03:41');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_logs`
--

CREATE TABLE `transaction_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `changed_by` bigint(20) UNSIGNED NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_logs`
--

INSERT INTO `transaction_logs` (`id`, `transaction_id`, `changed_by`, `old_status`, `new_status`, `note`, `created_at`, `updated_at`) VALUES
(1, 17, 1, '2', '1', 'Status transaksi diubah oleh admin', '2025-09-15 08:42:13', '2025-09-15 15:42:13'),
(2, 17, 1, 'Sedang Dikerjakan', 'Selesai', 'Status transaksi diubah oleh admin', '2025-09-15 08:46:32', '2025-09-15 15:46:32'),
(3, 16, 1, 'Belum Dikerjakan', 'Sedang Dikerjakan', 'Status transaksi diubah oleh admin', '2025-09-15 08:51:13', '2025-09-15 15:51:13'),
(4, 19, 1, 'Belum Dikerjakan', 'Sedang Dikerjakan', 'Status transaksi diubah oleh admin', '2025-09-15 10:46:48', '2025-09-15 17:46:48'),
(5, 19, 1, 'Sedang Dikerjakan', 'Selesai', 'Status transaksi diubah oleh admin', '2025-09-15 10:47:15', '2025-09-15 17:47:15'),
(10, 24, 1, NULL, 'Selesai', 'Transaksi otomatis dibuat dari jadwal rutin.', '2025-10-21 10:06:00', '2025-10-21 17:06:00'),
(11, 25, 1, NULL, 'Selesai', 'Transaksi otomatis dibuat dari jadwal rutin.', '2025-10-21 10:09:49', '2025-10-21 17:09:49'),
(12, 28, 1, 'Belum Dikerjakan', 'Selesai', 'Status transaksi diubah oleh admin', '2025-11-11 02:33:38', '2025-11-11 09:33:38'),
(13, 28, 2, '3', '4', 'Pengajuan komplain pengembalian oleh member.', '2025-11-11 02:57:28', '2025-11-11 09:57:28'),
(14, 28, 2, '3', '4', 'Pengajuan komplain pengembalian oleh member.', '2025-11-11 03:02:16', '2025-11-11 10:02:16'),
(15, 28, 2, '3', '4', 'Pengajuan komplain pengembalian oleh member.', '2025-11-11 03:02:54', '2025-11-11 10:02:54'),
(16, 28, 2, '3', '4', 'Pengajuan komplain pengembalian oleh member.', '2025-11-11 03:04:34', '2025-11-11 10:04:34'),
(17, 28, 1, 'Proses Pengembalian', 'Pengembalian Diterima', 'Status transaksi diubah oleh admin', '2025-11-11 03:21:49', '2025-11-11 10:21:49'),
(18, 30, 1, 'Belum Dikerjakan', 'Selesai', 'Status transaksi diubah oleh admin', '2025-11-11 10:04:20', '2025-11-11 17:04:20'),
(19, 30, 2, '3', '4', 'Pengajuan komplain pengembalian oleh member.', '2025-11-11 10:05:09', '2025-11-11 17:05:09'),
(20, 30, 1, 'Proses Pengembalian', 'Pengembalian Diterima', 'Status transaksi diubah oleh admin', '2025-11-11 10:05:46', '2025-11-11 17:05:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 2,
  `gender` char(191) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `profile_picture` varchar(191) NOT NULL DEFAULT 'default.jpg',
  `point` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `gender`, `address`, `phone_number`, `profile_picture`, `point`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Laundry', 'admin@gmail.com', NULL, '$2y$10$wazW6BZ6jrS0k5O1WYt4qu0eDGSjCm8gbdHlGEdBulDTeJRXZ0m3K', 1, NULL, NULL, NULL, 'default.jpg', 0, NULL, '2025-05-22 08:30:23', '2025-05-22 08:30:23'),
(2, 'Fahrul Adib', 'fahruladib9@gmail.com', NULL, '$2y$10$2bi1RobEcQu79esgRYnQAu6uQ5PyZ0xELeXalgUJbM0X3OEICSxny', 2, 'L', 'Jl. Sisingamangaraja No.35, RT.1/RW.4, Gunung, Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12120, Indonesia', '089613325456', 'default.jpg', 16, NULL, '2025-05-26 03:25:54', '2025-11-11 10:03:41'),
(4, 'Owner', 'owner@gmail.com', NULL, '$2y$10$wazW6BZ6jrS0k5O1WYt4qu0eDGSjCm8gbdHlGEdBulDTeJRXZ0m3K', 4, NULL, NULL, NULL, 'default.jpg', 0, NULL, NULL, NULL),
(5, 'Kasir', 'kasir@gmail.com', NULL, '$2y$10$wazW6BZ6jrS0k5O1WYt4qu0eDGSjCm8gbdHlGEdBulDTeJRXZ0m3K', 3, NULL, NULL, NULL, 'default.jpg', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_vouchers`
--

CREATE TABLE `user_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `discount_value` int(11) NOT NULL,
  `point_need` int(11) NOT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT 1,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `complaint_suggestions`
--
ALTER TABLE `complaint_suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_suggestions_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fotopengembalian`
--
ALTER TABLE `fotopengembalian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_name_unique` (`name`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`idpengeluaran`);

--
-- Indexes for table `price_lists`
--
ALTER TABLE `price_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_lists_item_id_foreign` (`item_id`),
  ADD KEY `price_lists_category_id_foreign` (`category_id`),
  ADD KEY `price_lists_service_id_foreign` (`service_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_schedules_user` (`user_id`),
  ADD KEY `fk_schedules_service` (`service_type_id`),
  ADD KEY `fk_schedules_voucher` (`voucher_id`);

--
-- Indexes for table `schedule_logs`
--
ALTER TABLE `schedule_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_logs_schedule_id_index` (`schedule_id`),
  ADD KEY `schedule_logs_user_id_index` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_name_unique` (`name`);

--
-- Indexes for table `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `statuses_name_unique` (`name`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`idstok`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_status_id_foreign` (`status_id`),
  ADD KEY `transactions_admin_id_foreign` (`admin_id`),
  ADD KEY `transactions_member_id_foreign` (`member_id`),
  ADD KEY `transactions_service_type_id_foreign` (`service_type_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_details_price_list_id_foreign` (`price_list_id`);

--
-- Indexes for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_logs_transaction_id_index` (`transaction_id`),
  ADD KEY `transaction_logs_changed_by_index` (`changed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_index` (`role`);

--
-- Indexes for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_vouchers_voucher_id_foreign` (`voucher_id`),
  ADD KEY `user_vouchers_user_id_foreign` (`user_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `complaint_suggestions`
--
ALTER TABLE `complaint_suggestions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fotopengembalian`
--
ALTER TABLE `fotopengembalian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `idpengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `price_lists`
--
ALTER TABLE `price_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `schedule_logs`
--
ALTER TABLE `schedule_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `idstok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaint_suggestions`
--
ALTER TABLE `complaint_suggestions`
  ADD CONSTRAINT `complaint_suggestions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `price_lists`
--
ALTER TABLE `price_lists`
  ADD CONSTRAINT `price_lists_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `price_lists_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `price_lists_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `fk_schedules_service` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_schedules_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_schedules_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `schedule_logs`
--
ALTER TABLE `schedule_logs`
  ADD CONSTRAINT `schedule_logs_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_service_type_id_foreign` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`),
  ADD CONSTRAINT `transactions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_price_list_id_foreign` FOREIGN KEY (`price_list_id`) REFERENCES `price_lists` (`id`),
  ADD CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  ADD CONSTRAINT `transaction_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_logs_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD CONSTRAINT `user_vouchers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_vouchers_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
