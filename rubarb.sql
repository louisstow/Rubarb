-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 01, 2011 at 04:04 AM
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
  `exp` smallint(5) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `hunger` tinyint(3) unsigned NOT NULL,
  `thirst` tinyint(3) unsigned NOT NULL,
  `hp` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `status` enum('stored','carried','limbo') NOT NULL,
  PRIMARY KEY (`alienID`),
  KEY `playerID` (`playerID`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `aliens`
--

INSERT INTO `aliens` (`alienID`, `alienAlias`, `playerID`, `species`, `attack`, `defense`, `speed`, `exp`, `level`, `hunger`, `thirst`, `hp`, `status`) VALUES
(1, 'Killer', 1, 3, 5, 5, 5, 5, 5, 9, 9, 120, 'carried'),
(2, 'Vyel', 1, 5, 8, 5, 8, 100, 1, 0, 0, 100, 'carried'),
(3, 'Vyel', 1, 5, 6, 5, 8, 100, 1, 0, 0, 100, 'carried'),
(4, 'John', 1, 5, 5, 8, 5, 100, 1, 0, 0, 100, 'carried');

-- --------------------------------------------------------

--
-- Table structure for table `banned`
--

CREATE TABLE IF NOT EXISTS `banned` (
  `playerID` int(10) unsigned NOT NULL,
  `until` date NOT NULL,
  PRIMARY KEY (`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banned`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle`
--

CREATE TABLE IF NOT EXISTS `battle` (
  `battleID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('pvp','team','test') NOT NULL,
  `ownerID` int(10) unsigned NOT NULL,
  `turn` int(11) unsigned NOT NULL,
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
  `playerAlien` int(10) unsigned NOT NULL,
  `playerActive` datetime NOT NULL,
  `opponentID` int(10) unsigned NOT NULL,
  `opponentAlien` int(10) unsigned NOT NULL,
  `opponentActive` datetime NOT NULL,
  PRIMARY KEY (`battleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Player vs Player. Inactivity of 2 mins, skip turn';

--
-- Dumping data for table `battle_pvp`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle_request`
--

CREATE TABLE IF NOT EXISTS `battle_request` (
  `battleID` int(10) unsigned NOT NULL,
  `playerID` int(10) unsigned NOT NULL,
  `requestDate` datetime NOT NULL,
  PRIMARY KEY (`battleID`,`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `battle_request`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle_snapshot`
--

CREATE TABLE IF NOT EXISTS `battle_snapshot` (
  `battleID` int(10) unsigned NOT NULL,
  `alienID` int(10) unsigned NOT NULL,
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
  PRIMARY KEY (`battleID`,`alienID`),
  KEY `battleID` (`battleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='For test matches. Details as of start of match';

--
-- Dumping data for table `battle_snapshot`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle_team`
--

CREATE TABLE IF NOT EXISTS `battle_team` (
  `battleID` int(10) unsigned NOT NULL,
  `playerID` int(10) unsigned NOT NULL,
  `team` enum('A','B') NOT NULL,
  `alienID` int(10) unsigned NOT NULL,
  `lastActive` datetime NOT NULL,
  PRIMARY KEY (`battleID`,`playerID`,`team`),
  KEY `battleID` (`battleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `battle_team`
--


-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `playerID` int(10) unsigned NOT NULL,
  `friendID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`playerID`,`friendID`),
  KEY `friendID` (`friendID`),
  KEY `playerID` (`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`playerID`, `friendID`) VALUES
(3, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `playerID` int(10) unsigned NOT NULL,
  `itemID` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`playerID`,`itemID`),
  KEY `playerID` (`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`playerID`, `itemID`, `quantity`) VALUES
(1, 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `itemID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `itemName` varchar(50) NOT NULL,
  `itemDescr` varchar(250) NOT NULL,
  `attack` tinyint(3) unsigned NOT NULL,
  `defense` tinyint(3) unsigned NOT NULL,
  `speed` tinyint(3) unsigned NOT NULL,
  `exp` tinyint(3) unsigned NOT NULL,
  `hunger` tinyint(3) unsigned NOT NULL,
  `thirst` tinyint(3) unsigned NOT NULL,
  `hp` tinyint(3) unsigned NOT NULL,
  `cost` int(5) unsigned NOT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `itemName`, `itemDescr`, `attack`, `defense`, `speed`, `exp`, `hunger`, `thirst`, `hp`, `cost`) VALUES
(1, 'Potion', 'Heals by 10 HP', 0, 0, 0, 0, 2, 2, 10, 25);

-- --------------------------------------------------------

--
-- Table structure for table `moves`
--

CREATE TABLE IF NOT EXISTS `moves` (
  `moveID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `moveName` varchar(50) NOT NULL,
  `attackSelf` tinyint(5) unsigned NOT NULL,
  `defenseSelf` tinyint(5) unsigned NOT NULL,
  `speedSelf` tinyint(5) unsigned NOT NULL,
  `expSelf` tinyint(5) unsigned NOT NULL,
  `hungerSelf` tinyint(3) unsigned NOT NULL,
  `thirstSelf` tinyint(3) unsigned NOT NULL,
  `hpSelf` tinyint(3) unsigned NOT NULL,
  `attackOpp` tinyint(5) unsigned NOT NULL,
  `defenseOpp` tinyint(5) unsigned NOT NULL,
  `speedOpp` tinyint(5) unsigned NOT NULL,
  `expOpp` tinyint(5) unsigned NOT NULL,
  `hungerOpp` tinyint(3) unsigned NOT NULL,
  `thirstOpp` tinyint(3) unsigned NOT NULL,
  `hpOpp` tinyint(3) unsigned NOT NULL,
  `levelAquired` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`moveID`),
  KEY `speciesID` (`moveID`,`levelAquired`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `moves`
--

INSERT INTO `moves` (`moveID`, `moveName`, `attackSelf`, `defenseSelf`, `speedSelf`, `expSelf`, `hungerSelf`, `thirstSelf`, `hpSelf`, `attackOpp`, `defenseOpp`, `speedOpp`, `expOpp`, `hungerOpp`, `thirstOpp`, `hpOpp`, `levelAquired`) VALUES
(1, 'Flame Whip', 0, 0, 0, 8, 4, 4, 1, 0, 0, 0, 0, 5, 5, 8, 10),
(2, 'Headbutt', 0, 0, 0, 6, 2, 1, 0, 0, 0, 0, 0, 1, 1, 5, 0),
(3, 'Scratch', 0, 0, 0, 3, 2, 1, 0, 5, 0, 0, 0, 1, 1, 3, 0),
(4, 'Spitfire', 0, 0, 0, 5, 4, 3, 0, 0, 0, 0, 0, 3, 3, 6, 5),
(5, 'Claw', 0, 0, 0, 4, 2, 1, 0, 0, 0, 0, 0, 2, 2, 4, 0),
(6, 'Distract', 0, 0, 0, 2, 0, 0, 0, 5, 0, 2, 0, 0, 0, 0, 0),
(7, 'Taunt', 0, 0, 0, 0, 1, 0, 0, 0, 6, 6, 0, 0, 0, 0, 3),
(8, 'Flame Throw', 0, 0, 0, 15, 5, 5, 2, 2, 1, 1, 1, 5, 8, 15, 20),
(9, 'Fire Wind', 0, 0, 0, 10, 7, 6, 0, 1, 3, 2, 1, 7, 6, 12, 15),
(10, 'Deep Heat', 0, 0, 0, 9, 6, 7, 0, 0, 0, 0, 9, 4, 4, 7, 6),
(11, 'Focus', 10, 0, 10, 5, 4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 'Shield', 0, 15, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 'Blizzard', 0, 0, 0, 25, 8, 8, 2, 5, 5, 5, 5, 10, 10, 30, 56),
(14, 'Ice Attack', 0, 0, 0, 10, 4, 7, 0, 0, 0, 0, 0, 4, 5, 16, 15),
(15, 'Crystal Kick', 0, 0, 0, 14, 6, 6, 0, 0, 0, 0, 0, 3, 4, 12, 17),
(16, 'Wing Clip', 0, 0, 0, 6, 2, 2, 0, 0, 0, 0, 0, 1, 1, 9, 8),
(17, 'Ice Bullets', 0, 0, 0, 13, 6, 7, 0, 0, 0, 0, 0, 3, 4, 15, 22),
(18, 'Frost Bite', 0, 0, 0, 5, 3, 4, 0, 0, 0, 0, 0, 2, 1, 9, 7),
(19, 'Zingar', 0, 0, 0, 4, 3, 3, 0, 0, 0, 0, 0, 2, 2, 7, 5);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `playerID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `screenName` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `playerPass` varchar(100) NOT NULL,
  `wins` int(10) unsigned NOT NULL DEFAULT '0',
  `loses` int(10) unsigned NOT NULL DEFAULT '0',
  `money` int(10) unsigned NOT NULL DEFAULT '20',
  `status` enum('online','offline','inbattle') NOT NULL DEFAULT 'offline',
  `location` enum('none','fire','rock','lava','jungle','ice','water','gas') NOT NULL DEFAULT 'none',
  `battleID` int(11) DEFAULT NULL,
  PRIMARY KEY (`playerID`),
  UNIQUE KEY `screenName` (`screenName`),
  KEY `List` (`status`,`location`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`playerID`, `screenName`, `email`, `playerPass`, `wins`, `loses`, `money`, `status`, `location`, `battleID`) VALUES
(1, 'Louis', 'test', '78082cdf14a959be08ed58d887c0f1e4fc2e88ff', 0, 0, 20, 'offline', 'none', NULL),
(3, 'Andrew', 'test', '78082cdf14a959be08ed58d887c0f1e4fc2e88ff', 0, 0, 20, 'offline', 'none', NULL),
(4, 'Tester', 'test', '78082cdf14a959be08ed58d887c0f1e4fc2e88ff', 0, 0, 20, 'offline', 'none', NULL),
(5, 'Tester2', 'test', '78082cdf14a959be08ed58d887c0f1e4fc2e88ff', 0, 0, 20, 'offline', 'none', NULL),
(6, 'Tester3', 'test', '78082cdf14a959be08ed58d887c0f1e4fc2e88ff', 0, 0, 20, 'offline', 'none', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `playerID` int(10) unsigned NOT NULL,
  `friendID` int(10) unsigned NOT NULL,
  `requestDate` datetime NOT NULL,
  PRIMARY KEY (`playerID`,`friendID`),
  KEY `playerID` (`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`playerID`, `friendID`, `requestDate`) VALUES
(2, 1, '2011-06-08 18:29:07');

-- --------------------------------------------------------

--
-- Table structure for table `species`
--

CREATE TABLE IF NOT EXISTS `species` (
  `speciesID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `speciesName` varchar(50) NOT NULL,
  `speciesDescr` varchar(255) NOT NULL,
  `world` enum('fire','rock','lava','jungle','ice','water','gas') NOT NULL,
  `attack` tinyint(3) unsigned NOT NULL,
  `defense` tinyint(3) unsigned NOT NULL,
  `speed` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`speciesID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `species`
--

INSERT INTO `species` (`speciesID`, `speciesName`, `speciesDescr`, `world`, `attack`, `defense`, `speed`) VALUES
(1, 'Possel', 'A sugar glider that excretes flammable fluid to fly in the direction needed much like a hot air balloon.', 'fire', 4, 4, 7),
(2, 'Stilcer', 'A crablike creature with long thin legs and small surface area to walk on fire.', 'fire', 6, 7, 3),
(3, 'Pyrock', 'Humanoid alien with a metal shell that hardens with extreme heat.', 'fire', 4, 9, 2),
(4, 'Skarrier', 'Dog like alien with lots of fluffy fur and long hind legs that can act as skates to quickly travel on ice.', 'ice', 4, 3, 7),
(5, 'Vyel', 'A large bird with wings comprised of icicles.', 'ice', 6, 6, 6),
(6, 'Kriskross', 'A goblin like creature with large paddle shaped hands and feet for snow travel and armour made of icicles.', 'ice', 7, 6, 1),
(7, 'Dooth', 'Angler fish with giant fins, 2 fangs and a blinding light.', 'water', 6, 3, 6),
(8, 'Alliman', 'Similar to a Crocodile but can stand on hind legs.', 'water', 4, 8, 6),
(9, 'Triclee', 'An enhanced form of an electric eel.', 'water', 4, 3, 9),
(10, 'Apelim', 'Similar to a Monkey but with an extra set of arms.', 'jungle', 7, 3, 7),
(11, 'Wormbo', 'A large earth worm. Weak but very fast.', 'jungle', 1, 3, 9),
(12, 'Scorn', 'Large scorpion with the ability to stand on hind legs.', 'jungle', 7, 7, 1),
(13, 'Ent', 'A more intelligent Tree. Strong but very slow.', 'jungle', 4, 9, 1),
(14, 'Drillst', 'A creature with a large drill for a mouth and giant fists to break through rock.', 'rock', 9, 6, 1),
(15, 'Hechop', 'Humanoid creature with small lasers on hands to cut rock. Armoured with rock.', 'rock', 7, 7, 3),
(16, 'Diggimal', 'A mole-like creature with giant sharp claws.', 'rock', 2, 3, 7),
(17, 'Samalanda', 'Salamander with a hardened back that resembles rock.', 'lava', 2, 5, 8),
(18, 'Serenifly', 'Large firefly with blinding lights.', 'lava', 4, 5, 6),
(19, 'Fubar', 'Cave dwelling bear with lava and fire proof paws and stomach.', 'lava', 8, 4, 1),
(20, 'Sepelem', 'Pelican like bird with tiny wings as there is low gravity.', 'gas', 4, 5, 4),
(21, 'Enmesh', 'Glowing floating orb of intelligence. Contains neurons which light up.', 'gas', 1, 1, 1),
(22, 'Modflap', 'Flying worm. Consists of circles to form the body and small wings on every second circle.', 'gas', 3, 2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `trade`
--

CREATE TABLE IF NOT EXISTS `trade` (
  `tradeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `playerID` int(10) unsigned NOT NULL,
  `friendID` int(10) unsigned NOT NULL,
  `tradeDate` datetime NOT NULL,
  `status` enum('Waiting','Accepted') NOT NULL DEFAULT 'Waiting',
  PRIMARY KEY (`tradeID`),
  KEY `friendID` (`friendID`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `trade`
--

INSERT INTO `trade` (`tradeID`, `playerID`, `friendID`, `tradeDate`, `status`) VALUES
(1, 3, 1, '2011-07-03 11:55:28', 'Waiting'),
(19, 1, 5, '2011-07-01 03:07:53', 'Waiting');

-- --------------------------------------------------------

--
-- Table structure for table `trade_aliens`
--

CREATE TABLE IF NOT EXISTS `trade_aliens` (
  `tradeID` int(11) unsigned NOT NULL,
  `playerID` int(11) unsigned NOT NULL,
  `alienID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`tradeID`,`playerID`,`alienID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trade_aliens`
--


-- --------------------------------------------------------

--
-- Table structure for table `trade_items`
--

CREATE TABLE IF NOT EXISTS `trade_items` (
  `tradeID` int(11) unsigned NOT NULL,
  `playerID` int(11) unsigned NOT NULL,
  `itemID` int(11) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tradeID`,`playerID`,`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trade_items`
--

INSERT INTO `trade_items` (`tradeID`, `playerID`, `itemID`, `quantity`) VALUES
(19, 1, 1, 5);
