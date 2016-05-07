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
-- Table structure for table `leden`
--

DROP TABLE IF EXISTS `leden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leden` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `habbonaam` varchar(255) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `level` int(1) NOT NULL DEFAULT '0',
  `regdatum` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `donator` int(30) NOT NULL DEFAULT '0',
  `donation_amount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `rang` varchar(255) NOT NULL DEFAULT 'HabboFan',
  `lastonline` datetime NOT NULL,
  `stem_punten` int(225) NOT NULL DEFAULT '0',
  `sessie_onthouden` int(1) NOT NULL DEFAULT '0',
  `allow_pw_change` int(1) NOT NULL DEFAULT '0',
  `hipchat_user_id` int(1) DEFAULT NULL,
  `signature` mediumtext NOT NULL,
  `signature_enabled` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gebruikersnaam_2` (`habbonaam`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_FCategorie`
--

DROP TABLE IF EXISTS `paneel_FCategorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_FCategorie` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `naam` text NOT NULL,
  `omschrijving` text NOT NULL,
  `toegang` varchar(3) NOT NULL,
  `topic_maken` varchar(3) NOT NULL,
  `order` int(60) NOT NULL DEFAULT '0',
  `habbonaam` varchar(255) NOT NULL,
  `departement` int(50) NOT NULL,
  `prullenbak` int(1) NOT NULL,
  `datum` datetime NOT NULL,
  `paneel` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_FDepartement`
--

DROP TABLE IF EXISTS `paneel_FDepartement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_FDepartement` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `naam` text NOT NULL,
  `habbonaam` varchar(255) NOT NULL,
  `zichtbaarheid` int(1) NOT NULL,
  `badge` varchar(250) NOT NULL,
  `ordening` int(50) NOT NULL,
  `prullenbak` int(1) NOT NULL,
  `datum` datetime NOT NULL,
  `paneel` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ordening` (`ordening`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_FGelezen`
--

DROP TABLE IF EXISTS `paneel_FGelezen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_FGelezen` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `paneel` varchar(3) NOT NULL,
  `habbonaam` varchar(255) NOT NULL,
  `topic` int(50) NOT NULL,
  `prullenbak` int(1) NOT NULL,
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `datum` (`datum`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_FLeden`
--

DROP TABLE IF EXISTS `paneel_FLeden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_FLeden` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `paneel` varchar(3) NOT NULL,
  `habbonaam` varchar(255) NOT NULL,
  `departement` int(50) NOT NULL,
  `level` varchar(3) NOT NULL,
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_FLevel`
--

DROP TABLE IF EXISTS `paneel_FLevel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_FLevel` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `level` varchar(3) NOT NULL,
  `naamlevel` varchar(30) NOT NULL,
  `reactie_wis` int(1) NOT NULL,
  `topic_wis` int(1) NOT NULL,
  `topic_sticky_edit` int(1) NOT NULL,
  `topic_edit` int(1) NOT NULL,
  `topic_close` int(1) NOT NULL,
  `topic_reageerlevel` int(1) NOT NULL,
  `topic_clean_comments` int(1) NOT NULL,
  `categorie_maken` int(1) NOT NULL,
  `categorie_edit` int(1) NOT NULL,
  `Flidlevel_aanstelle` int(2) NOT NULL,
  `Flidlevel_edit` int(2) NOT NULL,
  `show_gewist` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `paneel_FLevel` WRITE;
/*!40000 ALTER TABLE `paneel_FLevel` DISABLE KEYS */;
INSERT INTO `paneel_FLevel` VALUES (1,'1','Lid',0,0,0,0,0,0,0,0,0,0,0,0),(2,'2','MOD',1,0,0,0,0,0,0,0,0,1,1,0),(3,'3','Admin',1,0,1,1,1,1,0,0,0,2,2,0),(4,'4','Assistent Beheerder',1,1,1,1,1,1,1,0,0,3,3,0),(5,'5','Beheerder',1,1,1,1,1,1,1,1,1,4,4,1),(6,'1.1','Groep A',0,0,0,0,0,0,0,0,0,0,0,0),(7,'1.2','Groep B',0,0,0,0,0,0,0,0,0,0,0,0),(8,'1.3','Groep C',0,0,0,0,0,0,0,0,0,0,0,0),(9,'1.4','Groep D',0,0,0,0,0,0,0,0,0,0,0,0),(10,'1.5','Groep E',0,0,0,0,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `paneel_FLevel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paneel_FReactie`
--

DROP TABLE IF EXISTS `paneel_FReactie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_FReactie` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `paneel` varchar(3) NOT NULL,
  `topic` int(50) NOT NULL,
  `habbonaam` varchar(255) NOT NULL,
  `reactie` mediumtext NOT NULL,
  `special` int(2) NOT NULL,
  `prullenbak` int(1) NOT NULL,
  `datum` datetime NOT NULL,
  `aangepast_datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `datum` (`datum`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_FTopic`
--

DROP TABLE IF EXISTS `paneel_FTopic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_FTopic` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `titel` text NOT NULL,
  `bericht` mediumtext NOT NULL,
  `habbonaam` varchar(255) NOT NULL,
  `sticky` int(1) NOT NULL,
  `closed` int(1) NOT NULL,
  `reageerlevel` varchar(3) NOT NULL,
  `categorie` int(30) NOT NULL,
  `prullenbak` int(1) NOT NULL,
  `datum` datetime NOT NULL,
  `aangepast_datum` datetime NOT NULL,
  `paneel` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `datum` (`datum`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_admins`
--

DROP TABLE IF EXISTS `paneel_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `habbonaam` varchar(45) NOT NULL,
  `access_level` int(5) NOT NULL,
  `has_ip_restriction` int(1) NOT NULL DEFAULT '0',
  `access_ip` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_afwezigen`
--

DROP TABLE IF EXISTS `paneel_afwezigen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_afwezigen` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `habbonaam` varchar(250) NOT NULL,
  `afgemeld_bij` varchar(250) NOT NULL,
  `reden` text NOT NULL,
  `van` date NOT NULL,
  `start` int(9) NOT NULL DEFAULT '0',
  `tot` date NOT NULL,
  `end` int(9) NOT NULL DEFAULT '0',
  `paneel` varchar(250) NOT NULL,
  `panel_id` int(5) NOT NULL DEFAULT '0',
  `wis` int(1) NOT NULL DEFAULT '0',
  `toegevoegd_op` datetime NOT NULL,
  `added_on` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_bans`
--

DROP TABLE IF EXISTS `paneel_bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_bans` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` enum('username','ip') NOT NULL,
  `value` varchar(45) NOT NULL,
  `reason` varchar(400) NOT NULL,
  `by` varchar(45) NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `value_UNIQUE` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_conversatie_berichten`
--

DROP TABLE IF EXISTS `paneel_conversatie_berichten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_conversatie_berichten` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(9) DEFAULT NULL,
  `message` text NOT NULL,
  `from_user` int(9) NOT NULL,
  `timestamp` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_conversaties`
--

DROP TABLE IF EXISTS `paneel_conversaties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_conversaties` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `starter` int(9) NOT NULL,
  `created_on` int(9) NOT NULL,
  `closed` int(2) DEFAULT NULL,
  `last_reply` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_convo_participants`
--

DROP TABLE IF EXISTS `paneel_convo_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_convo_participants` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) NOT NULL,
  `user_id` int(9) NOT NULL,
  `last_viewed` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_logs`
--

DROP TABLE IF EXISTS `paneel_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_logs` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `habbonaam` varchar(250) NOT NULL,
  `user_id` int(9) NOT NULL,
  `paneel` varchar(250) NOT NULL,
  `panel_id` int(9) NOT NULL,
  `actie` text NOT NULL,
  `ip` varchar(250) NOT NULL,
  `UA` text NOT NULL,
  `datum` datetime NOT NULL,
  `date` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_personeel`
--

DROP TABLE IF EXISTS `paneel_personeel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_personeel` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `habbonaam` varchar(250) NOT NULL,
  `user_id` int(9) NOT NULL,
  `paneel` varchar(250) NOT NULL,
  `panel_id` int(9) NOT NULL,
  `toegang_beheer` int(1) NOT NULL,
  `toegang_trainingen` int(1) NOT NULL,
  `toegang_afwezigen` int(1) NOT NULL,
  `toegang_warn` int(1) NOT NULL,
  `toegang_promotie` int(1) NOT NULL,
  `toegang_degradatie` int(1) NOT NULL,
  `toegang_ontslag` int(1) NOT NULL,
  `toegang_promotag` int(1) NOT NULL DEFAULT '0',
  `hipchat_user_maken` int(1) NOT NULL DEFAULT '0',
  `grant_pw_change` int(1) NOT NULL DEFAULT '0',
  `datum` datetime NOT NULL,
  `date` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_promotag`
--

DROP TABLE IF EXISTS `paneel_promotag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_promotag` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `habbonaam` varchar(250) NOT NULL,
  `user_id` int(9) NOT NULL,
  `gegeven_door` varchar(250) NOT NULL,
  `from_user_id` int(9) NOT NULL,
  `promotag` varchar(10) NOT NULL,
  `paneel` varchar(5) NOT NULL,
  `panel_id` int(9) NOT NULL,
  `datum` datetime NOT NULL,
  `date` int(9) NOT NULL DEFAULT '0',
  `wis` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_rangniveau`
--

DROP TABLE IF EXISTS `paneel_rangniveau`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_rangniveau` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `rang_naam` varchar(250) NOT NULL,
  `rang_level` int(5) NOT NULL,
  `promoveren_tot` int(5) NOT NULL,
  `ontslaan_tot` int(5) NOT NULL,
  `degraderen_tot` int(5) NOT NULL,
  `paneel` varchar(250) NOT NULL,
  `panel_id` int(9) NOT NULL,
  `warn` int(1) NOT NULL DEFAULT '0',
  `trainingen` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_rangverandering`
--

DROP TABLE IF EXISTS `paneel_rangverandering`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_rangverandering` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `habbonaam` varchar(250) NOT NULL,
  `user_id` int(9) NOT NULL DEFAULT '0',
  `rang_oud` varchar(250) NOT NULL,
  `rang_nieuw` int(50) NOT NULL,
  `rang_door` varchar(250) NOT NULL,
  `from_user_id` int(9) NOT NULL DEFAULT '0',
  `rang_op` datetime NOT NULL,
  `date` int(9) NOT NULL DEFAULT '0',
  `rang_soort` varchar(250) NOT NULL,
  `reden` text NOT NULL,
  `paneel` varchar(250) DEFAULT NULL,
  `panel_id` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rang_op` (`rang_op`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_settings`
--

DROP TABLE IF EXISTS `paneel_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_settings` (
  `name` varchar(40) NOT NULL,
  `content` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_trainingen`
--

DROP TABLE IF EXISTS `paneel_trainingen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_trainingen` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `habbonaam` varchar(250) NOT NULL,
  `user_id` int(9) NOT NULL,
  `training` varchar(250) NOT NULL,
  `gehaald` int(1) NOT NULL,
  `door` varchar(250) NOT NULL,
  `from_user_id` int(9) NOT NULL,
  `datum` datetime NOT NULL,
  `date` int(9) NOT NULL DEFAULT '0',
  `paneel` varchar(250) NOT NULL,
  `panel_id` int(9) NOT NULL,
  `wis` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paneel_warn`
--

DROP TABLE IF EXISTS `paneel_warn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paneel_warn` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `warn_ontvanger` varchar(250) NOT NULL,
  `user_id` int(9) NOT NULL,
  `warn_gever` varchar(250) NOT NULL,
  `from_user_id` int(9) NOT NULL,
  `warn_op` datetime NOT NULL,
  `date` int(9) NOT NULL DEFAULT '0',
  `warn` text NOT NULL,
  `paneel` varchar(250) NOT NULL,
  `panel_id` int(9) NOT NULL,
  `wis` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
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

-- Dump completed on 2016-05-06 21:38:23
