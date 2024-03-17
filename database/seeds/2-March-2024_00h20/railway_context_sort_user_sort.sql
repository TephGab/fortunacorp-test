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
-- Table structure for table `context_sort_user_sort`
--

DROP TABLE IF EXISTS `context_sort_user_sort`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `context_sort_user_sort` (
  `context_sort_id` bigint unsigned NOT NULL,
  `user_sort_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`context_sort_id`,`user_sort_id`,`user_id`),
  KEY `context_sort_user_sort_user_sort_id_foreign` (`user_sort_id`),
  KEY `context_sort_user_sort_user_id_foreign` (`user_id`),
  CONSTRAINT `context_sort_user_sort_context_sort_id_foreign` FOREIGN KEY (`context_sort_id`) REFERENCES `context_sorts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `context_sort_user_sort_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `context_sort_user_sort_user_sort_id_foreign` FOREIGN KEY (`user_sort_id`) REFERENCES `user_sorts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `context_sort_user_sort`
--

LOCK TABLES `context_sort_user_sort` WRITE;
/*!40000 ALTER TABLE `context_sort_user_sort` DISABLE KEYS */;
INSERT INTO `context_sort_user_sort` VALUES (1,1,1,'2024-01-20 08:29:48','2024-01-20 08:29:48'),(1,1,143,'2024-01-21 23:55:33','2024-01-21 23:55:33'),(1,1,146,'2024-01-17 19:12:51','2024-01-17 19:12:51'),(1,1,148,'2024-01-31 11:38:33','2024-01-31 11:38:33'),(1,1,149,'2024-01-19 15:40:29','2024-01-19 15:40:29'),(1,1,150,'2024-01-24 21:01:46','2024-01-24 21:01:46'),(1,1,152,'2024-01-13 16:57:16','2024-01-13 16:57:16'),(1,1,155,'2024-01-20 02:26:38','2024-01-20 02:26:38'),(1,1,156,'2024-01-17 13:05:08','2024-01-17 13:05:08'),(1,1,157,'2024-01-31 13:41:52','2024-01-31 13:41:52'),(1,1,158,'2024-01-13 18:31:10','2024-01-13 18:31:10'),(1,1,159,'2024-01-15 15:43:21','2024-01-15 15:43:21'),(1,1,161,'2024-01-13 09:34:20','2024-01-13 09:34:20'),(1,1,162,'2024-01-22 15:50:05','2024-01-22 15:50:05'),(1,1,163,'2024-01-30 15:07:06','2024-01-30 15:07:06'),(1,1,164,'2024-01-14 14:29:12','2024-01-14 14:29:12'),(1,1,166,'2024-01-17 19:04:21','2024-01-17 19:04:21'),(1,1,168,'2024-01-19 17:51:40','2024-01-19 17:51:40'),(1,1,171,'2024-01-26 16:47:44','2024-01-26 16:47:44'),(1,1,172,'2024-01-26 17:55:54','2024-01-26 17:55:54'),(1,1,175,'2024-01-31 13:12:26','2024-01-31 13:12:26'),(1,1,180,'2024-01-13 20:22:26','2024-01-13 20:22:26'),(1,1,181,'2024-01-25 14:55:37','2024-01-25 14:55:37'),(1,1,182,'2024-01-18 19:47:26','2024-01-18 19:47:26'),(1,1,190,'2024-01-14 17:47:46','2024-01-14 17:47:46'),(1,1,191,'2024-01-30 20:39:43','2024-01-30 20:39:43'),(1,1,192,'2024-01-21 15:11:28','2024-01-21 15:11:28'),(1,1,197,'2024-01-29 11:09:59','2024-01-29 11:09:59'),(1,1,198,'2024-01-15 13:05:22','2024-01-15 13:05:22'),(1,1,199,'2024-01-16 15:17:08','2024-01-16 15:17:08'),(1,1,200,'2024-01-14 11:47:58','2024-01-14 11:47:58'),(1,1,205,'2024-01-23 10:52:34','2024-01-23 10:52:34'),(1,1,208,'2024-01-19 19:31:21','2024-01-19 19:31:21'),(1,1,211,'2024-01-15 09:58:03','2024-01-15 09:58:03'),(1,1,212,'2024-01-25 23:52:33','2024-01-25 23:52:33'),(1,1,217,'2024-01-31 07:30:34','2024-01-31 07:30:34'),(1,1,223,'2024-01-15 13:43:56','2024-01-15 13:43:56'),(1,1,231,'2024-01-19 09:48:04','2024-01-19 09:48:04'),(1,1,236,'2024-01-18 18:51:49','2024-01-18 18:51:49'),(1,1,238,'2024-01-21 12:59:02','2024-01-21 12:59:02'),(1,1,240,'2024-01-24 20:03:51','2024-01-24 20:03:51'),(1,1,245,'2024-01-17 00:15:06','2024-01-17 00:15:06'),(1,1,247,'2024-02-12 08:41:08','2024-02-12 08:41:08'),(1,1,248,'2024-01-14 21:06:25','2024-01-14 21:06:25'),(1,1,251,'2024-01-26 17:53:23','2024-01-26 17:53:23'),(1,1,252,'2024-01-26 00:05:39','2024-01-26 00:05:39'),(1,1,253,'2024-01-14 02:02:18','2024-01-14 02:02:18'),(1,1,256,'2024-01-13 17:18:56','2024-01-13 17:18:56'),(1,1,258,'2024-01-15 10:36:18','2024-01-15 10:36:18'),(1,1,261,'2024-01-24 09:45:44','2024-01-24 09:45:44'),(1,1,262,'2024-01-20 02:34:26','2024-01-20 02:34:26'),(1,1,266,'2024-01-22 08:05:02','2024-01-22 08:05:02'),(1,1,270,'2024-01-14 15:39:08','2024-01-14 15:39:08'),(1,1,276,'2024-01-15 08:46:59','2024-01-15 08:46:59'),(1,1,278,'2024-02-23 18:06:42','2024-02-23 18:06:42'),(1,1,279,'2024-01-25 14:12:33','2024-01-25 14:12:33'),(1,1,280,'2024-01-22 21:47:29','2024-01-22 21:47:29'),(1,1,286,'2024-02-05 12:50:34','2024-02-05 12:50:34'),(1,1,291,'2024-01-18 09:33:32','2024-01-18 09:33:32'),(1,1,293,'2024-01-13 07:39:02','2024-01-13 07:39:02'),(1,1,297,'2024-01-14 23:21:53','2024-01-14 23:21:53'),(1,1,298,'2024-01-18 18:53:39','2024-01-18 18:53:39'),(1,1,304,'2024-01-24 10:57:35','2024-01-24 10:57:35'),(1,1,307,'2024-01-29 15:11:37','2024-01-29 15:11:37'),(1,1,309,'2024-01-31 20:08:51','2024-01-31 20:08:51'),(1,2,148,'2024-01-31 11:39:01','2024-01-31 11:39:01'),(1,2,247,'2024-02-12 08:32:23','2024-02-12 08:32:23'),(1,2,252,'2024-02-16 17:35:17','2024-02-16 17:35:17'),(1,3,148,'2024-01-31 11:39:03','2024-01-31 11:39:03'),(1,3,247,'2024-02-12 08:32:26','2024-02-12 08:32:26'),(1,3,252,'2024-02-16 17:35:20','2024-02-16 17:35:20'),(1,4,145,'2024-02-03 22:28:34','2024-02-03 22:28:34'),(1,4,147,'2024-02-16 09:08:59','2024-02-16 09:08:59'),(1,4,151,'2024-02-24 00:15:38','2024-02-24 00:15:38'),(1,4,153,'2024-02-26 00:57:18','2024-02-26 00:57:18'),(1,4,154,'2024-02-03 11:53:05','2024-02-03 11:53:05'),(1,4,155,'2024-02-03 08:34:27','2024-02-03 08:34:27'),(1,4,158,'2024-02-03 01:31:34','2024-02-03 01:31:34'),(1,4,164,'2024-02-10 01:07:30','2024-02-10 01:07:30'),(1,4,170,'2024-02-23 18:23:35','2024-02-23 18:23:35'),(1,4,173,'2024-02-23 16:50:41','2024-02-23 16:50:41'),(1,4,176,'2024-02-25 12:29:33','2024-02-25 12:29:33'),(1,4,179,'2024-02-14 13:24:25','2024-02-14 13:24:25'),(1,4,182,'2024-02-03 01:02:02','2024-02-03 01:02:02'),(1,4,188,'2024-02-10 21:58:31','2024-02-10 21:58:31'),(1,4,191,'2024-02-25 10:05:51','2024-02-25 10:05:51'),(1,4,194,'2024-02-09 17:45:45','2024-02-09 17:45:45'),(1,4,195,'2024-02-16 17:33:06','2024-02-16 17:33:06'),(1,4,200,'2024-02-28 13:18:09','2024-02-28 13:18:09'),(1,4,201,'2024-02-18 22:19:55','2024-02-18 22:19:55'),(1,4,202,'2024-02-04 16:53:07','2024-02-04 16:53:07'),(1,4,204,'2024-02-27 13:00:25','2024-02-27 13:00:25'),(1,4,208,'2024-02-08 15:25:20','2024-02-08 15:25:20'),(1,4,210,'2024-02-20 12:51:42','2024-02-20 12:51:42'),(1,4,219,'2024-02-17 23:53:03','2024-02-17 23:53:03'),(1,4,228,'2024-02-28 09:49:51','2024-02-28 09:49:51'),(1,4,233,'2024-02-26 08:03:08','2024-02-26 08:03:08'),(1,4,247,'2024-02-12 08:32:12','2024-02-12 08:32:12'),(1,4,252,'2024-02-03 01:05:40','2024-02-03 01:05:40'),(1,4,271,'2024-02-18 09:16:28','2024-02-18 09:16:28'),(1,4,273,'2024-02-12 22:49:22','2024-02-12 22:49:22'),(1,4,275,'2024-02-06 17:43:35','2024-02-06 17:43:35'),(1,4,277,'2024-02-23 08:42:33','2024-02-23 08:42:33'),(1,4,278,'2024-02-23 18:06:31','2024-02-23 18:06:31'),(1,4,281,'2024-02-08 08:43:28','2024-02-08 08:43:28'),(1,4,286,'2024-02-04 03:01:13','2024-02-04 03:01:13'),(1,4,290,'2024-02-01 13:33:51','2024-02-01 13:33:51'),(1,4,308,'2024-02-12 18:53:48','2024-02-12 18:53:48'),(1,4,311,'2024-02-09 21:58:17','2024-02-09 21:58:17'),(1,4,313,'2024-02-06 08:05:39','2024-02-06 08:05:39'),(1,4,315,'2024-02-06 17:04:16','2024-02-06 17:04:16'),(1,4,316,'2024-02-12 18:52:43','2024-02-12 18:52:43'),(1,4,317,'2024-02-25 22:11:11','2024-02-25 22:11:11'),(1,4,319,'2024-02-25 10:42:07','2024-02-25 10:42:07'),(1,4,320,'2024-02-13 16:18:13','2024-02-13 16:18:13'),(1,4,324,'2024-02-20 19:41:37','2024-02-20 19:41:37'),(1,4,329,'2024-02-22 23:03:31','2024-02-22 23:03:31'),(1,4,337,'2024-02-27 14:04:28','2024-02-27 14:04:28'),(1,4,338,'2024-02-26 17:38:46','2024-02-26 17:38:46'),(1,5,247,'2024-02-12 08:41:05','2024-02-12 08:41:05'),(1,5,279,'2024-02-04 09:20:00','2024-02-04 09:20:00'),(1,6,151,'2024-03-01 22:48:43','2024-03-01 22:48:43'),(1,6,177,'2024-03-01 20:27:18','2024-03-01 20:27:18'),(1,6,243,'2024-03-01 00:45:51','2024-03-01 00:45:51'),(1,6,252,'2024-03-01 19:40:52','2024-03-01 19:40:52'),(1,6,286,'2024-02-08 13:44:07','2024-02-08 13:44:07'),(1,7,151,'2024-03-01 22:48:41','2024-03-01 22:48:41'),(1,7,286,'2024-02-08 13:44:07','2024-02-08 13:44:07'),(1,8,286,'2024-02-08 13:44:07','2024-02-08 13:44:07'),(1,9,252,'2024-02-16 17:35:26','2024-02-16 17:35:26');
/*!40000 ALTER TABLE `context_sort_user_sort` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-02  0:23:09
