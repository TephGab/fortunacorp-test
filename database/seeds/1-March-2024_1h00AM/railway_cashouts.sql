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
-- Table structure for table `cashouts`
--

DROP TABLE IF EXISTS `cashouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cashouts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` double(15,2) DEFAULT NULL,
  `status` set('pending','completed','partially_completed','confirmed') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `type` set('user_account','user_bank_account') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'user_account',
  `currency` set('us','htg','pesos') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pesos',
  `comment` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `user_bank_account_id` int DEFAULT NULL,
  `system_bank_account_id` int DEFAULT NULL,
  `user_account_id` int DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `envoy_id` int DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `user_in_person` tinyint(1) DEFAULT '0',
  `completed_by_admin` tinyint(1) DEFAULT '0',
  `confirmed_by_user` tinyint(1) DEFAULT '0',
  `confirmed_by_envoy` tinyint(1) DEFAULT '0',
  `admin_completion_date` timestamp NULL DEFAULT NULL,
  `user_confirmation_date` timestamp NULL DEFAULT NULL,
  `envoy_confirmation_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_balance` double(15,2) DEFAULT NULL,
  `new_balance` double(15,2) DEFAULT NULL,
  `user_role` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'operator',
  `use_envoy_debt` tinyint(1) DEFAULT '1',
  `receiver_confirmation_date` timestamp NULL DEFAULT NULL,
  `commission_category` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'commission',
  PRIMARY KEY (`id`),
  KEY `cashouts_user_id_foreign` (`user_id`),
  CONSTRAINT `cashouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cashouts`
--

LOCK TABLES `cashouts` WRITE;
/*!40000 ALTER TABLE `cashouts` DISABLE KEYS */;
INSERT INTO `cashouts` VALUES (5,1730.00,'confirmed','user_account','pesos',NULL,NULL,NULL,10,1,154,152,0,1,1,1,'2023-10-19 16:57:33','2023-10-19 16:58:07','2023-10-19 16:58:05','2023-10-19 16:56:07','2023-10-19 16:58:07',0.00,-1730.00,'operator',1,NULL,'commission'),(9,8131.00,'confirmed','user_account','pesos',NULL,NULL,NULL,8,1,155,150,0,1,1,1,'2023-10-28 22:25:17','2023-10-28 22:25:54','2023-10-28 22:25:46','2023-10-28 22:24:56','2023-10-28 22:25:54',0.75,-8130.25,'operator',1,NULL,'commission'),(10,7500.00,'confirmed','user_account','pesos',NULL,NULL,NULL,10,1,252,152,NULL,1,1,1,'2023-12-03 12:17:24','2023-12-03 12:22:45','2023-12-03 12:21:15','2023-11-15 07:54:35','2023-12-03 12:22:45',NULL,NULL,'operator',1,NULL,'commission'),(11,14000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,8,1,155,150,NULL,1,1,1,'2023-12-03 12:17:07','2023-12-03 12:27:15','2023-12-03 12:21:51','2023-11-28 11:37:04','2023-12-03 12:27:15',NULL,NULL,'operator',1,NULL,'commission'),(12,10500.00,'confirmed','user_account','pesos',NULL,NULL,NULL,9,1,252,151,NULL,1,1,1,'2023-12-03 12:16:56','2023-12-13 20:25:55','2023-12-03 12:21:17','2023-11-30 12:28:13','2023-12-13 20:25:55',NULL,NULL,'operator',1,NULL,'commission'),(13,5000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,119,1,252,261,NULL,1,1,1,'2023-12-03 12:16:46','2023-12-07 10:19:22','2023-12-03 12:21:19','2023-12-03 09:11:21','2023-12-07 10:19:22',NULL,NULL,'operator',1,NULL,'commission'),(14,2100.00,'confirmed','user_account','pesos',NULL,NULL,NULL,10,1,252,152,NULL,1,1,1,'2023-12-05 10:10:23','2023-12-05 13:16:55','2023-12-05 13:16:44','2023-12-04 18:34:06','2023-12-05 13:16:55',NULL,NULL,'operator',1,NULL,'commission'),(15,4000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,9,1,252,151,NULL,1,1,1,'2023-12-13 20:28:38','2023-12-13 20:34:21','2023-12-13 20:33:47','2023-12-13 20:27:02','2023-12-13 20:34:21',NULL,NULL,'operator',1,NULL,'commission'),(16,5460.00,'confirmed','user_account','pesos',NULL,NULL,NULL,111,1,252,253,NULL,1,1,1,'2023-12-24 14:27:17','2024-01-01 16:41:03','2023-12-28 07:08:27','2023-12-21 03:15:49','2024-01-01 16:41:03',NULL,NULL,'operator',1,NULL,'commission'),(17,12743.00,'confirmed','user_account','pesos',NULL,NULL,NULL,8,1,252,150,NULL,1,1,1,'2023-12-29 00:56:19','2023-12-30 17:12:47','2023-12-29 00:56:56','2023-12-24 19:55:12','2023-12-30 17:12:47',NULL,NULL,'operator',1,NULL,'commission'),(18,3800.00,'confirmed','user_account','pesos',NULL,NULL,NULL,119,1,252,261,NULL,1,1,1,'2023-12-29 00:56:27','2024-01-01 23:17:11','2023-12-29 00:56:57','2023-12-27 21:23:33','2024-01-01 23:17:11',NULL,NULL,'operator',1,NULL,'commission'),(19,5000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,9,1,252,151,NULL,1,1,1,'2023-12-29 00:56:34','2023-12-29 11:14:42','2023-12-29 00:56:57','2023-12-28 21:50:51','2023-12-29 11:14:42',NULL,NULL,'operator',1,NULL,'commission'),(20,2257.00,'confirmed','user_account','pesos',NULL,NULL,NULL,8,1,252,150,NULL,1,1,1,'2024-01-04 12:11:56',NULL,'2024-01-13 23:00:37','2023-12-30 17:14:38','2024-01-13 23:02:05',NULL,NULL,'operator',1,'2024-01-13 23:02:05','commission'),(22,10000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,119,1,252,261,NULL,1,1,1,'2024-01-04 12:11:02','2024-01-06 20:19:48','2024-01-06 20:19:25','2024-01-03 11:05:29','2024-01-06 20:19:48',NULL,NULL,'operator',1,NULL,'commission'),(23,4500.00,'confirmed','user_account','pesos',NULL,NULL,NULL,10,1,252,152,NULL,1,1,1,'2024-01-04 14:10:16','2024-01-07 08:27:12','2024-01-06 20:19:28','2024-01-04 12:15:48','2024-01-07 08:27:12',NULL,NULL,'operator',1,NULL,'commission'),(24,1106.69,'confirmed','user_account','pesos',NULL,NULL,NULL,13,1,155,155,NULL,1,1,1,'2024-01-04 14:10:02','2024-01-04 14:10:47','2024-01-04 14:10:47','2024-01-04 14:08:37','2024-01-04 14:10:47',NULL,NULL,'envoy',1,NULL,'commission'),(25,5000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,109,1,155,251,NULL,1,1,1,'2024-01-13 08:37:00',NULL,'2024-01-18 08:58:33','2024-01-06 20:18:00','2024-01-18 08:58:45',NULL,NULL,'operator',1,'2024-01-18 08:58:45','commission'),(26,4181.84,'confirmed','user_account','pesos',NULL,NULL,NULL,14,1,156,156,NULL,1,1,1,'2024-01-08 00:10:31','2024-01-08 00:10:54','2024-01-08 00:10:54','2024-01-08 00:09:32','2024-01-08 00:10:54',NULL,NULL,'envoy',1,NULL,'commission'),(27,1702.00,'confirmed','user_account','pesos',NULL,NULL,NULL,8,1,252,150,NULL,1,1,1,'2024-01-31 11:47:06',NULL,'2024-01-31 12:09:19','2024-01-26 12:14:54','2024-01-31 12:59:25',NULL,NULL,'operator',1,'2024-01-31 12:59:25','commission'),(28,910.00,'pending','user_account','pesos',NULL,NULL,NULL,111,1,252,253,NULL,1,0,0,'2024-02-10 20:29:19',NULL,NULL,'2024-01-30 20:04:46','2024-02-10 20:29:19',NULL,NULL,'operator',1,NULL,'commission'),(29,4500.00,'confirmed','user_account','pesos',NULL,NULL,NULL,134,1,252,277,NULL,1,1,1,'2024-02-10 20:29:29',NULL,'2024-02-22 20:17:42','2024-02-05 17:20:10','2024-02-28 17:53:01',NULL,NULL,'operator',1,'2024-02-28 17:53:01','commission'),(30,10000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,119,1,252,261,NULL,1,1,1,'2024-02-10 20:29:08',NULL,'2024-02-20 19:18:19','2024-02-07 09:46:59','2024-02-24 19:35:30',NULL,NULL,'operator',1,'2024-02-24 19:35:30','commission'),(31,2700.00,'confirmed','user_account','pesos',NULL,NULL,NULL,10,1,252,152,NULL,1,1,1,'2024-02-09 14:23:56',NULL,'2024-02-09 14:27:36','2024-02-07 15:25:34','2024-02-09 14:28:17',NULL,NULL,'operator',1,'2024-02-09 14:28:17','commission'),(32,290.00,'confirmed','user_account','pesos',NULL,NULL,NULL,10,1,252,152,NULL,1,1,1,'2024-02-09 14:33:52',NULL,'2024-02-09 14:34:58','2024-02-09 14:29:44','2024-02-09 14:35:57',NULL,NULL,'operator',1,'2024-02-09 14:35:57','commission'),(33,10000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,109,147,252,251,NULL,1,1,1,'2024-02-16 09:02:55',NULL,'2024-02-20 19:18:13','2024-02-11 14:01:56','2024-02-24 19:35:14',NULL,NULL,'operator',1,'2024-02-24 19:35:14','commission'),(34,10000.00,'pending','user_account','pesos',NULL,NULL,NULL,9,149,155,151,NULL,1,0,0,'2024-03-01 01:43:26',NULL,NULL,'2024-02-23 21:14:42','2024-03-01 01:43:26',NULL,NULL,'operator',1,NULL,'commission');
/*!40000 ALTER TABLE `cashouts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-01  1:02:20
