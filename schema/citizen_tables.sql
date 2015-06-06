-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2015 at 02:48 AM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

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
-- Table structure for table `cir`
--

CREATE TABLE IF NOT EXISTS `cir` (
  `cir_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_id` int(11) DEFAULT NULL,
  `num_crimes` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cir_id`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `citizens`
--

CREATE TABLE IF NOT EXISTS `citizens` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `passwd` varchar(64) DEFAULT NULL,
  `role` char(2) DEFAULT NULL,
  `org_name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `citizens`
--

INSERT INTO `citizens` (`c_id`, `username`, `passwd`, `role`, `org_name`, `created_at`, `updated_at`) VALUES
(1, 'mangla', 'test', 'EE', 'test', '2015-06-06 21:18:43', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `forwarded_cir`
--

CREATE TABLE IF NOT EXISTS `forwarded_cir` (
  `cf_id` int(11) NOT NULL AUTO_INCREMENT,
  `cir_id` int(11) DEFAULT NULL,
  `status` char(2) DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rcvd_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `seen_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cf_id`),
  KEY `cir_id` (`cir_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Crime Investigation Reports forwarded by a person (employee) to another (employer)' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `map_cir_to_forwarded_cir`
--

CREATE TABLE IF NOT EXISTS `map_cir_to_forwarded_cir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cf_id` int(11) DEFAULT NULL,
  `c_id_rcvr` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `c_id_rcvr` (`c_id_rcvr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cir`
--
ALTER TABLE `cir`
  ADD CONSTRAINT `cir_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `citizens` (`c_id`);

--
-- Constraints for table `forwarded_cir`
--
ALTER TABLE `forwarded_cir`
  ADD CONSTRAINT `forwarded_cir_ibfk_1` FOREIGN KEY (`cir_id`) REFERENCES `cir` (`cir_id`);

--
-- Constraints for table `map_cir_to_forwarded_cir`
--
ALTER TABLE `map_cir_to_forwarded_cir`
  ADD CONSTRAINT `map_cir_to_forwarded_cir_ibfk_1` FOREIGN KEY (`c_id_rcvr`) REFERENCES `citizens` (`c_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
