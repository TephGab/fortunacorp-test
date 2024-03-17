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
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(1,'App\\Models\\User',3),(1,'App\\Models\\User',4),(2,'App\\Models\\User',5),(2,'App\\Models\\User',6),(3,'App\\Models\\User',7),(5,'App\\Models\\User',8),(5,'App\\Models\\User',9),(3,'App\\Models\\User',10),(3,'App\\Models\\User',11),(3,'App\\Models\\User',12),(3,'App\\Models\\User',13),(3,'App\\Models\\User',14),(3,'App\\Models\\User',15),(3,'App\\Models\\User',16),(3,'App\\Models\\User',17),(3,'App\\Models\\User',18),(3,'App\\Models\\User',19),(3,'App\\Models\\User',20),(3,'App\\Models\\User',21),(3,'App\\Models\\User',22),(3,'App\\Models\\User',23),(3,'App\\Models\\User',24),(3,'App\\Models\\User',25),(3,'App\\Models\\User',26),(3,'App\\Models\\User',27),(3,'App\\Models\\User',28),(3,'App\\Models\\User',29),(3,'App\\Models\\User',30),(3,'App\\Models\\User',31),(3,'App\\Models\\User',32),(3,'App\\Models\\User',33),(3,'App\\Models\\User',34),(3,'App\\Models\\User',35),(3,'App\\Models\\User',36),(3,'App\\Models\\User',37),(3,'App\\Models\\User',38),(3,'App\\Models\\User',39),(3,'App\\Models\\User',40),(3,'App\\Models\\User',42),(3,'App\\Models\\User',43),(3,'App\\Models\\User',44),(3,'App\\Models\\User',45),(3,'App\\Models\\User',46),(3,'App\\Models\\User',47),(3,'App\\Models\\User',48),(3,'App\\Models\\User',49),(3,'App\\Models\\User',50),(3,'App\\Models\\User',51),(3,'App\\Models\\User',52),(3,'App\\Models\\User',53),(3,'App\\Models\\User',54),(3,'App\\Models\\User',55),(3,'App\\Models\\User',56),(3,'App\\Models\\User',57),(3,'App\\Models\\User',59),(3,'App\\Models\\User',60),(3,'App\\Models\\User',61),(3,'App\\Models\\User',62),(3,'App\\Models\\User',63),(3,'App\\Models\\User',64),(3,'App\\Models\\User',65),(3,'App\\Models\\User',66),(3,'App\\Models\\User',67),(3,'App\\Models\\User',68),(3,'App\\Models\\User',69),(3,'App\\Models\\User',70),(3,'App\\Models\\User',71),(3,'App\\Models\\User',73),(3,'App\\Models\\User',74),(5,'App\\Models\\User',75),(3,'App\\Models\\User',76),(3,'App\\Models\\User',77),(3,'App\\Models\\User',78),(3,'App\\Models\\User',79),(3,'App\\Models\\User',80),(3,'App\\Models\\User',81),(3,'App\\Models\\User',82),(3,'App\\Models\\User',83),(3,'App\\Models\\User',84),(3,'App\\Models\\User',85),(3,'App\\Models\\User',86),(3,'App\\Models\\User',87),(3,'App\\Models\\User',88),(3,'App\\Models\\User',89),(3,'App\\Models\\User',90),(3,'App\\Models\\User',91),(4,'App\\Models\\User',92),(4,'App\\Models\\User',93),(3,'App\\Models\\User',94),(3,'App\\Models\\User',95),(3,'App\\Models\\User',96),(3,'App\\Models\\User',97),(3,'App\\Models\\User',98),(3,'App\\Models\\User',99),(3,'App\\Models\\User',100),(3,'App\\Models\\User',101),(3,'App\\Models\\User',102),(4,'App\\Models\\User',103),(5,'App\\Models\\User',104),(3,'App\\Models\\User',105),(3,'App\\Models\\User',106),(3,'App\\Models\\User',107),(3,'App\\Models\\User',108),(3,'App\\Models\\User',109),(3,'App\\Models\\User',110),(3,'App\\Models\\User',111),(3,'App\\Models\\User',112),(3,'App\\Models\\User',113),(3,'App\\Models\\User',114),(3,'App\\Models\\User',115),(3,'App\\Models\\User',116),(3,'App\\Models\\User',117),(3,'App\\Models\\User',118),(2,'App\\Models\\User',119),(3,'App\\Models\\User',120),(3,'App\\Models\\User',121),(3,'App\\Models\\User',122),(3,'App\\Models\\User',123),(3,'App\\Models\\User',124),(3,'App\\Models\\User',125),(3,'App\\Models\\User',126),(3,'App\\Models\\User',127),(3,'App\\Models\\User',128),(6,'App\\Models\\User',129),(3,'App\\Models\\User',130),(3,'App\\Models\\User',131),(6,'App\\Models\\User',132),(3,'App\\Models\\User',133),(3,'App\\Models\\User',134),(3,'App\\Models\\User',135),(3,'App\\Models\\User',136),(3,'App\\Models\\User',137),(3,'App\\Models\\User',138),(3,'App\\Models\\User',139),(3,'App\\Models\\User',140),(3,'App\\Models\\User',141),(3,'App\\Models\\User',142),(1,'App\\Models\\User',143),(2,'App\\Models\\User',144),(2,'App\\Models\\User',145),(1,'App\\Models\\User',146),(1,'App\\Models\\User',147),(6,'App\\Models\\User',148),(6,'App\\Models\\User',149),(4,'App\\Models\\User',150),(4,'App\\Models\\User',151),(4,'App\\Models\\User',152),(3,'App\\Models\\User',153),(5,'App\\Models\\User',154),(5,'App\\Models\\User',155),(5,'App\\Models\\User',156),(5,'App\\Models\\User',157),(3,'App\\Models\\User',158),(3,'App\\Models\\User',159),(3,'App\\Models\\User',160),(3,'App\\Models\\User',161),(3,'App\\Models\\User',162),(3,'App\\Models\\User',163),(3,'App\\Models\\User',164),(3,'App\\Models\\User',165),(3,'App\\Models\\User',166),(3,'App\\Models\\User',167),(3,'App\\Models\\User',168),(3,'App\\Models\\User',169),(3,'App\\Models\\User',170),(3,'App\\Models\\User',171),(3,'App\\Models\\User',172),(3,'App\\Models\\User',173),(3,'App\\Models\\User',174),(3,'App\\Models\\User',175),(3,'App\\Models\\User',176),(3,'App\\Models\\User',177),(3,'App\\Models\\User',178),(3,'App\\Models\\User',179),(3,'App\\Models\\User',180),(3,'App\\Models\\User',181),(3,'App\\Models\\User',182),(3,'App\\Models\\User',183),(3,'App\\Models\\User',184),(3,'App\\Models\\User',185),(3,'App\\Models\\User',186),(3,'App\\Models\\User',187),(3,'App\\Models\\User',188),(3,'App\\Models\\User',189),(3,'App\\Models\\User',190),(3,'App\\Models\\User',191),(3,'App\\Models\\User',192),(3,'App\\Models\\User',193),(3,'App\\Models\\User',194),(3,'App\\Models\\User',195),(3,'App\\Models\\User',196),(3,'App\\Models\\User',197),(3,'App\\Models\\User',198),(3,'App\\Models\\User',199),(3,'App\\Models\\User',200),(3,'App\\Models\\User',201),(3,'App\\Models\\User',202),(3,'App\\Models\\User',203),(3,'App\\Models\\User',204),(3,'App\\Models\\User',205),(3,'App\\Models\\User',206),(3,'App\\Models\\User',207),(3,'App\\Models\\User',208),(3,'App\\Models\\User',209),(3,'App\\Models\\User',210),(3,'App\\Models\\User',211),(3,'App\\Models\\User',212),(3,'App\\Models\\User',213),(3,'App\\Models\\User',214),(3,'App\\Models\\User',215),(3,'App\\Models\\User',216),(3,'App\\Models\\User',217),(3,'App\\Models\\User',218),(3,'App\\Models\\User',219),(3,'App\\Models\\User',220),(3,'App\\Models\\User',221),(3,'App\\Models\\User',222),(3,'App\\Models\\User',223),(3,'App\\Models\\User',224),(3,'App\\Models\\User',225),(3,'App\\Models\\User',226),(3,'App\\Models\\User',227),(3,'App\\Models\\User',228),(3,'App\\Models\\User',229),(3,'App\\Models\\User',230),(3,'App\\Models\\User',231),(3,'App\\Models\\User',232),(3,'App\\Models\\User',233),(3,'App\\Models\\User',234),(3,'App\\Models\\User',235),(3,'App\\Models\\User',236),(3,'App\\Models\\User',237),(3,'App\\Models\\User',238),(3,'App\\Models\\User',239),(3,'App\\Models\\User',240),(3,'App\\Models\\User',241),(3,'App\\Models\\User',242),(3,'App\\Models\\User',243),(3,'App\\Models\\User',244),(3,'App\\Models\\User',245),(3,'App\\Models\\User',246),(3,'App\\Models\\User',247),(3,'App\\Models\\User',248),(3,'App\\Models\\User',249),(3,'App\\Models\\User',250),(4,'App\\Models\\User',251),(5,'App\\Models\\User',252),(4,'App\\Models\\User',253),(3,'App\\Models\\User',254),(4,'App\\Models\\User',255),(3,'App\\Models\\User',256),(3,'App\\Models\\User',257),(3,'App\\Models\\User',258),(3,'App\\Models\\User',259),(3,'App\\Models\\User',260),(4,'App\\Models\\User',261),(3,'App\\Models\\User',262),(3,'App\\Models\\User',263),(3,'App\\Models\\User',265),(3,'App\\Models\\User',266),(3,'App\\Models\\User',267),(3,'App\\Models\\User',268),(3,'App\\Models\\User',269),(3,'App\\Models\\User',270),(3,'App\\Models\\User',271),(3,'App\\Models\\User',272),(3,'App\\Models\\User',273),(3,'App\\Models\\User',274),(3,'App\\Models\\User',275),(3,'App\\Models\\User',276),(4,'App\\Models\\User',277),(3,'App\\Models\\User',278),(3,'App\\Models\\User',279),(3,'App\\Models\\User',280),(3,'App\\Models\\User',281),(3,'App\\Models\\User',282),(3,'App\\Models\\User',283),(3,'App\\Models\\User',284),(2,'App\\Models\\User',285),(3,'App\\Models\\User',286),(3,'App\\Models\\User',287),(3,'App\\Models\\User',289),(3,'App\\Models\\User',290),(3,'App\\Models\\User',291),(3,'App\\Models\\User',292),(3,'App\\Models\\User',293),(3,'App\\Models\\User',294),(3,'App\\Models\\User',296),(3,'App\\Models\\User',297),(3,'App\\Models\\User',298),(3,'App\\Models\\User',299),(3,'App\\Models\\User',300),(3,'App\\Models\\User',301),(3,'App\\Models\\User',302),(3,'App\\Models\\User',303),(3,'App\\Models\\User',304),(3,'App\\Models\\User',305),(3,'App\\Models\\User',306),(3,'App\\Models\\User',307),(3,'App\\Models\\User',308),(5,'App\\Models\\User',309),(3,'App\\Models\\User',310),(3,'App\\Models\\User',311),(3,'App\\Models\\User',312),(3,'App\\Models\\User',313),(3,'App\\Models\\User',315),(3,'App\\Models\\User',316),(3,'App\\Models\\User',317),(3,'App\\Models\\User',318),(3,'App\\Models\\User',319),(3,'App\\Models\\User',320),(3,'App\\Models\\User',321),(3,'App\\Models\\User',322),(3,'App\\Models\\User',324),(3,'App\\Models\\User',325),(3,'App\\Models\\User',326),(3,'App\\Models\\User',327),(3,'App\\Models\\User',328),(3,'App\\Models\\User',329),(3,'App\\Models\\User',330),(3,'App\\Models\\User',331),(3,'App\\Models\\User',332),(3,'App\\Models\\User',333),(3,'App\\Models\\User',334),(3,'App\\Models\\User',336),(3,'App\\Models\\User',337),(3,'App\\Models\\User',338),(3,'App\\Models\\User',339),(3,'App\\Models\\User',340),(3,'App\\Models\\User',341),(3,'App\\Models\\User',342),(3,'App\\Models\\User',343),(3,'App\\Models\\User',344),(3,'App\\Models\\User',345),(3,'App\\Models\\User',346);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-02  0:21:05
