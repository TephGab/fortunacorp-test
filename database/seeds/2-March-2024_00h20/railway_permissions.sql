-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: monorail.proxy.rlwy.net    Database: railway
-- ------------------------------------------------------
-- Server version	8.3.0

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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'create user','web','2023-07-09 16:32:31','2023-07-09 16:32:31'),(2,'read user','web','2023-07-09 16:32:31','2023-07-09 16:32:31'),(3,'update user','web','2023-07-09 16:32:31','2023-07-09 16:32:31'),(4,'delete user','web','2023-07-09 16:32:32','2023-07-09 16:32:32'),(5,'create transaction','web','2023-07-09 16:32:32','2023-07-09 16:32:32'),(6,'read transaction','web','2023-07-09 16:32:32','2023-07-09 16:32:32'),(7,'update transaction','web','2023-07-09 16:32:32','2023-07-09 16:32:32'),(8,'delete transaction','web','2023-07-09 16:32:32','2023-07-09 16:32:32'),(10,'approve transaction','web','2023-08-29 12:01:26','2023-08-29 12:01:26'),(12,'review transaction','web','2023-08-29 13:08:59','2023-08-29 13:08:59'),(13,'update account','web','2023-08-29 13:47:47','2023-08-29 13:47:47'),(14,'delete account','web','2023-08-29 13:59:25','2023-08-29 13:59:25'),(15,'view activity','web','2023-08-29 23:46:25','2023-08-29 23:46:25'),(16,'confirm cashout','web','2023-09-01 01:33:16','2023-09-01 01:33:16'),(17,'reset password','web','2023-09-01 01:35:29','2023-09-01 01:35:29'),(18,'create account','web','2023-09-01 02:24:35','2023-09-01 02:24:35'),(19,'transfert_between account','web','2023-09-01 02:24:45','2023-09-01 02:24:45'),(20,'export_pdf transaction','web','2023-09-06 15:28:44','2023-09-06 15:28:44'),(21,'create permission','web','2023-09-06 18:56:39','2023-09-06 18:56:39'),(22,'create role','web','2023-09-06 18:56:51','2023-09-06 18:56:51'),(23,'add_super_admin permission','web','2023-09-06 18:57:24','2023-09-06 18:57:24'),(24,'make replenishment','web','2023-09-11 22:31:52','2023-09-11 22:31:52'),(25,'confirm replenishment','web','2023-09-11 22:32:14','2023-09-11 22:32:14');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-02  0:20:15
