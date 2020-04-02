-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: project-api-devstagram
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL DEFAULT 0,
  `url` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos`
--

LOCK TABLES `photos` WRITE;
/*!40000 ALTER TABLE `photos` DISABLE KEYS */;
INSERT INTO `photos` VALUES (1,2,'phototest.jpg'),(2,3,'dsadsaas'),(4,6,'1321312'),(5,7,'32131'),(6,10,'3ffdsfds'),(7,2,'0dsadsadas'),(8,4,'sdasdsadsa');
/*!40000 ALTER TABLE `photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos_comments`
--

DROP TABLE IF EXISTS `photos_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL DEFAULT 0,
  `id_photo` int(11) unsigned NOT NULL DEFAULT 0,
  `data_comment` datetime NOT NULL,
  `txt` text CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos_comments`
--

LOCK TABLES `photos_comments` WRITE;
/*!40000 ALTER TABLE `photos_comments` DISABLE KEYS */;
INSERT INTO `photos_comments` VALUES (1,3,1,'0000-00-00 00:00:00','HAHAHA'),(4,7,4,'0000-00-00 00:00:00','HEHEHEH'),(5,10,6,'0000-00-00 00:00:00','HAUHAUHUA'),(7,6,5,'0000-00-00 00:00:00','odsjajiodjaso'),(8,4,2,'2020-04-01 18:22:12','Coment├írio sinistro');
/*!40000 ALTER TABLE `photos_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos_likes`
--

DROP TABLE IF EXISTS `photos_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos_likes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL DEFAULT 0,
  `id_photo` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos_likes`
--

LOCK TABLES `photos_likes` WRITE;
/*!40000 ALTER TABLE `photos_likes` DISABLE KEYS */;
INSERT INTO `photos_likes` VALUES (1,3,1),(2,4,1),(3,6,2),(5,10,4),(6,3,6),(8,4,7),(9,4,2),(11,4,6);
/*!40000 ALTER TABLE `photos_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `email` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `pass` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `avatar` varchar(100) CHARACTER SET utf8 DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Lucas','lucas@gmail.com','$2y$10$p6qfJVns3PFwwXDIz1QTnOWdmUOTPL4iQcpiqO5pxkTAU9lxJncwi',''),(4,'Lucas','lucas123@gmail.com','$2y$10$en9/HgI/OFsCbDTBygM5KuhUE/5TKr9JgAB.94iclhnvoRNcZPZo6',''),(6,'Norma','norma@gmail.com','$2y$10$CW7yZrJUI9mrtAZPtYyRJ.FbzY6K4PPdV3WTuKMcJn5NIrU/f4NHe',''),(7,'Sebastiao','sebastiao@gmail.com','$2y$10$v6Anx/DlU0xKX0dr/QaTjeB2OaWdvlCidHnZX9e99MV0AXRffPrry',''),(10,'Karine','karine@gmail.com','$2y$10$d9RFNH0QRhzacWnqufc6oOseHtoB.eAAIqzVHtQmi7a91B3Ob3M2i','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_following`
--

DROP TABLE IF EXISTS `users_following`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_following` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user_active` int(11) unsigned NOT NULL DEFAULT 0,
  `id_user_passive` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_following`
--

LOCK TABLES `users_following` WRITE;
/*!40000 ALTER TABLE `users_following` DISABLE KEYS */;
INSERT INTO `users_following` VALUES (5,4,3),(6,4,7),(7,6,7),(8,7,10),(9,10,4),(10,3,7),(11,10,3),(12,4,10),(13,4,6);
/*!40000 ALTER TABLE `users_following` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-02 13:20:33
