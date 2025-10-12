-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2025 at 01:52 PM
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
-- Database: `todo_list`
--

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'Raju05', '$2y$10$Z.ZTyFt6A3K.dr4Q4JPkt.SbWO0Bzk2FH2jk/oGr0TqctGLot./v2', '2025-10-12 15:12:02'),
(2, 'Raju06', '$2y$10$0JiL/29TVw6mGycH4x//WO3qozkLZUj6w2YxIXzgmwUjAXFD9ALzi', '2025-10-12 15:23:05'),
(3, 'Raju07', '$2y$10$eWmNwLURAPh3OSwcHa7gLeTYpgCPplwqSbRX7sY8/4/ZzGYBBZCES', '2025-10-12 15:28:49'),
(4, 'Raju08', '$2y$10$nvSf.y/pEjLrFNdnbv.EB.K82S61OUdk3ELaxVA2Dp8zrDs5WCpNy', '2025-10-12 15:29:05');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `due_datetime` datetime DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `task`, `due_datetime`, `is_completed`, `created_at`) VALUES
(0, 1, 'Back-end Creation', '2025-10-12 16:48:00', 0, '2025-10-12 11:18:23'),
(0, 1, 'sdfvg', '2025-10-12 16:48:00', 0, '2025-10-12 11:18:33'),
(0, 1, 'sdfrdfc', '2025-10-12 16:48:00', 0, '2025-10-12 11:18:39'),
(0, 1, 'sdfggtvfcdxszzssdwfgvfcdxszaxsdfvgggebrfscx', '2025-10-12 16:48:00', 0, '2025-10-12 11:18:49'),
(0, 1, 'adcsfvgvsda', '2025-10-12 16:48:00', 0, '2025-10-12 11:18:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
