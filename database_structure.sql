-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: neocaja
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

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
-- Table structure for table `account_company_history`
--

DROP TABLE IF EXISTS `account_company_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_company_history` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `account` int unsigned NOT NULL,
  `company` int unsigned DEFAULT NULL,
  `current` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account_company_history_company_idx` (`company`),
  KEY `account_company_history_account_idx` (`account`),
  CONSTRAINT `account_company_history_account` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `account_company_history_company` FOREIGN KEY (`company`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_company_history_AFTER_INSERT` AFTER INSERT ON `account_company_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de cuenta-empresa ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' account:', COALESCE(NEW.account, 'NULL'), ',',
                ' company:', COALESCE(NEW.company, 'NULL'), ',',
                ' current:', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_company_history_AFTER_UPDATE` AFTER UPDATE ON `account_company_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de cuenta-empresa de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ' => ', COALESCE(NEW.account, 'NULL'), ',',
                ' company:', COALESCE(OLD.company, 'NULL'), ' => ', COALESCE(NEW.company, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ' => ', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_company_history_AFTER_DELETE` AFTER DELETE ON `account_company_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de cuenta-empresa ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ',',
                ' company:', COALESCE(OLD.company, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `account_payment_products`
--

DROP TABLE IF EXISTS `account_payment_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_payment_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment` int unsigned NOT NULL,
  PRIMARY KEY (`id`,`payment`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_payment_products_AFTER_INSERT` AFTER INSERT ON `account_payment_products` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un producto pagado del carrito de un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' payment:', COALESCE(NEW.payment, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_payment_products_AFTER_UPDATE` AFTER UPDATE ON `account_payment_products` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un producto pagado del carrito de un cliente de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ' => ', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' payment:', COALESCE(OLD.payment, 'NULL'), ' => ', COALESCE(NEW.payment, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_payment_products_AFTER_DELETE` AFTER DELETE ON `account_payment_products` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un producto pagado del carrito de un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' payment:', COALESCE(OLD.payment, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `account_payments`
--

DROP TABLE IF EXISTS `account_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_payments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `related_id` int unsigned NOT NULL,
  `related_with` varchar(45) NOT NULL,
  `payment_method_type` varchar(50) NOT NULL,
  `payment_method` int unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `ref` varchar(40) NOT NULL,
  `document` varchar(30) NOT NULL,
  `capture` varchar(45) NOT NULL,
  `state` varchar(30) NOT NULL,
  `response` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_payments_AFTER_INSERT` AFTER INSERT ON `account_payments` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un pago de un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' related_id:', COALESCE(NEW.related_id, 'NULL'), ',',
                ' related_with:', COALESCE(NEW.related_with, 'NULL'), ',',
                ' payment_method_type:', COALESCE(NEW.payment_method_type, 'NULL'), ',',
                ' payment_method:', COALESCE(NEW.payment_method, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' ref:', COALESCE(NEW.ref, 'NULL'), ',',
                ' document:', COALESCE(NEW.document, 'NULL'), ',',
                ' capture:', COALESCE(NEW.capture, 'NULL'), ',',
                ' state:', COALESCE(NEW.state, 'NULL'), ',',
                ' response:', COALESCE(NEW.response, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_payments_AFTER_UPDATE` AFTER UPDATE ON `account_payments` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un pago de un cliente de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' related_id:', COALESCE(OLD.related_id, 'NULL'), ' => ', COALESCE(NEW.related_id, 'NULL'), ',',
                ' related_with:', COALESCE(OLD.related_with, 'NULL'), ' => ', COALESCE(NEW.related_with, 'NULL'), ',',
                ' payment_method_type:', COALESCE(OLD.payment_method_type, 'NULL'), ' => ', COALESCE(NEW.payment_method_type, 'NULL'), ',',
                ' payment_method:', COALESCE(OLD.payment_method, 'NULL'), ' => ', COALESCE(NEW.payment_method, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' ref:', COALESCE(OLD.ref, 'NULL'), ' => ', COALESCE(NEW.ref, 'NULL'), ',',
                ' document:', COALESCE(OLD.document, 'NULL'), ' => ', COALESCE(NEW.document, 'NULL'), ',',
                ' capture:', COALESCE(OLD.capture, 'NULL'), ' => ', COALESCE(NEW.capture, 'NULL'), ',',
                ' state:', COALESCE(OLD.state, 'NULL'), ' => ', COALESCE(NEW.state, 'NULL'), ',',
                ' response:', COALESCE(OLD.response, 'NULL'), ' => ', COALESCE(NEW.response, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_account_payments_AFTER_DELETE` AFTER DELETE ON `account_payments` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un pago de un cliente',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' related_id:', COALESCE(OLD.related_id, 'NULL'), ',',
                ' related_with:', COALESCE(OLD.related_with, 'NULL'), ',',
                ' payment_method_type:', COALESCE(OLD.payment_method_type, 'NULL'), ',',
                ' payment_method:', COALESCE(OLD.payment_method, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' ref:', COALESCE(OLD.ref, 'NULL'), ',',
                ' document:', COALESCE(OLD.document, 'NULL'), ',',
                ' capture:', COALESCE(OLD.capture, 'NULL'), ',',
                ' state:', COALESCE(OLD.state, 'NULL'), ',',
                ' response:', COALESCE(OLD.response, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cedula` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `names` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `surnames` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_student` tinyint(1) NOT NULL,
  `scholarship` int unsigned DEFAULT NULL,
  `scholarship_coverage` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `cedula_UNIQUE` (`cedula`),
  KEY `accounts_scholarship_idx` (`scholarship`),
  CONSTRAINT `accounts_scholarship` FOREIGN KEY (`scholarship`) REFERENCES `scholarships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_accounts_AFTER_INSERT` AFTER INSERT ON `accounts` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' names:', COALESCE(NEW.names, 'NULL'), ',',
                ' surnames:', COALESCE(NEW.surnames, 'NULL'), ',',
                ' address:', COALESCE(NEW.address, 'NULL'), ',',
                ' phone:', COALESCE(NEW.phone, 'NULL'), ',',
                ' is_student:', COALESCE(NEW.is_student, 'NULL'), ',',
                ' scholarship:', COALESCE(NEW.scholarship, 'NULL'), ',',
                ' scholarship_coverage:', COALESCE(NEW.scholarship_coverage, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_accounts_AFTER_UPDATE` AFTER UPDATE ON `accounts` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un cliente de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' names:', COALESCE(OLD.names, 'NULL'), ' => ', COALESCE(NEW.names, 'NULL'), ',',
                ' surnames:', COALESCE(OLD.surnames, 'NULL'), ' => ', COALESCE(NEW.surnames, 'NULL'), ',',
                ' address:', COALESCE(OLD.address, 'NULL'), ' => ', COALESCE(NEW.address, 'NULL'), ',',
                ' phone:', COALESCE(OLD.phone, 'NULL'), ' => ', COALESCE(NEW.phone, 'NULL'), ',',
                ' is_student:', COALESCE(OLD.is_student, 'NULL'), ' => ', COALESCE(NEW.is_student, 'NULL'), ',',
                ' scholarship:', COALESCE(OLD.scholarship, 'NULL'), ' => ', COALESCE(NEW.scholarship, 'NULL'), ',',
                ' scholarship_coverage:', COALESCE(OLD.scholarship_coverage, 'NULL'), ' => ', COALESCE(NEW.scholarship_coverage, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_accounts_AFTER_DELETE` AFTER DELETE ON `accounts` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' names:', COALESCE(OLD.names, 'NULL'), ',',
                ' surnames:', COALESCE(OLD.surnames, 'NULL'), ',',
                ' address:', COALESCE(OLD.address, 'NULL'), ',',
                ' phone:', COALESCE(OLD.phone, 'NULL'), ',',
                ' is_student:', COALESCE(OLD.is_student, 'NULL'), ',',
                ' scholarship:', COALESCE(OLD.scholarship, 'NULL'), ',',
                ' scholarship_coverage:', COALESCE(OLD.scholarship_coverage, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_admins_AFTER_INSERT` AFTER INSERT ON `admins` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un administrador ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' role:', COALESCE(NEW.role, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_admins_AFTER_UPDATE` AFTER UPDATE ON `admins` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un administrador de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' role:', COALESCE(OLD.role, 'NULL'), ' => ', COALESCE(NEW.role, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_admins_AFTER_DELETE` AFTER DELETE ON `admins` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un administrador ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' role:', COALESCE(OLD.role, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `authentications`
--

DROP TABLE IF EXISTS `authentications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authentications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cedula` varchar(11) NOT NULL,
  `last_attempt` datetime DEFAULT NULL,
  `login_attempts` int DEFAULT '0',
  `login_cooldown` datetime DEFAULT NULL,
  `last_connection` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cedula_UNIQUE` (`cedula`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_authentications_AFTER_INSERT` AFTER INSERT ON `authentications` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una información de autenticación',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' last_attempt:', COALESCE(NEW.last_attempt, 'NULL'), ',',
                ' login_attempts:', COALESCE(NEW.login_attempts, 'NULL'), ',',
                ' login_cooldown:', COALESCE(NEW.login_cooldown, 'NULL'), ',',
                ' last_connection:', COALESCE(NEW.last_connection, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_authentications_AFTER_UPDATE` AFTER UPDATE ON `authentications` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una información de autenticación de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' last_attempt:', COALESCE(OLD.last_attempt, 'NULL'), ' => ', COALESCE(NEW.last_attempt, 'NULL'), ',',
                ' login_attempts:', COALESCE(OLD.login_attempts, 'NULL'), ' => ', COALESCE(NEW.login_attempts, 'NULL'), ',',
                ' login_cooldown:', COALESCE(OLD.login_cooldown, 'NULL'), ' => ', COALESCE(NEW.login_cooldown, 'NULL'), ',',
                ' last_connection:', COALESCE(OLD.last_connection, 'NULL'), ' => ', COALESCE(NEW.last_connection, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_authentications_AFTER_DELETE` AFTER DELETE ON `authentications` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' last_attempt:', COALESCE(OLD.last_attempt, 'NULL'), ',',
                ' login_attempts:', COALESCE(OLD.login_attempts, 'NULL'), ',',
                ' login_cooldown:', COALESCE(OLD.login_cooldown, 'NULL'), ',',
                ' last_connection:', COALESCE(OLD.last_connection, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_banks_AFTER_INSERT` AFTER INSERT ON `banks` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un banco ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_banks_AFTER_UPDATE` AFTER UPDATE ON `banks` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un banco de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_banks_AFTER_DELETE` AFTER DELETE ON `banks` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un banco ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `binnacle`
--

DROP TABLE IF EXISTS `binnacle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `binnacle` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(30) DEFAULT NULL,
  `user` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `binnacle_admin_idx` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=428 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `coin_history`
--

DROP TABLE IF EXISTS `coin_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coin_history` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `coin` int unsigned NOT NULL,
  `price` decimal(10,4) NOT NULL,
  `current` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `coin_history_coin_idx` (`coin`),
  CONSTRAINT `coin_history_coin` FOREIGN KEY (`coin`) REFERENCES `coins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_coin_history_AFTER_INSERT` AFTER INSERT ON `coin_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de moneda ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' coin:', COALESCE(NEW.coin, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' current:', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_coin_history_AFTER_UPDATE` AFTER UPDATE ON `coin_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de moneda de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' coin:', COALESCE(OLD.coin, 'NULL'), ' => ', COALESCE(NEW.coin, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ' => ', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_coin_history_AFTER_DELETE` AFTER DELETE ON `coin_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de moneda ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' coin:', COALESCE(OLD.coin, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `coins`
--

DROP TABLE IF EXISTS `coins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `auto_update` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_coins_AFTER_INSERT` AFTER INSERT ON `coins` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una moneda ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' url:', COALESCE(NEW.url, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' auto_update:', COALESCE(NEW.auto_update, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_coins_AFTER_UPDATE` AFTER UPDATE ON `coins` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una moneda de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' url:', COALESCE(OLD.url, 'NULL'), ' => ', COALESCE(NEW.url, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' auto_update:', COALESCE(OLD.auto_update, 'NULL'), ' => ', COALESCE(NEW.auto_update, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_coins_AFTER_DELETE` AFTER DELETE ON `coins` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una moneda ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' url:', COALESCE(OLD.url, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' auto_update:', COALESCE(OLD.auto_update, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rif_letter` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rif_number` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_companies_AFTER_INSERT` AFTER INSERT ON `companies` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una empresa ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' rif_letter:', COALESCE(NEW.rif_letter, 'NULL'), ',',
                ' rif_number:', COALESCE(NEW.rif_number, 'NULL'), ',',
                ' address:', COALESCE(NEW.address, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_companies_AFTER_UPDATE` AFTER UPDATE ON `companies` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una empresa de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' rif_letter:', COALESCE(OLD.rif_letter, 'NULL'), ' => ', COALESCE(NEW.rif_letter, 'NULL'), ',',
                ' rif_number:', COALESCE(OLD.rif_number, 'NULL'), ' => ', COALESCE(NEW.rif_number, 'NULL'), ',',
                ' address:', COALESCE(OLD.address, 'NULL'), ' => ', COALESCE(NEW.address, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_companies_AFTER_DELETE` AFTER DELETE ON `companies` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una empresa ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' rif_letter:', COALESCE(OLD.rif_letter, 'NULL'), ',',
                ' rif_number:', COALESCE(OLD.rif_number, 'NULL'), ',',
                ' address:', COALESCE(OLD.address, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `month` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `concepts_product_history_idx` (`product`),
  KEY `concepts_invoice_idx` (`invoice`),
  CONSTRAINT `concepts_invoice` FOREIGN KEY (`invoice`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `concepts_product_history` FOREIGN KEY (`product`) REFERENCES `product_history` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_concepts_AFTER_INSERT` AFTER INSERT ON `concepts` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un concepto ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' invoice:', COALESCE(NEW.invoice, 'NULL'), ',',
                ' month:', COALESCE(NEW.month, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_concepts_AFTER_UPDATE` AFTER UPDATE ON `concepts` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un concepto de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ' => ', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' invoice:', COALESCE(OLD.invoice, 'NULL'), ' => ', COALESCE(NEW.invoice, 'NULL'), ',',
                ' month:', COALESCE(OLD.month, 'NULL'), ' => ', COALESCE(NEW.month, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_concepts_AFTER_DELETE` AFTER DELETE ON `concepts` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un concepto ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' invoice:', COALESCE(OLD.invoice, 'NULL'), ',',
                ' month:', COALESCE(OLD.month, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_global_vars_AFTER_INSERT` AFTER INSERT ON `global_vars` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una variable global ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_global_vars_AFTER_UPDATE` AFTER UPDATE ON `global_vars` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una variable global de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_global_vars_AFTER_DELETE` AFTER DELETE ON `global_vars` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una variable global ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_global_vars_history_AFTER_INSERT` AFTER INSERT ON `global_vars_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de variable global ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' global_var:', COALESCE(NEW.global_var, 'NULL'), ',',
                ' value:', COALESCE(NEW.value, 'NULL'), ',',
                ' current:', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_global_vars_history_AFTER_UPDATE` AFTER UPDATE ON `global_vars_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de variable global de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' global_var:', COALESCE(OLD.global_var, 'NULL'), ' => ', COALESCE(NEW.global_var, 'NULL'), ',',
                ' value:', COALESCE(OLD.value, 'NULL'), ' => ', COALESCE(NEW.value, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ' => ', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_global_vars_history_AFTER_DELETE` AFTER DELETE ON `global_vars_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de variable global ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' global_var:', COALESCE(OLD.global_var, 'NULL'), ',',
                ' value:', COALESCE(OLD.value, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `coin` int unsigned NOT NULL,
  `bank` int unsigned DEFAULT NULL,
  `sale_point` int unsigned DEFAULT NULL,
  `document_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `igtf` tinyint NOT NULL DEFAULT '0',
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
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_invoice_payment_method_AFTER_INSERT` AFTER INSERT ON `invoice_payment_method` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un método de pago de una factura ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' invoice:', COALESCE(NEW.invoice, 'NULL'), ',',
                ' type:', COALESCE(NEW.type, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' coin:', COALESCE(NEW.coin, 'NULL'), ',',
                ' bank:', COALESCE(NEW.bank, 'NULL'), ',',
                ' sale_point:', COALESCE(NEW.sale_point, 'NULL'), ',',
                ' document_number:', COALESCE(NEW.document_number, 'NULL'), ',',
                ' igtf:', COALESCE(NEW.igtf, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_invoice_payment_method_AFTER_UPDATE` AFTER UPDATE ON `invoice_payment_method` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un método de pago de una factura de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' invoice:', COALESCE(OLD.invoice, 'NULL'), ' => ', COALESCE(NEW.invoice, 'NULL'), ',',
                ' type:', COALESCE(OLD.type, 'NULL'), ' => ', COALESCE(NEW.type, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' coin:', COALESCE(OLD.coin, 'NULL'), ' => ', COALESCE(NEW.coin, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ' => ', COALESCE(NEW.bank, 'NULL'), ',',
                ' sale_point:', COALESCE(OLD.sale_point, 'NULL'), ' => ', COALESCE(NEW.sale_point, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ' => ', COALESCE(NEW.document_number, 'NULL'), ',',
                ' igtf:', COALESCE(OLD.igtf, 'NULL'), ' => ', COALESCE(NEW.igtf, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_invoice_payment_method_AFTER_DELETE` AFTER DELETE ON `invoice_payment_method` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un método de pago de una factura',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' invoice:', COALESCE(OLD.invoice, 'NULL'), ',',
                ' type:', COALESCE(OLD.type, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' coin:', COALESCE(OLD.coin, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ',',
                ' sale_point:', COALESCE(OLD.sale_point, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ',',
                ' igtf:', COALESCE(OLD.igtf, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `period` int unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rate_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `invoice_number_UNIQUE` (`invoice_number`),
  UNIQUE KEY `control_number_UNIQUE` (`control_number`),
  KEY `invoices_account_company_history_idx` (`account`),
  CONSTRAINT `invoices_account_company_history` FOREIGN KEY (`account`) REFERENCES `account_company_history` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_invoices_AFTER_INSERT` AFTER INSERT ON `invoices` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una factura ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' invoice_number:', COALESCE(NEW.invoice_number, 'NULL'), ',',
                ' control_number:', COALESCE(NEW.control_number, 'NULL'), ',',
                ' account:', COALESCE(NEW.account, 'NULL'), ',',
                ' observation:', COALESCE(NEW.observation, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' period:', COALESCE(NEW.period, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL'), ',',
                ' rate_date:', COALESCE(NEW.rate_date, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_invoices_AFTER_UPDATE` AFTER UPDATE ON `invoices` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una factura de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' invoice_number:', COALESCE(OLD.invoice_number, 'NULL'), ' => ', COALESCE(NEW.invoice_number, 'NULL'), ',',
                ' control_number:', COALESCE(OLD.control_number, 'NULL'), ' => ', COALESCE(NEW.control_number, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ' => ', COALESCE(NEW.account, 'NULL'), ',',
                ' observation:', COALESCE(OLD.observation, 'NULL'), ' => ', COALESCE(NEW.observation, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' period:', COALESCE(OLD.period, 'NULL'), ' => ', COALESCE(NEW.period, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL'), ',',
                ' rate_date:', COALESCE(OLD.rate_date, 'NULL'), ' => ', COALESCE(NEW.rate_date, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_invoices_AFTER_DELETE` AFTER DELETE ON `invoices` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una factura ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' invoice_number:', COALESCE(OLD.invoice_number, 'NULL'), ',',
                ' control_number:', COALESCE(OLD.control_number, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ',',
                ' observation:', COALESCE(OLD.observation, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' period:', COALESCE(OLD.period, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ',',
                ' rate_date:', COALESCE(OLD.rate_date, 'NULL')
            )
    );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `login_history`
--

DROP TABLE IF EXISTS `login_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_history` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cedula` varchar(11) NOT NULL,
  `success` tinyint NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_login_history_AFTER_INSERT` AFTER INSERT ON `login_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de login ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' success:', COALESCE(NEW.success, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_login_history_AFTER_UPDATE` AFTER UPDATE ON `login_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de login de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' success:', COALESCE(OLD.success, 'NULL'), ' => ', COALESCE(NEW.success, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_login_history_AFTER_DELETE` AFTER DELETE ON `login_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una factura ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' success:', COALESCE(OLD.success, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
    );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `mobile_payments`
--

DROP TABLE IF EXISTS `mobile_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mobile_payments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) NOT NULL,
  `bank` int unsigned NOT NULL,
  `document_letter` char(1) NOT NULL,
  `document_number` varchar(45) NOT NULL,
  `active` tinyint NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `mobile_payment_bank_idx` (`bank`),
  CONSTRAINT `mobile_payment_bank` FOREIGN KEY (`bank`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_mobile_payments_AFTER_INSERT` AFTER INSERT ON `mobile_payments` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
        CONCAT(
            'Una persona ha agregado manualmente un pago móvil',
            ' con los siguientes datos: ',
            ' id:', COALESCE(NEW.id, 'NULL'), ',',
            ' phone:', COALESCE(NEW.phone, 'NULL'), ',',
            ' bank:', COALESCE(NEW.bank, 'NULL'), ',',
            ' document_letter:', COALESCE(NEW.document_letter, 'NULL'), ',',
            ' document_number:', COALESCE(NEW.document_number, 'NULL'), ',',
            ' active:', COALESCE(NEW.active, 'NULL'), ',',
            ' created_at:', COALESCE(NEW.created_at, 'NULL')
        )
    );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_mobile_payments_AFTER_UPDATE` AFTER UPDATE ON `mobile_payments` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un pago móvil de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' phone:', COALESCE(OLD.phone, 'NULL'), ' => ', COALESCE(NEW.phone, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ' => ', COALESCE(NEW.bank, 'NULL'), ',',
                ' document_letter:', COALESCE(OLD.document_letter, 'NULL'), ' => ', COALESCE(NEW.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ' => ', COALESCE(NEW.document_number, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_mobile_payments_AFTER_DELETE` AFTER DELETE ON `mobile_payments` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un pago móvil',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' phone:', COALESCE(OLD.phone, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ',',
                ' document_letter:', COALESCE(OLD.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cedula` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `viewed` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_notifications_AFTER_INSERT` AFTER INSERT ON `notifications` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una notificación ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' message:', COALESCE(NEW.message, 'NULL'), ',',
                ' viewed:', COALESCE(NEW.viewed, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_notifications_AFTER_UPDATE` AFTER UPDATE ON `notifications` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una notificación de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' message:', COALESCE(OLD.message, 'NULL'), ' => ', COALESCE(NEW.message, 'NULL'), ',',
                ' viewed:', COALESCE(OLD.viewed, 'NULL'), ' => ', COALESCE(NEW.viewed, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_notifications_AFTER_DELETE` AFTER DELETE ON `notifications` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una notificación',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' message:', COALESCE(OLD.message, 'NULL'), ',',
                ' viewed:', COALESCE(OLD.viewed, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `payment_method_types`
--

DROP TABLE IF EXISTS `payment_method_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_method_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_payment_method_types_AFTER_INSERT` AFTER INSERT ON `payment_method_types` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un método de pago',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_payment_method_types_AFTER_UPDATE` AFTER UPDATE ON `payment_method_types` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un método de pago de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_payment_method_types_AFTER_DELETE` AFTER DELETE ON `payment_method_types` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un método de pago',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_product_history_AFTER_INSERT` AFTER INSERT ON `product_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de producto',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' current:', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_product_history_AFTER_UPDATE` AFTER UPDATE ON `product_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de producto de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ' => ', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ' => ', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
            );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_product_history_AFTER_DELETE` AFTER DELETE ON `product_history` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de producto',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_products_AFTER_INSERT` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un producto',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_products_AFTER_UPDATE` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un producto de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_products_AFTER_DELETE` AFTER DELETE ON `products` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un producto',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
            );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_roles_AFTER_INSERT` AFTER INSERT ON `roles` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un rol',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_roles_AFTER_UPDATE` AFTER UPDATE ON `roles` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un rol de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_roles_AFTER_DELETE` AFTER DELETE ON `roles` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un rol',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `sale_points`
--

DROP TABLE IF EXISTS `sale_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_points` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bank` int unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `sale_points_banks_idx` (`bank`),
  CONSTRAINT `sale_points_banks` FOREIGN KEY (`bank`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_sale_points_AFTER_INSERT` AFTER INSERT ON `sale_points` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un punto de venta',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' code:', COALESCE(NEW.code, 'NULL'), ',',
                ' bank:', COALESCE(NEW.bank, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_sale_points_AFTER_UPDATE` AFTER UPDATE ON `sale_points` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un punto de venta de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                'id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                'code:', COALESCE(OLD.code, 'NULL'), ' => ', COALESCE(NEW.code, 'NULL'), ',',
                'bank:', COALESCE(OLD.bank, 'NULL'), ' => ', COALESCE(NEW.bank, 'NULL'), ',',
                'created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_sale_points_AFTER_DELETE` AFTER DELETE ON `sale_points` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un punto de venta',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' code:', COALESCE(OLD.code, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `scholarships`
--

DROP TABLE IF EXISTS `scholarships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scholarships` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_scholarships_AFTER_INSERT` AFTER INSERT ON `scholarships` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una beca',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
            );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_scholarships_AFTER_UPDATE` AFTER UPDATE ON `scholarships` FOR EACH ROW BEGIN
        IF @app_audit IS NULL THEN
            INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una beca de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_scholarships_AFTER_DELETE` AFTER DELETE ON `scholarships` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una beca',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `self_data`
--

DROP TABLE IF EXISTS `self_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `self_data` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_self_data_AFTER_INSERT` AFTER INSERT ON `self_data` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una información propia ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' fullname:', COALESCE(NEW.fullname, 'NULL'), ',',
                ' city:', COALESCE(NEW.city, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_self_data_AFTER_UPDATE` AFTER UPDATE ON `self_data` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una información propia de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' fullname:', COALESCE(OLD.fullname, 'NULL'), ' => ', COALESCE(NEW.fullname, 'NULL'), ',',
                ' city:', COALESCE(OLD.city, 'NULL'), ' => ', COALESCE(NEW.city, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_self_data_AFTER_DELETE` AFTER DELETE ON `self_data` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una información propia',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' fullname:', COALESCE(OLD.fullname, 'NULL'), ',',
                ' city:', COALESCE(OLD.city, 'NULL')
            ));
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `transfers`
--

DROP TABLE IF EXISTS `transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transfers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `account_number` varchar(20) NOT NULL,
  `document_letter` char(1) NOT NULL,
  `document_number` varchar(45) NOT NULL,
  `bank` int unsigned NOT NULL,
  `active` tinyint NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `transfer_bank_idx` (`bank`),
  CONSTRAINT `transfer_bank` FOREIGN KEY (`bank`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_transfers_AFTER_INSERT` AFTER INSERT ON `transfers` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una cuenta de transferencias',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' account_number:', COALESCE(NEW.account_number, 'NULL'), ',',
                ' document_letter:', COALESCE(NEW.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(NEW.document_number, 'NULL'), ',',
                ' bank:', COALESCE(NEW.bank, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_transfers_AFTER_UPDATE` AFTER UPDATE ON `transfers` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una cuenta de transferencias de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' account_number:', COALESCE(OLD.account_number, 'NULL'), ' => ', COALESCE(NEW.account_number, 'NULL'), ',',
                ' document_letter:', COALESCE(OLD.document_letter, 'NULL'), ' => ', COALESCE(NEW.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ' => ', COALESCE(NEW.document_number, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ' => ', COALESCE(NEW.bank, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_transfers_AFTER_DELETE` AFTER DELETE ON `transfers` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una cuenta de transferencias',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' account_number:', COALESCE(OLD.account_number, 'NULL'), ',',
                ' document_letter:', COALESCE(OLD.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `unknown_incomes`
--

DROP TABLE IF EXISTS `unknown_incomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unknown_incomes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `ref` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `account` int unsigned DEFAULT NULL,
  `generation` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `unknown_incomes_account_idx` (`account`),
  KEY `unknown_incomes_unknown_incomes_generation_idx` (`generation`),
  CONSTRAINT `unknown_incomes_account` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `unknown_incomes_unknown_incomes_generation` FOREIGN KEY (`generation`) REFERENCES `unknown_incomes_generations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=637 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_unknown_incomes_AFTER_INSERT` AFTER INSERT ON `unknown_incomes` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un ingreso desconocido',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' date:', COALESCE(NEW.date, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' ref:', COALESCE(NEW.ref, 'NULL'), ',',
                ' description:', COALESCE(NEW.description, 'NULL'), ',',
                ' account:', COALESCE(NEW.account, 'NULL'), ',',
                ' generation:', COALESCE(NEW.generation, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_unknown_incomes_AFTER_UPDATE` AFTER UPDATE ON `unknown_incomes` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un ingreso desconocido de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                'id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                'date:', COALESCE(OLD.date, 'NULL'), ' => ', COALESCE(NEW.date, 'NULL'), ',',
                'price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                'ref:', COALESCE(OLD.ref, 'NULL'), ' => ', COALESCE(NEW.ref, 'NULL'), ',',
                'description:', COALESCE(OLD.description, 'NULL'), ' => ', COALESCE(NEW.description, 'NULL'), ',',
                'account:', COALESCE(OLD.account, 'NULL'), ' => ', COALESCE(NEW.account, 'NULL'), ',',
                'generation:', COALESCE(OLD.generation, 'NULL'), ' => ', COALESCE(NEW.generation, 'NULL')
            ));
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_unknown_incomes_AFTER_DELETE` AFTER DELETE ON `unknown_incomes` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un ingreso desconocido',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' date:', COALESCE(OLD.date, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' ref:', COALESCE(OLD.ref, 'NULL'), ',',
                ' description:', COALESCE(OLD.description, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ',',
                ' generation:', COALESCE(OLD.generation, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `unknown_incomes_generations`
--

DROP TABLE IF EXISTS `unknown_incomes_generations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unknown_incomes_generations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_unknown_incomes_generations_AFTER_INSERT` AFTER INSERT ON `unknown_incomes_generations` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una generación de ingresos desconocidos',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_unknown_incomes_generations_AFTER_UPDATE` AFTER UPDATE ON `unknown_incomes_generations` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una generación de ingresos desconocidos de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `MANUAL_unknown_incomes_generations_AFTER_DELETE` AFTER DELETE ON `unknown_incomes_generations` FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-12  9:01:02
