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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2013_08_25_120546_create_departments_table',1),(2,'2014_10_12_000000_create_users_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2023_04_04_003610_create_clients_table',1),(7,'2023_04_07_220858_create_notifications_table',1),(8,'2023_04_08_163019_create_permission_tables',1),(9,'2023_05_03_223345_create_transactions_table',1),(10,'2023_05_19_221230_create_rates_table',1),(11,'2023_05_26_172138_create_accounts_table',1),(12,'2023_05_26_174239_create_account_users_table',1),(13,'2023_06_07_012700_create_providers_table',1),(14,'2023_06_24_133307_create_transaction_comments_table',1),(15,'2023_06_27_162307_create_user_accounts_table',1),(16,'2023_06_27_162930_create_system_accounts_table',1),(17,'2023_06_27_163549_create_user_bank_accounts_table',1),(18,'2023_06_27_163712_create_system_bank_accounts_table',1),(19,'2023_06_29_200407_create_agent_deposits_table',1),(20,'2023_07_15_134606_create_cashouts_table',1),(21,'2023_07_15_152634_create_cashins_table',1),(22,'2023_07_27_104216_create_account_transferts_table',1),(23,'2023_07_29_150155_create_provider_payments_table',1),(24,'2023_08_11_223441_create_user_visits_table',1),(25,'2023_08_16_135217_create_role_and_permissions_table',1),(26,'2023_09_09_114330_create_replenishments_table',1),(27,'2023_09_12_130330_create_loans_table',1),(28,'2023_09_15_115527_create_expenses_table',1),(29,'2023_09_15_122306_create_user_investments_table',1),(30,'2023_09_20_202609_create_replenishment_requirements_table',1),(31,'2023_10_13_170045_create_expense_types_table',1),(32,'2023_10_15_231929_create_agent_investments_table',1),(33,'2023_10_15_232335_create_agent_debt_deposits_table',1),(34,'2023_10_20_211400_create_transfert_profit_to_recharges_table',1),(35,'2023_10_23_131648_create_envoy_deposits_table',1),(36,'2023_10_27_143213_create_send_money_table',1),(37,'2023_11_10_191533_create_account_activits_table',1),(38,'2023_11_22_160325_create_transaction_activits_table',1),(39,'2023_11_23_160142_create_user_activits_table',1),(40,'2023_11_27_171820_create_send_money_activits_table',1),(41,'2023_11_28_193509_create_agent_deposit_activits_table',1),(42,'2023_12_01_223622_create_cashout_activits_table',1),(43,'2023_12_04_081416_create_jobs_table',1),(44,'2023_12_08_192248_create_cancel_transactions_table',1),(45,'2024_01_02_211430_create_user_sorts_table',1),(46,'2024_01_02_213216_create_context_sorts_table',1),(47,'2024_01_02_213540_create_context_sort_user_sort_table',1),(48,'2024_01_18_132308_create_envoy_deposit_activits_table',1),(49,'2024_01_23_171628_create_cashin_activits_table',1),(50,'2024_01_23_221244_create_account_transfert_activits_table',1),(51,'2024_01_28_141836_create_envoy_transferts_table',1),(52,'2024_01_28_151832_create_envoy_transfert_activits_table',1),(53,'2024_02_05_124011_create_agent_loans_table',1),(54,'2024_02_05_124614_create_agent_loan_activits_table',1),(55,'2024_02_07_204202_create_agent_loan_repays_table',1),(56,'2024_02_07_204203_create_agent_loan_repay_activits_table',1),(57,'2024_02_09_193617_create_agent_loan_transaction_repays_table',1),(58,'2024_02_09_194418_create_agent_loan_transaction_repay_activits_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-02  0:22:59
