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
-- Table structure for table `agent_loan_repay_activits`
--

DROP TABLE IF EXISTS `agent_loan_repay_activits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_loan_repay_activits` (
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
  `completed_date` timestamp NULL DEFAULT NULL,
  `agent_loan_repay_id` bigint unsigned NOT NULL,
  `new_agent_balance` double(15,2) DEFAULT NULL,
  `current_agent_investment` double(15,2) DEFAULT NULL,
  `new_agent_investment` double(15,2) DEFAULT NULL,
  `current_agent_debt` double(15,2) DEFAULT NULL,
  `new_agent_debt` double(15,2) DEFAULT NULL,
  `current_agent_capital` double(15,2) DEFAULT NULL,
  `new_agent_capital` double(15,2) DEFAULT NULL,
  `current_envoy_balance` double(15,2) DEFAULT NULL,
  `new_envoy_balance` double(15,2) DEFAULT NULL,
  `current_envoy_debt` double(15,2) DEFAULT NULL,
  `new_envoy_debt` double(15,2) DEFAULT NULL,
  `current_system_fund` double(15,2) DEFAULT NULL,
  `new_system_fund` double(15,2) DEFAULT NULL,
  `step` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `current_agent_balance` double(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_loan_repay_activits_user_id_foreign` (`user_id`),
  KEY `agent_loan_repay_activits_agent_loan_repay_id_foreign` (`agent_loan_repay_id`),
  CONSTRAINT `agent_loan_repay_activits_agent_loan_repay_id_foreign` FOREIGN KEY (`agent_loan_repay_id`) REFERENCES `agent_loan_repays` (`id`),
  CONSTRAINT `agent_loan_repay_activits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_loan_repay_activits`
--

LOCK TABLES `agent_loan_repay_activits` WRITE;
/*!40000 ALTER TABLE `agent_loan_repay_activits` DISABLE KEYS */;
INSERT INTO `agent_loan_repay_activits` VALUES (4,1000.00,'pending','user_account','pesos',NULL,NULL,NULL,91,252,233,NULL,233,1,0,0,'2024-02-27 23:49:21',NULL,NULL,NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,4,57.54,24598.00,24598.00,1000.00,1000.00,NULL,NULL,0.00,0.00,330314.80,330314.80,0.00,0.00,'initialization',57.54,'2024-02-27 23:49:21','2024-02-27 23:49:21'),(5,1000.00,'pending','user_account','pesos',NULL,NULL,NULL,91,252,233,NULL,233,1,0,1,'2024-02-27 23:49:21',NULL,'2024-02-27 23:49:36',NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,4,57.54,24598.00,24598.00,1000.00,1000.00,26155.54,26155.54,0.00,0.00,330314.80,331314.80,0.00,0.00,'envoy-confirmation',57.54,'2024-02-27 23:49:36','2024-02-27 23:49:36'),(6,1000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,91,252,233,NULL,233,1,1,1,'2024-02-27 23:49:21','2024-02-27 23:50:07','2024-02-27 23:49:36',NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,4,57.54,24598.00,24598.00,1000.00,0.00,26155.54,27155.54,0.00,0.00,330314.80,331314.80,0.00,0.00,'super-admin-confirmation',57.54,'2024-02-27 23:50:07','2024-02-27 23:50:07'),(7,1000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,91,252,233,NULL,233,1,1,1,'2024-02-27 23:49:21','2024-02-27 23:50:07','2024-02-27 23:49:36',NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,4,57.54,24598.00,24598.00,1000.00,0.00,26155.54,27155.54,0.00,0.00,330314.80,331314.80,0.00,0.00,'super-admin-confirmation',57.54,'2024-02-27 23:50:07','2024-02-27 23:50:07'),(8,6000.00,'pending','user_account','pesos',NULL,NULL,NULL,138,252,281,NULL,281,1,0,0,'2024-02-28 00:33:59',NULL,NULL,NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,5,765.00,0.00,0.00,12981.25,12981.25,NULL,NULL,0.00,0.00,330314.80,330314.80,0.00,0.00,'initialization',765.00,'2024-02-28 00:33:59','2024-02-28 00:33:59'),(9,5000.00,'pending','user_account','pesos',NULL,NULL,NULL,166,156,311,NULL,311,1,0,0,'2024-02-28 10:52:46',NULL,NULL,NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,6,50.94,6430.00,6430.00,9949.81,9949.81,NULL,NULL,0.00,0.00,0.00,0.00,0.00,0.00,'initialization',50.94,'2024-02-28 10:52:46','2024-02-28 10:52:46'),(10,5000.00,'pending','user_account','pesos',NULL,NULL,NULL,166,156,311,NULL,311,1,0,1,'2024-02-28 10:52:46',NULL,'2024-02-28 10:53:45',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,6,50.94,6430.00,6430.00,9949.81,9949.81,1931.12,1931.12,3140.13,3140.13,0.00,5000.00,0.00,0.00,'envoy-confirmation',50.94,'2024-02-28 10:53:45','2024-02-28 10:53:45'),(11,5000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,166,156,311,NULL,311,1,1,1,'2024-02-28 10:52:46','2024-02-28 10:56:04','2024-02-28 10:53:45',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,6,50.94,6430.00,6430.00,9949.81,4949.81,1931.12,6931.12,3140.13,3140.13,0.00,5000.00,0.00,0.00,'super-admin-confirmation',50.94,'2024-02-28 10:56:04','2024-02-28 10:56:04'),(12,10000.00,'pending','user_account','pesos',NULL,NULL,NULL,116,156,258,NULL,258,1,0,0,'2024-02-28 11:15:08',NULL,NULL,NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,7,13.09,14408.24,14408.24,19826.16,19826.16,NULL,NULL,0.00,0.00,9950.00,9950.00,0.00,0.00,'initialization',13.09,'2024-02-28 11:15:08','2024-02-28 11:15:08'),(13,10000.00,'pending','user_account','pesos',NULL,NULL,NULL,116,156,258,NULL,258,1,0,1,'2024-02-28 11:15:08',NULL,'2024-02-28 11:15:34',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,7,13.09,14408.24,14408.24,19826.16,19826.16,9110.44,9110.44,3140.13,3140.13,9950.00,19950.00,0.00,0.00,'envoy-confirmation',13.09,'2024-02-28 11:15:34','2024-02-28 11:15:34'),(14,15940.00,'pending','user_account','pesos',NULL,NULL,NULL,75,156,217,NULL,217,1,0,0,'2024-02-28 11:53:16',NULL,NULL,NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,8,6312.06,14537.50,14537.50,15940.00,15940.00,NULL,NULL,0.00,0.00,19950.00,19950.00,0.00,0.00,'initialization',6312.06,'2024-02-28 11:53:16','2024-02-28 11:53:16'),(15,15940.00,'pending','user_account','pesos',NULL,NULL,NULL,75,156,217,NULL,217,1,0,1,'2024-02-28 11:53:16',NULL,'2024-02-28 11:54:14',NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,8,6312.06,14537.50,14537.50,15940.00,15940.00,21409.56,21409.56,3140.13,3140.13,19950.00,35890.00,0.00,0.00,'envoy-confirmation',6312.06,'2024-02-28 11:54:14','2024-02-28 11:54:14'),(16,15940.00,'confirmed','user_account','pesos',NULL,NULL,NULL,75,156,217,NULL,217,1,1,1,'2024-02-28 11:53:16','2024-02-28 13:14:25','2024-02-28 11:54:14',NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,8,6312.06,14537.50,14537.50,15940.00,0.00,21409.56,37349.56,3140.13,3140.13,19950.00,35890.00,0.00,0.00,'super-admin-confirmation',6312.06,'2024-02-28 13:14:25','2024-02-28 13:14:25'),(17,10000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,116,156,258,NULL,258,1,1,1,'2024-02-28 11:15:08','2024-02-28 13:14:32','2024-02-28 11:15:34',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,7,13.09,14408.24,14408.24,19826.16,9826.16,9110.44,19110.44,3140.13,3140.13,19950.00,35890.00,0.00,0.00,'super-admin-confirmation',13.09,'2024-02-28 13:14:32','2024-02-28 13:14:32'),(18,40000.00,'pending','user_account','pesos',NULL,NULL,NULL,16,252,158,NULL,158,1,0,0,'2024-02-28 14:45:02',NULL,NULL,NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,9,3497.84,14989.00,14989.00,87347.65,87347.65,NULL,NULL,0.00,0.00,309394.80,309394.80,0.00,0.00,'initialization',3497.84,'2024-02-28 14:45:02','2024-02-28 14:45:02'),(19,40000.00,'pending','user_account','pesos',NULL,NULL,NULL,16,252,158,NULL,158,1,0,1,'2024-02-28 14:45:02',NULL,'2024-02-28 21:44:19',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,9,3506.59,65489.00,65489.00,87338.90,87338.90,-18343.31,-18343.31,0.00,0.00,360894.80,400894.80,0.00,0.00,'envoy-confirmation',3506.59,'2024-02-28 21:44:19','2024-02-28 21:44:19'),(20,6000.00,'pending','user_account','pesos',NULL,NULL,NULL,138,252,281,NULL,281,1,0,1,'2024-02-28 00:33:59',NULL,'2024-02-28 21:44:31',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,5,765.00,0.00,0.00,12981.25,12981.25,5783.75,5783.75,0.00,0.00,400894.80,406894.80,0.00,0.00,'envoy-confirmation',765.00,'2024-02-28 21:44:31','2024-02-28 21:44:31'),(21,6000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,138,252,281,NULL,281,1,1,1,'2024-02-28 00:33:59','2024-02-28 21:45:39','2024-02-28 21:44:31',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,5,765.00,0.00,0.00,12981.25,6981.25,5783.75,11783.75,0.00,0.00,400894.80,406894.80,0.00,0.00,'super-admin-confirmation',765.00,'2024-02-28 21:45:39','2024-02-28 21:45:39'),(22,40000.00,'confirmed','user_account','pesos',NULL,NULL,NULL,16,252,158,NULL,158,1,1,1,'2024-02-28 14:45:02','2024-02-28 21:45:43','2024-02-28 21:44:19',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,9,3506.59,65489.00,65489.00,87338.90,47338.90,-18343.31,21656.69,0.00,0.00,400894.80,406894.80,0.00,0.00,'super-admin-confirmation',3506.59,'2024-02-28 21:45:43','2024-02-28 21:45:43'),(23,10300.00,'pending','user_account','pesos',NULL,NULL,NULL,28,156,170,NULL,170,1,0,0,'2024-02-29 12:52:11',NULL,NULL,NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,10,2645.80,974.70,974.70,20148.96,20148.96,NULL,NULL,0.00,0.00,4540.00,4540.00,0.00,0.00,'initialization',2645.80,'2024-02-29 12:52:11','2024-02-29 12:52:11'),(24,10300.00,'pending','user_account','pesos',NULL,NULL,NULL,28,156,170,NULL,170,1,0,1,'2024-02-29 12:52:11',NULL,'2024-02-29 12:52:31',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,10,2645.80,974.70,974.70,20148.96,20148.96,1872.45,1872.45,3179.43,3179.43,4540.00,14840.00,0.00,0.00,'envoy-confirmation',2645.80,'2024-02-29 12:52:31','2024-02-29 12:52:31'),(25,10300.00,'confirmed','user_account','pesos',NULL,NULL,NULL,28,156,170,NULL,170,1,1,1,'2024-02-29 12:52:11','2024-02-29 12:58:07','2024-02-29 12:52:31',NULL,NULL,NULL,NULL,'loan_repayment',50,NULL,10,2645.80,974.70,974.70,20148.96,9848.96,1872.45,12172.45,3179.43,3179.43,4540.00,14840.00,0.00,0.00,'super-admin-confirmation',2645.80,'2024-02-29 12:58:07','2024-02-29 12:58:07'),(26,5405.00,'pending','user_account','pesos',NULL,NULL,NULL,129,156,272,NULL,272,1,0,0,'2024-02-29 18:43:17',NULL,NULL,NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,11,49.38,433.20,433.20,5405.62,5405.62,NULL,NULL,0.00,0.00,33340.00,33340.00,0.00,0.00,'initialization',49.38,'2024-02-29 18:43:17','2024-02-29 18:43:17'),(27,5405.00,'pending','user_account','pesos',NULL,NULL,NULL,129,156,272,NULL,272,1,0,1,'2024-02-29 18:43:17',NULL,'2024-02-29 18:44:11',NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,11,49.38,433.20,433.20,5405.62,5405.62,1997.83,1997.83,3179.43,3179.43,33340.00,38745.00,0.00,0.00,'envoy-confirmation',49.38,'2024-02-29 18:44:11','2024-02-29 18:44:11'),(28,5405.00,'confirmed','user_account','pesos',NULL,NULL,NULL,129,156,272,NULL,272,1,1,1,'2024-02-29 18:43:17','2024-02-29 19:20:32','2024-02-29 18:44:11',NULL,NULL,NULL,NULL,'loan_repayment',0,NULL,11,49.38,3833.20,3833.20,5405.62,0.62,-1302.17,4102.83,3179.43,3179.43,33340.00,38745.00,0.00,0.00,'super-admin-confirmation',49.38,'2024-02-29 19:20:32','2024-02-29 19:20:32');
/*!40000 ALTER TABLE `agent_loan_repay_activits` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-01  1:05:47
