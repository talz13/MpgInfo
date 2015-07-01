-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2015 at 04:48 PM
-- Server version: 5.6.24
-- PHP Version: 5.5.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mpginfo`
--
CREATE DATABASE IF NOT EXISTS `mpginfo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `mpginfo`;

-- --------------------------------------------------------

--
-- Table structure for table `mileage`
--

CREATE TABLE IF NOT EXISTS `mileage` (
  `rowid` int(11) NOT NULL,
  `date` date NOT NULL,
  `miles` double NOT NULL,
  `gallons` double NOT NULL,
  `priceGallon` double NOT NULL,
  `vehicle` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `insert_ds` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_upd_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=220 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `servicelogs`
--

CREATE TABLE IF NOT EXISTS `servicelogs` (
  `placeholder` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(40) NOT NULL,
  `lastLoginDT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `joinDT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session` char(40) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE IF NOT EXISTS `vehicles` (
  `vehicle_index` int(11) NOT NULL,
  `model_year` year(4) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(100) NOT NULL,
  `trim` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `date_purchased` date NOT NULL,
  `date_sold` date DEFAULT NULL,
  `mileage_current` double NOT NULL,
  `mileage_purchased` double NOT NULL,
  `mileage_sold` double DEFAULT NULL,
  `price_purchased` decimal(10,0) NOT NULL,
  `price_sold` decimal(10,0) DEFAULT NULL,
  `updateDS` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `createDS` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `default_vehicle` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mileage`
--
ALTER TABLE `mileage`
  ADD PRIMARY KEY (`rowid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`), ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_index`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mileage`
--
ALTER TABLE `mileage`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=220;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
