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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_loans`
--

LOCK TABLES `agent_loans` WRITE;
/*!40000 ALTER TABLE `agent_loans` DISABLE KEYS */;
INSERT INTO `agent_loans` VALUES (1,10000.00,'confirmed','start_loan','pesos',1,315,NULL,1,'2024-02-23 15:17:11',0,'unpaid',0.00,0.00,50,'2024-02-23 15:16:53','2024-02-23 15:17:11'),(2,5000.00,'confirmed','start_loan','pesos',1,328,NULL,1,'2024-02-23 17:50:25',0,'unpaid',0.00,0.00,0,'2024-02-23 17:48:22','2024-02-23 17:50:25'),(4,10000.00,'confirmed','start_loan','pesos',1,278,NULL,1,'2024-02-23 18:15:40',0,'unpaid',0.00,0.00,50,'2024-02-23 18:15:06','2024-02-23 18:15:40'),(5,21500.00,'confirmed','start_loan','pesos',1,159,NULL,1,'2024-02-23 19:16:29',0,'unpaid',0.00,0.00,50,'2024-02-23 19:13:40','2024-02-23 19:16:29'),(6,10000.00,'confirmed','start_loan','pesos',146,226,NULL,1,'2024-02-23 20:46:41',0,'unpaid',0.00,0.00,50,'2024-02-23 20:42:36','2024-02-23 20:46:41'),(7,10000.00,'confirmed','start_loan','pesos',146,201,NULL,1,'2024-02-23 20:58:41',0,'unpaid',0.00,0.00,50,'2024-02-23 20:57:45','2024-02-23 20:58:41'),(8,10000.00,'confirmed','start_loan','pesos',146,219,NULL,1,'2024-02-23 21:27:39',0,'unpaid',0.00,0.00,50,'2024-02-23 21:24:35','2024-02-23 21:27:39'),(9,30000.00,'confirmed','start_loan','pesos',1,160,NULL,1,'2024-02-23 22:54:28',0,'unpaid',0.00,0.00,50,'2024-02-23 22:53:58','2024-02-23 22:54:28'),(10,50000.00,'confirmed','start_loan','pesos',143,158,NULL,1,'2024-02-24 01:07:17',0,'unpaid',0.00,0.00,50,'2024-02-24 01:06:17','2024-02-24 01:07:17'),(11,10000.00,'confirmed','start_loan','pesos',1,321,NULL,1,'2024-02-24 13:27:38',0,'unpaid',0.00,0.00,50,'2024-02-24 13:27:21','2024-02-24 13:27:38'),(12,11500.00,'confirmed','start_loan','pesos',143,191,NULL,1,'2024-02-25 10:20:39',0,'unpaid',0.00,0.00,50,'2024-02-25 10:20:02','2024-02-25 10:20:39'),(13,5000.00,'confirmed','start_loan','pesos',1,270,NULL,1,'2024-02-25 17:09:19',0,'unpaid',0.00,0.00,50,'2024-02-25 17:04:57','2024-02-25 17:09:19'),(14,5000.00,'confirmed','start_loan','pesos',1,311,NULL,1,'2024-02-25 17:51:05',0,'unpaid',0.00,0.00,50,'2024-02-25 17:50:07','2024-02-25 17:51:05'),(15,12000.00,'confirmed','start_loan','pesos',1,281,NULL,1,'2024-02-25 18:50:46',0,'unpaid',0.00,0.00,50,'2024-02-25 18:50:15','2024-02-25 18:50:46'),(16,10000.00,'confirmed','start_loan','pesos',1,258,NULL,1,'2024-02-25 20:04:32',0,'unpaid',0.00,0.00,50,'2024-02-25 20:02:41','2024-02-25 20:04:32'),(17,9900.00,'confirmed','start_loan','pesos',1,181,NULL,1,'2024-02-25 23:02:46',0,'unpaid',0.00,0.00,50,'2024-02-25 23:02:34','2024-02-25 23:02:46'),(18,10000.00,'confirmed','start_loan','pesos',1,170,NULL,1,'2024-02-26 11:51:04',0,'unpaid',0.00,0.00,50,'2024-02-26 11:50:53','2024-02-26 11:51:04'),(19,5000.00,'confirmed','start_loan','pesos',1,172,NULL,1,'2024-02-26 13:24:42',0,'unpaid',0.00,0.00,50,'2024-02-26 13:23:50','2024-02-26 13:24:42'),(20,5000.00,'confirmed','start_loan','pesos',1,189,NULL,1,'2024-02-26 18:05:46',0,'unpaid',0.00,0.00,50,'2024-02-26 18:04:33','2024-02-26 18:05:46'),(21,10000.00,'confirmed','start_loan','pesos',1,258,NULL,1,'2024-02-27 10:43:37',0,'unpaid',0.00,0.00,50,'2024-02-27 10:29:22','2024-02-27 10:43:37'),(22,5000.00,'confirmed','start_loan','pesos',1,232,NULL,1,'2024-02-27 11:08:50',0,'unpaid',0.00,0.00,50,'2024-02-27 11:08:32','2024-02-27 11:08:50'),(23,1000.00,'confirmed','start_loan','pesos',1,221,NULL,1,'2024-02-27 14:22:19',0,'unpaid',0.00,0.00,0,'2024-02-27 11:31:16','2024-02-27 14:22:19'),(24,20000.00,'confirmed','start_loan','pesos',1,166,NULL,1,'2024-02-27 11:40:28',0,'unpaid',0.00,0.00,50,'2024-02-27 11:40:07','2024-02-27 11:40:28'),(25,3500.00,'confirmed','start_loan','pesos',1,272,NULL,1,'2024-02-27 12:28:35',0,'unpaid',0.00,0.00,0,'2024-02-27 12:21:45','2024-02-27 12:28:35'),(26,16000.00,'confirmed','start_loan','pesos',1,217,NULL,1,'2024-02-27 20:24:18',0,'unpaid',0.00,0.00,0,'2024-02-27 12:22:12','2024-02-27 20:24:18'),(27,10000.00,'confirmed','start_loan','pesos',1,153,NULL,1,'2024-02-27 13:31:10',0,'unpaid',0.00,0.00,100,'2024-02-27 12:40:14','2024-02-27 13:31:10'),(28,15000.00,'confirmed','start_loan','pesos',1,204,NULL,1,'2024-02-27 13:07:26',0,'unpaid',0.00,0.00,50,'2024-02-27 12:59:48','2024-02-27 13:07:26'),(29,15000.00,'confirmed','start_loan','pesos',1,199,NULL,1,'2024-02-27 16:57:18',0,'unpaid',0.00,0.00,50,'2024-02-27 16:56:38','2024-02-27 16:57:18'),(30,5000.00,'confirmed','start_loan','pesos',1,199,NULL,1,'2024-02-27 17:01:17',0,'unpaid',0.00,0.00,50,'2024-02-27 17:00:50','2024-02-27 17:01:17'),(31,40000.00,'confirmed','start_loan','pesos',1,158,NULL,1,'2024-02-27 18:51:58',0,'unpaid',0.00,0.00,50,'2024-02-27 18:16:53','2024-02-27 18:51:58'),(32,4000.00,'confirmed','start_loan','pesos',1,211,NULL,1,'2024-02-27 18:58:31',0,'unpaid',0.00,0.00,50,'2024-02-27 18:57:28','2024-02-27 18:58:31'),(33,1000.00,'confirmed','start_loan','pesos',1,233,NULL,1,'2024-02-27 23:48:56',0,'unpaid',0.00,0.00,0,'2024-02-27 23:48:47','2024-02-27 23:48:56'),(34,1000.00,'confirmed','start_loan','pesos',1,281,NULL,1,'2024-02-28 00:05:56',0,'unpaid',0.00,0.00,50,'2024-02-28 00:04:24','2024-02-28 00:05:56'),(35,5000.00,'confirmed','start_loan','pesos',1,311,NULL,1,'2024-02-28 08:59:08',0,'unpaid',0.00,0.00,50,'2024-02-28 08:42:58','2024-02-28 08:59:08'),(36,10000.00,'confirmed','start_loan','pesos',1,200,NULL,1,'2024-02-28 13:12:55',0,'unpaid',0.00,0.00,50,'2024-02-28 12:50:42','2024-02-28 13:12:55'),(37,8000.00,'confirmed','start_loan','pesos',1,212,NULL,1,'2024-02-28 17:11:26',0,'unpaid',0.00,0.00,0,'2024-02-28 16:58:36','2024-02-28 17:11:26'),(38,10300.00,'pending','start_loan','pesos',147,158,NULL,0,NULL,0,'unpaid',0.00,0.00,50,'2024-02-28 21:21:23','2024-02-28 21:21:23'),(39,10300.00,'confirmed','start_loan','pesos',147,170,NULL,1,'2024-02-28 21:43:30',0,'unpaid',0.00,0.00,50,'2024-02-28 21:25:30','2024-02-28 21:43:30'),(40,16000.00,'confirmed','start_loan','pesos',1,174,NULL,1,'2024-02-28 22:48:24',0,'unpaid',0.00,0.00,50,'2024-02-28 22:48:13','2024-02-28 22:48:24'),(41,15000.00,'confirmed','start_loan','pesos',1,275,NULL,1,'2024-02-28 22:51:52',0,'unpaid',0.00,0.00,50,'2024-02-28 22:51:44','2024-02-28 22:51:52'),(42,5000.00,'confirmed','start_loan','pesos',147,222,NULL,1,'2024-02-29 13:47:08',0,'unpaid',0.00,0.00,0,'2024-02-29 13:32:37','2024-02-29 13:47:08'),(43,2000.00,'confirmed','start_loan','pesos',147,272,NULL,1,'2024-02-29 17:33:58',0,'unpaid',0.00,0.00,0,'2024-02-29 17:22:13','2024-02-29 17:33:58'),(44,3000.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-02-29 20:55:06',0,'unpaid',0.00,0.00,50,'2024-02-29 19:33:00','2024-02-29 20:55:06'),(45,10500.00,'confirmed','start_loan','pesos',147,270,NULL,1,'2024-02-29 20:16:54',0,'unpaid',0.00,0.00,50,'2024-02-29 20:16:07','2024-02-29 20:16:54'),(46,1500.00,'confirmed','start_loan','pesos',147,191,NULL,1,'2024-02-29 22:33:33',0,'unpaid',0.00,0.00,50,'2024-02-29 22:09:21','2024-02-29 22:33:33'),(47,3600.00,'confirmed','start_loan','pesos',147,346,NULL,1,'2024-03-01 11:03:41',0,'unpaid',0.00,0.00,0,'2024-03-01 10:39:05','2024-03-01 11:03:41'),(48,1625.00,'confirmed','start_loan','pesos',147,211,NULL,1,'2024-03-01 12:41:10',0,'unpaid',0.00,0.00,50,'2024-03-01 12:31:04','2024-03-01 12:41:10'),(49,2500.00,'confirmed','start_loan','pesos',147,168,NULL,1,'2024-03-01 14:04:51',0,'unpaid',0.00,0.00,0,'2024-03-01 13:59:16','2024-03-01 14:04:51'),(50,7000.00,'confirmed','start_loan','pesos',1,195,NULL,1,'2024-03-01 22:40:34',0,'unpaid',0.00,0.00,50,'2024-03-01 22:39:58','2024-03-01 22:40:34');
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

-- Dump completed on 2024-03-02  0:22:29
