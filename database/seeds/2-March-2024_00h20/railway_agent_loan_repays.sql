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
-- Table structure for table `agent_loan_repays`
--

DROP TABLE IF EXISTS `agent_loan_repays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_loan_repays` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` double(15,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `type` set('user_account','user_bank_account') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'user_account',
  `currency` set('us','htg','pesos') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pesos',
  `comment` text COLLATE utf8mb3_unicode_ci,
  `user_bank_account_id` int DEFAULT NULL,
  `system_bank_account_id` int DEFAULT NULL,
  `user_account_id` int DEFAULT NULL,
  `envoy_id` int DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `system_account_id` int DEFAULT NULL,
  `sender_id` int DEFAULT NULL,
  `receiver_id` int DEFAULT NULL,
  `confirmed_by_receiver` tinyint(1) DEFAULT '0',
  `confirmed_by_envoy` tinyint(1) DEFAULT '0',
  `initialization_date` timestamp NULL DEFAULT NULL,
  `receiver_confirmation_date` timestamp NULL DEFAULT NULL,
  `envoy_confirmation_date` timestamp NULL DEFAULT NULL,
  `current_depts` double(15,2) DEFAULT NULL,
  `new_depts` double(15,2) DEFAULT NULL,
  `current_capital` double(15,2) DEFAULT NULL,
  `new_capital` double(15,2) DEFAULT NULL,
  `operation` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `loan_percentage` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_loan_repays_user_id_foreign` (`user_id`),
  CONSTRAINT `agent_loan_repays_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_loan_repays`
--

LOCK TABLES `agent_loan_repays` WRITE;
/*!40000 ALTER TABLE `agent_loan_repays` DISABLE KEYS */;
INSERT INTO `agent_loan_repays` VALUES (4,1000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,91,252,233,1,233,1,1,1,'2024-02-27 23:49:21','2024-02-27 23:50:07','2024-02-27 23:49:36',1000.00,0.00,NULL,NULL,'loan_repayment',0,'2024-02-27 23:49:21','2024-02-27 23:50:07'),(5,6000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,138,252,281,1,281,1,1,1,'2024-02-28 00:33:59','2024-02-28 21:45:39','2024-02-28 21:44:31',12981.25,6981.25,NULL,NULL,'loan_repayment',50,'2024-02-28 00:33:59','2024-02-28 21:45:39'),(6,5000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,166,156,311,1,311,1,1,1,'2024-02-28 10:52:46','2024-02-28 10:56:04','2024-02-28 10:53:45',9949.81,4949.81,NULL,NULL,'loan_repayment',50,'2024-02-28 10:52:46','2024-02-28 10:56:04'),(7,10000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,116,156,258,1,258,1,1,1,'2024-02-28 11:15:08','2024-02-28 13:14:32','2024-02-28 11:15:34',19826.16,9826.16,NULL,NULL,'loan_repayment',50,'2024-02-28 11:15:08','2024-02-28 13:14:32'),(8,15940.00,'confirmed','user_account','pesos',NULL,NULL,NULL,75,156,217,1,217,1,1,1,'2024-02-28 11:53:16','2024-02-28 13:14:25','2024-02-28 11:54:14',15940.00,0.00,NULL,NULL,'loan_repayment',0,'2024-02-28 11:53:16','2024-02-28 13:14:25'),(9,40000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,16,252,158,1,158,1,1,1,'2024-02-28 14:45:02','2024-02-28 21:45:43','2024-02-28 21:44:19',87338.90,47338.90,NULL,NULL,'loan_repayment',50,'2024-02-28 14:45:02','2024-02-28 21:45:43'),(10,10300.00,'confirmed','user_account','pesos',NULL,NULL,NULL,28,156,170,1,170,1,1,1,'2024-02-29 12:52:11','2024-02-29 12:58:07','2024-02-29 12:52:31',20148.96,9848.96,NULL,NULL,'loan_repayment',50,'2024-02-29 12:52:11','2024-02-29 12:58:07'),(11,5405.00,'confirmed','user_account','pesos',NULL,NULL,NULL,129,156,272,1,272,1,1,1,'2024-02-29 18:43:17','2024-02-29 19:20:32','2024-02-29 18:44:11',5405.62,0.62,NULL,NULL,'loan_repayment',0,'2024-02-29 18:43:17','2024-02-29 19:20:32'),(12,10500.00,'confirmed','user_account','pesos',NULL,NULL,NULL,127,156,270,1,270,1,1,1,'2024-03-01 10:54:32','2024-03-01 11:24:52','2024-03-01 10:59:43',15487.50,4987.50,NULL,NULL,'loan_repayment',50,'2024-03-01 10:54:32','2024-03-01 11:24:52'),(13,3600.00,'confirmed','user_account','pesos',NULL,NULL,NULL,198,156,346,1,346,1,1,1,'2024-03-01 12:50:36','2024-03-01 18:12:34','2024-03-01 12:51:07',3600.00,0.00,NULL,NULL,'loan_repayment',0,'2024-03-01 12:50:36','2024-03-01 18:12:34'),(14,10000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,11,252,153,1,153,1,1,1,'2024-03-01 16:45:58','2024-03-01 18:12:21','2024-03-01 17:30:53',10000.00,0.00,NULL,NULL,'loan_repayment',100,'2024-03-01 16:45:58','2024-03-01 18:12:21');
/*!40000 ALTER TABLE `agent_loan_repays` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-02  0:21:59
