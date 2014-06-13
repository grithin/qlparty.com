-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: qlparty
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.12.04.1

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
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id_` int(11) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `foreign_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`foreign_id`),
  KEY `time_created` (`time_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity`
--

LOCK TABLES `activity` WRITE;
/*!40000 ALTER TABLE `activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_type`
--

DROP TABLE IF EXISTS `activity_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `table` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_type`
--

LOCK TABLES `activity_type` WRITE;
/*!40000 ALTER TABLE `activity_type` DISABLE KEYS */;
INSERT INTO `activity_type` VALUES (1,'candidate','candidate'),(2,'volunteer','volunteer'),(3,'post','post'),(4,'event','event'),(5,'newsletter','newsletter');
/*!40000 ALTER TABLE `activity_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `actor`
--

DROP TABLE IF EXISTS `actor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(250) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `time_updated` datetime DEFAULT NULL,
  `referrer` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`display_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actor`
--

LOCK TABLES `actor` WRITE;
/*!40000 ALTER TABLE `actor` DISABLE KEYS */;
/*!40000 ALTER TABLE `actor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ban`
--

DROP TABLE IF EXISTS `ban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identity` varchar(200) DEFAULT NULL,
  `type_id_` int(11) DEFAULT NULL,
  `time_expire` datetime DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identity` (`identity`),
  KEY `type_id` (`type_id_`),
  KEY `time_expire` (`time_expire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ban`
--

LOCK TABLES `ban` WRITE;
/*!40000 ALTER TABLE `ban` DISABLE KEYS */;
/*!40000 ALTER TABLE `ban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ban_type`
--

DROP TABLE IF EXISTS `ban_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ban_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `reason` text,
  `time_span` int(11) DEFAULT NULL,
  `limit` int(11) DEFAULT NULL,
  `ban_time` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ban_type`
--

LOCK TABLES `ban_type` WRITE;
/*!40000 ALTER TABLE `ban_type` DISABLE KEYS */;
INSERT INTO `ban_type` VALUES (1,'admin ban','admin banned you',1,1,NULL),(2,'admin ban 2','admin banned you',1,1,'+1 month'),(3,'admin ban 3','admin banned you',1,1,'+2 weeks'),(4,'admin ban 4','admin banned you',1,1,'+1 week'),(5,'admin ban 5','admin banned you',1,1,'+2 days'),(6,'admin ban 6','admin banned you',1,1,'+1 day'),(7,'admin ban 7','admin banned you',1,1,'+4 hours'),(8,'admin ban 8','admin banned you',1,1,'+1 hour'),(9,'page load','Loading pages too quickly',30,12,'+60 seconds'),(10,'page load+','Loading pages too quickly',3000,3,'+600 seconds'),(11,'page load++','Loading pages too quickly',30000,3,'+6000 seconds'),(12,'page load+++','Loading pages too quickly',300000,3,NULL),(13,'form create','adding too much',20,4,'+600 seconds'),(14,'form create+','adding too much',6000,2,'+6000 seconds'),(15,'form create++','adding too much',60000,2,'+3 days'),(16,'form create2','adding too much',100,5,'+600 seconds'),(17,'form create2+','adding too much',6000,3,'+6000 seconds'),(18,'form create2++','adding too much',600000,3,NULL);
/*!40000 ALTER TABLE `ban_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidate`
--

DROP TABLE IF EXISTS `candidate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_id` int(11) DEFAULT NULL,
  `desired_position` text,
  `location` text,
  `qualifications` text,
  `personal_support` text,
  `about` text,
  `has_signed_contract` int(11) DEFAULT NULL,
  `is_endorsed` int(11) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `name_first` varchar(250) DEFAULT NULL,
  `name_last` varchar(250) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `actor` (`actor_id`),
  KEY `time_created` (`time_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidate`
--

LOCK TABLES `candidate` WRITE;
/*!40000 ALTER TABLE `candidate` DISABLE KEYS */;
/*!40000 ALTER TABLE `candidate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_ip`
--

DROP TABLE IF EXISTS `client_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `actor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_UNIQUE` (`ip`),
  UNIQUE KEY `actor` (`actor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_ip`
--

LOCK TABLES `client_ip` WRITE;
/*!40000 ALTER TABLE `client_ip` DISABLE KEYS */;
INSERT INTO `client_ip` VALUES (3,'127.0.0.1','2013-08-26 14:18:24',4);
/*!40000 ALTER TABLE `client_ip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_id` int(11) DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_end` datetime DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `text` text,
  `time_created` datetime DEFAULT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_zone` varchar(45) DEFAULT NULL,
  `location` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time_created` (`time_created`),
  KEY `time_start` (`time_start`),
  KEY `time_end` (`time_end`),
  KEY `actor` (`actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_joiner`
--

DROP TABLE IF EXISTS `event_joiner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_joiner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_id` int(11) DEFAULT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `actor` (`actor_id`,`_id`),
  KEY `event` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_joiner`
--

LOCK TABLES `event_joiner` WRITE;
/*!40000 ALTER TABLE `event_joiner` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_joiner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_id__from` int(11) DEFAULT NULL,
  `actor_id__to` int(11) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `text` text,
  `is_read` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `actor_to` (`actor_id__to`,`time_created`),
  KEY `actor_from` (`actor_id__from`,`time_created`),
  KEY `time_creaated` (`time_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `text` text,
  `time_created` datetime DEFAULT NULL,
  `time_sent` datetime DEFAULT NULL,
  `actor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time_sent` (`time_sent`),
  KEY `time_created` (`time_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter`
--

LOCK TABLES `newsletter` WRITE;
/*!40000 ALTER TABLE `newsletter` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscriber`
--

DROP TABLE IF EXISTS `newsletter_subscriber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_subscriber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) DEFAULT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `actor` (`actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscriber`
--

LOCK TABLES `newsletter_subscriber` WRITE;
/*!40000 ALTER TABLE `newsletter_subscriber` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_subscriber` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_id` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `text` text,
  `time_created` datetime DEFAULT NULL,
  `time_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `actor` (`actor_id`),
  KEY `time_created` (`time_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` varchar(32) NOT NULL,
  `data` text NOT NULL,
  `permanent` int(1) DEFAULT NULL,
  `time` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `actor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
INSERT INTO `session` VALUES ('a72d93070c3dcac1afd5a8e80fb28d2f','a:1:{s:7:\"actorId\";s:1:\"4\";}',NULL,1379718571,NULL,4),('b46ea2a2b237b53cec174b60562b2fc4','a:3:{s:7:\"actorId\";N;s:6:\"userId\";s:1:\"2\";s:11:\"displayName\";s:4:\"test\";}',NULL,1379720201,2,NULL);
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table`
--

DROP TABLE IF EXISTS `table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table`
--

LOCK TABLES `table` WRITE;
/*!40000 ALTER TABLE `table` DISABLE KEYS */;
/*!40000 ALTER TABLE `table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `text_UNIQUE` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(200) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `name_first` varchar(200) DEFAULT NULL,
  `name_last` varchar(200) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `time_last_login` datetime DEFAULT NULL,
  `is_verified` int(11) DEFAULT NULL,
  `is_disabled` int(11) DEFAULT NULL,
  `public_statement` text,
  `website` text,
  `actor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `actor` (`actor_id`),
  KEY `email` (`email`,`password`),
  KEY `time_created` (`time_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_email_verification`
--

DROP TABLE IF EXISTS `user_email_verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_email_verification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_created` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `time_verified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_email_verification`
--

LOCK TABLES `user_email_verification` WRITE;
/*!40000 ALTER TABLE `user_email_verification` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_email_verification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group`
--

LOCK TABLES `user_group` WRITE;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group_privilege`
--

DROP TABLE IF EXISTS `user_group_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group_privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_id` int(11) NOT NULL,
  `__privilege_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`_id`,`__privilege_type_id`),
  UNIQUE KEY `privilege_id` (`__privilege_type_id`,`_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group_privilege`
--

LOCK TABLES `user_group_privilege` WRITE;
/*!40000 ALTER TABLE `user_group_privilege` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_group_privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group_user`
--

DROP TABLE IF EXISTS `user_group_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_id` int(11) NOT NULL,
  `__id` int(11) NOT NULL,
  `time_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`__id`,`_id`),
  KEY `group_id` (`_id`),
  KEY `time_created` (`time_created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group_user`
--

LOCK TABLES `user_group_user` WRITE;
/*!40000 ALTER TABLE `user_group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_group_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_ip`
--

DROP TABLE IF EXISTS `user_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_id` int(11) DEFAULT NULL,
  `client_ip_id` int(11) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `time_updated` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `_id` (`_id`,`client_ip_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_ip`
--

LOCK TABLES `user_ip` WRITE;
/*!40000 ALTER TABLE `user_ip` DISABLE KEYS */;
INSERT INTO `user_ip` VALUES (1,1,1,'2013-08-06 22:35:28','2013-08-06 22:36:24',2),(2,2,3,'2013-09-20 23:09:22','2013-09-20 23:09:34',2);
/*!40000 ALTER TABLE `user_ip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_privilege`
--

DROP TABLE IF EXISTS `user_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`_id`,`type_id`),
  UNIQUE KEY `privilege_id` (`type_id`,`_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_privilege`
--

LOCK TABLES `user_privilege` WRITE;
/*!40000 ALTER TABLE `user_privilege` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_privilege_type`
--

DROP TABLE IF EXISTS `user_privilege_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_privilege_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_privilege_type`
--

LOCK TABLES `user_privilege_type` WRITE;
/*!40000 ALTER TABLE `user_privilege_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_privilege_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer`
--

DROP TABLE IF EXISTS `volunteer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_id` int(11) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `name_first` varchar(250) DEFAULT NULL,
  `name_last` varchar(250) DEFAULT NULL,
  `skills` text,
  `time_available` text,
  `notes` text,
  `is_public` int(11) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `location` text,
  PRIMARY KEY (`id`),
  KEY `time_created` (`time_created`,`is_public`),
  KEY `actor` (`actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer`
--

LOCK TABLES `volunteer` WRITE;
/*!40000 ALTER TABLE `volunteer` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer_email`
--

DROP TABLE IF EXISTS `volunteer_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_id` int(11) DEFAULT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `text` text,
  `time_created` datetime DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `volunteer` (`_id`),
  KEY `actor` (`actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer_email`
--

LOCK TABLES `volunteer_email` WRITE;
/*!40000 ALTER TABLE `volunteer_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteer_email` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-09-20 16:37:07
