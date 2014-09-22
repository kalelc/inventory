-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: kaozend
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `apps`
--

DROP TABLE IF EXISTS `apps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apps`
--

LOCK TABLES `apps` WRITE;
/*!40000 ALTER TABLE `apps` DISABLE KEYS */;
INSERT INTO `apps` VALUES (1,'Office 2014','',''),(2,'Suite Adobe','','');
/*!40000 ALTER TABLE `apps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` VALUES (2,'Bancolombia',''),(3,'Davivienda','');
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `image` varchar(45) DEFAULT NULL,
  `background_image` varchar(45) DEFAULT NULL COMMENT 'esta variable  o foto se va a usar para hacer el manual del producto el fondo de la vitrina ',
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Toshiba','','',''),(2,'Acer','','',''),(3,'Lenovo','','','');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `master_category` int(11) NOT NULL,
  `singular_name` varchar(120) NOT NULL,
  `plural_name` varchar(120) NOT NULL,
  `image` varchar(60) DEFAULT NULL,
  `shipping_cost` float NOT NULL,
  `additional_shipping` float NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`),
  KEY `fk_categorias_clasificaciones1_idx` (`master_category`),
  CONSTRAINT `fk_categorias_clasificaciones1` FOREIGN KEY (`master_category`) REFERENCES `master_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,2,'Portatil Mini','Portatiles Mini','',15000,10000,'');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_serials_name`
--

DROP TABLE IF EXISTS `categories_serials_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories_serials_name` (
  `category` int(11) NOT NULL,
  `serial_name` int(11) NOT NULL,
  PRIMARY KEY (`category`,`serial_name`),
  KEY `fk_table1_categories1_idx` (`category`),
  KEY `fk_table1_serials_name1_idx` (`serial_name`),
  CONSTRAINT `fk_table1_categories1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_serials_name1` FOREIGN KEY (`serial_name`) REFERENCES `serials_name` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_serials_name`
--

LOCK TABLES `categories_serials_name` WRITE;
/*!40000 ALTER TABLE `categories_serials_name` DISABLE KEYS */;
INSERT INTO `categories_serials_name` VALUES (1,1),(1,2),(1,3);
/*!40000 ALTER TABLE `categories_serials_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_specifications`
--

DROP TABLE IF EXISTS `categories_specifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories_specifications` (
  `category` int(11) NOT NULL,
  `specification` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`category`,`specification`),
  KEY `fk_categories_specifications_categories1_idx` (`category`),
  KEY `fk_categories_specifications_specifications1_idx` (`specification`),
  KEY `order` (`order`),
  CONSTRAINT `fk_categories_specifications_categories1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_categories_specifications_specifications1` FOREIGN KEY (`specification`) REFERENCES `specifications` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_specifications`
--

LOCK TABLES `categories_specifications` WRITE;
/*!40000 ALTER TABLE `categories_specifications` DISABLE KEYS */;
INSERT INTO `categories_specifications` VALUES (1,1,1,18),(1,2,0,19),(1,3,1,1),(1,4,1,2),(1,5,0,3),(1,6,0,4),(1,7,0,5),(1,8,1,6),(1,9,0,7),(1,10,0,8),(1,11,1,9),(1,12,0,10),(1,13,0,11),(1,14,1,12),(1,15,0,13),(1,16,1,14),(1,17,0,15),(1,18,0,16),(1,19,1,17);
/*!40000 ALTER TABLE `categories_specifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `department` int(11) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `fk_cities_departments1_idx` (`department`),
  CONSTRAINT `fk_cities_departments1` FOREIGN KEY (`department`) REFERENCES `departments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (0,'bogota',1,NULL);
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classifications`
--

DROP TABLE IF EXISTS `classifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `description` longtext,
  `user_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_classifications_user_types1_idx` (`user_type`),
  CONSTRAINT `fk_classifications_user_types1` FOREIGN KEY (`user_type`) REFERENCES `user_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classifications`
--

LOCK TABLES `classifications` WRITE;
/*!40000 ALTER TABLE `classifications` DISABLE KEYS */;
INSERT INTO `classifications` VALUES (1,'Usuario Final ','',1),(2,'Transportador Nacional ','',3),(3,'Proveedor Nacional','',2),(4,'Proveedor International','',2),(5,'Usuario Distrbuidor','',1);
/*!40000 ALTER TABLE `classifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identification` varchar(20) NOT NULL,
  `identification_type` int(11) NOT NULL,
  `first_name` varchar(120) NOT NULL,
  `last_name` varchar(120) NOT NULL,
  `emails` longtext,
  `addresses` longtext,
  `phones` longtext,
  `zipcode` int(11) DEFAULT NULL,
  `company` varchar(120) DEFAULT NULL,
  `manager` varchar(120) DEFAULT NULL,
  `webpage` varchar(120) DEFAULT NULL,
  `birthday` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `alias` varchar(45) NOT NULL,
  `description` longtext,
  `city` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identification_UNIQUE` (`identification`),
  KEY `fk_customers_cities1_idx` (`city`),
  CONSTRAINT `fk_customers_cities1` FOREIGN KEY (`city`) REFERENCES `cities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'772184631',5,'Jhon','londono','[\"omegausa@hotmail.com\"]','[\"Calle 20 No.  6-80\"]','[\"6534357\"]',33178,'Omega','Jhon Londono','www.omega.com','0000-00-00 00:00:00','22','',0),(2,'669558',2,'J','q',NULL,NULL,NULL,2,'Servientrega','1111','servientrega','0000-00-00 00:00:00','222','',0),(3,'8899988',1,'Javier','Campo','[\"qweqwewqe@hotmail.com\"]','[\"adadas asdasd\"]','[\"898999\"]',6669,'Javier\'s Show Corp','juanita Perez','www.cualqioier.com','0000-00-00 00:00:00','campo','',0),(4,'24932496',1,'Marleni','Arboleda Osorno','[\"ejemplo@hotmail.com\"]','[\"Cra 8 # 50-26\"]',NULL,499965,'Marleni  Corp','Paulina','www.ejemplo.com','0000-00-00 00:00:00','42','',0),(5,'89763366',2,'TCC','TCC','[\"ejemplo@hotmail.com\"]','[\"Calle 17 A # 69 B 35\"]','[\"8979563\"]',257933,'TCC Corp','SEBASTIAN MURCIA','www.ejemplo.com','0000-00-00 00:00:00','98','',0),(6,'598258',2,'Servientrega','Servientrega','[\"ejemplo@hotmail.com\"]','[\"Calle 17 A # 69 B 35\"]','[\"7896314\"]',4568,'Servientrega Corp','Claudia Castro','www.ejemplo.com','0000-00-00 00:00:00','798','',0),(7,'90556482-0',2,'Mps','Mps','[\"ejemplo@hotmail.com\"]','[\"Calle 17 A # 69 B 35\"]','[\"8979563\"]',8978,'Mps Corp','Maria Melo','www.ejemplo.com','0000-00-00 00:00:00','8798','',0),(8,'9879949-7',2,'Makro','Computo','[\"ejemplo@hotmail.com\"]','[\"Calle 17 A # 69 B 35\"]','[\"98963\"]',48954,'Makro Corp','caudia gutierrez','www.ejemplo.com','0000-00-00 00:00:00','8948','',0),(9,'1022380322-7',2,'INTRO','TECNO','[\"ejemplo@hotmail.com\"]','[\"Calle 17 A # 69 B 35\"]','[\"96569\"]',79892,'Intro Corp','Jose Rodriguez Zeledon','www.ejemplo.com','0000-00-00 00:00:00','789','',0),(10,'1005934361-1',2,'Compu Lima','International','[\"ejemplo@hotmail.com\"]','[\"Calle 17 A # 69 B 35\"]','[\"9856\"]',7861,'Compulima Corp','Obed  Prieto','www.ejemplo.com','0000-00-00 00:00:00','lois','',0);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers_authorized`
--

DROP TABLE IF EXISTS `customers_authorized`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers_authorized` (
  `customer` int(11) NOT NULL,
  `image` varchar(45) DEFAULT NULL,
  `first_name` varchar(120) NOT NULL,
  `last_name` varchar(120) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL,
  KEY `fk_terceros_personas_autorizadas_terceros1_idx` (`customer`),
  CONSTRAINT `fk_terceros_personas_autorizadas_terceros1` FOREIGN KEY (`customer`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers_authorized`
--

LOCK TABLES `customers_authorized` WRITE;
/*!40000 ALTER TABLE `customers_authorized` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers_authorized` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers_classifications`
--

DROP TABLE IF EXISTS `customers_classifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers_classifications` (
  `customer` int(11) NOT NULL,
  `classification` int(11) NOT NULL,
  PRIMARY KEY (`customer`,`classification`),
  KEY `fk_table1_classifications1_idx` (`classification`),
  KEY `fk_table1_customers1_idx` (`customer`),
  CONSTRAINT `fk_table1_classifications1` FOREIGN KEY (`classification`) REFERENCES `classifications` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_customers1` FOREIGN KEY (`customer`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers_classifications`
--

LOCK TABLES `customers_classifications` WRITE;
/*!40000 ALTER TABLE `customers_classifications` DISABLE KEYS */;
INSERT INTO `customers_classifications` VALUES (3,1),(4,1),(2,2),(5,2),(6,2),(7,3),(8,3),(1,4),(9,5),(10,5);
/*!40000 ALTER TABLE `customers_classifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'cundinamarca');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `details_receive_inventory`
--

DROP TABLE IF EXISTS `details_receive_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `details_receive_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receive_inventory` int(11) NOT NULL,
  `cost` float NOT NULL,
  `iva` int(11) DEFAULT NULL,
  `product` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `serials` longtext NOT NULL,
  `manifest_file` varchar(45) DEFAULT NULL,
  `register_date` timestamp NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_details_receive_inventory_receive_inventory1_idx` (`receive_inventory`),
  KEY `fk_details_receive_inventory_products1_idx` (`product`),
  CONSTRAINT `fk_details_receive_inventory_products1` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_details_receive_inventory_receive_inventory1` FOREIGN KEY (`receive_inventory`) REFERENCES `receive_inventory` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `details_receive_inventory`
--

LOCK TABLES `details_receive_inventory` WRITE;
/*!40000 ALTER TABLE `details_receive_inventory` DISABLE KEYS */;
INSERT INTO `details_receive_inventory` VALUES (1,14,545454,1,3,1,'[[\"adfl\\u00f1kjljk123\",\"\",\"\"]]','',NULL,NULL),(2,13,15000,1,1,1,'[[\"55899\",\"\",\"\"]]','',NULL,NULL),(3,13,15000,1,2,2,'[[\"845636\",\"\",\"\"],[\"23542341\",\"\",\"\"]]','',NULL,NULL),(4,15,980000,3,3,2,'[[\"8998558\"],[\"364799\"]]','',NULL,NULL),(5,15,850000,1,1,1,'[[\"58985599\"]]','',NULL,NULL),(7,17,100000,2,3,1,'[[\"598666\"]]','',NULL,NULL),(8,17,16000,1,2,1,'[[\"25584\"]]','',NULL,NULL),(9,17,100000,3,1,1,'[[\"8878435\"]]','',NULL,NULL),(10,18,100000,1,3,1,'[[\"fsad21313\"]]','',NULL,NULL),(11,18,100000,2,1,1,'[[\"4523da2123\"]]','',NULL,NULL),(12,18,100000,3,2,1,'[[\"dsdf123123\"]]','',NULL,NULL),(13,18,100000,1,1,1,'[[\"451231\"]]','',NULL,NULL),(14,19,100000,1,3,1,'[[\"dfa1231\"]]','',NULL,NULL),(15,20,55000,1,3,1,'[[\"asdfadfadf\",\"asdfasdf\"]]','',NULL,NULL);
/*!40000 ALTER TABLE `details_receive_inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_prices`
--

DROP TABLE IF EXISTS `list_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` longtext,
  `principal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_prices`
--

LOCK TABLES `list_prices` WRITE;
/*!40000 ALTER TABLE `list_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `list_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(100) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `operation` int(11) NOT NULL COMMENT '1,2,3,4 constantes',
  `user` int(11) NOT NULL,
  `data` longtext,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_logs_users2_idx` (`user`),
  CONSTRAINT `fk_logs_users2` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (3,'output_inventory',1,1,1,'{\"user\":\"1\",\"client\":\"3\",\"seller\":\"2\",\"payment_method\":\"1\",\"guide\":\"654654\",\"observations\":\"\",\"register_date\":\"2014-09-16 20:45:26\",\"update_date\":\"2014-09-16 20:45:26\"}','2014-09-16 20:45:26'),(4,'output_inventory',2,1,1,'{\"user\":\"1\",\"client\":\"3\",\"seller\":\"6\",\"payment_method\":\"1\",\"guide\":\"\",\"observations\":\"\",\"register_date\":\"2014-09-17 00:34:07\",\"update_date\":\"2014-09-17 00:34:07\"}','2014-09-17 00:34:07');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_categories`
--

DROP TABLE IF EXISTS `master_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `image` varchar(45) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_categories`
--

LOCK TABLES `master_categories` WRITE;
/*!40000 ALTER TABLE `master_categories` DISABLE KEYS */;
INSERT INTO `master_categories` VALUES (2,'Portatiles','',''),(3,'Disco Duro Externo','',''),(4,'Partes De Portatiles','','');
/*!40000 ALTER TABLE `master_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `measures`
--

DROP TABLE IF EXISTS `measures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `specification` int(11) NOT NULL,
  `measure_type` int(11) DEFAULT NULL,
  `measure_value` varchar(120) NOT NULL,
  `image` varchar(45) DEFAULT NULL,
  `meaning` longtext,
  `general_information` longtext,
  PRIMARY KEY (`id`),
  KEY `fk_table1_unidades_medidas1_idx` (`measure_type`),
  KEY `fk_medidas_especificaciones1_idx` (`specification`),
  CONSTRAINT `fk_medidas_especificaciones1` FOREIGN KEY (`specification`) REFERENCES `specifications` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_unidades_medidas1` FOREIGN KEY (`measure_type`) REFERENCES `measures_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `measures`
--

LOCK TABLES `measures` WRITE;
/*!40000 ALTER TABLE `measures` DISABLE KEYS */;
INSERT INTO `measures` VALUES (3,5,2,'2.70','','',''),(5,7,1,'2','','',''),(7,3,NULL,'Core® i5 4Ta. Generación','','',''),(10,4,NULL,'Intel','','',''),(11,6,NULL,'Integrado','','',''),(12,8,1,'8','','',''),(13,9,NULL,'DDR3','','',''),(14,10,2,'1333','','',''),(15,11,1,'500','','',''),(16,12,NULL,'Sata','','',''),(17,13,2,'5400','','',''),(18,14,NULL,'Dvd±Rw ','','',''),(19,15,NULL,'Multiformato DVD±RW/CD-RW doble capa ','','',''),(20,16,NULL,'15.6','','',''),(21,17,NULL,'Led','','',''),(22,18,NULL,'No','','',''),(23,19,NULL,'Negro ','','',''),(24,1,NULL,'W8','','',''),(25,2,NULL,'Español','','',''),(26,8,1,'4','','',''),(27,4,NULL,'Amd','','','');
/*!40000 ALTER TABLE `measures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `measures_types`
--

DROP TABLE IF EXISTS `measures_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measures_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abbreviation` varchar(60) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `measures_types`
--

LOCK TABLES `measures_types` WRITE;
/*!40000 ALTER TABLE `measures_types` DISABLE KEYS */;
INSERT INTO `measures_types` VALUES (1,'Gb','Gigabyte',''),(2,'Ghz','Gigahertz','');
/*!40000 ALTER TABLE `measures_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules_roles`
--

DROP TABLE IF EXISTS `modules_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules_roles` (
  `rol` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  PRIMARY KEY (`rol`,`module`),
  KEY `fk_table1_roles1_idx` (`rol`),
  CONSTRAINT `fk_table1_roles1` FOREIGN KEY (`rol`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules_roles`
--

LOCK TABLES `modules_roles` WRITE;
/*!40000 ALTER TABLE `modules_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `modules_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` longtext,
  `register_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ntes_users1_idx` (`user`),
  CONSTRAINT `fk_ntes_users1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES (7,1,'login','revisar ataques xss en campos de usuario y contraseña',NULL);
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `output_inventory`
--

DROP TABLE IF EXISTS `output_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `output_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `seller` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `guide` varchar(45) DEFAULT NULL,
  `observations` longtext,
  `register_date` timestamp NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_output_inventory_users1` (`user`),
  KEY `fk_output_inventory_customers1` (`client`),
  KEY `fk_output_inventory_customers2` (`seller`),
  KEY `fk_output_inventory_payments_methods1` (`payment_method`),
  CONSTRAINT `fk_output_inventory_users1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_output_inventory_customers1` FOREIGN KEY (`client`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_output_inventory_customers2` FOREIGN KEY (`seller`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_output_inventory_payments_methods1` FOREIGN KEY (`payment_method`) REFERENCES `payments_methods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `output_inventory`
--

LOCK TABLES `output_inventory` WRITE;
/*!40000 ALTER TABLE `output_inventory` DISABLE KEYS */;
INSERT INTO `output_inventory` VALUES (1,1,3,2,1,'654654','','2014-09-16 20:45:26','2014-09-16 20:45:26'),(2,1,3,6,1,'','','2014-09-17 00:34:07','2014-09-17 00:34:07');
/*!40000 ALTER TABLE `output_inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments_methods`
--

DROP TABLE IF EXISTS `payments_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE latin1_bin NOT NULL,
  `description` longtext COLLATE latin1_bin,
  `bank_info` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments_methods`
--

LOCK TABLES `payments_methods` WRITE;
/*!40000 ALTER TABLE `payments_methods` DISABLE KEYS */;
INSERT INTO `payments_methods` VALUES (1,'Contado','',0),(2,'Credito','',0),(3,'Mps','descripción',0),(4,'Makro','descripción',0),(5,'Garantia','',0);
/*!40000 ALTER TABLE `payments_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `upc_bar_code` int(10) unsigned NOT NULL,
  `model` varchar(60) NOT NULL,
  `brand` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `part_no` varchar(60) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `iva` float DEFAULT NULL COMMENT '0,5,10,16',
  `qty_low` int(11) DEFAULT NULL,
  `qty_buy` int(11) DEFAULT NULL,
  `description` longtext,
  `specification_file` varchar(45) DEFAULT NULL,
  `image1` varchar(45) DEFAULT NULL,
  `image2` varchar(45) DEFAULT NULL,
  `image3` varchar(45) DEFAULT NULL,
  `image4` varchar(45) DEFAULT NULL,
  `image5` varchar(45) DEFAULT NULL,
  `image6` varchar(45) DEFAULT NULL,
  `manual_file` varchar(45) DEFAULT NULL,
  `video` longtext COMMENT 'nuevo\nusado\nremanufacturado\nnew pull',
  `status` int(11) DEFAULT NULL,
  `register_date` timestamp NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_productos_marcas1_idx` (`brand`),
  KEY `fk_productos_categorias1` (`category`),
  CONSTRAINT `fk_productos_categorias1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_productos_marcas1` FOREIGN KEY (`brand`) REFERENCES `brands` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,4294967295,'C55-ASP4502',1,1,'558944dww',1500000,0,1,4,'','e31407620933.pdf','061407620933.jpg','591407620933.jpg','9b1407620933.jpg','901407620933.jpg','','','','',1,'2014-08-09 21:48:53','2014-08-14 16:09:53'),(2,9868555,'T56-25635',1,1,'8975788',450000,0,4,6,'','','','','','','','','','',1,'2014-08-09 22:20:17','2014-08-09 22:20:17'),(3,89668,'Aspire A45-6985',2,1,'8966',815000,0,1,2,'','','','','','','','','','',1,'2014-08-09 22:21:50','2014-08-09 22:21:50');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_app`
--

DROP TABLE IF EXISTS `products_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_app` (
  `product` int(11) NOT NULL,
  `app` int(11) NOT NULL,
  PRIMARY KEY (`product`,`app`),
  KEY `fk_table1_products2_idx` (`product`),
  KEY `fk_table1_apps1_idx` (`app`),
  CONSTRAINT `fk_table1_apps1` FOREIGN KEY (`app`) REFERENCES `apps` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_products2` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_app`
--

LOCK TABLES `products_app` WRITE;
/*!40000 ALTER TABLE `products_app` DISABLE KEYS */;
INSERT INTO `products_app` VALUES (1,1),(1,2),(2,1),(3,1),(3,2);
/*!40000 ALTER TABLE `products_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_measures`
--

DROP TABLE IF EXISTS `products_measures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_measures` (
  `product` int(11) NOT NULL,
  `measure` int(11) NOT NULL,
  PRIMARY KEY (`product`,`measure`),
  KEY `fk_products_measures_products1_idx` (`product`),
  KEY `fk_products_measures_measures1_idx` (`measure`),
  CONSTRAINT `fk_products_measures_measures1` FOREIGN KEY (`measure`) REFERENCES `measures` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_measures_products1` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_measures`
--

LOCK TABLES `products_measures` WRITE;
/*!40000 ALTER TABLE `products_measures` DISABLE KEYS */;
INSERT INTO `products_measures` VALUES (1,3),(1,5),(1,7),(1,11),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(2,3),(2,5),(2,7),(2,10),(2,11),(2,12),(2,13),(2,14),(2,15),(2,16),(2,17),(2,18),(2,19),(2,20),(2,21),(2,22),(2,23),(2,24),(2,25),(3,3),(3,5),(3,7),(3,10),(3,11),(3,12),(3,13),(3,14),(3,15),(3,16),(3,17),(3,18),(3,19),(3,20),(3,21),(3,22),(3,23),(3,24),(3,25);
/*!40000 ALTER TABLE `products_measures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_relation`
--

DROP TABLE IF EXISTS `products_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_relation` (
  `product_principal` int(11) NOT NULL,
  `products_relation` int(11) NOT NULL,
  PRIMARY KEY (`product_principal`,`products_relation`),
  KEY `fk_products_relation_products1_idx` (`product_principal`),
  KEY `fk_products_relation_products2_idx` (`products_relation`),
  CONSTRAINT `fk_products_relation_products1` FOREIGN KEY (`product_principal`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_relation_products2` FOREIGN KEY (`products_relation`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_relation`
--

LOCK TABLES `products_relation` WRITE;
/*!40000 ALTER TABLE `products_relation` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receive_inventory`
--

DROP TABLE IF EXISTS `receive_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receive_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `shipment` int(11) NOT NULL,
  `guide` varchar(45) DEFAULT NULL,
  `invoice` varchar(45) DEFAULT NULL,
  `invoice_file` varchar(45) DEFAULT NULL,
  `observations` varchar(45) DEFAULT NULL,
  `register_date` timestamp NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_receive_inventory_customers1` (`customer`),
  KEY `fk_receive_inventory_payments_methods1` (`payment_method`),
  KEY `fk_receive_inventory_customers2` (`shipment`),
  KEY `fk_receive_inventory_users1_idx` (`user`),
  CONSTRAINT `fk_receive_inventory_customers1` FOREIGN KEY (`customer`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receive_inventory_customers2` FOREIGN KEY (`shipment`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receive_inventory_payments_methods1` FOREIGN KEY (`payment_method`) REFERENCES `payments_methods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receive_inventory_users1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receive_inventory`
--

LOCK TABLES `receive_inventory` WRITE;
/*!40000 ALTER TABLE `receive_inventory` DISABLE KEYS */;
INSERT INTO `receive_inventory` VALUES (1,0,1,1,2,'2222','222','','','2014-08-12 17:08:09',NULL),(2,0,1,1,1,'22','222','','','2014-08-12 17:09:17',NULL),(3,0,1,1,1,'65654','654654','','','2014-08-12 20:38:06',NULL),(4,0,1,2,1,'111','111','','','2014-08-12 20:49:23',NULL),(5,0,1,1,1,'111','111','','','2014-08-12 20:50:46',NULL),(6,0,1,1,1,'2222','222','','','2014-08-13 00:06:13',NULL),(7,0,1,1,1,'11','111','','','2014-08-13 00:06:48',NULL),(8,0,1,1,1,'11','11','','','2014-08-13 01:10:31',NULL),(10,1,1,2,1,'123123123','1321313','','','2014-08-19 16:18:43',NULL),(11,1,1,1,2,'1122','2222','','dsfdaf','2014-08-19 16:38:15',NULL),(12,1,1,1,1,'4444','555','','','2014-08-19 21:23:21',NULL),(13,1,1,2,1,'8899','9999','','','2014-08-19 21:55:40',NULL),(14,1,1,1,1,'654','654654','','','2014-08-19 22:12:55',NULL),(15,1,1,1,1,'1125','2222','','','2014-08-20 17:10:10',NULL),(16,1,1,1,1,'123123','123123','','','2014-08-20 17:22:45',NULL),(17,1,1,1,1,'5588','555','','','2014-08-20 19:34:52',NULL),(18,1,4,1,1,'88999','5555','','','2014-08-20 19:47:19',NULL),(19,1,1,1,1,'2558','55558','','','2014-08-20 23:11:40',NULL),(20,1,3,1,2,'654654','654654','','','2014-08-20 23:22:44',NULL);
/*!40000 ALTER TABLE `receive_inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin',NULL),(2,'Usuario Ventas','');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `serials_name`
--

DROP TABLE IF EXISTS `serials_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `serials_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serials_name`
--

LOCK TABLES `serials_name` WRITE;
/*!40000 ALTER TABLE `serials_name` DISABLE KEYS */;
INSERT INTO `serials_name` VALUES (1,'Producto Principal',''),(2,'Bateria',''),(3,'Cargador','');
/*!40000 ALTER TABLE `serials_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specifications`
--

DROP TABLE IF EXISTS `specifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `specification_master` int(11) NOT NULL,
  `image` varchar(45) DEFAULT NULL,
  `meaning` longtext,
  `general_information` longtext,
  PRIMARY KEY (`id`),
  KEY `fk_especificaciones_especificaciones_maestras1_idx` (`specification_master`),
  CONSTRAINT `fk_especificaciones_especificaciones_maestras1` FOREIGN KEY (`specification_master`) REFERENCES `specifications_masters` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specifications`
--

LOCK TABLES `specifications` WRITE;
/*!40000 ALTER TABLE `specifications` DISABLE KEYS */;
INSERT INTO `specifications` VALUES (1,'Sistema operativo',7,'','',''),(2,'Idioma Sismta Operativo',7,'','',''),(3,'Procesador',1,'','',''),(4,' Marca del Procesador',1,'','',''),(5,'Velocidad del Procesador',1,'','',''),(6,'Tipo de video',1,'','',''),(7,'Memoria de Video',1,'','',''),(8,'Tamaño de la Memoria Ram',2,'','',''),(9,'Tipo de Memoria RAM',2,'','',''),(10,'Velocidad de Funcionamiento',2,'','',''),(11,'Capacidad Disco Duro',3,'','',''),(12,'Conectividad',3,'','',''),(13,'Velocidad de Rotación',3,'','',''),(14,'Unidad óptica',4,'','',''),(15,'Tipo de unidad óptica ',4,'','',''),(16,'Tamaño de la Pantalla',5,'','',''),(17,'Tipo de Pantalla',5,'','',''),(18,' Touchscreen',5,'','',''),(19,'Color',6,'','','');
/*!40000 ALTER TABLE `specifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specifications_masters`
--

DROP TABLE IF EXISTS `specifications_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specifications_masters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `image` varchar(45) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specifications_masters`
--

LOCK TABLES `specifications_masters` WRITE;
/*!40000 ALTER TABLE `specifications_masters` DISABLE KEYS */;
INSERT INTO `specifications_masters` VALUES (1,'Procesador y Gráficos','',''),(2,'Memoria','',''),(3,'Unidad de almacenamiento','',''),(4,'Unidad de discos ópticos fija','',''),(5,'Pantalla','',''),(6,'Descripción física','',''),(7,'Sistema operativo','','');
/*!40000 ALTER TABLE `specifications_masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES (1,'Clientes',''),(2,'Proveedores',''),(3,'Transportadores',''),(4,'Banca','');
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(120) NOT NULL,
  `last_name` varchar(120) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(60) NOT NULL,
  `picture` varchar(60) DEFAULT NULL,
  `signature` varchar(60) DEFAULT NULL,
  `rol` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hash` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `fk_usuarios_roles1_idx` (`rol`),
  CONSTRAINT `fk_usuarios_roles1` FOREIGN KEY (`rol`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='password encriptado con md5\nfechas timestamp';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Andres','Colonia','admin','andreskal2@gmail.com',NULL,NULL,1,'$2y$10$GvjgLjiDLdG4qLpeBXjdJehvOJEIRBiI4ZEHorm9ewxu0FmQggIae','$2y$10$GvjgLjiDLdG4qLpeBXjdJehvOJEIRBiI4ZEHorm9ewxu0FmQggIae',1),(2,'Diana','Castano','campo','ventas@tiendatoshiba.com.co','','',2,'$2y$10$aNowz1D9WHLS6w.vVcTfM.fzUXaiHlnkxJmJwA7JUol.7RzLMOuAe','$2y$10$aNowz1D9WHLS6w.vVcTfM.fzUXaiHlnkxJmJwA7JUol.7RzLMOuAe',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-22 14:51:12
