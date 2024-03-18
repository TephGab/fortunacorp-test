-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
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
-- Table structure for table `agent_loans`
--

DROP TABLE IF EXISTS `agent_loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_loans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` double(15,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `type` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT 'start_loan',
  `currency` set('us','htg','pesos') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pesos',
  `user_id` bigint unsigned NOT NULL,
  `receiver_id` int NOT NULL,
  `comment` text COLLATE utf8mb3_unicode_ci,
  `confirmed_by_receiver` tinyint(1) DEFAULT '0',
  `receiver_confirmation_date` timestamp NULL DEFAULT NULL,
  `payment_progress` int DEFAULT '0',
  `payment_status` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT 'unpaid',
  `commission_payment` double(15,2) DEFAULT '0.00',
  `deposit_payment` double(15,2) DEFAULT '0.00',
  `loan_percentage` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_loans_user_id_foreign` (`user_id`),
  CONSTRAINT `agent_loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_loans`
--

LOCK TABLES `agent_loans` WRITE;
/*!40000 ALTER TABLE `agent_loans` DISABLE KEYS */;
INSERT INTO `agent_loans` VALUES (1,10000.00,'confirmed','start_loan','pesos',1,315,NULL,1,'2024-02-23 15:17:11',0,'unpaid',0.00,0.00,50,'2024-02-23 15:16:53','2024-02-23 15:17:11'),(2,5000.00,'confirmed','start_loan','pesos',1,328,NULL,1,'2024-02-23 17:50:25',0,'unpaid',0.00,0.00,0,'2024-02-23 17:48:22','2024-02-23 17:50:25'),(4,10000.00,'confirmed','start_loan','pesos',1,278,NULL,1,'2024-02-23 18:15:40',0,'unpaid',0.00,0.00,50,'2024-02-23 18:15:06','2024-02-23 18:15:40'),(5,21500.00,'confirmed','start_loan','pesos',1,159,NULL,1,'2024-02-23 19:16:29',0,'unpaid',0.00,0.00,50,'2024-02-23 19:13:40','2024-02-23 19:16:29'),(6,10000.00,'confirmed','start_loan','pesos',146,226,NULL,1,'2024-02-23 20:46:41',0,'unpaid',0.00,0.00,50,'2024-02-23 20:42:36','2024-02-23 20:46:41'),(7,10000.00,'confirmed','start_loan','pesos',146,201,NULL,1,'2024-02-23 20:58:41',0,'unpaid',0.00,0.00,50,'2024-02-23 20:57:45','2024-02-23 20:58:41'),(8,10000.00,'confirmed','start_loan','pesos',146,219,NULL,1,'2024-02-23 21:27:39',0,'unpaid',0.00,0.00,50,'2024-02-23 21:24:35','2024-02-23 21:27:39'),(9,30000.00,'confirmed','start_loan','pesos',1,160,NULL,1,'2024-02-23 22:54:28',0,'unpaid',0.00,0.00,50,'2024-02-23 22:53:58','2024-02-23 22:54:28'),(10,50000.00,'confirmed','start_loan','pesos',143,158,NULL,1,'2024-02-24 01:07:17',0,'unpaid',0.00,0.00,50,'2024-02-24 01:06:17','2024-02-24 01:07:17'),(11,10000.00,'confirmed','start_loan','pesos',1,321,NULL,1,'2024-02-24 13:27:38',0,'unpaid',0.00,0.00,50,'2024-02-24 13:27:21','2024-02-24 13:27:38'),(12,11500.00,'confirmed','start_loan','pesos',143,191,NULL,1,'2024-02-25 10:20:39',0,'unpaid',0.00,0.00,50,'2024-02-25 10:20:02','2024-02-25 10:20:39'),(13,5000.00,'confirmed','start_loan','pesos',1,270,NULL,1,'2024-02-25 17:09:19',0,'unpaid',0.00,0.00,50,'2024-02-25 17:04:57','2024-02-25 17:09:19'),(14,5000.00,'confirmed','start_loan','pesos',1,311,NULL,1,'2024-02-25 17:51:05',0,'unpaid',0.00,0.00,50,'2024-02-25 17:50:07','2024-02-25 17:51:05'),(15,12000.00,'confirmed','start_loan','pesos',1,281,NULL,1,'2024-02-25 18:50:46',0,'unpaid',0.00,0.00,50,'2024-02-25 18:50:15','2024-02-25 18:50:46'),(16,10000.00,'confirmed','start_loan','pesos',1,258,NULL,1,'2024-02-25 20:04:32',0,'unpaid',0.00,0.00,50,'2024-02-25 20:02:41','2024-02-25 20:04:32'),(17,9900.00,'confirmed','start_loan','pesos',1,181,NULL,1,'2024-02-25 23:02:46',0,'unpaid',0.00,0.00,50,'2024-02-25 23:02:34','2024-02-25 23:02:46'),(18,10000.00,'confirmed','start_loan','pesos',1,170,NULL,1,'2024-02-26 11:51:04',0,'unpaid',0.00,0.00,50,'2024-02-26 11:50:53','2024-02-26 11:51:04'),(19,5000.00,'confirmed','start_loan','pesos',1,172,NULL,1,'2024-02-26 13:24:42',0,'unpaid',0.00,0.00,50,'2024-02-26 13:23:50','2024-02-26 13:24:42'),(20,5000.00,'confirmed','start_loan','pesos',1,189,NULL,1,'2024-02-26 18:05:46',0,'unpaid',0.00,0.00,50,'2024-02-26 18:04:33','2024-02-26 18:05:46'),(21,10000.00,'confirmed','start_loan','pesos',1,258,NULL,1,'2024-02-27 10:43:37',0,'unpaid',0.00,0.00,50,'2024-02-27 10:29:22','2024-02-27 10:43:37'),(22,5000.00,'confirmed','start_loan','pesos',1,232,NULL,1,'2024-02-27 11:08:50',0,'unpaid',0.00,0.00,50,'2024-02-27 11:08:32','2024-02-27 11:08:50'),(23,1000.00,'confirmed','start_loan','pesos',1,221,NULL,1,'2024-02-27 14:22:19',0,'unpaid',0.00,0.00,0,'2024-02-27 11:31:16','2024-02-27 14:22:19'),(24,20000.00,'confirmed','start_loan','pesos',1,166,NULL,1,'2024-02-27 11:40:28',0,'unpaid',0.00,0.00,50,'2024-02-27 11:40:07','2024-02-27 11:40:28'),(25,3500.00,'confirmed','start_loan','pesos',1,272,NULL,1,'2024-02-27 12:28:35',0,'unpaid',0.00,0.00,0,'2024-02-27 12:21:45','2024-02-27 12:28:35'),(26,16000.00,'confirmed','start_loan','pesos',1,217,NULL,1,'2024-02-27 20:24:18',0,'unpaid',0.00,0.00,0,'2024-02-27 12:22:12','2024-02-27 20:24:18'),(27,10000.00,'confirmed','start_loan','pesos',1,153,NULL,1,'2024-02-27 13:31:10',0,'unpaid',0.00,0.00,100,'2024-02-27 12:40:14','2024-02-27 13:31:10'),(28,15000.00,'confirmed','start_loan','pesos',1,204,NULL,1,'2024-02-27 13:07:26',0,'unpaid',0.00,0.00,50,'2024-02-27 12:59:48','2024-02-27 13:07:26'),(29,15000.00,'confirmed','start_loan','pesos',1,199,NULL,1,'2024-02-27 16:57:18',0,'unpaid',0.00,0.00,50,'2024-02-27 16:56:38','2024-02-27 16:57:18'),(30,5000.00,'confirmed','start_loan','pesos',1,199,NULL,1,'2024-02-27 17:01:17',0,'unpaid',0.00,0.00,50,'2024-02-27 17:00:50','2024-02-27 17:01:17'),(31,40000.00,'confirmed','start_loan','pesos',1,158,NULL,1,'2024-02-27 18:51:58',0,'unpaid',0.00,0.00,50,'2024-02-27 18:16:53','2024-02-27 18:51:58'),(32,4000.00,'confirmed','start_loan','pesos',1,211,NULL,1,'2024-02-27 18:58:31',0,'unpaid',0.00,0.00,50,'2024-02-27 18:57:28','2024-02-27 18:58:31'),(33,1000.00,'confirmed','start_loan','pesos',1,233,NULL,1,'2024-02-27 23:48:56',0,'unpaid',0.00,0.00,0,'2024-02-27 23:48:47','2024-02-27 23:48:56'),(34,1000.00,'confirmed','start_loan','pesos',1,281,NULL,1,'2024-02-28 00:05:56',0,'unpaid',0.00,0.00,50,'2024-02-28 00:04:24','2024-02-28 00:05:56'),(35,5000.00,'confirmed','start_loan','pesos',1,311,NULL,1,'2024-02-28 08:59:08',0,'unpaid',0.00,0.00,50,'2024-02-28 08:42:58','2024-02-28 08:59:08'),(36,10000.00,'confirmed','start_loan','pesos',1,200,NULL,1,'2024-02-28 13:12:55',0,'unpaid',0.00,0.00,50,'2024-02-28 12:50:42','2024-02-28 13:12:55'),(37,8000.00,'confirmed','start_loan','pesos',1,212,NULL,1,'2024-02-28 17:11:26',0,'unpaid',0.00,0.00,0,'2024-02-28 16:58:36','2024-02-28 17:11:26'),(38,10300.00,'confirmed','start_loan','pesos',147,158,NULL,1,'2024-03-03 09:40:29',0,'unpaid',0.00,0.00,50,'2024-02-28 21:21:23','2024-03-03 09:40:29'),(39,10300.00,'confirmed','start_loan','pesos',147,170,NULL,1,'2024-02-28 21:43:30',0,'unpaid',0.00,0.00,50,'2024-02-28 21:25:30','2024-02-28 21:43:30'),(40,16000.00,'confirmed','start_loan','pesos',1,174,NULL,1,'2024-02-28 22:48:24',0,'unpaid',0.00,0.00,50,'2024-02-28 22:48:13','2024-02-28 22:48:24'),(41,15000.00,'confirmed','start_loan','pesos',1,275,NULL,1,'2024-02-28 22:51:52',0,'unpaid',0.00,0.00,50,'2024-02-28 22:51:44','2024-02-28 22:51:52'),(42,5000.00,'confirmed','start_loan','pesos',147,222,NULL,1,'2024-02-29 13:47:08',0,'unpaid',0.00,0.00,0,'2024-02-29 13:32:37','2024-02-29 13:47:08'),(43,2000.00,'confirmed','start_loan','pesos',147,272,NULL,1,'2024-02-29 17:33:58',0,'unpaid',0.00,0.00,0,'2024-02-29 17:22:13','2024-02-29 17:33:58'),(44,3000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-02-29 20:55:06',0,'unpaid',0.00,0.00,50,'2024-02-29 19:33:00','2024-02-29 20:55:06'),(45,10500.00,'confirmed','start_loan','pesos',147,270,NULL,1,'2024-02-29 20:16:54',0,'unpaid',0.00,0.00,50,'2024-02-29 20:16:07','2024-02-29 20:16:54'),(46,1500.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-02-29 22:33:33',0,'unpaid',0.00,0.00,50,'2024-02-29 22:09:21','2024-02-29 22:33:33'),(47,3600.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-01 11:03:41',0,'unpaid',0.00,0.00,0,'2024-03-01 10:39:05','2024-03-01 11:03:41'),(48,1625.00,'confirmed','start_loan','pesos',147,211,NULL,1,'2024-03-01 12:41:10',0,'unpaid',0.00,0.00,50,'2024-03-01 12:31:04','2024-03-01 12:41:10'),(49,2500.00,'confirmed','start_loan','pesos',147,168,NULL,1,'2024-03-01 14:04:51',0,'unpaid',0.00,0.00,0,'2024-03-01 13:59:16','2024-03-01 14:04:51'),(50,7000.00,'confirmed','start_loan','pesos',1,195,NULL,1,'2024-03-01 22:40:34',0,'unpaid',0.00,0.00,50,'2024-03-01 22:39:58','2024-03-01 22:40:34'),(51,14700.00,'confirmed','start_loan','pesos',147,170,NULL,1,'2024-03-02 09:15:07',0,'unpaid',0.00,0.00,50,'2024-03-02 09:09:31','2024-03-02 09:15:07'),(52,1870.00,'confirmed','start_loan','pesos',147,272,NULL,1,'2024-03-02 12:57:51',0,'unpaid',0.00,0.00,0,'2024-03-02 12:38:08','2024-03-02 12:57:51'),(53,10000.00,'confirmed','start_loan','pesos',147,315,NULL,1,'2024-03-02 13:10:58',0,'unpaid',0.00,0.00,50,'2024-03-02 12:41:23','2024-03-02 13:10:58'),(54,4000.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-02 13:38:18',0,'unpaid',0.00,0.00,0,'2024-03-02 12:45:31','2024-03-02 13:38:18'),(55,6000.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-02 15:43:12',0,'unpaid',0.00,0.00,0,'2024-03-02 15:40:14','2024-03-02 15:43:12'),(56,1500.00,'confirmed','start_loan','pesos',147,331,NULL,1,'2024-03-02 20:29:32',0,'unpaid',0.00,0.00,0,'2024-03-02 19:52:21','2024-03-02 20:29:32'),(57,28000.00,'confirmed','start_loan','pesos',147,217,NULL,1,'2024-03-02 20:34:43',0,'unpaid',0.00,0.00,0,'2024-03-02 20:17:18','2024-03-02 20:34:43'),(58,20000.00,'confirmed','start_loan','pesos',1,274,NULL,1,'2024-03-02 22:09:59',0,'unpaid',0.00,0.00,50,'2024-03-02 22:09:46','2024-03-02 22:09:59'),(59,8000.00,'confirmed','start_loan','pesos',147,158,NULL,1,'2024-03-03 10:18:24',0,'unpaid',0.00,0.00,50,'2024-03-03 09:41:33','2024-03-03 10:18:24'),(60,5000.00,'confirmed','start_loan','pesos',146,308,NULL,1,'2024-03-03 10:35:15',0,'unpaid',0.00,0.00,0,'2024-03-03 10:31:26','2024-03-03 10:35:15'),(61,4000.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-03 11:15:03',0,'unpaid',0.00,0.00,0,'2024-03-03 10:56:02','2024-03-03 11:15:03'),(62,61700.00,'confirmed','start_loan','pesos',147,158,NULL,1,'2024-03-03 11:54:43',0,'unpaid',0.00,0.00,50,'2024-03-03 11:42:06','2024-03-03 11:54:43'),(63,10000.00,'confirmed','start_loan','pesos',1,346,NULL,1,'2024-03-03 11:46:16',0,'unpaid',0.00,0.00,50,'2024-03-03 11:45:18','2024-03-03 11:46:16'),(64,19500.00,'confirmed','start_loan','pesos',147,212,NULL,1,'2024-03-03 12:02:15',0,'unpaid',0.00,0.00,0,'2024-03-03 12:01:08','2024-03-03 12:02:15'),(65,2100.00,'confirmed','start_loan','pesos',147,313,NULL,1,'2024-03-03 13:31:45',0,'unpaid',0.00,0.00,0,'2024-03-03 13:30:12','2024-03-03 13:31:45'),(66,5200.00,'confirmed','start_loan','pesos',147,192,NULL,1,'2024-03-03 13:56:01',0,'unpaid',0.00,0.00,0,'2024-03-03 13:38:42','2024-03-03 13:56:01'),(67,3150.00,'confirmed','start_loan','pesos',147,171,NULL,1,'2024-03-04 13:25:02',0,'unpaid',0.00,0.00,0,'2024-03-03 14:03:20','2024-03-04 13:25:02'),(68,5000.00,'confirmed','start_loan','pesos',1,208,NULL,1,'2024-03-03 15:31:47',0,'unpaid',0.00,0.00,50,'2024-03-03 15:28:48','2024-03-03 15:31:47'),(69,2000.00,'confirmed','start_loan','pesos',147,223,NULL,1,'2024-03-03 16:32:55',0,'unpaid',0.00,0.00,0,'2024-03-03 16:07:51','2024-03-03 16:32:55'),(70,10000.00,'confirmed','start_loan','pesos',1,302,NULL,1,'2024-03-03 16:28:43',0,'unpaid',0.00,0.00,50,'2024-03-03 16:24:36','2024-03-03 16:28:43'),(71,4000.00,'confirmed','start_loan','pesos',147,324,NULL,1,'2024-03-03 16:55:07',0,'unpaid',0.00,0.00,0,'2024-03-03 16:37:12','2024-03-03 16:55:07'),(72,35000.00,'confirmed','start_loan','pesos',147,169,NULL,1,'2024-03-03 17:18:51',0,'unpaid',0.00,0.00,0,'2024-03-03 17:17:24','2024-03-03 17:18:51'),(73,11000.00,'confirmed','start_loan','pesos',146,231,NULL,1,'2024-03-03 17:38:46',0,'unpaid',0.00,0.00,0,'2024-03-03 17:37:25','2024-03-03 17:38:46'),(74,10000.00,'confirmed','start_loan','pesos',147,170,NULL,1,'2024-03-03 18:19:46',0,'unpaid',0.00,0.00,50,'2024-03-03 18:18:26','2024-03-03 18:19:46'),(75,5500.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-03 19:53:45',0,'unpaid',0.00,0.00,50,'2024-03-03 18:42:41','2024-03-03 19:53:45'),(76,20000.00,'confirmed','start_loan','pesos',147,158,NULL,1,'2024-03-03 21:58:21',0,'unpaid',0.00,0.00,50,'2024-03-03 21:54:42','2024-03-03 21:58:21'),(77,3000.00,'confirmed','start_loan','pesos',147,222,NULL,1,'2024-03-04 09:05:44',0,'unpaid',0.00,0.00,0,'2024-03-04 09:05:09','2024-03-04 09:05:44'),(78,2000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-04 09:08:03',0,'unpaid',0.00,0.00,50,'2024-03-04 09:06:59','2024-03-04 09:08:03'),(79,7500.00,'confirmed','start_loan','pesos',147,258,NULL,1,'2024-03-04 09:29:25',0,'unpaid',0.00,0.00,50,'2024-03-04 09:21:59','2024-03-04 09:29:25'),(80,5000.00,'confirmed','start_loan','pesos',147,315,NULL,1,'2024-03-04 10:19:42',0,'unpaid',0.00,0.00,50,'2024-03-04 10:12:40','2024-03-04 10:19:42'),(81,20000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-04 10:48:21',0,'unpaid',0.00,0.00,0,'2024-03-04 10:34:00','2024-03-04 10:48:21'),(82,8000.00,'confirmed','start_loan','pesos',146,179,NULL,1,'2024-03-17 14:22:35',0,'unpaid',0.00,0.00,0,'2024-03-04 12:06:49','2024-03-17 14:22:35'),(83,8000.00,'confirmed','start_loan','pesos',146,179,NULL,1,'2024-03-06 16:26:02',0,'unpaid',0.00,0.00,0,'2024-03-04 12:09:11','2024-03-06 16:26:02'),(84,8000.00,'confirmed','start_loan','pesos',146,179,NULL,1,'2024-03-04 12:36:32',0,'unpaid',0.00,0.00,0,'2024-03-04 12:09:12','2024-03-04 12:36:32'),(85,4000.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-04 15:45:09',0,'unpaid',0.00,0.00,0,'2024-03-04 12:43:43','2024-03-04 15:45:09'),(86,1500.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-04 14:10:03',0,'unpaid',0.00,0.00,50,'2024-03-04 13:41:44','2024-03-04 14:10:03'),(87,5000.00,'confirmed','start_loan','pesos',1,344,NULL,1,'2024-03-04 20:14:39',0,'unpaid',0.00,0.00,50,'2024-03-04 20:12:27','2024-03-04 20:14:39'),(88,20000.00,'confirmed','start_loan','pesos',146,231,NULL,1,'2024-03-05 11:46:41',0,'unpaid',0.00,0.00,0,'2024-03-05 11:15:28','2024-03-05 11:46:41'),(89,1000.00,'confirmed','start_loan','pesos',147,298,NULL,1,'2024-03-05 11:29:42',0,'unpaid',0.00,0.00,0,'2024-03-05 11:20:14','2024-03-05 11:29:42'),(90,12000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-05 11:30:55',0,'unpaid',0.00,0.00,0,'2024-03-05 11:29:27','2024-03-05 11:30:55'),(91,5000.00,'confirmed','start_loan','pesos',1,298,NULL,1,'2024-03-05 11:49:05',0,'unpaid',0.00,0.00,50,'2024-03-05 11:48:45','2024-03-05 11:49:05'),(92,10000.00,'confirmed','start_loan','pesos',147,158,NULL,1,'2024-03-05 12:23:41',0,'unpaid',0.00,0.00,50,'2024-03-05 12:22:54','2024-03-05 12:23:41'),(93,10000.00,'confirmed','start_loan','pesos',1,290,NULL,1,'2024-03-05 15:20:11',0,'unpaid',0.00,0.00,50,'2024-03-05 15:19:33','2024-03-05 15:20:11'),(94,20000.00,'confirmed','start_loan','pesos',146,162,NULL,1,'2024-03-05 16:01:35',0,'unpaid',0.00,0.00,0,'2024-03-05 15:22:19','2024-03-05 16:01:35'),(95,1500.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-05 15:31:45',0,'unpaid',0.00,0.00,0,'2024-03-05 15:28:24','2024-03-05 15:31:45'),(96,15000.00,'confirmed','start_loan','pesos',147,170,NULL,1,'2024-03-05 19:03:45',0,'unpaid',0.00,0.00,50,'2024-03-05 19:00:45','2024-03-05 19:03:45'),(97,6000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-05 21:24:30',0,'unpaid',0.00,0.00,0,'2024-03-05 19:36:21','2024-03-05 21:24:30'),(98,2200.00,'confirmed','start_loan','pesos',147,192,NULL,1,'2024-03-05 21:17:46',0,'unpaid',0.00,0.00,0,'2024-03-05 21:15:29','2024-03-05 21:17:46'),(99,5000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-06 11:14:21',0,'unpaid',0.00,0.00,0,'2024-03-06 11:05:13','2024-03-06 11:14:21'),(100,8000.00,'confirmed','start_loan','pesos',147,223,NULL,1,'2024-03-06 16:34:52',0,'unpaid',0.00,0.00,0,'2024-03-06 15:53:13','2024-03-06 16:34:52'),(101,10000.00,'confirmed','start_loan','pesos',147,250,NULL,1,'2024-03-06 17:04:24',0,'unpaid',0.00,0.00,0,'2024-03-06 16:22:35','2024-03-06 17:04:24'),(102,1600.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-06 17:12:00',0,'unpaid',0.00,0.00,0,'2024-03-06 17:09:01','2024-03-06 17:12:00'),(103,15000.00,'confirmed','start_loan','pesos',1,193,NULL,1,'2024-03-06 17:22:20',0,'unpaid',0.00,0.00,50,'2024-03-06 17:20:47','2024-03-06 17:22:20'),(104,2000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-06 21:18:35',0,'unpaid',0.00,0.00,0,'2024-03-06 21:13:04','2024-03-06 21:18:35'),(105,14500.00,'confirmed','start_loan','pesos',147,217,NULL,1,'2024-03-07 10:20:58',0,'unpaid',0.00,0.00,0,'2024-03-07 10:10:48','2024-03-07 10:20:58'),(106,3000.00,'confirmed','start_loan','pesos',147,232,NULL,1,'2024-03-07 13:40:53',0,'unpaid',0.00,0.00,50,'2024-03-07 10:37:38','2024-03-07 13:40:53'),(107,10000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-07 11:20:53',0,'unpaid',0.00,0.00,0,'2024-03-07 11:11:29','2024-03-07 11:20:53'),(108,1500.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-07 11:54:32',0,'unpaid',0.00,0.00,50,'2024-03-07 11:52:39','2024-03-07 11:54:32'),(109,1500.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-07 13:47:45',0,'unpaid',0.00,0.00,0,'2024-03-07 12:43:29','2024-03-07 13:47:45'),(110,900.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-07 17:31:00',0,'unpaid',0.00,0.00,0,'2024-03-07 17:30:32','2024-03-07 17:31:00'),(111,4000.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-07 18:19:39',0,'unpaid',0.00,0.00,0,'2024-03-07 18:18:48','2024-03-07 18:19:39'),(112,3250.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-07 21:01:34',0,'unpaid',0.00,0.00,0,'2024-03-07 19:32:43','2024-03-07 21:01:34'),(113,11000.00,'confirmed','start_loan','pesos',147,247,NULL,1,'2024-03-07 19:40:18',0,'unpaid',0.00,0.00,0,'2024-03-07 19:37:11','2024-03-07 19:40:18'),(114,3000.00,'confirmed','start_loan','pesos',147,287,NULL,1,'2024-03-07 21:19:12',0,'unpaid',0.00,0.00,0,'2024-03-07 21:05:09','2024-03-07 21:19:12'),(115,2100.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-08 09:27:26',0,'unpaid',0.00,0.00,0,'2024-03-08 09:06:38','2024-03-08 09:27:26'),(116,20000.00,'confirmed','start_loan','pesos',147,211,NULL,1,'2024-03-08 09:27:01',0,'unpaid',0.00,0.00,50,'2024-03-08 09:07:05','2024-03-08 09:27:01'),(117,13700.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-08 13:51:45',0,'unpaid',0.00,0.00,50,'2024-03-08 13:50:21','2024-03-08 13:51:45'),(118,2000.00,'confirmed','start_loan','pesos',146,200,NULL,1,'2024-03-08 15:22:31',0,'unpaid',0.00,0.00,50,'2024-03-08 15:11:38','2024-03-08 15:22:31'),(119,1000.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-08 16:22:17',0,'unpaid',0.00,0.00,0,'2024-03-08 16:21:54','2024-03-08 16:22:17'),(120,2000.00,'confirmed','start_loan','pesos',147,250,NULL,1,'2024-03-08 18:06:22',0,'unpaid',0.00,0.00,0,'2024-03-08 17:30:58','2024-03-08 18:06:22'),(121,10000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-08 18:44:08',0,'unpaid',0.00,0.00,0,'2024-03-08 18:42:58','2024-03-08 18:44:08'),(122,10000.00,'confirmed','start_loan','pesos',1,308,NULL,1,'2024-03-08 22:35:36',0,'unpaid',0.00,0.00,50,'2024-03-08 22:35:09','2024-03-08 22:35:36'),(123,10250.00,'confirmed','start_loan','pesos',147,189,NULL,1,'2024-03-09 11:59:33',0,'unpaid',0.00,0.00,50,'2024-03-09 10:48:36','2024-03-09 11:59:33'),(124,8250.00,'confirmed','start_loan','pesos',147,189,NULL,1,'2024-03-09 11:59:36',0,'unpaid',0.00,0.00,50,'2024-03-09 11:09:44','2024-03-09 11:59:36'),(125,40000.00,'confirmed','start_loan','pesos',1,354,NULL,1,'2024-03-09 12:17:11',0,'unpaid',0.00,0.00,50,'2024-03-09 12:15:53','2024-03-09 12:17:11'),(126,1000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-09 12:54:27',0,'unpaid',0.00,0.00,0,'2024-03-09 12:53:15','2024-03-09 12:54:27'),(127,6000.00,'confirmed','start_loan','pesos',147,315,NULL,1,'2024-03-09 14:04:44',0,'unpaid',0.00,0.00,50,'2024-03-09 14:03:55','2024-03-09 14:04:44'),(128,5000.00,'confirmed','start_loan','pesos',147,315,NULL,1,'2024-03-09 19:12:34',0,'unpaid',0.00,0.00,0,'2024-03-09 18:47:07','2024-03-09 19:12:34'),(129,5000.00,'confirmed','start_loan','pesos',147,324,NULL,1,'2024-03-09 18:51:11',0,'unpaid',0.00,0.00,0,'2024-03-09 18:50:43','2024-03-09 18:51:11'),(130,12700.00,'confirmed','start_loan','pesos',1,272,NULL,1,'2024-03-09 18:56:13',0,'unpaid',0.00,0.00,100,'2024-03-09 18:54:53','2024-03-09 18:56:13'),(131,4000.00,'confirmed','start_loan','pesos',1,272,NULL,1,'2024-03-09 20:13:28',0,'unpaid',0.00,0.00,100,'2024-03-09 20:12:33','2024-03-09 20:13:28'),(132,10000.00,'confirmed','start_loan','pesos',1,355,NULL,1,'2024-03-09 20:49:28',0,'unpaid',0.00,0.00,50,'2024-03-09 20:48:49','2024-03-09 20:49:28'),(133,3000.00,'confirmed','start_loan','pesos',147,169,NULL,1,'2024-03-10 10:46:40',0,'unpaid',0.00,0.00,0,'2024-03-09 20:57:32','2024-03-10 10:46:40'),(134,3000.00,'confirmed','start_loan','pesos',147,326,NULL,1,'2024-03-09 21:07:44',0,'unpaid',0.00,0.00,0,'2024-03-09 21:05:28','2024-03-09 21:07:44'),(135,12500.00,'confirmed','start_loan','pesos',147,170,NULL,1,'2024-03-09 21:20:45',0,'unpaid',0.00,0.00,50,'2024-03-09 21:18:10','2024-03-09 21:20:45'),(136,9000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-09 21:43:57',0,'unpaid',0.00,0.00,0,'2024-03-09 21:37:07','2024-03-09 21:43:57'),(137,3600.00,'confirmed','start_loan','pesos',147,250,NULL,1,'2024-03-09 22:17:07',0,'unpaid',0.00,0.00,0,'2024-03-09 22:13:41','2024-03-09 22:17:07'),(138,38000.00,'confirmed','start_loan','pesos',147,217,NULL,1,'2024-03-10 09:16:03',0,'unpaid',0.00,0.00,0,'2024-03-10 09:09:39','2024-03-10 09:16:03'),(139,50000.00,'confirmed','start_loan','pesos',147,158,NULL,1,'2024-03-10 09:39:58',0,'unpaid',0.00,0.00,50,'2024-03-10 09:38:58','2024-03-10 09:39:58'),(140,21000.00,'confirmed','start_loan','pesos',147,212,NULL,1,'2024-03-10 09:58:27',0,'unpaid',0.00,0.00,0,'2024-03-10 09:50:48','2024-03-10 09:58:27'),(141,27000.00,'confirmed','start_loan','pesos',147,169,NULL,1,'2024-03-10 10:46:39',0,'unpaid',0.00,0.00,0,'2024-03-10 10:46:07','2024-03-10 10:46:39'),(142,10000.00,'confirmed','start_loan','pesos',147,258,NULL,1,'2024-03-10 10:55:12',0,'unpaid',0.00,0.00,50,'2024-03-10 10:54:54','2024-03-10 10:55:12'),(143,5500.00,'confirmed','start_loan','pesos',147,272,NULL,1,'2024-03-10 12:23:52',0,'unpaid',0.00,0.00,100,'2024-03-10 12:17:44','2024-03-10 12:23:52'),(144,9100.00,'confirmed','start_loan','pesos',147,172,NULL,1,'2024-03-10 13:47:48',0,'unpaid',0.00,0.00,50,'2024-03-10 13:42:38','2024-03-10 13:47:48'),(145,10000.00,'confirmed','start_loan','pesos',147,258,NULL,1,'2024-03-10 17:27:18',0,'unpaid',0.00,0.00,0,'2024-03-10 17:25:02','2024-03-10 17:27:18'),(146,4000.00,'confirmed','start_loan','pesos',147,223,NULL,1,'2024-03-10 18:51:52',0,'unpaid',0.00,0.00,0,'2024-03-10 17:25:22','2024-03-10 18:51:52'),(147,3000.00,'confirmed','start_loan','pesos',147,331,NULL,1,'2024-03-10 20:04:03',0,'unpaid',0.00,0.00,0,'2024-03-10 19:55:43','2024-03-10 20:04:03'),(148,1000.00,'confirmed','start_loan','pesos',146,308,NULL,1,'2024-03-10 21:56:11',0,'unpaid',0.00,0.00,0,'2024-03-10 21:39:32','2024-03-10 21:56:11'),(149,10000.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-11 11:53:07',0,'unpaid',0.00,0.00,50,'2024-03-11 11:47:53','2024-03-11 11:53:07'),(150,3000.00,'confirmed','start_loan','pesos',147,331,NULL,1,'2024-03-11 12:31:11',0,'unpaid',0.00,0.00,0,'2024-03-11 12:19:01','2024-03-11 12:31:11'),(151,3800.00,'confirmed','start_loan','pesos',147,223,NULL,1,'2024-03-11 12:59:27',0,'unpaid',0.00,0.00,0,'2024-03-11 12:56:23','2024-03-11 12:59:27'),(152,3100.00,'confirmed','start_loan','pesos',147,250,NULL,1,'2024-03-11 15:04:11',0,'unpaid',0.00,0.00,0,'2024-03-11 14:28:45','2024-03-11 15:04:11'),(153,2000.00,'confirmed','start_loan','pesos',147,331,NULL,1,'2024-03-11 14:52:14',0,'unpaid',0.00,0.00,0,'2024-03-11 14:39:25','2024-03-11 14:52:14'),(154,2000.00,'confirmed','start_loan','pesos',147,331,NULL,1,'2024-03-11 14:53:47',0,'unpaid',0.00,0.00,0,'2024-03-11 14:42:11','2024-03-11 14:53:47'),(155,2000.00,'confirmed','start_loan','pesos',147,331,NULL,1,'2024-03-11 14:53:47',0,'unpaid',0.00,0.00,0,'2024-03-11 14:42:16','2024-03-11 14:53:47'),(156,2000.00,'confirmed','start_loan','pesos',147,331,NULL,1,'2024-03-11 20:51:19',0,'unpaid',0.00,0.00,0,'2024-03-11 20:36:53','2024-03-11 20:51:19'),(157,5000.00,'confirmed','start_loan','pesos',147,324,NULL,1,'2024-03-11 20:50:30',0,'unpaid',0.00,0.00,0,'2024-03-11 20:50:03','2024-03-11 20:50:30'),(158,10000.00,'confirmed','start_loan','pesos',1,362,NULL,1,'2024-03-11 22:31:41',0,'unpaid',0.00,0.00,50,'2024-03-11 22:29:17','2024-03-11 22:31:41'),(159,1950.00,'confirmed','start_loan','pesos',147,221,NULL,1,'2024-03-12 00:00:21',0,'unpaid',0.00,0.00,0,'2024-03-11 23:46:43','2024-03-12 00:00:21'),(160,15000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-12 09:27:09',0,'unpaid',0.00,0.00,0,'2024-03-12 09:23:35','2024-03-12 09:27:09'),(161,6000.00,'confirmed','start_loan','pesos',146,308,NULL,1,'2024-03-12 09:39:28',0,'unpaid',0.00,0.00,0,'2024-03-12 09:32:11','2024-03-12 09:39:28'),(162,8800.00,'confirmed','start_loan','pesos',146,198,NULL,1,'2024-03-12 10:22:53',0,'unpaid',0.00,0.00,0,'2024-03-12 09:35:05','2024-03-12 10:22:53'),(163,8000.00,'confirmed','start_loan','pesos',147,250,NULL,1,'2024-03-12 11:30:12',0,'unpaid',0.00,0.00,0,'2024-03-12 11:11:45','2024-03-12 11:30:12'),(164,10000.00,'confirmed','start_loan','pesos',1,364,NULL,1,'2024-03-12 13:00:52',0,'unpaid',0.00,0.00,50,'2024-03-12 12:58:20','2024-03-12 13:00:52'),(165,2000.00,'confirmed','start_loan','pesos',146,308,NULL,1,'2024-03-12 13:44:25',0,'unpaid',0.00,0.00,0,'2024-03-12 13:36:30','2024-03-12 13:44:25'),(166,40000.00,'confirmed','start_loan','pesos',147,217,NULL,1,'2024-03-12 15:54:12',0,'unpaid',0.00,0.00,0,'2024-03-12 14:51:48','2024-03-12 15:54:12'),(167,30000.00,'confirmed','start_loan','pesos',147,169,NULL,1,'2024-03-12 18:13:06',0,'unpaid',0.00,0.00,0,'2024-03-12 18:12:02','2024-03-12 18:13:06'),(168,7500.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-12 18:15:17',0,'unpaid',0.00,0.00,0,'2024-03-12 18:15:08','2024-03-12 18:15:17'),(169,6000.00,'confirmed','start_loan','pesos',147,258,NULL,1,'2024-03-12 18:32:05',0,'unpaid',0.00,0.00,0,'2024-03-12 18:31:53','2024-03-12 18:32:05'),(170,5000.00,'confirmed','start_loan','pesos',147,211,NULL,1,'2024-03-12 22:29:30',0,'unpaid',0.00,0.00,0,'2024-03-12 21:34:49','2024-03-12 22:29:30'),(171,101.47,'confirmed','start_loan','pesos',1,217,NULL,1,'2024-03-12 23:47:24',0,'unpaid',0.00,0.00,0,'2024-03-12 23:43:39','2024-03-12 23:47:24'),(172,10000.00,'confirmed','start_loan','pesos',146,231,NULL,1,'2024-03-13 15:55:01',0,'unpaid',0.00,0.00,0,'2024-03-13 15:30:03','2024-03-13 15:55:01'),(173,20000.00,'confirmed','start_loan','pesos',1,366,NULL,1,'2024-03-13 17:29:36',0,'unpaid',0.00,0.00,50,'2024-03-13 17:29:27','2024-03-13 17:29:36'),(174,1500.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-13 18:09:22',0,'unpaid',0.00,0.00,0,'2024-03-13 18:08:24','2024-03-13 18:09:22'),(175,8000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-13 18:39:43',0,'unpaid',0.00,0.00,0,'2024-03-13 18:35:31','2024-03-13 18:39:43'),(176,1100.00,'confirmed','start_loan','pesos',147,221,NULL,1,'2024-03-13 19:49:26',0,'unpaid',0.00,0.00,0,'2024-03-13 19:29:43','2024-03-13 19:49:26'),(177,10000.00,'confirmed','start_loan','pesos',1,365,NULL,1,'2024-03-13 20:34:44',0,'unpaid',0.00,0.00,50,'2024-03-13 20:34:20','2024-03-13 20:34:44'),(178,7000.00,'confirmed','start_loan','pesos',147,258,NULL,1,'2024-03-13 21:42:16',0,'unpaid',0.00,0.00,0,'2024-03-13 21:41:38','2024-03-13 21:42:16'),(179,1000.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-14 09:20:50',0,'unpaid',0.00,0.00,0,'2024-03-14 09:20:33','2024-03-14 09:20:50'),(180,5000.00,'confirmed','start_loan','pesos',1,370,NULL,1,'2024-03-14 14:04:24',0,'unpaid',0.00,0.00,50,'2024-03-14 13:22:55','2024-03-14 14:04:24'),(181,2450.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-14 14:20:39',0,'unpaid',0.00,0.00,50,'2024-03-14 14:19:19','2024-03-14 14:20:39'),(182,10000.00,'confirmed','start_loan','pesos',147,211,NULL,1,'2024-03-14 16:40:56',0,'unpaid',0.00,0.00,0,'2024-03-14 16:38:11','2024-03-14 16:40:56'),(183,5000.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-14 20:17:45',0,'unpaid',0.00,0.00,0,'2024-03-14 20:17:18','2024-03-14 20:17:45'),(184,5000.00,'confirmed','start_loan','pesos',1,361,NULL,1,'2024-03-14 20:32:46',0,'unpaid',0.00,0.00,50,'2024-03-14 20:27:35','2024-03-14 20:32:46'),(185,20000.00,'confirmed','start_loan','pesos',147,158,NULL,1,'2024-03-14 21:52:46',0,'unpaid',0.00,0.00,50,'2024-03-14 21:51:41','2024-03-14 21:52:46'),(186,5000.00,'confirmed','start_loan','pesos',1,196,NULL,1,'2024-03-15 11:49:51',0,'unpaid',0.00,0.00,50,'2024-03-15 11:49:30','2024-03-15 11:49:51'),(187,2000.00,'confirmed','start_loan','pesos',146,308,NULL,1,'2024-03-15 17:52:05',0,'unpaid',0.00,0.00,0,'2024-03-15 17:50:26','2024-03-15 17:52:05'),(188,4000.00,'confirmed','start_loan','pesos',147,324,NULL,1,'2024-03-15 19:01:46',0,'unpaid',0.00,0.00,0,'2024-03-15 19:01:24','2024-03-15 19:01:46'),(189,5000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-15 19:20:46',0,'unpaid',0.00,0.00,0,'2024-03-15 19:03:14','2024-03-15 19:20:46'),(190,2000.00,'confirmed','start_loan','pesos',146,163,NULL,1,'2024-03-15 20:30:13',0,'unpaid',0.00,0.00,0,'2024-03-15 20:20:01','2024-03-15 20:30:13'),(191,2500.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-15 20:24:35',0,'unpaid',0.00,0.00,0,'2024-03-15 20:23:47','2024-03-15 20:24:35'),(192,1000.00,'confirmed','start_loan','pesos',147,298,NULL,1,'2024-03-16 10:47:26',0,'unpaid',0.00,0.00,50,'2024-03-16 10:44:02','2024-03-16 10:47:26'),(193,8500.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-16 13:43:51',0,'unpaid',0.00,0.00,50,'2024-03-16 13:42:33','2024-03-16 13:43:51'),(194,2000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-16 20:10:15',0,'unpaid',0.00,0.00,0,'2024-03-16 13:43:01','2024-03-16 20:10:15'),(195,1000.00,'pending','start_loan','pesos',1,347,NULL,0,NULL,0,'unpaid',0.00,0.00,50,'2024-03-16 18:36:24','2024-03-16 18:36:24'),(196,10000.00,'pending','start_loan','pesos',147,347,NULL,0,NULL,0,'unpaid',0.00,0.00,0,'2024-03-16 18:40:03','2024-03-16 18:40:03'),(197,1000.00,'confirmed','start_loan','pesos',146,194,NULL,1,'2024-03-16 20:51:55',0,'unpaid',0.00,0.00,0,'2024-03-16 20:45:20','2024-03-16 20:51:55'),(198,25000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-16 22:34:31',0,'unpaid',0.00,0.00,0,'2024-03-16 22:32:46','2024-03-16 22:34:31'),(199,7000.00,'confirmed','start_loan','pesos',147,258,NULL,1,'2024-03-17 06:25:10',0,'unpaid',0.00,0.00,0,'2024-03-17 02:51:45','2024-03-17 06:25:10'),(200,2000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-17 11:39:24',0,'unpaid',0.00,0.00,0,'2024-03-17 02:52:17','2024-03-17 11:39:24'),(201,60000.00,'confirmed','start_loan','pesos',147,158,NULL,1,'2024-03-17 09:49:01',0,'unpaid',0.00,0.00,50,'2024-03-17 09:01:59','2024-03-17 09:49:01'),(202,15000.00,'confirmed','start_loan','pesos',147,153,NULL,1,'2024-03-17 09:57:21',0,'unpaid',0.00,0.00,0,'2024-03-17 09:24:06','2024-03-17 09:57:21'),(203,10000.00,'confirmed','start_loan','pesos',147,211,NULL,1,'2024-03-17 09:30:18',0,'unpaid',0.00,0.00,0,'2024-03-17 09:29:18','2024-03-17 09:30:18'),(204,10000.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-17 10:47:46',0,'unpaid',0.00,0.00,50,'2024-03-17 10:00:09','2024-03-17 10:47:46'),(205,1700.00,'confirmed','start_loan','pesos',147,221,NULL,1,'2024-03-17 23:23:39',0,'unpaid',0.00,0.00,0,'2024-03-17 11:24:35','2024-03-17 23:23:39'),(206,10000.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-17 12:45:33',0,'unpaid',0.00,0.00,50,'2024-03-17 12:43:31','2024-03-17 12:45:33'),(207,2800.00,'pending','start_loan','pesos',147,315,NULL,0,NULL,0,'unpaid',0.00,0.00,0,'2024-03-17 15:01:53','2024-03-17 15:01:53'),(208,6300.00,'confirmed','start_loan','pesos',146,232,NULL,1,'2024-03-17 22:10:48',0,'unpaid',0.00,0.00,50,'2024-03-17 15:49:39','2024-03-17 22:10:48'),(209,7000.00,'confirmed','start_loan','pesos',146,308,NULL,1,'2024-03-17 15:56:08',0,'unpaid',0.00,0.00,0,'2024-03-17 15:50:07','2024-03-17 15:56:08'),(210,1000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-03-17 18:45:38',0,'unpaid',0.00,0.00,0,'2024-03-17 18:44:43','2024-03-17 18:45:38');
/*!40000 ALTER TABLE `agent_loans` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-18  0:09:26
