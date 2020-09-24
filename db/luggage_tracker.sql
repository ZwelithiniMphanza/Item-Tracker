-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2020 at 02:20 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `luggage_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `delivered`
--

CREATE TABLE `delivered` (
  `id` int(60) NOT NULL,
  `luggage_number_id` int(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivered`
--

INSERT INTO `delivered` (`id`, `luggage_number_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-12-18 09:45:36', '2019-12-18 09:45:36');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(60) NOT NULL,
  `place` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `place`, `created_at`, `updated_at`) VALUES
(1, 'Kabwe', '2019-12-16 22:00:00', NULL),
(2, 'Kapili', '2019-12-16 22:00:00', NULL),
(3, 'Ndola', '2019-12-16 22:00:00', NULL),
(4, 'Kitwe', '2019-12-16 22:00:00', NULL),
(5, 'Chambeshi', '2019-12-16 22:00:00', NULL),
(6, 'Chingola', '2019-12-16 22:00:00', NULL),
(7, 'Solwezi', '2019-12-16 22:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dispatch`
--

CREATE TABLE `dispatch` (
  `id` int(60) NOT NULL,
  `route_id` int(10) NOT NULL,
  `luggage_manifest` text NOT NULL,
  `status` enum('Transit','Arrived','Delayed','') NOT NULL,
  `notification_status` int(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dispatch`
--

INSERT INTO `dispatch` (`id`, `route_id`, `luggage_manifest`, `status`, `notification_status`, `created_at`, `updated_at`) VALUES
(2, 1, '{\n	\"manifest\": [{\n		\"luggage_number\": \"78445541\",\n		\"qr_code\": \"hgjavsjhcjashvcasm\",\n		\"ref_number\": \"hvahsasvajh\",\n		\"passenger_id\": 1,\n		\"destination_id\": 1\n	}, {\n		\"luggage_number\": \"78445541\",\n		\"qr_code\": \"hgjavsjhcjashvcasm\",\n		\"ref_number\": \"hvahsasvajh\",\n		\"passenger_id\": 2,\n		\"destination_id\": 2\n	}]\n}', 'Transit', 0, '2019-12-17 08:02:51', '2019-12-17 08:02:51'),
(3, 1, '{\n	\"manifest\": [{\n		\"luggage_number\": \"10003\",\n		\"qr_code\": \"45156412584\",\n		\"ref_number\": \"45156412584\",\n		\"passenger_id\": 1,\n		\"destination_id\": 1\n	}, {\n		\"luggage_number\": \"78445541\",\n		\"qr_code\": \"hgjavsjhcjashvcasm\",\n		\"ref_number\": \"hvahsasvajh\",\n		\"passenger_id\": 2,\n		\"destination_id\": 2\n	}]\n}', 'Transit', 0, '2019-12-17 08:03:59', '2019-12-17 08:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `luggage`
--

CREATE TABLE `luggage` (
  `id` int(255) NOT NULL,
  `luggage_number` int(255) NOT NULL,
  `qr_code` varchar(60) NOT NULL,
  `ref_number` varchar(90) NOT NULL,
  `passenger_id` int(60) NOT NULL,
  `dispatched` enum('Yes','No','','') NOT NULL,
  `destination_id` int(60) NOT NULL,
  `delivery_id` int(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `luggage`
--

INSERT INTO `luggage` (`id`, `luggage_number`, `qr_code`, `ref_number`, `passenger_id`, `dispatched`, `destination_id`, `delivery_id`, `created_at`, `updated_at`) VALUES
(1, 10003, '45156412584', '45156412584', 1, 'Yes', 2, 1, '2019-12-17 07:49:05', '2019-12-18 09:45:36'),
(3, 10005, '45156412584', '45156412584', 1, 'No', 4, NULL, '2019-12-19 12:12:08', '2019-12-19 12:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(60) NOT NULL,
  `message` varchar(160) NOT NULL,
  `recipient` varchar(20) NOT NULL,
  `sender_id` varchar(60) NOT NULL,
  `status` enum('Delivered','Failed','Pending','') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `recipient`, `sender_id`, `status`, `created_at`, `updated_at`) VALUES
(7, 'Your luggage with luggage# 78445541has been dispatched for Kabwe. Thank you for using this service', '260965376154', 'NHL', 'Pending', '2019-12-17 08:02:51', '2019-12-17 08:02:51'),
(8, 'Your luggage with luggage# 78445541has been dispatched for Kapili. Thank you for using this service', '260950207061', 'NHL', 'Pending', '2019-12-17 08:02:51', '2019-12-17 08:02:51'),
(9, 'Your luggage with luggage# 10003has been dispatched for Kabwe. Thank you for using this service', '260965376154', 'NHL', 'Pending', '2019-12-17 08:03:59', '2019-12-17 08:03:59'),
(10, 'Your luggage with luggage# 78445541has been dispatched for Kapili. Thank you for using this service', '260950207061', 'NHL', 'Pending', '2019-12-17 08:03:59', '2019-12-17 08:03:59'),
(11, 'Your luggage with ref# 45156412584has been logged for tracking. Thank you for using this service', '260965376154', 'NHL', 'Pending', '2019-12-19 12:12:08', '2019-12-19 12:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `passenger`
--

CREATE TABLE `passenger` (
  `id` int(255) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `passport_number` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `address` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `passenger`
--

INSERT INTO `passenger` (`id`, `firstname`, `lastname`, `passport_number`, `mobile`, `password`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Leonard', 'Mbasela', '10010', '260965376154', '$2y$10$W.RYU2rkl9lQWL6f2EgCt.1qfNW/4k12k1OsNczDoKyg5QB76xMTO', 'LC78A ZAF Twin Palms', '2019-12-16 22:00:00', NULL),
(2, 'Peter', 'McMillan', '10019', '260950207061', '$2y$10$0qHiz3fe5P88jl.RhXkkL.uem9UGysv58sUxjoyFTA3d4c3P0pnL6', 'LC70 B ZAF Twin Palms', '2019-12-17 07:41:53', '2019-12-17 07:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` int(255) NOT NULL,
  `qr_code` varchar(60) NOT NULL,
  `used` enum('Yes','No','','') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qr_codes`
--

INSERT INTO `qr_codes` (`id`, `qr_code`, `used`, `created_at`, `updated_at`) VALUES
(1, '45156412584', 'Yes', '2019-12-16 22:00:00', '2019-12-19 12:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(60) NOT NULL,
  `title` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2019-12-16 22:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(60) NOT NULL,
  `name` varchar(60) NOT NULL,
  `coming_from` varchar(60) NOT NULL,
  `going_to` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `name`, `coming_from`, `going_to`, `created_at`, `updated_at`) VALUES
(1, 'Kabwe-Ndola', '1', '3', '2019-12-16 22:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sys_settings`
--

CREATE TABLE `sys_settings` (
  `id` int(60) NOT NULL,
  `_key` varchar(60) NOT NULL,
  `value` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_settings`
--

INSERT INTO `sys_settings` (`id`, `_key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'sender_id', 'NHL', '2019-12-16 22:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(60) NOT NULL,
  `firsname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `employee_id` varchar(60) NOT NULL,
  `role_id` int(60) NOT NULL,
  `password` varchar(90) NOT NULL,
  `address` text NOT NULL,
  `status` enum('Active','Suspended','','') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firsname`, `lastname`, `employee_id`, `role_id`, `password`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Zwelithini', 'Mphanza', '10010', 1, '$2y$10$W.RYU2rkl9lQWL6f2EgCt.1qfNW/4k12k1OsNczDoKyg5QB76xMTO', 'LC79A ZAF Twin Palms', 'Active', '2019-12-16 22:00:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivered`
--
ALTER TABLE `delivered`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatch`
--
ALTER TABLE `dispatch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `luggage`
--
ALTER TABLE `luggage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `luggage_number` (`luggage_number`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passenger`
--
ALTER TABLE `passenger`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `passport_number` (`passport_number`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_settings`
--
ALTER TABLE `sys_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `delivered`
--
ALTER TABLE `delivered`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dispatch`
--
ALTER TABLE `dispatch`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `luggage`
--
ALTER TABLE `luggage`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `passenger`
--
ALTER TABLE `passenger`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sys_settings`
--
ALTER TABLE `sys_settings`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
