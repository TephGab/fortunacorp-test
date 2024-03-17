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
-- Table structure for table `user_accounts`
--

DROP TABLE IF EXISTS `user_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `balance` double(15,2) NOT NULL DEFAULT '0.00',
  `depts` double(15,2) NOT NULL DEFAULT '0.00',
  `withdrawal` double(15,2) NOT NULL DEFAULT '0.00',
  `category` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `replenishments` double(15,2) NOT NULL DEFAULT '0.00',
  `investments` double(15,2) NOT NULL DEFAULT '0.00',
  `profits` double(15,2) NOT NULL DEFAULT '0.00',
  `cash_in_hand` double(15,2) DEFAULT '0.00',
  `referral_commissions` double(15,2) DEFAULT '0.00',
  `capital` double(15,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `user_accounts_user_id_foreign` (`user_id`),
  CONSTRAINT `user_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_accounts`
--

LOCK TABLES `user_accounts` WRITE;
/*!40000 ALTER TABLE `user_accounts` DISABLE KEYS */;
INSERT INTO `user_accounts` VALUES (1,0.00,0.00,0.00,'none',143,'2023-10-14 06:14:37','2023-11-28 17:51:03',0.00,0.00,1424.04,0.00,0.00,1424.04),(2,0.00,0.00,0.00,'none',144,'2023-10-14 06:21:06','2023-10-14 06:21:06',0.00,0.00,0.00,0.00,0.00,0.00),(3,0.00,0.00,0.00,'none',145,'2023-10-14 06:26:37','2024-03-01 21:56:22',0.00,0.00,0.00,0.00,119.31,96.81),(4,0.00,0.00,0.00,'none',146,'2023-10-14 06:32:57','2023-10-14 06:32:57',0.00,0.00,0.00,0.00,0.00,0.00),(5,0.00,0.00,0.00,'none',147,'2023-10-14 06:37:35','2024-01-19 15:51:30',0.00,996.20,190.00,0.00,0.00,1186.20),(6,0.00,0.00,0.00,'none',148,'2023-10-14 06:42:55','2023-10-14 06:42:55',0.00,0.00,0.00,0.00,0.00,0.00),(7,0.00,0.00,0.00,'none',149,'2023-10-13 22:48:03','2024-01-19 01:39:17',0.00,0.00,0.00,0.00,197.06,197.06),(8,0.00,0.00,0.00,'none',150,'2023-10-14 06:52:49','2024-02-29 20:30:37',0.00,0.00,1520.75,0.00,90.17,910.92),(9,0.00,0.00,0.00,'none',151,'2023-10-14 06:56:38','2024-03-01 20:43:24',0.00,0.00,16493.80,0.00,631.14,14468.96),(10,0.00,0.00,0.00,'none',152,'2023-10-14 07:03:19','2024-03-01 21:56:22',0.00,0.00,9310.00,0.00,0.00,7040.00),(11,0.00,0.00,0.00,'none',153,'2023-10-14 07:38:25','2024-03-01 19:41:13',0.00,75.02,82.89,39408.00,145.01,39710.94),(12,0.00,26900.00,0.00,'none',154,'2023-10-14 07:43:31','2024-03-01 20:51:47',0.00,0.00,0.00,0.00,0.00,0.00),(13,0.00,24300.00,0.00,'none',155,'2023-10-14 03:49:39','2024-03-02 00:19:19',0.00,0.00,0.00,0.00,226.14,214.89),(14,0.00,37695.00,0.00,'none',156,'2023-10-14 07:54:10','2024-03-01 19:46:20',0.00,0.00,0.00,0.00,3267.99,4452.71),(15,0.00,23995.00,0.00,'none',157,'2023-10-14 08:00:22','2024-03-01 19:14:06',0.00,0.00,0.00,0.00,0.00,0.00),(16,0.00,46140.94,0.00,'none',158,'2023-10-14 08:15:21','2024-03-01 21:59:58',0.00,34439.00,4704.56,29650.00,0.00,22652.62),(17,0.00,20025.28,0.00,'none',159,'2023-10-14 08:21:31','2024-03-01 20:15:07',0.00,24565.35,630.00,1075.00,214.47,6459.57),(18,0.00,29918.25,0.00,'none',160,'2023-10-14 08:27:18','2024-02-25 17:56:44',0.00,30000.50,2.16,0.00,0.00,84.41),(19,0.00,0.00,0.00,'none',161,'2023-10-14 08:40:17','2024-03-01 18:36:39',0.00,15.15,431.00,3850.00,1314.48,5610.64),(20,0.00,0.00,0.00,'none',162,'2023-10-14 08:52:49','2024-03-01 21:32:37',0.00,1807.47,2307.71,12150.00,1447.02,17712.22),(21,0.00,0.00,0.00,'none',163,'2023-10-14 09:06:19','2024-02-29 18:57:00',0.00,2888.03,80.00,400.00,0.00,3368.03),(22,0.00,0.00,0.00,'none',164,'2023-10-14 09:14:22','2024-03-01 10:16:52',0.00,7105.18,10681.62,27500.00,0.00,45286.80),(23,0.00,0.00,0.00,'none',165,'2023-10-14 09:43:42','2024-03-01 19:50:51',0.00,25.00,627.47,0.00,417.10,1069.57),(24,0.00,19718.18,0.00,'none',166,'2023-10-14 10:40:41','2024-03-01 17:57:12',0.00,16832.74,740.82,3819.68,14.83,1689.90),(25,0.00,0.00,0.00,'none',167,'2023-10-14 10:55:46','2024-03-01 21:32:37',0.00,397.00,231.25,5000.00,0.00,5628.25),(26,0.00,2500.00,0.00,'none',168,'2023-10-14 11:19:05','2024-03-01 15:03:56',0.00,2533.12,357.55,5000.00,203.36,5594.03),(27,0.00,0.00,0.00,'none',169,'2023-10-14 11:22:47','2024-03-01 19:15:18',0.00,13100.17,2285.95,21900.00,0.00,37286.12),(28,0.00,9848.96,0.00,'none',170,'2023-10-14 11:25:43','2024-03-01 19:39:34',0.00,8774.70,2645.80,0.00,61.53,1633.04),(29,0.00,0.00,0.00,'none',171,'2023-10-14 11:28:43','2024-02-29 17:19:20',0.00,1148.00,1674.82,4900.00,0.00,7722.82),(30,0.00,4915.00,0.00,'none',172,'2023-10-14 11:32:08','2024-03-01 13:25:56',0.00,6367.00,85.24,3000.00,0.00,4537.24),(31,0.00,0.00,0.00,'none',173,'2023-10-14 11:34:49','2024-03-01 19:07:21',0.00,9095.00,3276.18,3000.00,0.00,15371.18),(32,0.00,15987.50,0.00,'none',174,'2023-10-14 11:41:24','2024-02-29 00:42:49',0.00,1500.00,252.76,29150.00,20.96,14936.22),(33,0.00,0.00,0.00,'none',175,'2023-10-14 11:46:11','2024-03-01 22:43:57',0.00,6106.42,236.12,0.00,0.00,6342.55),(34,0.00,0.00,0.00,'none',176,'2023-10-14 11:51:02','2024-03-01 08:27:27',0.00,0.30,1396.63,10100.00,94.88,11591.81),(35,0.00,0.00,0.00,'none',177,'2023-10-14 11:55:17','2024-03-01 09:31:52',0.00,3602.00,2421.68,9400.00,0.00,15423.67),(36,0.00,0.00,0.00,'none',178,'2023-10-14 11:59:44','2024-03-01 19:41:28',0.00,11900.00,606.60,6100.00,0.00,18606.60),(37,0.00,0.00,0.00,'none',179,'2023-10-14 12:05:13','2024-02-26 16:14:38',0.00,2725.20,2003.60,4000.00,0.00,8728.80),(38,0.00,0.00,0.00,'none',180,'2023-10-14 12:16:05','2024-03-01 17:42:24',0.00,27420.00,1845.45,0.00,0.00,29265.45),(39,0.00,9832.50,0.00,'none',181,'2023-10-14 12:16:19','2024-02-29 20:30:37',0.00,6277.28,67.57,2500.00,0.00,-987.65),(40,0.00,0.00,0.00,'none',182,'2023-10-14 12:20:08','2024-03-01 12:59:31',0.00,268.95,0.00,0.00,81.00,349.95),(41,0.00,0.00,0.00,'none',183,'2023-10-14 12:23:18','2024-03-01 18:22:50',0.00,1351.57,0.00,9700.00,0.00,11051.57),(42,0.00,0.00,0.00,'none',184,'2023-10-14 12:25:51','2024-01-23 10:01:00',0.00,163.75,102.00,0.00,15.00,280.75),(43,0.00,0.00,0.00,'none',185,'2023-10-14 12:28:01','2024-02-24 16:22:03',0.00,8122.02,852.50,0.00,0.00,8974.52),(44,0.00,0.00,0.00,'none',186,'2023-10-14 12:32:44','2024-01-20 13:52:37',0.00,291.00,589.12,0.00,0.00,880.12),(45,0.00,0.00,0.00,'none',187,'2023-10-14 12:32:51','2023-10-14 12:32:51',0.00,0.00,0.00,0.00,0.00,0.00),(46,0.00,0.00,0.00,'none',188,'2023-10-14 08:39:53','2024-02-28 10:53:15',0.00,30.73,29.15,1250.00,0.00,1309.88),(47,0.00,5000.00,0.00,'none',189,'2023-10-14 12:49:10','2024-02-26 18:39:07',0.00,5580.00,0.62,0.00,0.00,580.62),(48,0.00,0.00,0.00,'none',190,'2023-10-14 13:17:49','2024-02-26 20:27:00',0.00,39.00,47.99,3000.00,0.00,3086.99),(49,0.00,15521.70,0.00,'none',191,'2023-10-14 13:21:22','2024-03-01 11:10:19',0.00,2015.00,69.00,5650.00,0.00,-7787.70),(50,0.00,0.00,0.00,'none',192,'2023-10-14 13:24:51','2024-02-26 21:45:33',0.00,308.56,455.06,3750.00,0.00,4513.62),(51,0.00,0.00,0.00,'none',193,'2023-10-14 13:30:45','2024-02-27 12:02:33',0.00,5351.40,7795.65,7000.00,27.00,20174.05),(52,0.00,0.00,0.00,'none',194,'2023-10-14 13:45:27','2024-03-01 19:33:19',0.00,7677.64,668.00,2350.00,0.00,10695.64),(53,0.00,7000.00,0.00,'none',195,'2023-10-14 13:52:37','2024-03-01 22:43:52',0.00,7095.00,446.50,0.00,0.00,541.50),(54,0.00,0.00,0.00,'none',196,'2023-10-14 13:55:41','2023-10-14 13:55:41',0.00,0.00,0.00,0.00,0.00,0.00),(55,0.00,0.00,0.00,'none',197,'2023-10-14 09:59:55','2024-03-01 16:30:40',0.00,652.06,25.00,0.00,345.17,1022.23),(56,0.00,0.00,0.00,'none',198,'2023-10-14 14:05:26','2024-02-29 19:25:49',0.00,364.68,0.00,3200.00,0.00,3564.67),(57,0.00,20000.00,0.00,'none',199,'2023-10-14 14:16:10','2024-02-27 17:14:35',0.00,20819.90,0.01,0.00,0.00,819.91),(58,0.00,10000.00,0.00,'none',200,'2023-10-14 14:21:33','2024-02-28 13:25:26',0.00,283.90,5.00,0.00,0.00,-9711.10),(59,0.00,9247.85,0.00,'none',201,'2023-10-14 14:21:36','2024-03-01 17:21:39',0.00,10103.09,1059.85,0.00,0.01,1915.10),(60,0.00,0.00,0.00,'none',202,'2023-10-14 14:26:00','2024-02-04 18:29:20',0.00,10138.00,0.56,0.00,0.00,10138.56),(61,0.00,0.00,0.00,'none',203,'2023-10-14 14:26:13','2023-12-17 11:59:48',0.00,2050.00,87.50,0.00,0.00,2137.50),(62,0.00,14896.88,0.00,'none',204,'2023-10-14 14:29:08','2024-03-01 23:38:03',0.00,4611.17,103.16,10750.00,0.00,567.45),(63,0.00,0.00,0.00,'none',205,'2023-10-14 14:29:51','2024-03-01 17:37:11',0.00,7400.00,201.37,10450.00,0.00,18051.36),(64,0.00,0.00,0.00,'none',206,'2023-10-14 14:32:57','2024-01-04 15:16:34',0.00,12.00,155.13,0.00,0.00,167.13),(65,0.00,0.00,0.00,'none',207,'2023-10-14 14:33:09','2024-03-01 17:51:39',0.00,5004.00,97.87,8300.00,0.00,13401.87),(66,0.00,0.00,0.00,'none',208,'2023-10-14 14:38:24','2024-02-28 14:54:44',0.00,5093.52,75.70,0.00,45.61,5144.84),(67,0.00,0.00,0.00,'none',209,'2023-10-14 14:42:11','2024-02-28 17:19:15',0.00,18350.00,331.50,0.00,0.00,18681.50),(68,0.00,0.00,0.00,'none',210,'2023-10-14 14:42:20','2024-03-01 21:09:58',0.00,28901.42,3628.34,700.00,0.00,33229.76),(69,0.00,5479.19,0.00,'none',211,'2023-10-14 14:47:22','2024-03-01 15:43:28',0.00,1040.12,145.82,3125.00,0.00,-1168.24),(70,0.00,7771.50,0.00,'none',212,'2023-10-14 14:52:42','2024-03-01 15:00:11',0.00,13941.10,2487.51,9200.00,0.00,17857.11),(71,0.00,0.00,0.00,'none',213,'2023-10-14 14:58:00','2023-10-14 14:58:00',0.00,0.00,0.00,0.00,0.00,0.00),(72,0.00,0.00,0.00,'none',214,'2023-10-14 15:00:42','2023-12-29 15:30:44',0.00,948.82,2437.21,0.00,0.00,3386.03),(73,0.00,0.00,0.00,'none',215,'2023-10-14 15:19:30','2023-11-11 15:17:22',0.00,10000.00,0.00,0.00,0.00,10000.00),(74,0.00,0.00,0.00,'none',216,'2023-10-14 15:32:57','2023-10-14 15:32:57',0.00,0.00,0.00,0.00,0.00,0.00),(75,0.00,0.00,0.00,'none',217,'2023-10-14 15:38:13','2024-03-01 19:39:34',0.00,1437.50,6596.61,29600.00,0.00,37634.11),(76,0.00,0.00,0.00,'none',218,'2023-10-14 15:41:08','2024-02-09 16:04:52',0.00,1738.00,628.75,0.00,0.00,2366.75),(77,0.00,9913.75,0.00,'none',219,'2023-10-14 15:43:31','2024-02-29 15:01:00',0.00,2050.00,268.75,7950.00,0.00,355.00),(78,0.00,0.00,0.00,'none',220,'2023-10-14 15:47:02','2023-10-14 15:47:02',0.00,0.00,0.00,0.00,0.00,0.00),(79,0.00,962.50,0.00,'none',221,'2023-10-14 15:49:08','2024-03-01 09:37:39',0.00,60.00,868.78,2000.00,96.75,2063.03),(80,0.00,4886.22,0.00,'none',222,'2023-10-14 16:51:57','2024-03-01 12:20:52',0.00,25.76,244.68,1025.00,0.00,-3590.78),(81,0.00,0.00,0.00,'none',223,'2023-10-14 18:06:51','2024-02-23 14:23:46',0.00,541.76,137.75,2000.00,0.00,2679.51),(82,0.00,0.00,0.00,'none',224,'2023-10-15 18:13:43','2024-02-19 20:12:39',0.00,154.91,0.00,0.00,0.00,154.91),(83,0.00,0.00,0.00,'none',225,'2023-10-17 19:41:16','2023-10-18 19:25:28',0.00,5.00,135.00,0.00,0.00,140.00),(84,0.00,9899.41,0.00,'none',226,'2023-10-17 15:46:59','2024-03-01 19:50:51',0.00,0.14,94.34,4150.00,0.00,-5654.92),(85,0.00,0.00,0.00,'none',227,'2023-10-17 19:57:43','2023-10-17 19:57:43',0.00,0.00,0.00,0.00,0.00,0.00),(86,0.00,0.00,0.00,'none',228,'2023-10-17 21:54:56','2024-03-01 16:01:06',0.00,3298.10,189.94,6000.00,272.01,9760.05),(87,0.00,0.00,0.00,'none',229,'2023-10-18 14:51:54','2023-11-28 15:43:36',0.00,50.00,327.00,0.00,0.00,377.00),(88,0.00,0.00,0.00,'none',230,'2023-10-18 16:24:10','2024-02-16 17:22:38',0.00,1761.62,5539.89,0.00,12.00,7313.51),(89,0.00,0.00,0.00,'none',231,'2023-10-18 18:41:31','2024-03-01 16:46:33',0.00,2042.98,5060.03,8200.00,0.00,15303.00),(90,0.00,4954.19,0.00,'none',232,'2023-10-19 15:50:04','2024-03-01 19:10:20',0.00,3607.00,46.67,5200.00,36.75,3936.24),(91,0.00,0.00,0.00,'none',233,'2023-10-19 23:48:10','2024-03-01 12:02:03',0.00,13098.00,281.79,14000.00,0.00,27379.79),(92,0.00,0.00,0.00,'none',234,'2023-10-19 23:52:26','2023-10-19 23:52:26',0.00,0.00,0.00,0.00,0.00,0.00),(93,0.00,0.00,0.00,'none',235,'2023-10-23 12:25:06','2024-03-01 21:56:22',0.00,7550.00,276.88,2000.00,0.00,9826.88),(94,0.00,0.00,0.00,'none',236,'2023-10-23 12:35:25','2024-02-02 11:31:28',0.00,766.89,913.08,0.00,11.25,1691.22),(95,0.00,0.00,0.00,'none',237,'2023-10-23 13:35:50','2023-10-23 15:03:38',0.00,5534.35,0.00,0.00,0.00,5534.35),(96,0.00,0.00,0.00,'none',238,'2023-10-23 14:13:08','2024-03-01 19:18:09',0.00,102905.43,5419.93,30325.00,0.00,138650.36),(97,0.00,0.00,0.00,'none',239,'2023-10-23 15:29:37','2023-10-23 15:29:37',0.00,0.00,0.00,0.00,0.00,0.00),(98,0.00,0.00,0.00,'none',240,'2023-10-23 17:20:09','2024-02-26 14:37:43',0.00,568.37,0.00,3300.00,55.50,3923.88),(99,0.00,0.00,0.00,'none',241,'2023-10-24 12:32:53','2023-12-30 20:58:55',0.00,412.90,112.75,0.00,0.00,525.65),(100,0.00,0.00,0.00,'none',242,'2023-10-26 07:56:35','2023-10-26 07:56:35',0.00,0.00,0.00,0.00,0.00,0.00),(101,0.00,0.00,0.00,'none',243,'2023-10-26 05:24:52','2024-03-01 00:47:06',0.00,450.26,0.00,1000.00,0.00,1450.26),(102,0.00,0.00,0.00,'none',244,'2023-10-26 10:13:31','2024-02-28 18:17:36',0.00,0.00,2843.74,1500.00,0.00,4343.74),(103,0.00,0.00,0.00,'none',245,'2023-10-31 12:53:06','2024-02-29 15:28:43',0.00,1762.00,359.66,4050.00,128.66,6300.31),(104,0.00,0.00,0.00,'none',246,'2023-11-01 10:46:56','2023-11-01 10:46:56',0.00,0.00,0.00,0.00,0.00,0.00),(105,0.00,0.00,0.00,'none',247,'2023-11-02 10:30:04','2024-03-01 16:01:06',0.00,8770.25,2949.39,60200.00,0.00,71919.64),(106,0.00,0.00,0.00,'none',248,'2023-11-02 11:39:10','2024-02-12 14:54:55',0.00,0.72,155.50,0.00,7.50,163.72),(107,0.00,0.00,0.00,'none',249,'2023-11-04 10:20:04','2023-11-04 10:20:04',0.00,0.00,0.00,0.00,0.00,0.00),(108,0.00,0.00,0.00,'none',250,'2023-11-06 00:10:59','2024-02-03 12:36:48',0.00,1300.00,1699.37,0.00,0.00,2999.37),(109,0.00,0.00,0.00,'none',251,'2023-11-06 22:23:58','2024-03-01 23:38:03',0.00,0.00,17820.00,0.00,0.00,14920.00),(110,0.00,447069.80,0.00,'none',252,'2023-11-07 14:22:06','2024-03-01 22:43:28',0.00,0.00,0.00,0.00,0.00,0.00),(111,0.00,0.00,0.00,'none',253,'2023-11-08 06:52:42','2024-02-07 14:54:47',0.00,0.00,1050.00,0.00,0.00,1050.00),(112,0.00,0.00,0.00,'none',254,'2023-11-08 14:20:30','2023-11-08 14:20:30',0.00,0.00,0.00,0.00,0.00,0.00),(113,0.00,0.00,0.00,'none',255,'2023-11-09 13:18:24','2024-02-26 18:41:39',0.00,0.00,3540.00,0.00,0.00,3530.00),(114,0.00,0.00,0.00,'none',256,'2023-11-13 11:28:33','2024-01-23 14:42:54',0.00,3700.00,240.00,0.00,0.00,3940.00),(115,0.00,0.00,0.00,'none',257,'2023-11-13 16:14:44','2024-02-07 17:19:33',0.00,100.00,161.25,0.00,0.00,261.25),(116,0.00,9813.66,0.00,'none',258,'2023-11-14 10:16:19','2024-02-29 19:03:54',0.00,13408.24,25.59,15514.76,49.25,19184.19),(117,0.00,0.00,0.00,'none',259,'2023-11-14 13:46:22','2023-11-14 13:46:22',0.00,0.00,0.00,0.00,0.00,0.00),(118,0.00,0.00,0.00,'none',260,'2023-11-16 15:00:35','2023-11-16 15:07:18',0.00,5000.00,0.00,0.00,0.00,5000.00),(119,0.00,0.00,0.00,'none',261,'2023-11-16 22:38:35','2024-03-01 17:57:12',0.00,0.00,19310.00,0.00,0.00,17440.00),(120,0.00,0.00,0.00,'none',262,'2023-11-17 12:32:24','2024-01-19 01:39:17',0.00,0.00,296.12,0.00,0.00,296.12),(121,0.00,0.00,0.00,'none',263,'2023-11-20 10:16:48','2023-12-13 18:38:03',0.00,0.00,12.84,0.00,0.00,12.84),(122,0.00,0.00,0.00,'none',265,'2023-11-21 10:08:29','2023-12-11 16:35:27',0.00,490.00,110.00,0.00,0.00,600.00),(123,0.00,0.00,0.00,'none',266,'2023-11-22 11:53:44','2024-02-20 22:21:59',0.00,301.00,126.00,0.00,0.00,427.00),(124,0.00,0.00,0.00,'none',267,'2023-11-27 08:47:40','2023-11-27 08:47:40',0.00,0.00,0.00,0.00,0.00,0.00),(125,0.00,0.00,0.00,'none',268,'2023-11-27 11:33:41','2023-11-27 11:33:41',0.00,0.00,0.00,0.00,0.00,0.00),(126,0.00,0.00,0.00,'none',269,'2023-11-30 12:49:14','2023-11-30 12:49:14',0.00,0.00,0.00,0.00,0.00,0.00),(127,0.00,4864.91,0.00,'none',270,'2023-12-02 15:04:40','2024-03-01 19:46:20',0.00,5946.32,135.89,14600.00,152.94,15970.24),(128,0.00,0.00,0.00,'none',271,'2023-12-13 09:41:41','2024-03-01 16:04:52',0.00,3000.00,1187.50,1000.00,36.00,5223.50),(129,0.00,-80.63,0.00,'none',272,'2023-12-13 09:51:29','2024-03-01 19:36:49',0.00,133.20,99.38,7100.00,220.87,7634.08),(130,0.00,0.00,0.00,'none',273,'2023-12-20 13:06:16','2024-02-14 09:22:54',0.00,1130.80,5.00,0.00,0.00,1135.80),(131,0.00,0.00,0.00,'none',274,'2023-12-25 15:08:41','2024-02-04 16:25:42',0.00,2333.50,1465.75,0.00,0.00,3799.25),(132,0.00,14962.50,0.00,'none',275,'2023-12-29 15:23:11','2024-03-01 12:37:08',0.00,12650.00,2310.62,1000.00,0.00,998.12),(133,0.00,0.00,0.00,'none',276,'2024-01-01 18:22:11','2024-01-16 22:28:37',0.00,0.00,172.50,0.00,0.00,172.50),(134,0.00,0.00,0.00,'none',277,'2024-01-02 17:42:47','2024-03-01 20:19:37',0.00,0.00,16150.00,0.00,91.50,13667.50),(135,0.00,9987.50,0.00,'none',278,'2024-01-03 14:21:56','2024-02-27 11:26:37',0.00,10000.00,135.00,0.00,0.00,147.50),(136,0.00,0.00,0.00,'none',279,'2024-01-04 12:43:58','2024-02-17 10:26:04',0.00,1494.00,1.00,0.00,0.00,1495.00),(137,0.00,0.00,0.00,'none',280,'2024-01-04 12:48:07','2024-02-29 15:28:43',0.00,4778.90,376.18,9875.00,0.00,15030.08),(138,0.00,6981.25,0.00,'none',281,'2024-01-04 13:23:36','2024-02-28 21:45:39',0.00,0.00,765.00,18000.00,0.00,11783.75),(139,0.00,0.00,0.00,'none',282,'2024-01-05 11:29:10','2024-01-05 11:29:10',0.00,0.00,0.00,0.00,0.00,0.00),(140,0.00,0.00,0.00,'none',283,'2024-01-05 17:08:40','2024-01-05 17:08:40',0.00,0.00,0.00,0.00,0.00,0.00),(141,0.00,0.00,0.00,'none',284,'2024-01-08 15:57:27','2024-01-08 15:57:27',0.00,0.00,0.00,0.00,0.00,0.00),(142,0.00,0.00,0.00,'none',285,'2024-01-08 17:48:36','2024-02-29 09:34:52',0.00,5200.00,0.00,0.00,67.50,5245.00),(143,0.00,0.00,0.00,'none',286,'2024-01-09 13:27:16','2024-03-01 16:30:40',0.00,5421.26,100.00,4800.00,0.00,10321.26),(144,0.00,0.00,0.00,'none',287,'2024-01-11 09:40:00','2024-01-11 09:40:00',0.00,0.00,0.00,0.00,0.00,0.00),(145,0.00,0.00,0.00,'none',289,'2024-01-11 09:45:34','2024-01-11 09:45:34',0.00,0.00,0.00,0.00,0.00,0.00),(146,0.00,0.00,0.00,'none',290,'2024-01-11 13:14:38','2024-02-24 12:54:26',0.00,5950.00,295.00,500.00,0.00,6745.00),(147,0.00,0.00,0.00,'none',291,'2024-01-11 19:24:57','2024-02-26 10:00:31',0.00,2039.70,421.52,2100.00,0.00,4561.22),(148,0.00,0.00,0.00,'none',292,'2024-01-12 12:49:17','2024-01-12 12:49:17',0.00,0.00,0.00,0.00,0.00,0.00),(149,0.00,0.00,0.00,'none',293,'2024-01-12 15:25:17','2024-01-12 15:25:17',0.00,0.00,0.00,0.00,0.00,0.00),(150,0.00,0.00,0.00,'none',294,'2024-01-12 21:26:42','2024-01-12 21:32:56',0.00,5000.00,0.00,0.00,0.00,5000.00),(151,0.00,0.00,0.00,'none',296,'2024-01-13 18:24:02','2024-01-13 18:24:02',0.00,0.00,0.00,0.00,0.00,0.00),(152,0.00,0.00,0.00,'none',297,'2024-01-14 22:58:49','2024-03-01 20:15:07',0.00,3176.36,402.09,3250.00,0.00,6828.46),(153,0.00,0.00,0.00,'none',298,'2024-01-17 16:02:02','2024-02-29 09:34:52',0.00,0.00,125.00,2000.00,0.00,2125.00),(154,0.00,0.00,0.00,'none',299,'2024-01-18 10:29:07','2024-01-18 10:29:07',0.00,0.00,0.00,0.00,0.00,0.00),(155,0.00,0.00,0.00,'none',300,'2024-01-19 12:40:46','2024-01-19 12:40:46',0.00,0.00,0.00,0.00,0.00,0.00),(156,0.00,0.00,0.00,'none',301,'2024-01-19 16:41:23','2024-01-19 16:41:23',0.00,0.00,0.00,0.00,0.00,0.00),(157,0.00,0.00,0.00,'none',302,'2024-01-19 17:44:28','2024-02-28 14:54:44',0.00,3800.00,785.38,5300.00,0.00,9885.38),(158,0.00,0.00,0.00,'none',303,'2024-01-23 11:06:39','2024-01-23 11:06:39',0.00,0.00,0.00,0.00,0.00,0.00),(159,0.00,0.00,0.00,'none',304,'2024-01-24 10:57:15','2024-01-24 10:57:15',0.00,0.00,0.00,0.00,0.00,0.00),(160,0.00,0.00,0.00,'none',305,'2024-01-25 12:41:15','2024-01-25 16:05:10',0.00,0.00,37.50,0.00,0.00,37.50),(161,0.00,0.00,0.00,'none',306,'2024-01-27 18:57:44','2024-02-26 09:06:23',0.00,2000.00,62.50,2000.00,0.00,4062.50),(162,0.00,0.00,0.00,'none',307,'2024-01-29 15:11:20','2024-03-01 12:59:31',0.00,137.27,270.00,1800.00,0.00,2207.28),(163,0.00,0.00,0.00,'none',308,'2024-01-29 15:55:15','2024-02-29 10:01:30',0.00,2035.00,824.50,18000.00,0.00,20859.50),(164,0.00,8600.00,0.00,'none',309,'2024-01-31 19:33:13','2024-03-01 19:13:19',0.00,0.00,0.00,0.00,0.00,0.00),(165,0.00,0.00,0.00,'none',310,'2024-01-31 19:42:24','2024-01-31 19:42:24',0.00,0.00,0.00,0.00,0.00,0.00),(166,0.00,4858.30,0.00,'none',311,'2024-02-02 10:57:07','2024-03-01 12:33:14',0.00,3040.00,142.45,3340.00,0.00,1664.14),(167,0.00,0.00,0.00,'none',312,'2024-02-02 19:33:55','2024-02-02 19:33:55',0.00,0.00,0.00,0.00,0.00,0.00),(168,0.00,0.00,0.00,'none',313,'2024-02-05 23:46:53','2024-03-01 11:03:30',0.00,3691.38,0.00,0.00,22.50,3713.88),(169,0.00,9877.50,0.00,'none',315,'2024-02-06 11:34:35','2024-03-01 15:03:56',0.00,3511.00,122.94,6750.00,0.00,506.44),(170,0.00,0.00,0.00,'none',316,'2024-02-07 16:41:17','2024-03-01 16:04:52',0.00,1200.00,72.50,1100.00,0.00,2372.50),(171,0.00,0.00,0.00,'none',317,'2024-02-09 10:10:56','2024-03-01 19:10:20',0.00,200.00,122.50,1500.00,0.00,1822.50),(172,0.00,0.00,0.00,'none',318,'2024-02-11 16:13:18','2024-02-25 16:58:01',0.00,1000.00,185.00,1000.00,0.00,2185.00),(173,0.00,0.00,0.00,'none',319,'2024-02-11 16:20:03','2024-02-11 16:20:03',0.00,0.00,0.00,0.00,0.00,0.00),(174,0.00,0.00,0.00,'none',320,'2024-02-11 22:56:12','2024-02-23 20:28:00',0.00,82.51,0.01,0.00,0.00,82.52),(175,0.00,9806.87,0.00,'none',321,'2024-02-13 19:15:43','2024-03-01 19:08:27',0.00,14600.00,193.13,13700.00,0.00,18686.25),(176,0.00,0.00,0.00,'none',322,'2024-02-16 00:50:31','2024-02-16 00:50:31',0.00,0.00,0.00,0.00,0.00,0.00),(177,0.00,0.00,0.00,'none',324,'2024-02-18 17:45:40','2024-03-01 18:26:21',0.00,3800.00,305.00,1300.00,0.00,5405.00),(178,0.00,0.00,0.00,'none',325,'2024-02-18 17:55:19','2024-02-18 17:55:19',0.00,0.00,0.00,0.00,0.00,0.00),(179,0.00,0.00,0.00,'none',326,'2024-02-18 20:22:00','2024-02-28 20:16:32',0.00,8000.00,337.50,2000.00,0.00,10337.50),(180,0.00,0.00,0.00,'none',327,'2024-02-22 08:43:02','2024-02-23 12:19:24',0.00,0.00,47.50,2800.00,0.00,2847.50),(181,0.00,4825.00,0.00,'none',328,'2024-02-22 13:46:38','2024-03-01 11:03:30',0.00,4450.00,0.00,550.00,0.00,175.00),(182,0.00,0.00,0.00,'none',329,'2024-02-22 16:26:24','2024-02-22 16:26:24',0.00,0.00,0.00,0.00,0.00,0.00),(183,0.00,0.00,0.00,'none',330,'2024-02-23 19:55:00','2024-02-29 19:03:54',0.00,1150.00,270.00,4200.00,0.00,5620.00),(184,0.00,0.00,0.00,'none',331,'2024-02-26 11:45:40','2024-02-26 11:45:40',0.00,0.00,0.00,0.00,0.00,0.00),(185,0.00,0.00,0.00,'none',332,'2024-02-26 14:48:21','2024-02-28 09:49:55',0.00,150.00,50.00,1850.00,0.00,2050.00),(186,0.00,0.00,0.00,'none',333,'2024-02-26 15:56:49','2024-03-01 21:52:11',0.00,0.00,0.00,0.00,33.00,33.00),(187,0.00,0.00,0.00,'none',334,'2024-02-26 16:25:30','2024-02-26 16:25:30',0.00,0.00,0.00,0.00,0.00,0.00),(188,0.00,0.00,0.00,'none',336,'2024-02-26 16:44:08','2024-02-26 16:44:08',0.00,0.00,0.00,0.00,0.00,0.00),(189,0.00,0.00,0.00,'none',337,'2024-02-26 17:11:45','2024-03-01 21:52:11',0.00,0.00,62.50,3500.00,0.00,3562.50),(190,0.00,0.00,0.00,'none',338,'2024-02-26 17:34:15','2024-02-26 17:34:15',0.00,0.00,0.00,0.00,0.00,0.00),(191,0.00,0.00,0.00,'none',339,'2024-02-26 17:47:27','2024-02-26 17:47:27',0.00,0.00,0.00,0.00,0.00,0.00),(192,0.00,0.00,0.00,'none',340,'2024-02-27 15:51:34','2024-03-01 17:48:15',0.00,0.00,47.50,2200.00,0.00,2247.50),(193,0.00,0.00,0.00,'none',341,'2024-02-27 17:06:41','2024-02-27 17:06:41',0.00,0.00,0.00,0.00,0.00,0.00),(194,0.00,0.00,0.00,'none',342,'2024-02-27 17:18:08','2024-02-27 17:18:08',0.00,0.00,0.00,0.00,0.00,0.00),(195,0.00,0.00,0.00,'none',343,'2024-02-27 17:44:09','2024-02-27 17:44:09',0.00,0.00,0.00,0.00,0.00,0.00),(196,0.00,0.00,0.00,'none',344,'2024-02-28 17:09:25','2024-02-28 17:09:25',0.00,0.00,0.00,0.00,0.00,0.00),(197,0.00,0.00,0.00,'none',345,'2024-02-29 14:18:20','2024-02-29 14:18:20',0.00,0.00,0.00,0.00,0.00,0.00),(198,0.00,0.00,0.00,'none',346,'2024-02-29 19:54:27','2024-03-01 18:36:39',0.00,0.00,72.50,3600.00,0.00,3672.50);
/*!40000 ALTER TABLE `user_accounts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-02  0:26:02
