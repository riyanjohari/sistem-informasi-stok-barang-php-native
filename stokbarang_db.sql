-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2025 at 01:47 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stokbarang_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `goods`
--

CREATE TABLE `goods` (
  `id_goods` int NOT NULL,
  `goods_name` varchar(70) NOT NULL,
  `description` varchar(100) NOT NULL,
  `stock` int NOT NULL,
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `goods`
--

INSERT INTO `goods` (`id_goods`, `goods_name`, `description`, `stock`, `image`) VALUES
(7, 'SONY', 'SONY EXPERIA VII', 15, 'c21e2e0bc0d5868055b083ed34c1d4d6.jpeg'),
(8, 'TOSHIBA', 'LAPTOP TOSHIBA ', 20, '32e37bbc13755a77622f6bebbdaa480f.jpeg'),
(9, 'FUJITSU', 'FUJIFILM X-T4 XT4 KIT 18-55MM KAMERA MIRRORLESS', 20, '663796e103efa33a4c88ec8130c74cb2.jpeg'),
(13, 'MOTOROLA', 'MOTOROLA G45 5G', 10, '5de300889568cfa6f7a2eb2f1662bfdf.jpeg'),
(16, 'KOMIK', 'JUJUTSU KAISEN', 10, 'fda184b141c1046a890e459ed8ce326e.jpeg'),
(20, 'BUMI DATAR', 'BUKU KONSPIRASI BUMI DATAR', 15, '5b8b4d9109a9a8e9822526fbb45e5d8b.jpg'),
(21, 'FUJITSU', 'HAPE FUJITSU', 10, '7dc8ec144b34b711cc4ab6dd7fdb930e.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `goods_enter`
--

CREATE TABLE `goods_enter` (
  `id_enter` int NOT NULL,
  `goods_id` int NOT NULL,
  `time_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accepted` varchar(70) NOT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `goods_enter`
--

INSERT INTO `goods_enter` (`id_enter`, `goods_id`, `time_in`, `accepted`, `qty`) VALUES
(9, 7, '2025-02-14 15:29:43', 'osaragi', 10),
(10, 7, '2025-02-14 15:31:57', 'aoi', 5),
(11, 11, '2025-02-14 15:37:19', 'kagami', 3),
(22, 8, '2025-02-21 08:20:45', 'penerima 1', 25),
(23, 13, '2025-02-21 09:25:33', 'midorima', 15),
(24, 6, '2025-02-23 09:07:07', 'test', 50),
(25, 6, '2025-02-23 12:31:18', 'gedo', 20),
(26, 6, '2025-02-23 13:39:44', 'sayu', 7),
(27, 9, '2025-02-23 13:40:41', 'hana', 6),
(28, 7, '2025-02-23 13:42:35', 'shin', 3),
(29, 9, '2025-03-02 08:39:16', 'test 1', 10),
(30, 22, '2025-03-03 04:24:43', 'midorin', 5);

-- --------------------------------------------------------

--
-- Table structure for table `goods_out`
--

CREATE TABLE `goods_out` (
  `id_out` int NOT NULL,
  `goods_id` int NOT NULL,
  `out_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receiver` varchar(70) NOT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `goods_out`
--

INSERT INTO `goods_out` (`id_out`, `goods_id`, `out_time`, `receiver`, `qty`) VALUES
(2, 6, '2025-02-14 15:11:31', 'gojo', 5),
(3, 8, '2025-02-14 15:19:54', 'nagumo', 5),
(4, 6, '2025-02-14 15:21:03', 'sakamoto taro', 3),
(8, 8, '2025-02-21 08:20:02', 'coba', 20),
(10, 13, '2025-02-21 09:24:36', 'john wick', 10),
(11, 6, '2025-02-23 09:07:22', 'test', 20),
(12, 13, '2025-02-23 12:44:12', 'kise', 5),
(13, 6, '2025-02-23 12:46:51', 'aomine', 10),
(14, 7, '2025-02-23 12:47:14', 'akashi', 3),
(15, 8, '2025-02-23 12:56:56', 'teppei', 5),
(16, 9, '2025-03-02 08:39:35', 'test 1', 5),
(17, 9, '2025-03-02 08:39:50', 'test 2', 5),
(18, 22, '2025-03-03 04:25:57', 'aomine', 10),
(19, 7, '2025-03-03 04:32:09', 'gojo', 3),
(20, 9, '2025-03-03 04:33:12', 'juju', 1),
(21, 20, '2025-03-03 04:35:17', 'bubu', 500),
(22, 20, '2025-03-03 04:36:51', 'terui', 200),
(23, 20, '2025-03-03 04:38:10', 'yuta', 200);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `name` varchar(70) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `name`, `email`, `password`) VALUES
(4, 'マリン', 'admin@admin.com', '$2y$10$rMjHwyW1IVqk7PKuGmoXpuonid/iFiQ5GYd3BUXhE88xC4R1JRzTS'),
(5, '猫猫', 'mao@mao.com', '$2y$10$XyI07sM28bQz9bei3qWDxeZ7zciK7TpCTweH54.PdYmjUBVSQbuxy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id_goods`);

--
-- Indexes for table `goods_enter`
--
ALTER TABLE `goods_enter`
  ADD PRIMARY KEY (`id_enter`);

--
-- Indexes for table `goods_out`
--
ALTER TABLE `goods_out`
  ADD PRIMARY KEY (`id_out`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `goods`
--
ALTER TABLE `goods`
  MODIFY `id_goods` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `goods_enter`
--
ALTER TABLE `goods_enter`
  MODIFY `id_enter` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `goods_out`
--
ALTER TABLE `goods_out`
  MODIFY `id_out` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
