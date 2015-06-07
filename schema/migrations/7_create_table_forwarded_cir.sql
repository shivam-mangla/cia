-- phpMyAdmin SQL Dump
-- version 4.2.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2015 at 11:51 AM
-- Server version: 5.5.41-0ubuntu0.12.04.1
-- PHP Version: 5.5.23-1+deb.sury.org~precise+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cia`
--

-- --------------------------------------------------------

--
-- Table structure for table `forwarded_cir`
--

CREATE TABLE IF NOT EXISTS `forwarded_cir` (
`id` int(10) NOT NULL,
  `report_id` int(10) NOT NULL,
  `status` enum('in_review','accepted','rejected') NOT NULL,
  `receiver_id` int(10) NOT NULL,
  `seen_at` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `updated_at` int(10) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `forwarded_cir`
--

INSERT INTO `forwarded_cir` (`id`, `report_id`, `status`, `receiver_id`, `seen_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'in_review', 2, 17, 16, 18);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forwarded_cir`
--
ALTER TABLE `forwarded_cir`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forwarded_cir`
--
ALTER TABLE `forwarded_cir`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
