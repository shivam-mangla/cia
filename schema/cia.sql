-- phpMyAdmin SQL Dump
-- version 4.2.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 06, 2015 at 11:55 PM
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
-- Table structure for table `police_members`
--

CREATE TABLE IF NOT EXISTS `police_members` (
`id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `passwd` varchar(64) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` enum('p_commissioner','p_officer') NOT NULL,
  `created_at` int(10) NOT NULL,
  `updated_at` int(10) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `police_members`
--

INSERT INTO `police_members` (`id`, `username`, `passwd`, `first_name`, `last_name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'kandoiabhi', 'blahblah', 'Abhishek', 'Kandoi', 'p_commissioner', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `police_stations`
--

CREATE TABLE IF NOT EXISTS `police_stations` (
`id` int(10) NOT NULL,
  `stationname` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `type` enum('district_level','city_level') NOT NULL,
  `created_at` int(10) NOT NULL,
  `updated_at` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `police_station_and_member_map`
--

CREATE TABLE IF NOT EXISTS `police_station_and_member_map` (
`id` int(10) NOT NULL,
  `station_id` int(10) NOT NULL,
  `member_id` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `updated_at` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `police_members`
--
ALTER TABLE `police_members`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `police_stations`
--
ALTER TABLE `police_stations`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `police_station_and_member_map`
--
ALTER TABLE `police_station_and_member_map`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `police_members`
--
ALTER TABLE `police_members`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `police_stations`
--
ALTER TABLE `police_stations`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `police_station_and_member_map`
--
ALTER TABLE `police_station_and_member_map`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
