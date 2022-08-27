-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2022 at 06:52 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a_gaming_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_sidebar`
--

CREATE TABLE `tbl_admin_sidebar` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `url` varchar(2000) NOT NULL,
  `sequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_admin_sidebar`
--

INSERT INTO `tbl_admin_sidebar` (`id`, `name`, `url`, `sequence`) VALUES
(1, 'Dashboard', 'Home', 1),
(2, 'Team', 'System/view_team', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_sidebar2`
--

CREATE TABLE `tbl_admin_sidebar2` (
  `id` int(11) NOT NULL,
  `main_id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `url` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_admin_sidebar2`
--

INSERT INTO `tbl_admin_sidebar2` (`id`, `main_id`, `name`, `url`) VALUES
(1, 2, 'View Team', 'System/view_team'),
(2, 2, 'Add Team', 'System/add_team');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_game_cases`
--

CREATE TABLE `tbl_game_cases` (
  `id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `round_id` int(11) DEFAULT NULL,
  `step_id` int(11) DEFAULT NULL,
  `action` int(11) DEFAULT NULL COMMENT '0 for out of money, 1 for yes, 2 for no',
  `salary` int(11) DEFAULT NULL,
  `cash_in_hand` int(11) DEFAULT NULL,
  `expenditure` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_game_cases`
--

INSERT INTO `tbl_game_cases` (`id`, `case_id`, `round_id`, `step_id`, `action`, `salary`, `cash_in_hand`, `expenditure`) VALUES
(1, NULL, 1, 2, 0, 50000, 10000, 40000),
(2, 1, 1, 3, 1, 60000, 15000, 45000),
(3, 1, 1, 3, 2, 50000, 10000, 40000),
(4, 1, 1, 4, 1, 58000, 3000, 55000),
(5, 1, 1, 4, 2, 50000, 10000, 40000),
(6, 2, 1, 4, 1, 68000, 8000, 60000),
(7, 2, 1, 4, 2, 60000, 15000, 45000),
(8, 3, 1, 4, 1, 58000, 3000, 55000),
(9, 3, 1, 4, 2, 50000, 10000, 40000),
(10, 1, 1, 5, 1, 50000, 0, 50000),
(11, 2, 1, 5, 1, 60000, 5000, 55000),
(12, 3, 1, 5, 1, 50000, 0, 50000),
(13, 4, 1, 5, 0, 58000, -7000, 65000),
(14, 5, 1, 5, 1, 50000, 0, 50000),
(15, 6, 1, 5, 0, 68000, -2000, 70000),
(16, 7, 1, 5, 1, 60000, 5000, 55000),
(17, 8, 1, 5, 0, 58000, -7000, 65000),
(18, 9, 1, 5, 1, 50000, 0, 50000),
(19, 1, 1, 6, 1, 50000, 0, 50000),
(20, 1, 1, 6, 2, 50000, 10000, 40000),
(21, 2, 1, 6, 1, 60000, 5000, 55000),
(22, 2, 1, 6, 2, 60000, 15000, 45000),
(23, 3, 1, 6, 1, 50000, 0, 50000),
(24, 3, 1, 6, 2, 50000, 10000, 40000),
(25, 4, 1, 6, 0, 58000, -7000, 65000),
(26, 5, 1, 6, 1, 50000, 0, 50000),
(27, 5, 1, 6, 2, 50000, 10000, 40000),
(28, 6, 1, 6, 0, 68000, -2000, 70000),
(29, 7, 1, 6, 1, 60000, 5000, 55000),
(30, 7, 1, 6, 2, 60000, 15000, 45000),
(31, 8, 1, 6, 0, 58000, -7000, 65000),
(32, 9, 1, 6, 1, 50000, 0, 50000),
(33, 9, 1, 6, 2, 50000, 10000, 40000),
(34, 10, 1, 6, 0, 50000, -10000, 60000),
(35, 11, 1, 6, 0, 60000, -5000, 65000),
(36, 12, 1, 6, 0, 50000, -10000, 60000),
(37, 13, 1, 6, 0, 58000, -17000, 75000),
(38, 14, 1, 6, 0, 50000, -10000, 60000),
(39, 15, 1, 6, 0, 68000, -12000, 80000),
(40, 16, 1, 6, 0, 60000, -5000, 65000),
(41, 17, 1, 6, 0, 58000, -17000, 75000),
(42, 18, 1, 6, 0, 50000, -10000, 60000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_team`
--

CREATE TABLE `tbl_team` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(500) NOT NULL,
  `password` varchar(2000) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `address` varchar(2000) DEFAULT NULL,
  `image` varchar(1000) DEFAULT NULL,
  `power` int(11) NOT NULL,
  `services` varchar(1000) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `added_by` int(11) NOT NULL,
  `is_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_team`
--

INSERT INTO `tbl_team` (`id`, `name`, `email`, `password`, `phone`, `address`, `image`, `power`, `services`, `ip`, `date`, `added_by`, `is_active`) VALUES
(1, 'Anay Pareek', 'anaypareek@rocketmail.com', '9ffd3dfaf18c6c0dededaba5d7db9375', '9799655891', '19 kalyanpuri new sanganer road sodala', '', 1, '[\"999\"]', '1000000', '16-05-2018', 1, 1),
(19, 'Demo', 'demo@gmail.com', 'f702c1502be8e55f4208d69419f50d0a', '9999999999', 'jaipur', NULL, 1, '[\"999\"]', '::1', '2020-01-04 18:12:55', 1, 1),
(29, 'Animesh Sharma', 'animesh.skyline@gmail.com', '8bda6fe26dad2b31f9cb9180ec3823e8', '8441849182', 'pratap nagar sitapura jaipur', '', 2, '[\"999\"]', '::1', '2020-01-06 14:47:11', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin_sidebar`
--
ALTER TABLE `tbl_admin_sidebar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admin_sidebar2`
--
ALTER TABLE `tbl_admin_sidebar2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_game_cases`
--
ALTER TABLE `tbl_game_cases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_team`
--
ALTER TABLE `tbl_team`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin_sidebar`
--
ALTER TABLE `tbl_admin_sidebar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tbl_admin_sidebar2`
--
ALTER TABLE `tbl_admin_sidebar2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_game_cases`
--
ALTER TABLE `tbl_game_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tbl_team`
--
ALTER TABLE `tbl_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
