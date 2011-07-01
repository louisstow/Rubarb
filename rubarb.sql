-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 17, 2011 at 06:00 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rubarb`
--

-- --------------------------------------------------------

--
-- Table structure for table `aliens`
--

CREATE TABLE IF NOT EXISTS `aliens` (
  `alienID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alienAlias` varchar(50) NOT NULL,
  `playerID` int(10) unsigned NOT NULL,
  `species` tinyint(3) unsigned NOT NULL,
  `attack` smallint(5) unsigned NOT NULL,
  `defense` smallint(5) unsigned NOT NULL,
  `speed` smallint(5) unsigned NOT NULL,
  `exp` smallint(5) unsigned DEFAULT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `hunger` tinyint(3) unsigned NOT NULL,
  `thirst` tinyint(3) unsigned NOT NULL,
  `hp` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`alienID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `aliens`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle`
--

CREATE TABLE IF NOT EXISTS `battle` (
  `battleID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('pvp','team','test') NOT NULL,
  `ownerID` int(10) unsigned NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime DEFAULT NULL,
  `environment` enum('fire','rock','lava','jungle','ice','water','gas') NOT NULL,
  PRIMARY KEY (`battleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `battle`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle_pvp`
--

CREATE TABLE IF NOT EXISTS `battle_pvp` (
  `battleID` int(10) unsigned NOT NULL,
  `playerID` int(10) unsigned NOT NULL,
  `opponentID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`battleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `battle_pvp`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle_team-a`
--

CREATE TABLE IF NOT EXISTS `battle_team-a` (
  `battleID` int(10) unsigned NOT NULL,
  `playerID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`battleID`,`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `battle_team-a`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle_team-b`
--

CREATE TABLE IF NOT EXISTS `battle_team-b` (
  `battleID` int(10) unsigned NOT NULL,
  `playerID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`battleID`,`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `battle_team-b`
--


-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `playerID` int(10) unsigned NOT NULL,
  `friendID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`playerID`,`friendID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friends`
--


-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `playerID` int(10) unsigned NOT NULL,
  `itemID` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`playerID`,`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--


-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `itemID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `itemName` varchar(50) NOT NULL,
  `itemDescr` varchar(255) NOT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `items`
--


-- --------------------------------------------------------

--
-- Table structure for table `moves`
--

CREATE TABLE IF NOT EXISTS `moves` (
  `speciesID` int(10) unsigned NOT NULL,
  `moveName` varchar(50) NOT NULL,
  `moveDamage` smallint(5) unsigned NOT NULL,
  `moveEnergy` smallint(5) unsigned NOT NULL,
  `levelAquired` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`speciesID`,`moveName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `moves`
--


-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `playerID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `screenName` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `playerPass` varchar(100) NOT NULL,
  `status` enum('online','offline','inbattle') NOT NULL,
  PRIMARY KEY (`playerID`),
  UNIQUE KEY `screenName` (`screenName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `players`
--


-- --------------------------------------------------------

--
-- Table structure for table `species`
--

CREATE TABLE IF NOT EXISTS `species` (
  `speciesID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `speciesName` varchar(50) DEFAULT NULL,
  `world` enum('fire','rock','lava','jungle','ice','water','gas') NOT NULL,
  `attack` tinyint(3) unsigned NOT NULL,
  `defense` tinyint(3) unsigned NOT NULL,
  `speed` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`speciesID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `species`
--

