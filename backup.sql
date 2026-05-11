-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: Inventory
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `sitio` varchar(255) NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'home',
  `isDefault` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,2,'dsfsdf','12345567898','wdwdas','sadasd','sadasd','adasdasd','home',1,'2025-08-30 20:46:39','2025-08-30 20:46:39'),(2,3,'Joshua karl Manuel','09288216783','baguio','pinget','benget','pinget','home',1,'2025-08-31 02:57:37','2025-08-31 02:57:37'),(3,4,'Angel Faye Parba','09351338648','Cebu City','Mandaue','cambaro','Cambaro','home',1,'2026-03-29 03:40:57','2026-03-29 03:40:57');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brandName` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (3,'Vanice Fresh Products Corp.','vanice-fresh-products-corp','1774773024.jpg','2026-03-29 00:30:24','2026-03-29 00:30:24'),(4,'New Creations Foods Inc.','new-creations-foods-inc','1774773428.jpg','2026-03-29 00:37:08','2026-03-29 00:37:08'),(5,'Sunpride','sunpride','1774773463.jpg','2026-03-29 00:37:43','2026-03-29 00:37:43'),(6,'PureFoods','purefoods','1774773491.png','2026-03-29 00:38:11','2026-03-29 00:38:11'),(7,'King\'s Quality Foods','kings-quality-foods','1774773752.jpg','2026-03-29 00:42:32','2026-03-29 00:42:32'),(8,'Instant Quality Foods','instant-quality-foods','1774774155.jpg','2026-03-29 00:49:16','2026-03-29 00:49:16'),(9,'Happy Fiesta','happy-fiesta','1774774187.jpg','2026-03-29 00:49:47','2026-03-29 00:49:47'),(10,'EMS Meat Product','ems-meat-product','1774774239.jpg','2026-03-29 00:50:39','2026-03-29 00:50:39'),(11,'Bounty Fresh','bounty-fresh','1774780907.jpg','2026-03-29 02:41:47','2026-03-29 02:41:47'),(12,'Holiday','holiday','1774782064.jpg','2026-03-29 03:01:04','2026-03-29 03:01:04'),(14,'Virginia','virginia','1774783031.png','2026-03-29 03:17:11','2026-03-29 03:17:11'),(15,'Sam','sam','1778507705.png','2026-05-11 05:55:05','2026-05-11 05:55:05');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (9,4,18,1,12.00,'2026-05-11 06:04:50','2026-05-11 06:04:50'),(22,13,21,1,500.00,'2026-05-11 08:48:48','2026-05-11 08:48:48');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (3,'Longganisa','longganisa','1774771378.jpg',NULL,'2026-03-29 00:02:58','2026-03-29 00:02:58'),(4,'Patties','patties','1774771437.jpg',NULL,'2026-03-29 00:03:57','2026-03-29 00:03:57'),(5,'Tocino','tocino','1774771794.jpg',NULL,'2026-03-29 00:09:54','2026-03-29 00:09:54'),(6,'Cooked Ham','cooked-ham','1774772283.png',NULL,'2026-03-29 00:18:04','2026-03-29 00:18:04'),(7,'Ground Pork','ground-pork','1774772357.jpg',NULL,'2026-03-29 00:19:17','2026-03-29 00:19:17'),(8,'Hotdog','hotdog','1774772479.jpg',NULL,'2026-03-29 00:21:19','2026-03-29 00:21:19'),(9,'Bacon','bacon','1774780560.png',NULL,'2026-03-29 02:36:01','2026-03-29 02:36:01'),(11,'Lumpiang Shanghai','lumpiang-shanghai','1774782310.jpg',NULL,'2026-03-29 03:05:10','2026-03-29 03:05:10'),(12,'Sausage','sausage','1774783059.jpeg',NULL,'2026-03-29 03:17:39','2026-03-29 03:17:39'),(13,'Sam','sam','1778507625.png',NULL,'2026-05-11 05:53:45','2026-05-11 05:53:45');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Angel Faye Parba','angelfayeparba@gmail.com','09456456456','Test Message','2026-05-11 06:02:49','2026-05-11 06:02:49'),(2,'Test User','testuser@gmail.com','09954546767','Test Message','2026-05-11 06:09:56','2026-05-11 06:09:56');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_04_29_113737_create_brands_table',1),(5,'2025_04_29_153755_create_categories_table',1),(6,'2025_04_29_235735_create_products_table',1),(7,'2025_05_03_120021_create_orders_table',1),(8,'2025_05_03_120037_create_order_items_table',1),(9,'2025_05_03_120051_create_addresses_table',1),(10,'2025_05_03_120107_create_transactions_table',1),(11,'2025_05_04_073215_create_slides_table',1),(12,'2025_05_04_133617_create_month_names_table',1),(13,'2025_05_05_082223_create_contacts_table',1),(14,'2026_04_16_150513_create_personal_access_tokens_table',2),(15,'2026_04_16_172242_update_users_table_add_first_last_name',3),(16,'2026_04_23_070302_add_weight_dimensions_to_products_table',4),(17,'2026_04_23_080326_create_reviews_table',5),(18,'2026_04_23_151800_create_cart_items_table',6),(19,'2026_05_10_105400_add_address_fields_to_users_table',7),(20,'2026_05_10_132000_create_wishlist_items_table',8),(22,'2026_05_11_171607_add_gallery_images_to_products_table',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `month_names`
--

DROP TABLE IF EXISTS `month_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `month_names` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `month_names`
--

LOCK TABLES `month_names` WRITE;
/*!40000 ALTER TABLE `month_names` DISABLE KEYS */;
INSERT INTO `month_names` VALUES (1,'January'),(2,'February'),(3,'March'),(4,'April'),(5,'May'),(6,'June'),(7,'July'),(8,'August'),(9,'September'),(10,'October'),(11,'November'),(12,'December'),(13,'January'),(14,'February'),(15,'March'),(16,'April'),(17,'May'),(18,'June'),(19,'July'),(20,'August'),(21,'September'),(22,'October'),(23,'November'),(24,'December'),(25,'January'),(26,'February'),(27,'March'),(28,'April'),(29,'May'),(30,'June'),(31,'July'),(32,'August'),(33,'September'),(34,'October'),(35,'November'),(36,'December');
/*!40000 ALTER TABLE `month_names` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `options` longtext DEFAULT NULL,
  `rstatus` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (3,3,3,100.00,1,NULL,0,'2026-03-29 03:40:57','2026-03-29 03:40:57'),(4,12,4,126.00,1,NULL,0,'2026-04-09 07:52:21','2026-04-09 07:52:21'),(5,12,5,126.00,1,NULL,0,'2026-04-09 08:26:41','2026-04-09 08:26:41'),(6,15,6,222.00,1,NULL,0,'2026-04-23 08:08:01','2026-04-23 08:08:01'),(7,14,7,160.00,2,NULL,0,'2026-04-23 21:42:17','2026-04-23 21:42:17'),(8,16,7,279.99,3,NULL,0,'2026-04-23 21:42:17','2026-04-23 21:42:17'),(9,16,8,250.00,3,NULL,0,'2026-05-10 05:04:02','2026-05-10 05:04:02'),(10,18,9,15.00,1,NULL,0,'2026-05-11 06:52:20','2026-05-11 06:52:20'),(11,15,10,222.00,1,NULL,0,'2026-05-11 06:54:59','2026-05-11 06:54:59'),(12,16,10,279.99,2,NULL,0,'2026-05-11 06:54:59','2026-05-11 06:54:59'),(13,12,11,270.00,3,NULL,0,'2026-05-11 07:35:23','2026-05-11 07:35:23'),(14,16,11,279.99,6,NULL,0,'2026-05-11 07:35:23','2026-05-11 07:35:23'),(15,15,11,222.00,1,NULL,0,'2026-05-11 07:35:23','2026-05-11 07:35:23'),(16,16,12,279.99,1,NULL,0,'2026-05-11 08:10:38','2026-05-11 08:10:38'),(17,11,12,95.00,3,NULL,0,'2026-05-11 08:10:38','2026-05-11 08:10:38'),(18,8,12,50.00,1,NULL,0,'2026-05-11 08:10:38','2026-05-11 08:10:38'),(19,18,12,15.00,1,NULL,0,'2026-05-11 08:10:38','2026-05-11 08:10:38');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `subTotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `sitio` varchar(255) NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'home',
  `status` enum('processing','delivered','cancelled') NOT NULL DEFAULT 'processing',
  `is_shipping_different` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_date` date DEFAULT NULL,
  `cancelled_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,2,84.00,101.64,'dsfsdf','12345567898','wdwdas','sadasd','sadasd','adasdasd','home','delivered',0,'2025-08-31',NULL,'2025-08-30 20:46:39','2025-08-30 20:48:01'),(2,3,100.00,121.00,'Joshua karl Manuel','09288216783','baguio','pinget','benget','pinget','home','delivered',0,'2025-08-31',NULL,'2025-08-31 02:57:37','2025-08-31 02:59:01'),(3,4,100.00,121.00,'Angel Faye Parba','09351338648','Cebu City','Mandaue','cambaro','Cambaro','home','processing',0,NULL,NULL,'2026-03-29 03:40:57','2026-03-29 03:40:57'),(4,4,126.00,152.46,'Angel Faye Parba','09351338648','Cebu City','Mandaue','cambaro','Cambaro','home','processing',0,NULL,NULL,'2026-04-09 07:52:21','2026-04-09 07:52:21'),(5,4,126.00,152.46,'Angel Faye Parba','09351338648','Cebu City','Mandaue','cambaro','Cambaro','home','delivered',0,'2026-04-23',NULL,'2026-04-09 08:26:41','2026-04-23 08:44:35'),(6,12,222.00,222.00,'Josephine','Tamayo','Mandaue City','Paknaan','Zone Carrots','Taytayan Akasya','home','cancelled',0,NULL,'2026-04-23','2026-04-23 08:08:01','2026-04-23 08:44:06'),(7,12,1159.97,1159.97,'Josephine','Tamayo','Mandaue City','Paknaan','Zone Carrots','Taytayan Akasya','home','delivered',0,'2026-04-24',NULL,'2026-04-23 21:42:17','2026-04-23 21:43:57'),(8,12,750.00,750.00,'Josephine Tamayo','09992345454','Liloan','Yati','Sityu','6001','home','processing',0,NULL,NULL,'2026-05-10 05:04:02','2026-05-10 05:04:02'),(9,13,15.00,15.00,'Test User','09954546767','Ccity','Baranggay','Ttest','2501','home','cancelled',0,NULL,'2026-05-11','2026-05-11 06:52:20','2026-05-11 07:06:30'),(10,13,781.98,781.98,'Test User','09954546767','Ccity','Baranggay','Ttest','2501','home','delivered',0,'2026-05-11',NULL,'2026-05-11 06:54:59','2026-05-11 07:03:48'),(11,13,2711.94,2711.94,'Test User','09954546767','Ccity','Baranggay','Ttest','2501','home','delivered',0,'2026-05-11',NULL,'2026-05-11 07:35:23','2026-05-11 08:18:19'),(12,13,629.99,629.99,'Test User','09954546767','Ccity','Baranggay','Ttest','2501','home','processing',0,NULL,NULL,'2026-05-11 08:10:38','2026-05-11 08:10:38');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',5,'auth-token','953e09df9820795da603b41507b5b5f256fd7f8e35d0a847c3f2c7c5367e9108','[\"*\"]',NULL,NULL,'2026-04-16 09:10:24','2026-04-16 09:10:24'),(2,'App\\Models\\User',5,'auth-token','aede329fc7446bea717d0cedfbcf9b2af6bed556e9cacc081db0f9a84a6e8de4','[\"*\"]',NULL,NULL,'2026-04-16 09:15:01','2026-04-16 09:15:01'),(3,'App\\Models\\User',1,'auth-token','df3f39ae6ba270bb46bdc2fd79bb700ea7f8e96f488a13b7c83bfb4e35335a02','[\"*\"]',NULL,NULL,'2026-04-16 09:16:00','2026-04-16 09:16:00'),(4,'App\\Models\\User',6,'auth-token','82c9625b927491ff84680d7b2d748517fd884518d7aaf94ca758a7637ab8903c','[\"*\"]',NULL,NULL,'2026-04-16 09:45:54','2026-04-16 09:45:54'),(5,'App\\Models\\User',7,'auth-token','654219a4db98d3eb134d0be2a9d9b64e4989b642294db2dad43fa1fd264aad52','[\"*\"]',NULL,NULL,'2026-04-16 09:52:08','2026-04-16 09:52:08'),(6,'App\\Models\\User',9,'auth-token','c4084ed5fe8e72f771f891ebda0f38938fd1f1b69222cc64f298b230178ba63c','[\"*\"]',NULL,NULL,'2026-04-16 09:55:11','2026-04-16 09:55:11'),(7,'App\\Models\\User',1,'auth-token','fb3791425bf4a972df0f9fbd9e19ef834db4e697cc4b813271afc917b5f4546e','[\"*\"]',NULL,NULL,'2026-04-16 09:57:06','2026-04-16 09:57:06'),(8,'App\\Models\\User',1,'auth-token','d87467e72be33494657c5ae0e36c3d8441beb568b2d5b2f988561424d008cb16','[\"*\"]',NULL,NULL,'2026-04-16 10:33:26','2026-04-16 10:33:26'),(9,'App\\Models\\User',10,'auth-token','11a68735895181ce8e0cf5f83bd8af123840626fa97e477b5900ff491b83848f','[\"*\"]',NULL,NULL,'2026-04-16 10:34:37','2026-04-16 10:34:37'),(10,'App\\Models\\User',1,'auth-token','6711e180e53d51289e73a1e4b91fa0c1804e134c98ab36e73eccd575e39049cf','[\"*\"]',NULL,NULL,'2026-04-16 11:03:15','2026-04-16 11:03:15'),(11,'App\\Models\\User',1,'auth-token','f127361e0e02a5c86883cc0db3526a2fada0101f52b81f3e0a510cf967333de8','[\"*\"]',NULL,NULL,'2026-04-16 11:13:41','2026-04-16 11:13:41'),(12,'App\\Models\\User',11,'auth-token','20920faf0860edeb97de8d4df2705040134302c075a6ead8c58a164ade429c61','[\"*\"]',NULL,NULL,'2026-04-16 11:31:34','2026-04-16 11:31:34'),(13,'App\\Models\\User',11,'auth-token','5c88dbb4cfa91e8dfd396e1ceb0a91ea9cd9df71e8854d56562df86fb04733d4','[\"*\"]',NULL,NULL,'2026-04-16 11:32:03','2026-04-16 11:32:03'),(14,'App\\Models\\User',12,'auth-token','880137c70d92d854bad5178036ada66ab68038c4d24a427865dfd7db31c7886d','[\"*\"]',NULL,NULL,'2026-04-16 11:42:15','2026-04-16 11:42:15'),(15,'App\\Models\\User',1,'auth-token','1f45822280ff7753ab3957d5306c08eb671527daa921abaed651c40096b85e7f','[\"*\"]',NULL,NULL,'2026-04-16 11:54:51','2026-04-16 11:54:51'),(16,'App\\Models\\User',12,'auth-token','278ab50ce062962193597e79c4e549ad0d04506b60f0db9ba027c21872877238','[\"*\"]',NULL,NULL,'2026-04-16 11:55:59','2026-04-16 11:55:59'),(17,'App\\Models\\User',1,'auth-token','3e3651a81ab421fa94dba49bf685a91360d3071a40f2a4454d6235df86248ee1','[\"*\"]',NULL,NULL,'2026-04-16 11:58:03','2026-04-16 11:58:03'),(18,'App\\Models\\User',1,'auth-token','f6aa12a90748fa663307894fee433608192bb145563f9799e3c806c51a629a85','[\"*\"]',NULL,NULL,'2026-04-16 12:11:08','2026-04-16 12:11:08'),(19,'App\\Models\\User',1,'auth-token','764659f36a4e0a811b28b326e052627ac2199a6a85f2d34c2170235463d56736','[\"*\"]',NULL,NULL,'2026-04-16 12:11:59','2026-04-16 12:11:59'),(20,'App\\Models\\User',1,'auth-token','09d51458612c5bc567d0601f524378cf7de7fc5a91789256635ed2ad8c92cfdc','[\"*\"]',NULL,NULL,'2026-04-16 12:14:46','2026-04-16 12:14:46'),(21,'App\\Models\\User',12,'auth-token','ac6d705eacd4f0f9f212a3f5a51f40201f73ad2624df8ea74e91353d0c42814b','[\"*\"]',NULL,NULL,'2026-04-16 12:22:04','2026-04-16 12:22:04'),(22,'App\\Models\\User',1,'auth-token','e340f21126bf5c940858864ecfaeb8958d7a3b5f12ee0631aae62e74a80fdf5a','[\"*\"]',NULL,NULL,'2026-04-16 12:53:58','2026-04-16 12:53:58'),(23,'App\\Models\\User',1,'auth-token','b3c4d635424d0daead4fc68bb09fe4315737deb6e896ad7807b4a55900f2feb8','[\"*\"]',NULL,NULL,'2026-04-16 13:03:44','2026-04-16 13:03:44'),(24,'App\\Models\\User',12,'auth-token','5ed5738836394269bb1380e3d3770802e487f947f873e484707f37ec37b5600f','[\"*\"]',NULL,NULL,'2026-04-16 13:04:54','2026-04-16 13:04:54'),(25,'App\\Models\\User',12,'auth-token','f7ce3fc689deaaa9f3dd8c2f348085b12730f27b26e38b029fc62fa2d082762f','[\"*\"]',NULL,NULL,'2026-04-16 13:09:40','2026-04-16 13:09:40'),(26,'App\\Models\\User',1,'auth-token','8d567508467e78c562a04f56fbeadc5289dacabecf5a81261dd849d1c9aa0cec','[\"*\"]',NULL,NULL,'2026-04-16 23:31:58','2026-04-16 23:31:58'),(27,'App\\Models\\User',1,'auth-token','6825672fe87d2c0b3858a63564f1c8514b71597c55638031431e5a33b2388ff3','[\"*\"]',NULL,NULL,'2026-04-16 23:35:50','2026-04-16 23:35:50'),(28,'App\\Models\\User',1,'auth-token','56ec51d8aba9ccf28255b7cf7c077801afc49faf4579110e4aa8037ab4a48854','[\"*\"]',NULL,NULL,'2026-04-16 23:40:45','2026-04-16 23:40:45'),(29,'App\\Models\\User',1,'auth-token','89782b6b9ae1154b9bf6a4db41d44c09e179edca6179af2db75f940d399c08d7','[\"*\"]',NULL,NULL,'2026-04-16 23:45:48','2026-04-16 23:45:48'),(30,'App\\Models\\User',1,'auth-token','f3d4ff2c48b1614286bb50fce1bb61940546ce4279485f3b359f6b62ccdf868a','[\"*\"]',NULL,NULL,'2026-04-16 23:48:06','2026-04-16 23:48:06'),(31,'App\\Models\\User',1,'auth-token','5c9b8c115834fb77a67156979de183c0b070c9178a9271f6b839515be2ea6f19','[\"*\"]',NULL,NULL,'2026-04-16 23:52:33','2026-04-16 23:52:33'),(32,'App\\Models\\User',1,'auth-token','49cea54145cb2d35f4e3cabe2b43a95468da7c680bb58a2c0747f2b94a9129d8','[\"*\"]',NULL,NULL,'2026-04-16 23:54:44','2026-04-16 23:54:44'),(33,'App\\Models\\User',1,'auth-token','0d7f12a9081d3e1ffa2967dce70885a9a49ab0a1f748889f870270bfa50e8f5d','[\"*\"]',NULL,NULL,'2026-04-17 00:08:18','2026-04-17 00:08:18'),(34,'App\\Models\\User',12,'auth-token','49c2eab3a18f0a98030442bddcd67ffbb478cf219a24b71c3c94fc9fec044053','[\"*\"]',NULL,NULL,'2026-04-17 00:08:38','2026-04-17 00:08:38'),(35,'App\\Models\\User',1,'auth-token','2a591cf5193c2275f34a153c71cbd08b207f7e008bdc73dbd3afea25506ada32','[\"*\"]',NULL,NULL,'2026-04-17 05:53:57','2026-04-17 05:53:57'),(36,'App\\Models\\User',12,'auth-token','f16548afc6f4566cb939ddbc91016f8f75cdcdb21a83c3bd2c6fbc319584ada7','[\"*\"]',NULL,NULL,'2026-04-17 05:54:29','2026-04-17 05:54:29'),(37,'App\\Models\\User',1,'auth-token','2b6aaf3d84f6afed4a39f2c347292f8df6b1a83fc2c5b0ed9c7b75acfd637baa','[\"*\"]',NULL,NULL,'2026-04-17 06:10:40','2026-04-17 06:10:40'),(38,'App\\Models\\User',1,'auth-token','7f0be2bdd03a19f3ce30888a7fd4eb08c39d917df2b4e15e8215943dc6f0d6f8','[\"*\"]',NULL,NULL,'2026-04-17 06:25:15','2026-04-17 06:25:15'),(39,'App\\Models\\User',1,'auth-token','4e009d1eae7156337cd28f2704637e99fd1d00fd7a14941c5c21157b7881ca06','[\"*\"]',NULL,NULL,'2026-04-17 06:26:29','2026-04-17 06:26:29'),(40,'App\\Models\\User',1,'auth-token','ffda5c43382abca019813e2ee3414a529be4b9e22fb301731e650f96047a0c4a','[\"*\"]',NULL,NULL,'2026-04-17 09:45:15','2026-04-17 09:45:15'),(41,'App\\Models\\User',12,'auth-token','adacf456e9c45b26e577f9bdcb631eb75d42bccc3b6a952514a9f3064a8dfe9b','[\"*\"]',NULL,NULL,'2026-04-17 11:38:18','2026-04-17 11:38:18'),(42,'App\\Models\\User',1,'auth-token','79dc3290be1d880ae6371a89cc3fde562682c504ef5a5e0e3605e06d4654f0e0','[\"*\"]','2026-04-17 12:54:13',NULL,'2026-04-17 11:39:05','2026-04-17 12:54:13'),(43,'App\\Models\\User',1,'auth-token','32b0d48ecc28914f28b11a008d2197410672efb20f8b33e896c2b3e95c755c97','[\"*\"]','2026-04-19 20:22:09',NULL,'2026-04-17 12:57:47','2026-04-19 20:22:09'),(44,'App\\Models\\User',1,'auth-token','9a296da968b3251762628d8aea5b9dde1bd733df4d7f8a224a535650ad062a7d','[\"*\"]','2026-04-19 21:11:46',NULL,'2026-04-19 21:11:23','2026-04-19 21:11:46'),(45,'App\\Models\\User',1,'auth-token','4e624e31e510527842eac3bff3ee015bc78e4fe9c47f21afc8482fe224117c68','[\"*\"]','2026-04-19 21:17:44',NULL,'2026-04-19 21:15:09','2026-04-19 21:17:44'),(46,'App\\Models\\User',12,'auth-token','ddafc4a2f33588bd6544f385a133f9a4dce2d22c5d29316de0dd5b79487972ac','[\"*\"]',NULL,NULL,'2026-04-20 07:50:06','2026-04-20 07:50:06'),(47,'App\\Models\\User',12,'auth-token','65a656e32a465f2ebe863c138d1605abfa0f9a2e9fac98a30b628daf0d509942','[\"*\"]',NULL,NULL,'2026-04-22 05:34:41','2026-04-22 05:34:41'),(48,'App\\Models\\User',1,'auth-token','a78f48c141a45bd067afbed4061ca459b7d84dcdd478999f715614a3cfb6160f','[\"*\"]','2026-04-22 06:31:32',NULL,'2026-04-22 06:31:19','2026-04-22 06:31:32'),(49,'App\\Models\\User',12,'auth-token','3916c097bc00ba69a6fab8699dd343af9dd2dd505ac05ff4503696efc6d298c2','[\"*\"]',NULL,NULL,'2026-04-22 20:49:42','2026-04-22 20:49:42'),(50,'App\\Models\\User',12,'auth-token','507856cc66acffa3633827a64ea3597b55c29984c1c7aab4a567922862e9afa6','[\"*\"]','2026-04-22 22:14:20',NULL,'2026-04-22 22:13:15','2026-04-22 22:14:20'),(51,'App\\Models\\User',12,'auth-token','4d09bafd09328ff175b87bf90419c841e082337be8f4e176b81dd738ad74e40d','[\"*\"]',NULL,NULL,'2026-04-22 23:24:36','2026-04-22 23:24:36'),(52,'App\\Models\\User',1,'auth-token','de0d65e2272d23fa88b1c9f3312b00b5bf4cae923fee66fda0b7be42ef68c323','[\"*\"]','2026-04-22 23:26:04',NULL,'2026-04-22 23:24:59','2026-04-22 23:26:04'),(53,'App\\Models\\User',12,'auth-token','2c6df68a7b665896bcbcd81b0ada09355317972918e44ce641aaffe7193d427f','[\"*\"]','2026-04-23 00:20:03',NULL,'2026-04-22 23:56:50','2026-04-23 00:20:03'),(54,'App\\Models\\User',4,'auth-token','1dea7190f3e6e77f12cf373b174def431f8ff7b11c4bd64e2795c4c04c87123f','[\"*\"]','2026-04-23 00:24:51',NULL,'2026-04-23 00:24:19','2026-04-23 00:24:51'),(55,'App\\Models\\User',12,'auth-token','38307f425a72cd63bdbce4f71f39b97327b37cf698973bfa817e40960de018e7','[\"*\"]','2026-04-23 00:47:35',NULL,'2026-04-23 00:37:25','2026-04-23 00:47:35'),(56,'App\\Models\\User',1,'auth-token','107fe5cc2c0931da1b4bfc224f0f86eb48710398af842ea65ff181b3aa460eff','[\"*\"]','2026-04-23 00:49:08',NULL,'2026-04-23 00:48:59','2026-04-23 00:49:08'),(57,'App\\Models\\User',12,'auth-token','f0d2571b669ad13f268b98a763b5f13a3511b70c82d4f1895178ea5bba61e746','[\"*\"]','2026-04-23 07:56:12',NULL,'2026-04-23 06:14:36','2026-04-23 07:56:12'),(58,'App\\Models\\User',1,'auth-token','fee0744579426a8f344bc622618805e33595c591fa33e38ebd10d0a9db1eed84','[\"*\"]','2026-04-23 08:07:02',NULL,'2026-04-23 08:06:11','2026-04-23 08:07:02'),(59,'App\\Models\\User',12,'auth-token','d5c471d85e44be96073922d9d895e010228693b459694e5a408dd0eb1a5680f2','[\"*\"]','2026-04-23 08:08:31',NULL,'2026-04-23 08:07:15','2026-04-23 08:08:31'),(60,'App\\Models\\User',1,'auth-token','8de79784b236d5f6faca8e6a990afd487a812c87e1b717ac675b2635e049f29d','[\"*\"]','2026-04-23 09:24:43',NULL,'2026-04-23 08:34:25','2026-04-23 09:24:43'),(61,'App\\Models\\User',1,'auth-token','019ea92710c99345fd44ebc2e4c816636a93a95a732700d7945b21022021ed8c','[\"*\"]','2026-04-23 09:41:59',NULL,'2026-04-23 09:38:54','2026-04-23 09:41:59'),(62,'App\\Models\\User',1,'auth-token','2ae39f4a456c8247c8fea0e2cafeb63cbfb6d7c86ef01332cce1154277c3e1d1','[\"*\"]','2026-04-23 10:34:57',NULL,'2026-04-23 10:05:40','2026-04-23 10:34:57'),(63,'App\\Models\\User',1,'auth-token','34ee06cd970fa69f1a28c74a2b51ad1fbc647aa4d948ce01ac07e2d50cf7a174','[\"*\"]','2026-04-23 11:16:29',NULL,'2026-04-23 11:16:26','2026-04-23 11:16:29'),(64,'App\\Models\\User',1,'auth-token','c893eaf1698a6ebedd0770daab7381bdc59812046125ccf2f962c1fc8d983db4','[\"*\"]','2026-04-23 11:18:09',NULL,'2026-04-23 11:16:59','2026-04-23 11:18:09'),(65,'App\\Models\\User',12,'auth-token','0548fa7d9ed133efc9ab46197ef4ee4dbdd76b70a18cbf85bc6a8a653101ef83','[\"*\"]','2026-04-23 11:22:21',NULL,'2026-04-23 11:21:35','2026-04-23 11:22:21'),(66,'App\\Models\\User',12,'auth-token','5feffaf7973d0201dd514c03cf22b2ab9077f66989d5e7c3dfc94b4da60876c6','[\"*\"]','2026-04-23 11:29:36',NULL,'2026-04-23 11:29:32','2026-04-23 11:29:36'),(67,'App\\Models\\User',1,'auth-token','46867684b688efa369a6a18caf062580722d4ce9b6a837cc28754f1c8c281985','[\"*\"]','2026-04-23 12:20:19',NULL,'2026-04-23 11:33:03','2026-04-23 12:20:19'),(68,'App\\Models\\User',1,'auth-token','261b814f521332bc389f2582ff5b5d936be95997171c77f099f4e7e5fe178df0','[\"*\"]','2026-04-23 15:09:42',NULL,'2026-04-23 15:06:28','2026-04-23 15:09:42'),(69,'App\\Models\\User',1,'auth-token','06bdb6b108b3923a1580b733f93ee593ae7d3a686a763399862f40817cfefd08','[\"*\"]','2026-04-23 21:30:08',NULL,'2026-04-23 21:30:05','2026-04-23 21:30:08'),(70,'App\\Models\\User',12,'auth-token','c79bc2785eeabbcd81a410142bc73ebdf2b576fe04c20a83bb3a8ae7c700ee41','[\"*\"]','2026-04-23 21:42:27',NULL,'2026-04-23 21:40:01','2026-04-23 21:42:27'),(71,'App\\Models\\User',1,'auth-token','205032e51edea40b6578b0782f16a59a8d7bade07b454a2a9a0c43b4636c0212','[\"*\"]','2026-04-23 21:44:11',NULL,'2026-04-23 21:42:51','2026-04-23 21:44:11'),(72,'App\\Models\\User',12,'auth-token','f2e937f8214b8f1459668d97e4b04bd9653bc1cd00ec29c9f08fc140e65ddbae','[\"*\"]','2026-04-23 21:45:30',NULL,'2026-04-23 21:44:58','2026-04-23 21:45:30'),(73,'App\\Models\\User',1,'auth-token','c9e3580804fb214b1e0b5ac4f8f99f8ced6c9f09365998ff344de5f064710a4e','[\"*\"]','2026-04-23 21:50:29',NULL,'2026-04-23 21:46:09','2026-04-23 21:50:29'),(74,'App\\Models\\User',1,'auth-token','ab4308336cf93af348d68e027c3243d48c337fa44a6f3fa1fe9a67488a59091c','[\"*\"]','2026-05-10 00:35:24',NULL,'2026-05-10 00:33:18','2026-05-10 00:35:24'),(75,'App\\Models\\User',12,'auth-token','0503b8f9c271c5ee9321f9817f1492d782d9ddac570626e47be45242dd1a8217','[\"*\"]','2026-05-10 02:25:46',NULL,'2026-05-10 00:36:43','2026-05-10 02:25:46'),(76,'App\\Models\\User',1,'auth-token','6f1a95ddc1e7cfc6c42e6f0f1c79afe1a489977ea79398b264ef272805b61f74','[\"*\"]','2026-05-10 02:41:56',NULL,'2026-05-10 02:26:59','2026-05-10 02:41:56'),(77,'App\\Models\\User',12,'auth-token','0d00254e6713b47800bbf87dec38f4c6d86f1fd5ae9d120d98ed134ebf84af2f','[\"*\"]','2026-05-10 05:04:03',NULL,'2026-05-10 02:42:58','2026-05-10 05:04:03'),(78,'App\\Models\\User',1,'auth-token','5a569c749566dce8ea996332d3f9337f66bc5cfd8da67915fff20a793ea5e958','[\"*\"]','2026-05-10 06:17:15',NULL,'2026-05-10 05:06:20','2026-05-10 06:17:15'),(79,'App\\Models\\User',1,'auth-token','88cac4d26ebc3fd89548eb316dada81ad5d387252eb5d8bf519b5c30b44ce6c1','[\"*\"]','2026-05-11 06:29:41',NULL,'2026-05-10 05:15:44','2026-05-11 06:29:41'),(80,'App\\Models\\User',1,'auth-token','27fb5101a6ebc50ffa4a5fef147c003f834b1f8c005f6b99f161090a758c288a','[\"*\"]','2026-05-11 05:38:20',NULL,'2026-05-11 05:38:16','2026-05-11 05:38:20'),(81,'App\\Models\\User',1,'auth-token','077994763dc051cdef6ef1a2d3eb7fe12b43172d1cc986ae4c8844349b7557f0','[\"*\"]','2026-05-11 05:56:00',NULL,'2026-05-11 05:46:04','2026-05-11 05:56:00'),(82,'App\\Models\\User',4,'auth-token','83b76a4932606cf654dadcad3f01b782e951319c955f49f40bbbcf21f24b4dac','[\"*\"]','2026-05-11 06:02:10',NULL,'2026-05-11 06:02:08','2026-05-11 06:02:10'),(83,'App\\Models\\User',1,'auth-token','6d375e36bd92a9b3349fc08b4b0f2f0ad7443c947f160969af182c27d180589a','[\"*\"]','2026-05-11 06:03:11',NULL,'2026-05-11 06:03:02','2026-05-11 06:03:11'),(84,'App\\Models\\User',4,'auth-token','e44592f8790c3f1025720bfb8867d50bbc60d177f068b05e29443da5a1acdafe','[\"*\"]','2026-05-11 06:04:56',NULL,'2026-05-11 06:04:34','2026-05-11 06:04:56'),(85,'App\\Models\\User',13,'auth-token','500fa883f12f513ed1ae1d2eeecfca3733c184ee379541851e9bea697472dada','[\"*\"]','2026-05-11 06:09:27',NULL,'2026-05-11 06:06:48','2026-05-11 06:09:27'),(86,'App\\Models\\User',1,'auth-token','3cbd78eea11fb33bb73e78b33754d2acea3e48e3a16a81fdb697eee9c26edac9','[\"*\"]','2026-05-11 06:10:33',NULL,'2026-05-11 06:10:09','2026-05-11 06:10:33'),(87,'App\\Models\\User',13,'auth-token','6d95517bb17fe4c8e0abfaf67cefd994756dfbd9ad807ec009a1441f1c68905d','[\"*\"]','2026-05-11 06:56:01',NULL,'2026-05-11 06:11:32','2026-05-11 06:56:01'),(88,'App\\Models\\User',13,'auth-token','9c7612bbca6c712eea511b1499bba56fe6b13a36a954c169e088e991ca187cf4','[\"*\"]','2026-05-11 06:58:48',NULL,'2026-05-11 06:56:20','2026-05-11 06:58:48'),(89,'App\\Models\\User',13,'auth-token','2b3c6834ff10ae87776eac9b33243655035ac55926b1026893baa98c2f8284cd','[\"*\"]','2026-05-11 06:59:21',NULL,'2026-05-11 06:59:02','2026-05-11 06:59:21'),(90,'App\\Models\\User',1,'auth-token','d1db74599c41e471432f0c2a489f3dbc94fa7b4b9815a94d686d99c5eb7b975b','[\"*\"]','2026-05-11 07:05:50',NULL,'2026-05-11 06:59:30','2026-05-11 07:05:50'),(91,'App\\Models\\User',13,'auth-token','68719f4cbc3ebc8356591cc8c962894a49a3c476fb09a090455ddf67ee70582e','[\"*\"]','2026-05-11 07:06:36',NULL,'2026-05-11 07:06:03','2026-05-11 07:06:36'),(92,'App\\Models\\User',1,'auth-token','c9f52ec9d8e976d0efcbab8e02d581c52efa84fe26267eb1352dd3a76fcaf50b','[\"*\"]','2026-05-11 07:07:11',NULL,'2026-05-11 07:07:00','2026-05-11 07:07:11'),(93,'App\\Models\\User',13,'auth-token','e8344aaff7269b5c0e857bdc27b3d6700db1e43e5788ba557611857d9afe55f2','[\"*\"]','2026-05-11 07:35:30',NULL,'2026-05-11 07:25:23','2026-05-11 07:35:30'),(94,'App\\Models\\User',1,'auth-token','456b269dc528a276a058244901f07080c60ffca28db61b2e41f37caabffe06dd','[\"*\"]','2026-05-11 10:46:23',NULL,'2026-05-11 07:36:03','2026-05-11 10:46:23'),(95,'App\\Models\\User',14,'auth-token','f04882e3db31bee8722025292a450b751c3aa8d7ef5795cde20e8c080e96d14b','[\"*\"]','2026-05-11 08:05:03',NULL,'2026-05-11 07:38:40','2026-05-11 08:05:03'),(96,'App\\Models\\User',13,'auth-token','d85daa3c7f981c447921ea183c41076edbd32ea893b67829a425d785c4bf6bc5','[\"*\"]','2026-05-11 08:11:40',NULL,'2026-05-11 08:05:11','2026-05-11 08:11:40'),(97,'App\\Models\\User',13,'auth-token','11ef80a771808e6d8afaf00c54b4fef59422afdd2b8a744ee471c9ebdc6e6a4f','[\"*\"]','2026-05-11 08:12:31',NULL,'2026-05-11 08:11:51','2026-05-11 08:12:31'),(98,'App\\Models\\User',13,'auth-token','3f6b67525c6e9b1d1de49ebb55476cdcce89f51505f45d9908c01dd99558e2aa','[\"*\"]','2026-05-11 08:17:15',NULL,'2026-05-11 08:12:54','2026-05-11 08:17:15'),(99,'App\\Models\\User',1,'auth-token','cfa541d889f817b580d17da24f2a9b8c9243bceb2e6fff986efaae8a85300d86','[\"*\"]','2026-05-11 08:48:20',NULL,'2026-05-11 08:17:31','2026-05-11 08:48:20'),(100,'App\\Models\\User',13,'auth-token','1c9e2c00902ac08c4572bbf358d54a6ab7b07086fe667e1e98086728970eae72','[\"*\"]','2026-05-11 09:05:43',NULL,'2026-05-11 08:48:31','2026-05-11 09:05:43'),(101,'App\\Models\\User',1,'auth-token','787480dbb84de584af3bb2cd8b43d0c6c17667193644043b2ca6456cf1573139','[\"*\"]','2026-05-11 09:06:34',NULL,'2026-05-11 09:05:58','2026-05-11 09:06:34'),(102,'App\\Models\\User',1,'auth-token','fc35e1c15c19763e76c35564314c014d0bba1853dac04b450c3f6cfd8ceb9049','[\"*\"]','2026-05-11 09:07:51',NULL,'2026-05-11 09:06:55','2026-05-11 09:07:51'),(103,'App\\Models\\User',13,'auth-token','c654211a25359448750df3b638bac68244137ffa3c90663436d58ac5a5f100c5','[\"*\"]','2026-05-11 09:08:27',NULL,'2026-05-11 09:08:06','2026-05-11 09:08:27'),(104,'App\\Models\\User',1,'auth-token','6c6e59e3e5d1531d0f9a6a2c9013f50ca6a3ae290a46c67380a84f91f2723848','[\"*\"]','2026-05-11 09:09:42',NULL,'2026-05-11 09:08:42','2026-05-11 09:09:42'),(105,'App\\Models\\User',13,'auth-token','69762f5bad98185e7a6844e99c17d20484b1e61f3925c6efddcd728ec6379500','[\"*\"]','2026-05-11 11:19:51',NULL,'2026-05-11 09:09:49','2026-05-11 11:19:51');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `regular_price` decimal(8,2) NOT NULL,
  `sale_price` decimal(8,2) DEFAULT NULL,
  `SKU` varchar(255) NOT NULL,
  `stock_status` enum('instock','outofstock') NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `quantity` int(10) unsigned NOT NULL DEFAULT 10,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `images` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (3,'Longga Patties','longga-patties','Delicious longganisa patties by Vanice, perfect for breakfast.','Vanice Longga Patties are made from quality pork, seasoned with the perfect blend of spices. Great for breakfast or any meal of the day.',150.00,100.00,'VAN-LONGGA-001','instock',1,99,1.25,'90 x 60 x 90 cm','1774774605.jpg',NULL,'',3,3,'2026-03-29 00:56:45','2026-04-22 23:36:20'),(4,'Pork Tocino - 250g','pork-tocino-250g','Savor the sweet and savory taste of our Pork Tocino Premium. Made from quality pork, perfectly marinated for a deliciously tender and flavorful breakfast treat. Best served fried with garlic rice and egg.','Our Pork Tocino Premium is made from quality pork, perfectly marinated for a deliciously tender and flavorful meal. A classic Filipino breakfast favorite that the whole family will love. Best served fried with garlic rice and egg. Net weight: 250g.',75.00,50.00,'PKT-250-001','instock',0,50,NULL,NULL,'1774776734.jpg',NULL,'',5,4,'2026-03-29 01:32:14','2026-03-29 02:51:30'),(5,'Pork Tocino - 150g','pork-tocino-150g','A sweet and savory Filipino-style Pork Tocino made from tender pork cuts, perfectly seasoned for a rich and appetizing flavor. Ideal for a quick and satisfying meal any time of the day.','A classic Filipino cured meat made from premium pork cuts, marinated in a perfect blend of sweet and savory seasonings. Easy to cook and bursting with flavor, it makes the perfect partner for garlic fried rice and a sunny side up egg. A must-have in every Filipino household.',55.00,40.00,'PKT-150-001','instock',1,20,NULL,NULL,'1774777081.jpg',NULL,'',5,4,'2026-03-29 01:38:02','2026-03-29 02:07:40'),(6,'King\'s Cooked Ham','kings-cooked-ham','A premium quality cooked ham by King\'s, made from selected pork cuts with a smooth and savory flavor. Perfect for sandwiches, breakfast meals, and everyday snacking.','King\'s Premium Cooked Ham is made from carefully selected pork cuts, processed to perfection for a tender, juicy, and flavorful experience. Whether sliced for sandwiches, served as a breakfast side, or used as a topping, it delivers consistent quality and taste in every bite. A trusted choice for families who love great-tasting ham.',75.00,70.00,'KNG-HAM-001','instock',1,30,NULL,NULL,'1774777301.jpg',NULL,'',6,7,'2026-03-29 01:41:42','2026-03-29 01:41:42'),(7,'Happy Fiesta Hotdog Regular','happy-fiesta-hotdog-regular','A classic Filipino-style regular hotdog with a juicy, tender bite and a rich savory flavor. Great for grilling, frying, or boiling — perfect for any meal or snack time.','A classic Filipino-style regular hotdog made from quality meat, seasoned with a perfect blend of spices for a juicy and flavorful experience. Easy to cook and enjoyable for the whole family, whether grilled, fried, or boiled. A delicious and affordable everyday treat that kids and adults will love.',35.00,33.00,'HFT-REG-001','instock',1,200,NULL,NULL,'1774777591.jpg',NULL,'',8,9,'2026-03-29 01:46:32','2026-03-29 01:46:32'),(8,'Happy Fiesta Hotdog Jumbo','happy-fiesta-hotdog-jumbo','Bigger, meatier, and more satisfying — this jumbo hotdog is packed with rich savory goodness. A perfect choice for hearty meals, backyard grilling, and special gatherings.','Our Jumbo Hotdog is larger in size and packed with rich, savory flavor. Made from quality meat and seasoned just right, it is juicy and delicious in every bite. Easy to cook and perfect for any occasion — whether for a family dinner, birthday party, or a simple merienda. A bigger hotdog for a bigger appetite.',50.00,45.00,'HFT-JMB-001','instock',1,59,NULL,NULL,'1774777965.jpg',NULL,'',8,9,'2026-03-29 01:52:45','2026-05-11 08:10:38'),(9,'IQF - Cooked Ham','iqf-cooked-ham','A tasty and ready-to-eat cooked ham made from quality pork. Great for sandwiches, breakfast, and everyday meals. Easy to slice and serve anytime.','IQF Instant Quality Foods Cooked Ham is made from quality pork, processed and seasoned for a smooth, savory, and satisfying flavor. Ready to eat and easy to prepare, it is perfect for sandwiches, breakfast plates, or as a side dish for any meal. A simple and delicious ham that the whole family will enjoy.',50.00,45.00,'IQF-HAM-001','instock',0,20,NULL,NULL,'1774778427.jpg',NULL,'',6,8,'2026-03-29 02:00:27','2026-03-29 02:51:41'),(10,'IQF- Longganisa','iqf-longganisa','Juicy and packed with flavor, this longganisa is a perfect start to your morning. Sweet, savory, and satisfying in every bite.','Wake up to the irresistible aroma of freshly cooked Longganisa. Made from carefully selected pork and seasoned with a unique blend of spices, it delivers a perfect balance of sweet and savory taste that keeps you coming back for more. Quick and easy to cook, it pairs best with warm garlic rice and a cold glass of juice. A simple pleasure that makes every morning feel special.',50.00,45.00,'IQF-LNGSA-001','instock',1,2000,NULL,NULL,'1774778759.jpg',NULL,'',3,8,'2026-03-29 02:06:00','2026-03-29 02:06:00'),(11,'PureFoods Tender Juicy Classic Hotdog','purefoods-tender-juicy-classic-hotdog','A classic and beloved hotdog that is tender, juicy, and full of flavor. A go-to favorite for kids and adults alike, perfect for any meal or snack.','Tender Juicy Classic Hotdog lives up to its name — soft, juicy, and bursting with rich savory flavor in every bite. A household staple that has been a favorite for generations, it is perfect for frying, grilling, or boiling. Whether packed in a lunchbox, served at a party, or cooked for a quick breakfast, it never fails to satisfy. A classic taste that kids can tell and families will always love.',95.00,75.00,'PF-HOTDOG-CLS-001','instock',1,997,NULL,NULL,'1774779127.jpg',NULL,'1778525139-1.png',8,6,'2026-03-29 02:12:08','2026-05-11 10:45:40'),(12,'Sunpride Honey Cured Bacon 200g','sunpride-honey-cured-bacon-200g','A sweet and smoky honey cured bacon made from choice pork bellies, smoked to perfection. Great for breakfast, sandwiches, and everyday meals.','Sunpride Honey Cured Bacon is made from carefully selected pork bellies, honey cured and smoked to perfection for a rich, sweet, and smoky flavor. Crispy on the outside and tender on the inside, it pairs perfectly with eggs, toast, or garlic rice. A delicious and satisfying treat for any meal of the day. Net weight: 200g.',270.00,126.00,'SP-BACON-200-001','instock',1,1495,NULL,NULL,'1774780647.png',NULL,'',9,5,'2026-03-29 02:37:28','2026-05-11 07:35:23'),(13,'Bounty Fresh Ground Pork Regular','bounty-fresh-ground-pork-regular','Fresh and finely ground regular pork, perfect for siomai, lumpia, burger patties, and many more everyday Filipino dishes.','Bounty Fresh Ground Pork Regular is made from quality pork, freshly ground and carefully packed to keep it fresh and flavorful. Versatile and easy to use, it is perfect for a wide range of Filipino dishes such as siomai, lumpia, pasta, burger patties, and more. A kitchen staple for every Filipino household.',180.00,160.00,'BF-GP-REG-001','instock',1,500,NULL,NULL,'1774781095.png',NULL,'',7,11,'2026-03-29 02:44:56','2026-03-29 02:44:56'),(14,'EMS Beef Burger Patty','ems-beef-burger-patty','Juicy and meaty beef burger patty perfect for grilling or frying. Great for homemade burgers and hearty meals.','Made from quality beef, seasoned with the right blend of spices for a rich, juicy, and flavorful bite. Easy to cook and perfect for homemade burgers, sandwiches, or as a main dish. A hearty and satisfying meal option for the whole family.',160.00,110.00,'EMS-BBP-001','instock',1,1298,NULL,NULL,'1774781456.jpg',NULL,'',4,10,'2026-03-29 02:50:56','2026-04-23 21:42:17'),(15,'Holiday Lumpiang Shanghai Sulit Pack 850g','holiday-lumpiang-shanghai-sulit-pack-850g','Crispy and delicious Lumpiang Shanghai in a sulit pack of 80 pieces. Perfect for parties, gatherings, and everyday snacking.','Holiday Lumpiang Shanghai Sulit Pack gives you more for less — 72 plus 8 pieces of crispy and flavorful spring rolls packed in one bag. Made from quality ingredients with a savory meat filling wrapped in a crispy golden wrapper. Easy to cook and perfect for any occasion, from family meals to big celebrations. Net weight: 850g.',222.00,175.00,'HOL-LS-850-001','instock',1,597,1.25,'90 x 60 x 90 cm','1774782466.jpg',NULL,'',11,12,'2026-03-29 03:07:46','2026-05-11 07:35:23'),(16,'Virginia Hungarian Sausage','virginia-hungarian-sausage','A smoky and savory Hungarian sausage with a rich, bold flavor. Perfect for grilling, frying, or adding to your favorite recipes. 660ggggggggggggggg','Virginia Hungarian Sausage is a Chef\'s Selection product made from quality meat, smoked and seasoned to perfection for a rich and bold flavor. Juicy on the inside with a satisfying smoky taste, it is perfect for grilling, frying, or pan cooking. A great source of protein that makes any meal more hearty and delicious. Net weight: 660g.',279.99,250.00,'VIR-HS-660-003','outofstock',1,185,NULL,NULL,'1774783157.jpg',NULL,'1776506812-1.png',12,14,'2026-03-29 03:19:17','2026-05-11 08:10:38'),(18,'Sad','sad','Sad','Das',12.00,15.00,'BAN-REP-U12','instock',1,49,NULL,NULL,'1776506956.jpg',NULL,'1776506956-1.jpg',9,11,'2026-04-18 02:09:16','2026-05-11 08:10:38'),(21,'shesh','shesh','shesh','sheeeesh',500.00,150.00,'TRY-ME-001-220','instock',1,1500,NULL,NULL,'1778524987.png',NULL,'1778524987-1.png,1778524987-2.png,1778524987-3.png',13,15,'2026-05-11 08:47:54','2026-05-11 10:44:37');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,16,5,'good','Josephine Tamayo','josephinetamayo@gmail.com','2026-05-10 02:48:51','2026-05-10 02:48:51'),(2,15,5,'Very good.','Josephine Tamayo','josephinetamayo@gmail.com','2026-05-10 05:05:47','2026-05-10 05:05:47'),(3,21,3,'shesshh','Test User','testuser@gmail.com','2026-05-11 08:50:18','2026-05-11 08:50:18');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('250u1Yi96eBpg2GQ5COM0znDBpD9gS7NViw27wi6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFlxUHR5V3l3ell1ek40a3hialJoN0E2UUVUWkVnRzNJaVpDSXkzUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaG9wIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1778519470),('8ibQhaLaJYSB8UOMghDN5a1gEfV6lvpFv7uPkf9n',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHJXZWhkWHp5bThkUkFaOE9YdURGS1dtUmtkd3lJanI0NUw1bmMyaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778526318),('btZxVk4sW8TGMxVOvYd5gdQMB6BwGBokpAsH7lHb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVgyc3VQNllvWTRxdER1MXJIdVppNGsyenRSTjRsWTVzQXczeWZkRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778526313),('DALjUTsUEcqLIfPajwbf8MbOmXJ9i5kt1LqTCa8H',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWpjTWo1UDM0dTFXd2VHWXlFY2x4R3RkVWZZUm5VRlFHWFRFM0xEaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaG9wIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1778519469),('dIe6tInqXkMxnf0qxKx2LRPJeM06DcJg8fFT68Ns',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVm5aR3hJZ0dJczYyelFxalB3SVFpVE52bnladDF3cTBzNFhpdzIyMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaG9wL3NoZXNoIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1778519474),('IzGX9CEl6Kde3sdr4aOHBw6bPJXdIJecBxdB2PHR',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiakp3UDRVUTJMS1JwVUVBU1p1WXc0Y1R0TlpGOE1QNE1kUHRrb1NUVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778519459),('j3EUYxa0KQPWq8lEGJxQlDTxI67D1Aa6DnYcNQ1v',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT2ZpRUMyVnhzQUZxdmNwMXFjVFlLR3MzNkJyWVlXODZ4Q3lKdHJoWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaG9wL3NoZXNoIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1778523211),('jmETDammgPsvqcTpUawbjdr6ATAMkHaeROw0GIcj',NULL,'127.0.0.1','Go-http-client/1.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXVibURsOG9zblJNNmIwd0E0WGFBNThQR1JIT0xFb0Y0YTZjU08xcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778524444),('UPpoBggTlG5NNOqCO5MM31D9aqAZygVidwjYsr0t',NULL,'127.0.0.1','Go-http-client/1.1','YToyOntzOjY6Il90b2tlbiI7czo0MDoic2FOcE9MMVpDRDRYTjNsaVlmZGVVSUNFUlRyOUttMVBxYm1tdUFYTiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1778524443);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tagline` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subTitle` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slides`
--

LOCK TABLES `slides` WRITE;
/*!40000 ALTER TABLE `slides` DISABLE KEYS */;
INSERT INTO `slides` VALUES (3,'NEW ARRIVAL','LUMPIANG SHANGHAI','Sulit Pack 80pcs','https://zbga.shopsuki.ph/products/holiday-lumpiang-shanghai-850g','1774782578.jpg',1,'2026-03-29 03:09:38','2026-03-29 03:09:38'),(4,'BEST SELLER','HUNGARIAN SAUSAGE','Smoky, Juicy & Delicious','https://en.wikipedia.org/wiki/Hungarian_sausages','1774783392.png',1,'2026-03-29 03:23:12','2026-03-29 03:23:12'),(5,'NEW ARRIVAL','LONGGANIZA HAMONADA','Taste the premium longganiza delight','https://www.landers.ph/frozen/kings-premium-longaniza-hamonada-1kg-133131-26562','1776968675.jpg',1,'2026-04-23 10:24:35','2026-04-23 15:09:39');
/*!40000 ALTER TABLE `slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `payment_method` enum('cod') NOT NULL DEFAULT 'cod',
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  KEY `transactions_order_id_foreign` (`order_id`),
  CONSTRAINT `transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,2,1,'cod','paid','2025-08-30 20:46:39','2025-08-30 20:48:01'),(2,3,2,'cod','paid','2025-08-31 02:57:37','2025-08-31 02:59:01'),(3,4,3,'cod','pending','2026-03-29 03:40:57','2026-03-29 03:40:57'),(4,4,4,'cod','pending','2026-04-09 07:52:21','2026-04-09 07:52:21'),(5,4,5,'cod','paid','2026-04-09 08:26:41','2026-04-23 08:44:35'),(6,12,6,'cod','pending','2026-04-23 08:08:01','2026-04-23 08:08:01'),(7,12,7,'cod','paid','2026-04-23 21:42:17','2026-04-23 21:43:57'),(8,12,8,'cod','pending','2026-05-10 05:04:02','2026-05-10 05:04:02'),(9,13,9,'cod','pending','2026-05-11 06:52:20','2026-05-11 06:52:20'),(10,13,10,'cod','paid','2026-05-11 06:54:59','2026-05-11 07:03:48'),(11,13,11,'cod','paid','2026-05-11 07:35:23','2026-05-11 08:18:19'),(12,13,12,'cod','pending','2026-05-11 08:10:38','2026-05-11 08:10:38');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `sitio` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `utype` varchar(255) NOT NULL DEFAULT 'USR' COMMENT 'USR = User or Customer, ADM = Admin',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_mobile_unique` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'','','admin1@gmail.com','09926351450',NULL,NULL,NULL,NULL,NULL,'$2y$12$V2djKC2Vk7ZQtyxlraxPq.aicUOLCgBo5JAY67rw82LoHh5Yw88jS','ADM',NULL,'2025-08-30 20:38:16','2025-08-30 20:39:42'),(2,'','','email@email.com','12345567898',NULL,NULL,NULL,NULL,NULL,'$2y$12$XWnWG2AxdbaQ78Y71OetSuqQ2FDng2Wjed2/SDyuA.77Dpnm9d1te','USR',NULL,'2025-08-30 20:45:47','2025-08-30 20:45:47'),(3,'','','joshuakarlmanuel@gmail.com','09288216783',NULL,NULL,NULL,NULL,NULL,'$2y$12$ser7mgavALokX2w7mT8tjOfL4/rhmP97GOO0QH4nJ.xFO786BlM1e','USR',NULL,'2025-08-31 02:42:25','2025-08-31 02:42:25'),(4,'','','angelfayeparba@gmail.com','09351338648',NULL,NULL,NULL,NULL,NULL,'$2y$12$uNti5.4D8DvqByc9kGyt7.cXE5GH3G.vx8olrH.jQHQwqb7N/w49S','USR',NULL,'2026-03-29 03:36:58','2026-03-29 03:36:58'),(5,'','','akemiyuki@gmail.com','09363753038',NULL,NULL,NULL,NULL,NULL,'$2y$12$Mms2Sk9zgxM2hgfgngaFFekIOJbWdgiNbCi8Ruat3S/0QIDqzwWsa','USR',NULL,'2026-04-16 08:30:23','2026-04-16 08:30:23'),(6,'Khevin','Casido','khevincasido@gmail.com','09934148575',NULL,NULL,NULL,NULL,NULL,'$2y$12$72e.dNPwbd8eJiyzOyQUsO1QWMwpyQETSadDcqEHVg.HY/.x7PBSC','USR',NULL,'2026-04-16 09:45:54','2026-04-16 09:45:54'),(7,'Janine','Casido','janinecasido@gmail.com','09999999999',NULL,NULL,NULL,NULL,NULL,'$2y$12$1QzRw4pUo92jIuuR2lhR8OFGrsZF5po6tposY/rPC8tMfZ5ec8GQO','USR',NULL,'2026-04-16 09:52:08','2026-04-16 09:52:08'),(9,'Janine','Casido','janinecasido123@gmail.com','09888888888',NULL,NULL,NULL,NULL,NULL,'$2y$12$3CZn1KIIuddyplG2tBJl6u47EZ8WqPRZTy7YqYP74GQQ7IUtJGRge','USR',NULL,'2026-04-16 09:55:11','2026-04-16 09:55:11'),(10,'Lyca','Amistad','lycaamistad@gmail.com','09229292322',NULL,NULL,NULL,NULL,NULL,'$2y$12$hSk/sULGCsrBtqyagjJazeXK8BjwOToFgJeXahqo0LD.LcSxhRLvy','USR',NULL,'2026-04-16 10:34:37','2026-04-16 10:34:37'),(11,'Nyl','Babatuan','nylbabatuan@gmail.com','09106474743',NULL,NULL,NULL,NULL,NULL,'$2y$12$dx7AVK3ouSmE4KEHMualwelPRk24.nNxvxTPrE3czEvGDBhWhvAtS','USR',NULL,'2026-04-16 11:31:34','2026-04-16 11:31:34'),(12,'Josephine','Tamayo','josephinetamayo@gmail.com','09992345454','Sityu','Yati','Liloan','6001',NULL,'$2y$12$2qpgunW0MoZm5bvoiJ.dpOtOYmu.9m0FkJZNfbHohhGUD0xd/Q1ra','USR',NULL,'2026-04-16 11:42:15','2026-05-10 05:03:45'),(13,'Test','User','testuser@gmail.com','09954546767','Ttest','Baranggay','Ccity','2501',NULL,'$2y$12$sS1M3UOxDdpQNRXgILcZiurA5cUBl0fw.9eD.MNqKNjMrVmWY2DRG','USR',NULL,'2026-05-11 06:06:48','2026-05-11 08:12:25'),(14,'Angel Frieren','Parba','angelp@gmail.com','09192837465',NULL,NULL,NULL,NULL,NULL,'$2y$12$PN3HD2IE7gxdgFglNXqpLOAxuA9pMqHhmn/242IEEotHc525SeeCm','ADM',NULL,'2026-05-11 07:37:17','2026-05-11 07:37:17');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist_items`
--

DROP TABLE IF EXISTS `wishlist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlist_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_items_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlist_items_product_id_foreign` (`product_id`),
  CONSTRAINT `wishlist_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist_items`
--

LOCK TABLES `wishlist_items` WRITE;
/*!40000 ALTER TABLE `wishlist_items` DISABLE KEYS */;
INSERT INTO `wishlist_items` VALUES (7,13,18,'2026-05-11 08:11:30','2026-05-11 08:11:30'),(8,13,21,'2026-05-11 08:48:50','2026-05-11 08:48:50'),(10,13,5,'2026-05-11 11:17:44','2026-05-11 11:17:44');
/*!40000 ALTER TABLE `wishlist_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-12  3:34:36
