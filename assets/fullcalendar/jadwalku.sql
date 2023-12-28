-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2014 at 02:06 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tesdata`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwalku`
--

CREATE TABLE IF NOT EXISTS `jadwalku` (
  `jadwal_id` int(10) NOT NULL AUTO_INCREMENT,
  `tgl1` datetime NOT NULL,
  `tgl2` datetime NOT NULL,
  `judul` text NOT NULL,
  PRIMARY KEY (`jadwal_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `jadwalku`
--

INSERT INTO `jadwalku` (`jadwal_id`, `tgl1`, `tgl2`, `judul`) VALUES
(1, '2014-11-08 09:00:00', '2014-11-08 11:00:00', 'Bersihkan kamar'),
(2, '2014-11-08 13:00:00', '2014-11-08 13:30:00', 'Makan Siang'),
(3, '2014-11-08 15:00:00', '2014-11-08 17:00:00', 'Jalan Bareng Pacar'),
(4, '2014-11-19 09:00:00', '2014-11-19 11:00:00', 'Lomba Makan'),
(5, '2014-11-12 10:00:00', '2014-11-18 10:00:00', 'Touring Sumbar');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
