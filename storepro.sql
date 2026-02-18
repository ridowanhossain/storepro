-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 17, 2026 at 11:48 AM
-- Server version: 11.4.9-MariaDB-deb12
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ridowanhossain_storepro`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `brand_active` int(11) NOT NULL DEFAULT 0,
  `brand_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `brand_active`, `brand_status`) VALUES
(1, 'BADC', 1, 1),
(2, 'China', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `categories_name` varchar(255) NOT NULL,
  `categories_active` int(11) NOT NULL DEFAULT 0,
  `categories_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `categories_name`, `categories_active`, `categories_status`) VALUES
(1, 'সার', 1, 1),
(2, 'কীটনাশক', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `c_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL,
  `activity` smallint(3) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `client_name` varchar(255) NOT NULL,
  `client_contact` varchar(255) NOT NULL,
  `sub_total` varchar(255) NOT NULL,
  `vat` varchar(255) NOT NULL,
  `total_amount` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `grand_total` varchar(255) NOT NULL,
  `paid` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `order_status` int(11) NOT NULL DEFAULT 0,
  `s_name` varchar(250) NOT NULL,
  `address` varchar(500) NOT NULL,
  `sr_id` int(11) NOT NULL,
  `o_feature` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `client_name`, `client_contact`, `sub_total`, `vat`, `total_amount`, `discount`, `grand_total`, `paid`, `due`, `payment_type`, `payment_status`, `order_status`, `s_name`, `address`, `sr_id`, `o_feature`) VALUES
(1, '2026-02-16 17:12:05', 'রিদোয়ান', '০১৭১৪৯৯৯২০২', '13500.00', '0.00', '13500.00', '0', '13500.00', '13000', '500.00', 2, 3, 1, '87658', ' কালাই', 1, 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `order_item_status` int(11) NOT NULL DEFAULT 0,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_status` int(11) NOT NULL,
  `brate` varchar(250) NOT NULL,
  `sr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_id`, `product_id`, `quantity`, `rate`, `total`, `order_item_status`, `order_date`, `payment_status`, `brate`, `sr_id`) VALUES
(1, 1, 1, '5', '1350', '6750.00', 1, '2026-02-16 17:12:05', 3, '6250', 1),
(2, 1, 1, '5', '1350', '6750.00', 1, '2026-02-16 17:12:05', 3, '6250', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_returns`
--

CREATE TABLE `order_returns` (
  `return_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `return_quantity` decimal(10,2) NOT NULL,
  `return_amount` decimal(10,2) NOT NULL,
  `return_date` datetime NOT NULL DEFAULT current_timestamp(),
  `return_reason` text DEFAULT NULL,
  `processed_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_returns`
--

INSERT INTO `order_returns` (`return_id`, `order_id`, `order_item_id`, `product_id`, `return_quantity`, `return_amount`, `return_date`, `return_reason`, `processed_by`) VALUES
(1, 1, 1, 1, 1.00, 1350.00, '2026-02-17 17:22:40', NULL, '87658');

-- --------------------------------------------------------

--
-- Table structure for table `pement_details`
--

CREATE TABLE `pement_details` (
  `pement_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `pement` int(250) NOT NULL,
  `s_name` varchar(250) NOT NULL,
  `sr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pement_details`
--

INSERT INTO `pement_details` (`pement_id`, `order_id`, `date`, `pement`, `s_name`, `sr_id`) VALUES
(1, 1, '2026-02-16 17:12:05', 13000, '87658', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro`
--

CREATE TABLE `pro` (
  `pro_id` int(11) NOT NULL,
  `pro_name` varchar(250) NOT NULL,
  `brand_name` varchar(250) NOT NULL,
  `cat_name` varchar(250) NOT NULL,
  `qty` varchar(250) NOT NULL,
  `pdate` datetime NOT NULL DEFAULT current_timestamp(),
  `brate` varchar(250) NOT NULL,
  `clor` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pro`
--

INSERT INTO `pro` (`pro_id`, `pro_name`, `brand_name`, `cat_name`, `qty`, `pdate`, `brate`, `clor`) VALUES
(1, 'ইউরিয়া', '1', '1', '100', '2026-02-16 00:00:00', '1250', 'ব্যাগ'),
(2, 'TSP', '1', '1', '100', '2026-02-16 00:00:00', '1350', 'ব্যাগ');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` mediumtext NOT NULL,
  `brand_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `brate` varchar(250) NOT NULL,
  `pdate` datetime NOT NULL DEFAULT current_timestamp(),
  `clor` varchar(250) NOT NULL,
  `features` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_image`, `brand_id`, `categories_id`, `quantity`, `rate`, `active`, `status`, `brate`, `pdate`, `clor`, `features`) VALUES
(1, 'ইউরিয়া', '../assests/images/product.jpg', 1, 1, '57', '1350', 1, 1, '1250', '2026-02-16 00:00:00', 'ব্যাগ', ''),
(2, 'TSP', '../assests/images/product.jpg', 1, 1, '91', '1550', 1, 1, '1350', '2026-02-16 00:00:00', 'ব্যাগ', '');

-- --------------------------------------------------------

--
-- Table structure for table `shop_settings`
--

CREATE TABLE `shop_settings` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `allproduct_name` text DEFAULT NULL,
  `shop_address` text DEFAULT NULL,
  `shop_mobile` varchar(100) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `email_addr` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_settings`
--

INSERT INTO `shop_settings` (`id`, `shop_name`, `owner_name`, `allproduct_name`, `shop_address`, `shop_mobile`, `company_name`, `contact_no`, `email_addr`, `created_at`, `updated_at`) VALUES
(1, 'মেসার্স মকতুলুর রহমান ট্রেডার্স', 'প্রোঃ মোছাঃ জাকিয়া ইয়াসমিন', 'এখানে সার, বীজ ও বালাইনাশক এবং ধান, আলু, সরিষা ও কৃষি পন্য পাইকারী ও খুচরা বিক্রয় করা হয়।', 'বড়ইতলী বাজার, আমদই, জয়পুরহাট', '০১৭১৮৬৪৬৩৩৩(মালিক)<br>০১৭৩৮৪১১৬৭৫(ম্যানেজার)', 'MS MOKTULUR RAHMAN TRADERS', '01718646333', 'moktulur.traders@gmail.com', '2025-06-01 12:59:30', '2025-07-05 09:52:06');

-- --------------------------------------------------------

--
-- Table structure for table `spend`
--

CREATE TABLE `spend` (
  `id` int(11) NOT NULL,
  `spend_date` datetime NOT NULL DEFAULT current_timestamp(),
  `c_name` varchar(255) NOT NULL,
  `total` int(255) NOT NULL,
  `paid` int(255) NOT NULL,
  `due` int(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spend_report`
--

CREATE TABLE `spend_report` (
  `id` int(11) NOT NULL,
  `paid_date` datetime NOT NULL DEFAULT current_timestamp(),
  `spend_id` int(11) NOT NULL,
  `paid` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sr`
--

CREATE TABLE `sr` (
  `sr_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `nmbr` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL,
  `b_status` varchar(250) NOT NULL,
  `c_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sr`
--

INSERT INTO `sr` (`sr_id`, `name`, `nmbr`, `address`, `b_status`, `c_date`) VALUES
(1, 'রিদোয়ান', '০১৭১৪৯৯৯২০২', ' কালাই', '1', '2026-02-01 00:00:00'),
(2, 'মকতুলুর', '০১৭১৪৯৯৯২০২', ' কালাই', '1', '2026-02-01 00:00:00'),
(3, 'তুষার', '01714999202', ' 10', '1', '2026-02-06 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `full_name`, `status`) VALUES
(87658, 'test', '413c1f0e77793572207582f92712fc9a', '', 'Admin', 5),
(99221, 'moktulur', '666318260dca1ff17712cf532dc7eadd', '', 'মকতুলুর রহমান', 5),
(99222, 'rakibs', 'd1163101fcd5b2110e90472a0649347a', '', 'রাকিব হোসেন', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `order_returns`
--
ALTER TABLE `order_returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `return_date` (`return_date`);

--
-- Indexes for table `pement_details`
--
ALTER TABLE `pement_details`
  ADD PRIMARY KEY (`pement_id`);

--
-- Indexes for table `pro`
--
ALTER TABLE `pro`
  ADD PRIMARY KEY (`pro_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `shop_settings`
--
ALTER TABLE `shop_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spend`
--
ALTER TABLE `spend`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sr`
--
ALTER TABLE `sr`
  ADD PRIMARY KEY (`sr_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_returns`
--
ALTER TABLE `order_returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pement_details`
--
ALTER TABLE `pement_details`
  MODIFY `pement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pro`
--
ALTER TABLE `pro`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shop_settings`
--
ALTER TABLE `shop_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sr`
--
ALTER TABLE `sr`
  MODIFY `sr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99225;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
