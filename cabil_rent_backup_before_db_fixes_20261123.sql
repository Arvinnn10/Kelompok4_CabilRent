-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: cabil_rent
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `baskets`
--

DROP TABLE IF EXISTS `baskets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `baskets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `jumlah` int unsigned NOT NULL DEFAULT '1',
  `tanggal_acara` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `baskets_user_id_foreign` (`user_id`),
  KEY `baskets_produk_id_foreign` (`produk_id`),
  CONSTRAINT `baskets_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `baskets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_baskets_jumlah` CHECK ((`jumlah` >= 1))
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `baskets`
--

LOCK TABLES `baskets` WRITE;
/*!40000 ALTER TABLE `baskets` DISABLE KEYS */;
INSERT INTO `baskets` VALUES (1,2,1,1,'2026-11-19','2026-11-16 14:48:46','2026-11-16 14:48:46'),(2,2,2,1,'2026-11-21','2026-11-16 14:48:46','2026-11-16 14:48:46'),(3,3,1,1,'2026-11-19','2026-11-16 14:51:43','2026-11-16 14:51:43'),(4,3,2,1,'2026-11-21','2026-11-20 15:25:18','2026-11-20 15:25:18'),(5,3,2,1,'2026-11-21','2026-11-20 15:25:21','2026-11-20 15:25:21'),(6,3,2,1,'2026-11-21','2026-11-20 15:25:23','2026-11-20 15:25:23'),(7,3,2,1,'2026-11-21','2026-11-20 15:25:24','2026-11-20 15:25:24'),(8,3,2,1,'2026-11-21','2026-11-20 15:25:25','2026-11-20 15:25:25'),(9,3,2,1,'2026-11-21','2026-11-20 15:25:27','2026-11-20 15:25:27'),(10,3,1,1,'2026-11-21','2026-11-20 15:26:10','2026-11-20 15:26:10'),(11,5,1,1,'2026-11-23','2026-11-21 17:55:46','2026-11-21 17:55:46'),(12,5,3,1,'2026-11-23','2026-11-21 17:55:47','2026-11-21 17:55:47'),(13,7,1,1,'2026-11-30','2026-11-22 16:21:57','2026-11-22 16:21:57'),(14,6,1,1,'2026-11-30','2026-11-22 18:56:05','2026-11-22 18:56:05');
/*!40000 ALTER TABLE `baskets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Paket Kebaya','kebaya',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(2,'Heels','heels',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(3,'Kamisol','kamisol',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'2025_01_01_000003_create_categories_table',1),(3,'2025_01_01_000004_create_products_table',1),(4,'2025_01_01_000006_create_baskets_table',1),(5,'2025_01_01_000007_create_orders_table',1),(6,'2026_10_27_000001_add_google_id_to_users_table',1),(7,'2026_11_16_134638_add_slug_to_products_and_categories_tables',1),(8,'2026_11_21_000001_add_status_to_users_and_stok_to_products_and_deskripsi_to_categories',2),(9,'2026_11_22_000001_ensure_schema_constraints_and_foreign_keys',3),(10,'2026_11_23_000001_add_kode_pesanan_to_orders_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_pesanan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `basket_id` bigint unsigned NOT NULL,
  `no_whatsapp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domisili` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desk_pemesanan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_harga` int NOT NULL,
  `status_pesan` enum('pending','dikonfirmasi','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tb_bb` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_basket_id_foreign` (`basket_id`),
  KEY `orders_kode_pesanan_index` (`kode_pesanan`),
  CONSTRAINT `orders_basket_id_foreign` FOREIGN KEY (`basket_id`) REFERENCES `baskets` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,NULL,1,'081234567891','@haileybieber','Banjarmasin','Kebaya Brokat Maroon (M) untuk Wisuda',250000,'selesai','162cm / 48kg','2026-11-16 14:48:46','2026-11-20 15:57:41'),(2,NULL,2,'089876543210','@billieeilis','Banjarbaru','Kebaya Tulle Sage Green untuk Acara Lamaran',275000,'pending','158cm / 50kg','2026-11-16 14:48:46','2026-11-16 14:48:46'),(3,NULL,11,'08955226625','-','-','Coco Kebaya (Acara: 2026-11-23)',300000,'selesai','big xl','2026-11-21 17:55:47','2026-11-22 18:29:25'),(4,NULL,12,'08955226625','-','-','Aira Kebaya (Acara: 2026-11-23)',550000,'pending','big xl','2026-11-21 17:55:47','2026-11-21 17:55:47'),(5,'CR-INV-20261123-Z5HWSY',14,'0896376362','-','-','Coco Kebaya (Acara: 2026-11-30)',300000,'pending','edefghjk','2026-11-22 18:56:05','2026-11-22 18:56:05');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint unsigned NOT NULL,
  `nama_produk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desk_produk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `harga_sewa` int NOT NULL,
  `ukuran` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stok` int unsigned NOT NULL DEFAULT '1',
  `foto_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_produk` enum('tersedia','tidak_tersedia') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_kebaya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `products_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `chk_products_stok` CHECK ((`stok` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Coco Kebaya','kebaya-coco','Inc : Rok, kamisol, selendang dan hijab.',200000,NULL,1,'koleksi/kebaya-coco.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-22 18:29:25'),(2,1,'Ivory Palazzo Kebaya','kebaya-ivory-palazzo','Inc : Rok, kamisol, selendang dan hijab.',300000,NULL,1,'koleksi/kebaya-ivory-palazzo.png','tidak_tersedia',NULL,'2026-11-16 14:48:46','2026-11-22 18:18:34'),(3,1,'Aira Kebaya','kebaya-aira','Inc : Rok, kamisol, selendang dan hijab.',450000,NULL,1,'koleksi/kebaya-aira.png','tidak_tersedia',NULL,'2026-11-16 14:48:46','2026-11-22 15:49:06'),(4,1,'Ayana Kebaya','kebaya-ayana','Inc : Rok, kamisol, selendang dan hijab.',250000,NULL,1,'koleksi/kebaya-ayana.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(5,1,'Ayuna Kebaya (non hijab)','kebaya-ayuna','Inc : Rok, kamisol, dan selendang.',300000,NULL,1,'koleksi/kebaya-ayuna.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(6,1,'Kiona Kebaya','kebaya-kiona','Inc : Rok, kamisol, selendang dan hijab.',350000,NULL,1,'koleksi/kebaya-kiona.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(7,1,'Meira Kebaya','kebaya-meira','Inc : Rok, kamisol, selendang dan hijab.',350000,NULL,1,'koleksi/kebaya-meira.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(8,1,'Arella Kebaya','kebaya-arella','Inc : Rok, kamisol, dan hijab.',350000,NULL,1,'koleksi/kebaya-arella.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(10,2,'Arin Heels','heels-arin','Heels 5cm ukuran 37 cm',50000,NULL,1,'koleksi/heels-arin.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(11,2,'Aqila Heels','heels-aqila','Heels 7cm ukuran 39 cm',40000,NULL,1,'koleksi/heels-aqila.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(12,2,'Aura Heels','heels-aura','Heels 5cm ukuran 37 cm',45000,NULL,1,'koleksi/heels-aura.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(13,2,'Kayla Heels','heels-kayla','Heels 5cm ukuran 37 cm',50000,NULL,1,'koleksi/heels-kayla.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(14,3,'Kamisol Ukuran M','kamisol-m','Kamisol ukuran M untuk dalaman kebaya.',200000,NULL,1,'koleksi/kamisol-m.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(15,3,'Kamisol Ukuran S','kamisol-s','Kamisol ukuran S untuk dalaman kebaya.',300000,NULL,1,'koleksi/kamisol-s.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(16,3,'Kamisol Ukuran L','kamisol-l','Kamisol ukuran L untuk dalaman kebaya.',450000,NULL,1,'koleksi/kamisol-l.png','tersedia',NULL,'2026-11-16 14:48:46','2026-11-16 14:48:46'),(18,1,'batik telkom','batik-telkom','andra peranh sewa',89999982,'XL',1,'koleksi/1795400295_0NDAJrwf7pCj.jpg','tersedia',NULL,'2026-11-22 18:18:15','2026-11-22 18:18:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','customers') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','diblokir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_no_telp_unique` (`no_telp`),
  UNIQUE KEY `users_google_id_unique` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'Administrator CabilRent','admin','$2y$12$LdFq0sM/BC.0wVHzf/6M4uLD88RjEaQ/2VjBxuCkFf3c4fs/Dr.LK','admin@cabilrent.com',NULL,'081234567890','admin','aktif','2026-11-16 14:48:46','2026-11-16 14:48:46'),(2,NULL,'Customer Demo','customer','$2y$12$0GiCfMnAVIb5RzuJjQpTR.ZgMmIzw4YojTc9PsGfE3K0mnqdnh22i','customer@cabilrent.com',NULL,'089876543210','customers','aktif','2026-11-16 14:48:46','2026-11-16 14:48:46'),(3,NULL,'userr1','user1','$2y$12$9XO/cDJxcFqjFe7mX2TJqeAxWBG8/1L2I5ld022bYElytumyoCVsq','rayahab@gmail.com',NULL,'089765432678','customers','aktif','2026-11-16 14:50:46','2026-11-16 14:50:46'),(4,'116128986222134413566','Muhammad Arvin Putra Felix','muharvinputra','$2y$12$D4dQ55Iggzwbu3ThjYUDjuzyBH2X4GVeWtwxtsa0ML0ZhUWxCVRwC','muharvinputra@gmail.com','https://lh3.googleusercontent.com/a/ACg8ocLOyq6DNFF4eqG4tplqp77y1JYqBjfLyKc689FH5rIHFE0AlIyA=s96-c',NULL,'admin','aktif','2026-11-20 15:16:39','2026-11-20 15:16:39'),(5,NULL,'andra sapto','cust_andra_sapto_6625','$2y$12$iEkMZm4gDjEoYzLD4ifeu.DguXn44vIDxjjlUFbJPGdGBV/tZJ36y','user_08955226625@cabilrent.id',NULL,'08955226625','customers','aktif','2026-11-21 17:55:46','2026-11-21 17:55:46'),(6,'103207237154510116733','MUHAMMAD IHSAN 27','543251115','$2y$12$7K.fAX33WK1RVSBZmOUWKeCG8bez1YgYh7GJtrdAYapEQH5Owuuey','543251115@smktelkom-bjb.sch.id','https://lh3.googleusercontent.com/a/ACg8ocL-F0uORU4aVM9N-_IBozDRv1VFR1MLwx0t_FO5iAYBlLfFoQ=s96-c',NULL,'admin','aktif','2026-11-22 03:27:08','2026-11-22 03:27:08'),(7,'104162674501751434561','Muhammad ihsan','mhmmdihsan776','$2y$12$7mQiO.wczk5FEwIdiRX.P.kVf34MJO5sIHKLcxQLs51u5ePGrJWDa','mhmmdihsan776@gmail.com','https://lh3.googleusercontent.com/a/ACg8ocKKkK7q3vYGuMsq7mFjVcx7WtJ90Z5yPVkb5hUQW-lZwaqXQ9c=s96-c',NULL,'customers','aktif','2026-11-22 04:08:08','2026-11-22 04:08:08');
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

-- Dump completed on 2026-11-23 19:42:10
