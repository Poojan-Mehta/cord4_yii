-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 13, 2024 at 03:26 PM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cord4_yii`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(55) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 1, '2024-11-13 11:52:25', '2024-11-13 14:57:02'),
(2, 'Furniture', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Health', 1, '0000-00-00 00:00:00', '2024-11-13 14:45:10'),
(4, 'Fashions', 1, '2024-11-13 15:11:59', '2024-11-13 15:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `product_name` varchar(55) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `product_name`, `price`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'IPhone', '50000', 1, '2024-11-13 12:47:38', '2024-11-13 12:47:38'),
(3, 2, 'Sofa', '25000', 1, '2024-11-13 14:48:04', '2024-11-13 14:48:04'),
(4, 3, 'Medicine', '25', 1, '2024-11-13 14:53:21', '2024-11-13 14:53:21');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `auth_key` varchar(55) DEFAULT NULL,
  `user_name` varchar(55) NOT NULL,
  `user_email` varchar(55) NOT NULL,
  `user_mobile_no` varchar(15) NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_type` tinyint(1) NOT NULL COMMENT '1=admin, 2=user, 3=employee',
  `user_status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active, 2=block',
  `otp` varchar(4) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `auth_key`, `user_name`, `user_email`, `user_mobile_no`, `user_password`, `user_type`, `user_status`, `otp`, `created_at`, `updated_at`) VALUES
(4, NULL, 'poojan', 'poojanmehta008@gmail.com', '8866319747', '$2y$10$VQLVRZ2R57r.F3yvcvChSuTuhEVlLObro4axslbUueJdNfiDvaLV6', 3, 1, NULL, '2024-11-13 11:20:27', '2024-11-13 11:26:54'),
(5, NULL, 'admin', 'admin@cord4.com', '1234567890', '$2y$10$lTaFCEbLggfmT7wAAInkx.wELo88REz8E1wZYG0OtoyTDa6UXvLo6', 1, 1, NULL, '2024-11-13 11:24:55', '2024-11-13 11:24:55'),
(6, NULL, 'John', 'john@gmail.com', '2314585258', '$2y$10$yMAr8KjcUXo5dgFKvk9AEuATtbwR1owZ2ygeWezYUGjqq/j/Oa9Hm', 3, 1, NULL, '2024-11-13 11:33:19', '2024-11-13 15:14:44'),
(7, NULL, 'Shane Watson', 'watson@gmail.com', '2222222222', '$2y$10$O63w8QAHqnLnomLlnWtGZeSFkAevqxv/h99KqGIh97uPyBlH0YfEK', 3, 1, NULL, '2024-11-13 14:05:18', '2024-11-13 14:06:25'),
(8, NULL, 'carlos braithwait', 'carlos@gmail.com', '6545784578', '$2y$10$nm.HIKZbBmVDdjBg.xsTq.LxwWw7lPW3D8pm6IpDFnAqsTv20ahvW', 3, 1, NULL, '2024-11-13 15:14:56', '2024-11-13 15:15:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
