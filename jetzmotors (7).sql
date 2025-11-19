-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 12:05 AM
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
-- Database: `jetzmotors`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--  
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `created_at`) VALUES
(1, 'System Admin', 'admin', 'admin', '2025-09-27 04:23:31');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `mechanic_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('Pending','Confirmed','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'OEM'),
(2, 'Racing Boy'),
(3, 'COMSTAR'),
(4, 'Yamaha Genuine Part'),
(5, 'Honda Genuine Part'),
(6, 'Suzuki Genuine Part'),
(7, 'Kawasaki Genuine Part'),
(8, 'OKM'),
(9, 'Enduro'),
(10, 'Motolite'),
(11, 'Bosch'),
(12, 'NGK'),
(13, 'Denso'),
(14, 'Shell Advance'),
(15, 'Motul'),
(16, 'Castrol'),
(17, 'Petron Sprint'),
(18, 'Yokohama'),
(19, 'Pirelli'),
(20, 'IRC'),
(21, 'Michelin'),
(22, 'Bridgestone'),
(23, 'FB Battery'),
(24, 'Aspira'),
(25, 'Sakura'),
(26, 'Sun Racing');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Accessories', '2025-11-17 13:49:29'),
(2, 'Spare Parts', '2025-11-17 13:49:29'),
(3, 'Lubricants', '2025-11-17 13:49:29'),
(4, 'Batteries', '2025-11-17 13:49:29'),
(5, 'Electrical', '2025-11-17 13:49:29'),
(6, 'Tires', '2025-11-17 13:49:29'),
(7, 'Motorcycle Parts', '2025-11-17 13:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `user_id`, `total`, `discount`, `created_at`) VALUES
(1, 1, 970.00, 30.00, '2025-09-25 07:00:00'),
(2, 2, 1500.00, 0.00, '2025-09-26 03:30:00'),
(3, 1, 300.00, 0.00, '2025-09-29 13:14:49'),
(4, 1, 250.00, 0.00, '2025-09-29 13:18:02'),
(5, 1, 300.00, 0.00, '2025-09-29 13:18:15'),
(6, 1, 1800.00, 0.00, '2025-09-29 13:18:44'),
(7, 1, 120.00, 0.00, '2025-09-29 13:20:15'),
(8, 1, 500.00, 0.00, '2025-09-29 13:21:01'),
(9, 1, 250.00, 0.00, '2025-09-29 13:23:33'),
(10, 1, 1746.00, 54.00, '2025-09-29 13:34:58'),
(11, 1, 436.50, 13.50, '2025-09-29 13:35:17'),
(12, 1, 250.00, 0.00, '2025-09-29 13:35:27'),
(13, 1, 116.40, 3.60, '2025-09-29 13:36:16'),
(14, 1, 250.00, 0.00, '2025-09-29 13:36:50'),
(15, 1, 2182.50, 67.50, '2025-09-29 13:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `qty`, `price`, `subtotal`) VALUES
(1, 1, 1, 1, 300.00, 300.00),
(2, 1, 2, 2, 120.00, 240.00),
(3, 1, 3, 1, 450.00, 450.00),
(4, 2, 4, 1, 1800.00, 1800.00),
(5, 3, 1, 1, 300.00, 300.00),
(6, 4, 5, 1, 250.00, 250.00),
(7, 5, 1, 1, 300.00, 300.00),
(8, 6, 4, 1, 1800.00, 1800.00),
(9, 7, 2, 1, 120.00, 120.00),
(10, 8, 5, 2, 250.00, 500.00),
(11, 9, 5, 1, 250.00, 250.00),
(12, 10, 4, 1, 1800.00, 1800.00),
(13, 11, 3, 1, 450.00, 450.00),
(14, 12, 5, 1, 250.00, 250.00),
(15, 13, 2, 1, 120.00, 120.00),
(16, 14, 5, 1, 250.00, 250.00),
(17, 15, 3, 1, 450.00, 450.00),
(18, 15, 4, 1, 1800.00, 1800.00);

-- --------------------------------------------------------

--
-- Table structure for table `mechanics`
--

CREATE TABLE `mechanics` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('Available','Busy','Inactive') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership_id` varchar(50) NOT NULL,
  `discount_rate` decimal(5,2) DEFAULT 3.00,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('Active','Expired') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `user_id`, `membership_id`, `discount_rate`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 1, 'MEM-2025-001', 3.00, '2025-01-01', '2025-12-31', 'Active', '2025-09-29 11:57:13'),
(2, 3, 'MEM-2025-002', 3.00, '2025-01-01', '2025-06-30', 'Expired', '2025-09-29 11:57:13'),
(8, 6, 'MEM-2025-081', 3.00, '2025-11-16', '2026-05-16', 'Active', '2025-11-16 13:44:14'),
(9, 11, 'MEM-2025-198', 3.00, '2025-11-17', '2026-05-17', 'Active', '2025-11-17 01:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE `models` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id`, `brand_id`, `name`) VALUES
(1, 1, 'Universal Fit'),
(2, 1, 'Replacement Standard'),
(3, 1, 'Heavy Duty'),
(4, 2, 'Alloy Rim Sports Type'),
(5, 2, 'Disc Plate Floating Type'),
(6, 2, 'Brake Lever Set'),
(7, 2, 'Side Mirror Sports'),
(8, 2, 'Adjustable Rear Set'),
(9, 3, 'Sprocket Set'),
(10, 3, 'Chain Set Heavy Duty'),
(11, 3, 'Drive Chain Premium'),
(12, 4, 'Gear Oil OEM'),
(13, 4, 'Brake Shoe OEM'),
(14, 4, 'Air Filter OEM'),
(15, 4, 'Belt Drive OEM'),
(16, 5, 'Spark Plug OEM'),
(17, 5, 'Roller Set'),
(18, 5, 'Clutch Lining OEM'),
(19, 5, 'Brake Pad OEM'),
(20, 6, 'Oil Filter OEM'),
(21, 6, 'Fuel Filter OEM'),
(22, 7, 'Ignition Coil OEM'),
(23, 7, 'Brake Shoe OEM'),
(24, 8, 'Shock Absorber Standard'),
(25, 8, 'Shock Absorber Heavy Duty'),
(26, 9, 'Sprocket Premium'),
(27, 9, 'Roller Complete'),
(28, 10, 'Motorcycle Battery 3SM'),
(29, 10, 'Motorcycle Battery 4SM'),
(30, 11, 'Headlight Bulb Halogen'),
(31, 11, 'Horn Set EC6'),
(32, 12, 'Spark Plug CPR8EA-9'),
(33, 12, 'Spark Plug CR8E'),
(34, 13, 'Spark Plug U22FS-U'),
(35, 13, 'Spark Plug IU24'),
(36, 14, '4T Oil 10W-40'),
(37, 14, '4T Oil Scooter 20W-40'),
(38, 15, '4T Oil 10W-50 Fully Synthetic'),
(39, 16, '4T Oil 20W-50 Premium'),
(40, 17, '4T Oil Sprint 4 Motorcycle'),
(41, 18, 'Tube-Type Tire'),
(42, 19, 'Scooter Tire 14\"'),
(43, 20, 'Underbone Tire 17\"'),
(44, 21, 'Sport Tire 17\"'),
(45, 22, 'Maintenance Free Battery'),
(46, 23, 'Conventional Battery'),
(47, 24, 'Motorcycle Chain'),
(48, 24, 'Brake Shoe Set'),
(49, 25, 'Air Filter'),
(50, 25, 'Oil Filter'),
(51, 26, 'Racing CDI'),
(52, 26, 'Racing Coil'),
(53, 26, 'Performance Clutch Set');

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `barcode` varchar(64) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `sku`, `barcode`, `category_id`, `name`, `unit_price`, `stock`, `is_active`, `created_at`) VALUES
(1, 'OIL-001', '8901234567890', NULL, 'Engine Oil 1L', 300.00, 48, 1, '2025-09-27 04:23:31'),
(2, 'SPK-002', '8901234567891', NULL, 'Spark Plug (NGK)', 120.00, 98, 1, '2025-09-27 04:23:31'),
(3, 'BRK-003', '8901234567892', NULL, 'Brake Pads (Honda)', 450.00, 18, 1, '2025-09-27 04:23:31'),
(4, 'BAT-004', '8901234567893', NULL, '12V Motorcycle Battery', 1800.00, 2, 1, '2025-09-27 04:23:31'),
(5, 'HLT-005', '8901234567894', NULL, 'Halogen Headlight Bulb', 250.00, 24, 1, '2025-09-27 04:23:31');

-- --------------------------------------------------------

--
-- Table structure for table `reset_tokens`
--

CREATE TABLE `reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_id`, `user_id`, `total_amount`, `discount_amount`, `final_amount`, `purchase_date`) VALUES
(1, 3, 1, 300.00, 0.00, 300.00, '2025-09-29 13:14:49'),
(2, 4, 1, 250.00, 0.00, 250.00, '2025-09-29 13:18:02'),
(3, 5, 1, 300.00, 0.00, 300.00, '2025-09-29 13:18:15'),
(4, 6, 1, 1800.00, 0.00, 1800.00, '2025-09-29 13:18:44'),
(5, 7, 1, 120.00, 0.00, 120.00, '2025-09-29 13:20:15'),
(6, 8, 1, 500.00, 0.00, 500.00, '2025-09-29 13:21:01'),
(7, 9, 1, 250.00, 0.00, 250.00, '2025-09-29 13:23:33'),
(8, 10, 1, 1800.00, 54.00, 1746.00, '2025-09-29 13:34:58'),
(9, 11, 1, 450.00, 13.50, 436.50, '2025-09-29 13:35:17'),
(10, 12, 1, 250.00, 0.00, 250.00, '2025-09-29 13:35:27'),
(11, 13, 1, 120.00, 3.60, 116.40, '2025-09-29 13:36:16'),
(12, 14, 1, 250.00, 0.00, 250.00, '2025-09-29 13:36:50'),
(13, 15, 1, 2250.00, 67.50, 2182.50, '2025-09-29 13:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `base_price`, `created_at`) VALUES
(1, 'Oil Change', 'Standard motorcycle oil change (1L).', 1000.00, '2025-09-27 04:23:31'),
(2, 'Brake Check', 'Brake pad replacement and system check.', 1500.00, '2025-09-27 04:23:31'),
(3, 'Full Service', 'Complete tune-up and inspection.', 3000.00, '2025-09-27 04:23:31');

-- --------------------------------------------------------

--
-- Table structure for table `stock_logs`
--

CREATE TABLE `stock_logs` (
  `id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `type` enum('IN','OUT') NOT NULL,
  `quantity` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_member` tinyint(1) DEFAULT 0,
  `member_until` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_initial`, `last_name`, `suffix`, `name`, `email`, `phone`, `address`, `password`, `is_member`, `member_until`, `created_at`) VALUES
(1, NULL, NULL, NULL, NULL, 'John Doe', 'john@example.com', '09171234567', NULL, '*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9', 1, '2025-12-31', '2025-09-27 04:23:31'),
(2, NULL, NULL, NULL, NULL, 'Jane Smith', 'jane@example.com', '09179876543', NULL, '*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9', 0, NULL, '2025-09-27 04:23:31'),
(3, NULL, NULL, NULL, NULL, 'Mark Rider', 'mark@example.com', '09174561234', NULL, '*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9', 1, '2025-06-30', '2025-09-27 04:23:31'),
(5, 'juditho', 'A', 'vybdua', 'jr', 'juditho A vybdua jr', 'user1@g.com', '09123456789', 'lowerlodiong, tambulig, zds', '$2y$10$wUQetwuNoB8Vi/pVf6J7deifW3W7a/shyZ.6xPf96nykBTwB1DodG', 0, NULL, '2025-11-08 08:03:37'),
(6, 'test', 'a', 'add', 'hr', 'test a add hr', 'test@g.com', '2312', 'adwda', '$2y$10$6G/UMtWBdDrYPuCEtyNpA./UZ60vPZQnO3ICWUU/P2PUUS6B.0/ZC', 1, '2026-05-16', '2025-11-09 13:54:15'),
(11, 'jude', 'a', 'test', '', 'jude a test', 'jude@g.com', '21312', 'complete', 'jude', 1, '2026-05-17', '2025-11-17 01:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `mechanic_id` (`mechanic_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `mechanics`
--
ALTER TABLE `mechanics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `membership_id` (`membership_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `fk_parts_category` (`category_id`);

--
-- Indexes for table `reset_tokens`
--
ALTER TABLE `reset_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_id` (`part_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `model_id` (`model_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `mechanics`
--
ALTER TABLE `mechanics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `models`
--
ALTER TABLE `models`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reset_tokens`
--
ALTER TABLE `reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_logs`
--
ALTER TABLE `stock_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `parts` (`id`);

--
-- Constraints for table `memberships`
--
ALTER TABLE `memberships`
  ADD CONSTRAINT `memberships_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parts`
--
ALTER TABLE `parts`
  ADD CONSTRAINT `fk_parts_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD CONSTRAINT `stock_logs_ibfk_1` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicles_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicles_ibfk_3` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
