-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: jefferys0
-- ------------------------------------------------------
-- Server version	5.7.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `belongs`
--

DROP TABLE IF EXISTS `belongs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `belongs` (
  `student_ID` int(6) NOT NULL,
  `group_ID` int(6) NOT NULL,
  KEY `noStudent` (`student_ID`),
  KEY `nosuchGroup` (`group_ID`),
  CONSTRAINT `noStudent` FOREIGN KEY (`student_ID`) REFERENCES `students` (`student_ID`) ON DELETE CASCADE,
  CONSTRAINT `nosuchGroup` FOREIGN KEY (`group_ID`) REFERENCES `groups` (`group_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `group_ID` int(6) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(20) NOT NULL,
  `group_subject` varchar(20) NOT NULL,
  `group_numUsers` int(8) NOT NULL,
  `creator_ID` int(8) NOT NULL,
  `group_created` date DEFAULT NULL,
  `image_ID` int(8) DEFAULT NULL,
  PRIMARY KEY (`group_ID`),
  UNIQUE KEY `group_ID` (`group_ID`),
  UNIQUE KEY `groupExists` (`group_name`),
  KEY `noCreator` (`creator_ID`),
  KEY `noGroupImage` (`image_ID`),
  CONSTRAINT `noCreator` FOREIGN KEY (`creator_ID`) REFERENCES `students` (`student_ID`) ON DELETE CASCADE,
  CONSTRAINT `noGroupImage` FOREIGN KEY (`image_ID`) REFERENCES `images` (`image_ID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `image_ID` int(8) NOT NULL AUTO_INCREMENT,
  `image_name` varchar(20) DEFAULT NULL,
  `image_location` varchar(20) NOT NULL,
  PRIMARY KEY (`image_ID`),
  UNIQUE KEY `image_ID` (`image_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `message_ID` int(8) NOT NULL AUTO_INCREMENT,
  `group_ID` int(8) NOT NULL,
  `student_ID` int(8) NOT NULL,
  `message_date` date DEFAULT NULL,
  `message_body` text NOT NULL,
  `reply_ID` int(8) DEFAULT '0',
  PRIMARY KEY (`message_ID`),
  UNIQUE KEY `message_ID` (`message_ID`),
  KEY `noGroupMessage` (`group_ID`),
  KEY `noSender` (`student_ID`),
  CONSTRAINT `noGroupMessage` FOREIGN KEY (`group_ID`) REFERENCES `groups` (`group_ID`) ON DELETE CASCADE,
  CONSTRAINT `noSender` FOREIGN KEY (`student_ID`) REFERENCES `students` (`student_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `student_ID` int(6) NOT NULL AUTO_INCREMENT,
  `fname` varchar(20) DEFAULT NULL,
  `lname` varchar(20) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL,
  `email` varchar(20) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `image_ID` int(8) DEFAULT NULL,
  PRIMARY KEY (`student_ID`),
  UNIQUE KEY `student_ID` (`student_ID`),
  UNIQUE KEY `userexists` (`username`),
  KEY `noImage` (`image_ID`),
  CONSTRAINT `noImage` FOREIGN KEY (`image_ID`) REFERENCES `images` (`image_ID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-03 18:41:02
