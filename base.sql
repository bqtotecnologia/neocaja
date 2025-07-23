-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: neocaja
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.24.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cedula` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `names` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `surnames` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_student` tinyint(1) NOT NULL,
  `scholarship` int unsigned DEFAULT NULL,
  `scholarship_coverage` int DEFAULT NULL,
  `company` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `cedula_UNIQUE` (`cedula`),
  KEY `accounts_scholarship_idx` (`scholarship`),
  KEY `accounts_company_idx` (`company`),
  CONSTRAINT `accounts_company` FOREIGN KEY (`company`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `accounts_scholarship` FOREIGN KEY (`scholarship`) REFERENCES `scholarships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (2,'28019228','GABRIEL ALBERTO','SILVA MONTILLA','carrera 17 entre calles 45 y 46 #45-29',1,1,NULL,NULL,'2025-07-23 10:18:57'),(3,'29997652','ABEL JOSUÉ','GIMÉNEZ PÉREZ','Calle 47 entre carreras 28 y 29 #28-57, Barrio Simón Rodríguez I',1,NULL,NULL,NULL,'2025-07-23 10:20:22'),(4,'30979407','Hehlenn Raissel','Aguero Rodriguez','Calle 49 entre carrera 16 y 17 casa 16-69',1,NULL,NULL,NULL,'2025-07-23 10:20:33');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `cedula` varchar(11) NOT NULL,
  `role` int unsigned NOT NULL,
  `active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `cedula_UNIQUE` (`cedula`),
  KEY `admins_role_idx` (`role`),
  CONSTRAINT `admins_role` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(4) COLLATE utf8mb4_general_ci NOT NULL,
  `active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` VALUES (1,'100%BANCO','0156',1,'2025-07-23 09:31:55'),(2,'ABN AMRO BANK','0196',1,'2025-07-23 09:31:55'),(3,'BANCAMIGA BANCO MICROFINANCIERO, C.A.','0172',1,'2025-07-23 09:31:55'),(4,'BANCO ACTIVO BANCO COMERCIAL, C.A.','0171',1,'2025-07-23 09:31:55'),(5,'BANCO AGRICOLA','0166',1,'2025-07-23 09:31:55'),(6,'BANCO BICENTENARIO','0175',1,'2025-07-23 09:31:55'),(7,'BANCO CARONI C.A. BANCO UNIVERSAL','0128',1,'2025-07-23 09:31:55'),(8,'BANCO DE DESARROLLO DEL MICROEMPRESARIO','0164',1,'2025-07-23 09:31:55'),(9,'BANCO DE VENEZUELA S.A.I.C.A.','0102',1,'2025-07-23 09:31:55'),(10,'BANCO DEL CARIBE C.A.','0114',1,'2025-07-23 09:31:55'),(11,'BANCO DEL PUEBLO SOBERANO C.A.','0149',1,'2025-07-23 09:31:55'),(12,'BANCO DEL TESORO','0163',1,'2025-07-23 09:31:55'),(13,'BANCO ESPIRITO SANTO S.A.','0176',1,'2025-07-23 09:31:55'),(14,'BANCO EXTERIOR C.A.','0115',1,'2025-07-23 09:31:55'),(15,'BANCO INDUSTRIAL DE VENEZUELA.','0003',1,'2025-07-23 09:31:55'),(16,'BANCO INTERNACIONAL DE DESARROLLO C.A.','0173',1,'2025-07-23 09:31:55'),(17,'BANCO MERCANTIL C.A.','0105',1,'2025-07-23 09:31:55'),(18,'BANCO NACIONAL DE CREDITO','0191',1,'2025-07-23 09:31:55'),(19,'BANCO OCCIDENTAL DE DESCUENTO.','0116',1,'2025-07-23 09:31:55'),(20,'BANCO PLAZA','0138',1,'2025-07-23 09:31:55'),(21,'BANCO PROVINCIAL BBVA','0108',1,'2025-07-23 09:31:55'),(22,'BANCO VENEZOLANO DE CREDITO S.A.','0104',1,'2025-07-23 09:31:55'),(23,'BANCRECER S.A. BANCO DE DESARROLLO','0168',1,'2025-07-23 09:31:55'),(24,'BANESCO BANCO UNIVERSAL','0134',1,'2025-07-23 09:31:55'),(25,'BANFANB','0177',1,'2025-07-23 09:31:55'),(26,'BANGENTE','0146',1,'2025-07-23 09:31:55'),(27,'BANPLUS BANCO COMERCIAL C.A','0174',1,'2025-07-23 09:31:55'),(28,'CITIBANK','0190',1,'2025-07-23 09:31:55'),(29,'CORP BANCA','0121',1,'2025-07-23 09:31:55'),(30,'DELSUR BANCO UNIVERSAL','0157',1,'2025-07-23 09:31:55'),(31,'FONDO COMUN','0151',1,'2025-07-23 09:31:55'),(32,'INSTITUTO MUNICIPAL DE CRÉDITO POPULAR','0601',1,'2025-07-23 09:31:55'),(33,'MIBANCO BANCO DE DESARROLLO C.A.','0169',1,'2025-07-23 09:31:55'),(34,'SOFITASA','0137',1,'2025-07-23 09:31:55');
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `binnacle`
--

DROP TABLE IF EXISTS `binnacle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `binnacle` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `action` text NOT NULL,
  `user` int unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `binnacle_admin_idx` (`user`),
  CONSTRAINT `binnacle_admin` FOREIGN KEY (`user`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `binnacle`
--

LOCK TABLES `binnacle` WRITE;
/*!40000 ALTER TABLE `binnacle` DISABLE KEYS */;
/*!40000 ALTER TABLE `binnacle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coin_history`
--

DROP TABLE IF EXISTS `coin_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coin_history` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `coin` int unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `current` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `coin_history_coin_idx` (`coin`),
  CONSTRAINT `coin_history_coin` FOREIGN KEY (`coin`) REFERENCES `coins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coin_history`
--

LOCK TABLES `coin_history` WRITE;
/*!40000 ALTER TABLE `coin_history` DISABLE KEYS */;
INSERT INTO `coin_history` VALUES (1,1,0.00,1,'2025-07-23 10:23:44'),(2,2,120.42,1,'2025-07-23 10:24:12'),(3,3,141.32,1,'2025-07-23 10:24:34');
/*!40000 ALTER TABLE `coin_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coins`
--

DROP TABLE IF EXISTS `coins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `url` text COLLATE utf8mb4_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `auto_update` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coins`
--

LOCK TABLES `coins` WRITE;
/*!40000 ALTER TABLE `coins` DISABLE KEYS */;
INSERT INTO `coins` VALUES (1,'Bolívar','https://neocaja.iujobarquisimeto.com/ves',1,0,'2025-07-23 10:23:44'),(2,'Dólar','https://neocaja.iujobarquisimeto.com/usd',1,1,'2025-07-23 10:24:12'),(3,'Euro','https://neocaja.iujobarquisimeto.com/eur',1,1,'2025-07-23 10:24:34');
/*!40000 ALTER TABLE `coins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rif_letter` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `rif_number` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Granel SA','V','123456789','carrera 18 entre calles 47 y 48','2025-07-23 10:20:03');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `concepts`
--

DROP TABLE IF EXISTS `concepts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `concepts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product` int unsigned NOT NULL,
  `price` float(10,2) NOT NULL,
  `invoice` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `concepts_product_history_idx` (`product`),
  KEY `concepts_invoice_idx` (`invoice`),
  CONSTRAINT `concepts_invoice` FOREIGN KEY (`invoice`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `concepts_product_history` FOREIGN KEY (`product`) REFERENCES `product_history` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `concepts`
--

LOCK TABLES `concepts` WRITE;
/*!40000 ALTER TABLE `concepts` DISABLE KEYS */;
/*!40000 ALTER TABLE `concepts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_vars`
--

DROP TABLE IF EXISTS `global_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `global_vars` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_vars`
--

LOCK TABLES `global_vars` WRITE;
/*!40000 ALTER TABLE `global_vars` DISABLE KEYS */;
INSERT INTO `global_vars` VALUES (1,'Dia tope mora'),(2,'Porcentaje mora');
/*!40000 ALTER TABLE `global_vars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_vars_history`
--

DROP TABLE IF EXISTS `global_vars_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `global_vars_history` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `global_var` int unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `current` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_vars_history`
--

LOCK TABLES `global_vars_history` WRITE;
/*!40000 ALTER TABLE `global_vars_history` DISABLE KEYS */;
INSERT INTO `global_vars_history` VALUES (1,1,21.00,1,'2025-07-23 09:34:54'),(2,2,20.00,1,'2025-07-23 09:39:23');
/*!40000 ALTER TABLE `global_vars_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_payment_method`
--

DROP TABLE IF EXISTS `invoice_payment_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_payment_method` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `invoice` int unsigned NOT NULL,
  `type` int unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `coin` int unsigned NOT NULL,
  `bank` int unsigned DEFAULT NULL,
  `sale_point` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `invoice_payment_method_invoice_idx` (`invoice`),
  KEY `invoice_payment_method_payment_method_type_idx` (`type`),
  KEY `invoice_payment_method_bank_idx` (`bank`),
  KEY `invoice_payment_method_sale_point_idx` (`sale_point`),
  KEY `invoice_payment_method_coin_history_idx` (`coin`),
  CONSTRAINT `invoice_payment_method_bank` FOREIGN KEY (`bank`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invoice_payment_method_coin_history` FOREIGN KEY (`coin`) REFERENCES `coin_history` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invoice_payment_method_invoice` FOREIGN KEY (`invoice`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invoice_payment_method_payment_method_type` FOREIGN KEY (`type`) REFERENCES `payment_method_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invoice_payment_method_sale_point` FOREIGN KEY (`sale_point`) REFERENCES `sale_points` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_payment_method`
--

LOCK TABLES `invoice_payment_method` WRITE;
/*!40000 ALTER TABLE `invoice_payment_method` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_payment_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` int unsigned NOT NULL,
  `control_number` int unsigned NOT NULL,
  `account` int unsigned NOT NULL,
  `reason` text COLLATE utf8mb4_general_ci NOT NULL,
  `observation` text COLLATE utf8mb4_general_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `period` int unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `invoice_number_UNIQUE` (`invoice_number`),
  UNIQUE KEY `control_number_UNIQUE` (`control_number`),
  KEY `invoices_account_idx` (`account`),
  CONSTRAINT `invoices_account` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_method_types`
--

DROP TABLE IF EXISTS `payment_method_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_method_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_method_types`
--

LOCK TABLES `payment_method_types` WRITE;
/*!40000 ALTER TABLE `payment_method_types` DISABLE KEYS */;
INSERT INTO `payment_method_types` VALUES (1,'Efectivo','2025-07-23 09:33:50'),(2,'Transferencia','2025-07-23 09:33:50'),(3,'Pago móvil','2025-07-23 09:33:50');
/*!40000 ALTER TABLE `payment_method_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_history`
--

DROP TABLE IF EXISTS `product_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_history` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product` int unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `current` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `product_history_product_idx` (`product`),
  CONSTRAINT `product_history_product` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_history`
--

LOCK TABLES `product_history` WRITE;
/*!40000 ALTER TABLE `product_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Cajero','2025-07-23 09:30:19'),(2,'Supervisor','2025-07-23 09:30:19'),(3,'Tecnologia','2025-07-23 09:30:19');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_points`
--

DROP TABLE IF EXISTS `sale_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_points` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_points`
--

LOCK TABLES `sale_points` WRITE;
/*!40000 ALTER TABLE `sale_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `sale_points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scholarships`
--

DROP TABLE IF EXISTS `scholarships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scholarships` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scholarships`
--

LOCK TABLES `scholarships` WRITE;
/*!40000 ALTER TABLE `scholarships` DISABLE KEYS */;
INSERT INTO `scholarships` VALUES (1,'Amigos del IUJO','2025-07-23 10:18:29');
/*!40000 ALTER TABLE `scholarships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `super_admin`
--

DROP TABLE IF EXISTS `super_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `super_admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `user_UNIQUE` (`username`),
  UNIQUE KEY `password_UNIQUE` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `super_admin`
--

LOCK TABLES `super_admin` WRITE;
/*!40000 ALTER TABLE `super_admin` DISABLE KEYS */;
INSERT INTO `super_admin` VALUES (1,'ca06df6dbcae22f932e9bab5b1aa589be0e27c38','d7ec5e996531ed1dea7da51b27fba22a98185815','2025-07-23 08:52:55');
/*!40000 ALTER TABLE `super_admin` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-23 10:36:57
