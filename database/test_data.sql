CREATE DATABASE  IF NOT EXISTS `official` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `official`;
-- MySQL dump 10.13  Distrib 5.7.9, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: official
-- ------------------------------------------------------
-- Server version	5.6.27-0ubuntu0.14.04.1

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
-- Table structure for table `cake_sessions`
--

DROP TABLE IF EXISTS `cake_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cake_sessions` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `data` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cake_sessions`
--

LOCK TABLES `cake_sessions` WRITE;
/*!40000 ALTER TABLE `cake_sessions` DISABLE KEYS */;
INSERT INTO `cake_sessions` VALUES ('kmbt3mej063ggatcqkf5jjtd56','Config|a:3:{s:9:\"userAgent\";s:32:\"ebd6e781d0552d461dda99204d86c1d5\";s:4:\"time\";i:1451993434;s:9:\"countdown\";i:10;}',1451993434);
/*!40000 ALTER TABLE `cake_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `media_name` varchar(128) NOT NULL,
  `media_mime_type` varchar(60) NOT NULL,
  `media_link` varchar(200) NOT NULL DEFAULT 'http://localhost/',
  `media_thumbnail` varchar(200) NOT NULL DEFAULT 'http://localhost/',
  `media_size` varchar(11) NOT NULL,
  `media_dimension` varchar(45) DEFAULT NULL,
  `media_origin_name` varchar(128) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`media_id`),
  UNIQUE KEY `media_id_UNIQUE` (`media_id`),
  KEY `media_link_idx` (`media_link`) USING BTREE,
  KEY `media_thumbnail_idx` (`media_thumbnail`),
  KEY `created_idx` (`created`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'Kurome','image/jpeg','http://img04.deviantart.net/f183/i/2014/342/d/5/akame_ga_kill___akame_cosplay_by_voizofsnow-d8943bf.jpg','http://img04.deviantart.net/f183/i/2014/342/d/5/akame_ga_kill___akame_cosplay_by_voizofsnow-d8943bf.jpg','86635','800x563','akame_ga_kill___akame_cosplay_by_voizofsnow-d8943bf.jpg','2015-12-16 06:13:06','2015-12-16 06:13:07'),(3,'Kurome2','image/jpeg','http://img04.deviantart.net/f183/i/2014/342/d/5/akame_ga_kill___akame_cosplay_by_voizofsnow-d8943bf.jpg','http://img04.deviantart.net/f183/i/2014/342/d/5/akame_ga_kill___akame_cosplay_by_voizofsnow-d8943bf.jpg','86635','800x563','akame_ga_kill___akame_cosplay_by_voizofsnow-d8943bf.jpg','2015-12-21 07:56:53','2015-12-21 07:56:53');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `api_access_key` text,
  `active` int(1) NOT NULL DEFAULT '1',
  `display_name` varchar(64) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  KEY `username_idx` (`username`) USING BTREE,
  KEY `created_idx` (`created`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'hieunc','a4cc9cd6da17387a7c71a7660de2cfd73adb1cd3720878beb5c73a69daf3a598','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsInVzZXJuYW1lIjoiaGlldW5jIiwiYWN0aXZlIjoiMSIsIm1vZGlmaWVkIjoiMjAxNi0wMS0wNyAxNDowMTo1MiJ9.Os_zX6SJXksd3j3teDknR1eYIqohjdU5VC1rD47u0Rs',1,'Hiếu Nguyễn','2015-12-22 11:23:40','2016-01-07 16:01:20');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-07 17:20:27
