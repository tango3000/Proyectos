-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: prueba
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comps`
--

DROP TABLE IF EXISTS `comps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comps` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comps`
--

LOCK TABLES `comps` WRITE;
/*!40000 ALTER TABLE `comps` DISABLE KEYS */;
INSERT INTO `comps` VALUES (1,'high school degree','1','2'),(2,'college degree','2','1'),(3,'masters degree','1','1'),(4,'doctoral degree','2','2'),(5,'PhD','1','2'),(6,'doctoral program','1','1');
/*!40000 ALTER TABLE `comps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drijf`
--

DROP TABLE IF EXISTS `drijf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drijf` (
  `geel` varchar(45) DEFAULT NULL,
  `groen` varchar(45) DEFAULT NULL,
  `oranje` varchar(45) DEFAULT NULL,
  `blauw` varchar(45) DEFAULT NULL,
  `rood` varchar(45) DEFAULT NULL,
  `paars` varchar(45) DEFAULT NULL,
  `turkoois` varchar(45) DEFAULT NULL,
  `drijfcol` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drijf`
--

LOCK TABLES `drijf` WRITE;
/*!40000 ALTER TABLE `drijf` DISABLE KEYS */;
/*!40000 ALTER TABLE `drijf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test0`
--

DROP TABLE IF EXISTS `test0`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test0` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test0`
--

LOCK TABLES `test0` WRITE;
/*!40000 ALTER TABLE `test0` DISABLE KEYS */;
/*!40000 ALTER TABLE `test0` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test1`
--

DROP TABLE IF EXISTS `test1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test1` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test1`
--

LOCK TABLES `test1` WRITE;
/*!40000 ALTER TABLE `test1` DISABLE KEYS */;
/*!40000 ALTER TABLE `test1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testresults`
--

DROP TABLE IF EXISTS `testresults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testresults` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `avg` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_testresults` (`uid`),
  KEY `fk_comps_testresults` (`cid`),
  CONSTRAINT `fk_comps_testresults` FOREIGN KEY (`cid`) REFERENCES `comps` (`id`),
  CONSTRAINT `fk_user_testresults` FOREIGN KEY (`uid`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testresults`
--

LOCK TABLES `testresults` WRITE;
/*!40000 ALTER TABLE `testresults` DISABLE KEYS */;
INSERT INTO `testresults` VALUES (1,4,4,'Samenwerken en overleggen','Er met anderen voor kunnen zorgen dat een gezamenlijk doel wordt bereikt.\n> raadpleegt en betrekt anderen bij het nemen van beslissingen en/of het uitvoeren van taken\n> overlegt tijdig en regelmatig met anderen en informeert hen voldoende\n> stelt zich in ',3.856),(62,2,2,'Geen angst bezitten','In welke mate en hoe dikwijls men angst voelt en ervaart.',6.092),(63,1,1,'Geen agitatie kennen','In welke mate en hoe dikwijls men kwaadheid voelt en ervaart',3.624),(65,3,3,'Niet kwetsbaar zijn','In welke mate men zich kan open stellen voor anderen.',5.468),(68,4562,6,'Groepsomgang','In welke mate men op zoek gaat naar sociaal contact en zich daar goed bij voelt.',5.708),(70,5,5,'Activiteitsniveau','De hoeveelheid van energie die men heeft om activiteiten aan te pakken.',3.592);
/*!40000 ALTER TABLE `testresults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Pepe@ukee.nl','Pepe','Perez'),(2,'Carlos@ukee.nl','Carlos','Fer'),(3,'Luis@ukee.nl','Luis','Zea'),(4,'Juan@ukee.nl','Juan','red'),(5,'Marcos@ukee.nl','Marcos','Lee'),(4562,'Pepe@ukee.nl','Pepe','Perez');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-15 20:55:03
