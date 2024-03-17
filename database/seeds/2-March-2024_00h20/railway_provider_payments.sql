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
-- Table structure for table `provider_payments`
--

DROP TABLE IF EXISTS `provider_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` double(15,2) NOT NULL,
  `provider_current_claims` double(15,2) DEFAULT NULL,
  `provider_new_claims` double(15,2) DEFAULT NULL,
  `system_current_depts` double(15,2) DEFAULT NULL,
  `system_new_depts` double(15,2) DEFAULT NULL,
  `status` set('pending','aproved','completed') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `provider_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_payments`
--

LOCK TABLES `provider_payments` WRITE;
/*!40000 ALTER TABLE `provider_payments` DISABLE KEYS */;
INSERT INTO `provider_payments` VALUES (1,985500.00,985500.00,0.00,8409810.15,7424310.15,'completed','test',1,3,'2023-12-08 22:50:51','2023-12-08 22:50:51'),(2,985500.00,985500.00,0.00,8409810.15,7424310.15,'completed','test',1,3,'2023-12-08 22:50:51','2023-12-08 22:50:51'),(3,41909.70,41909.70,0.00,7424310.15,7382400.45,'completed','test',1,2,'2023-12-08 22:53:48','2023-12-08 22:53:48'),(4,41909.70,41909.70,0.00,7424310.15,7382400.45,'completed','test',1,2,'2023-12-08 22:53:48','2023-12-08 22:53:48'),(5,9402804.00,9402804.15,0.15,9692306.55,289502.55,'completed','test',1,1,'2023-12-19 04:50:35','2023-12-19 04:50:35'),(6,250000.00,421500.00,171500.00,289502.55,39502.55,'completed','test',1,3,'2023-12-19 04:52:49','2023-12-19 04:52:49'),(7,171500.00,171500.00,0.00,5143815.15,4972315.15,'completed','test',1,3,'2024-01-05 12:19:13','2024-01-05 12:19:13'),(8,4000000.00,5104312.75,1104312.75,4972315.15,972315.15,'completed','test',1,1,'2024-01-05 12:20:00','2024-01-05 12:20:00'),(9,156000.00,262592.50,106592.50,8269337.09,8113337.09,'completed','test',1,6,'2024-02-09 05:35:07','2024-02-09 05:35:07'),(10,6312058.00,6312058.52,0.52,8113337.09,1801279.09,'completed','test',1,1,'2024-02-09 05:35:56','2024-02-09 05:35:56'),(11,1000000.00,1334037.67,334037.67,1801279.09,801279.09,'completed','test',1,3,'2024-02-09 05:36:31','2024-02-09 05:36:31'),(12,228644.00,492646.00,264002.00,801279.09,572635.09,'completed','test',1,5,'2024-02-09 05:38:25','2024-02-09 05:38:25'),(13,1000000.00,1583273.96,583273.96,2590994.93,1590994.93,'completed','test',1,1,'2024-02-16 17:23:14','2024-02-16 17:23:14');
/*!40000 ALTER TABLE `provider_payments` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-02  0:23:37
