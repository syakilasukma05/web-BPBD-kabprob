-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: diskominfo_db
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
-- Current Database: `diskominfo_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `diskominfo_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `diskominfo_db`;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baru',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `hero_sliders`
--

DROP TABLE IF EXISTS `hero_sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hero_sliders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_sliders`
--

LOCK TABLES `hero_sliders` WRITE;
/*!40000 ALTER TABLE `hero_sliders` DISABLE KEYS */;
INSERT INTO `hero_sliders` VALUES (1,'Selamat Datang di Website','Dinas Komunikasi informatika, statistik dan persandian','https://diskominfo.probolinggokab.go.id/slider_img/slider_sae.png',NULL,NULL,1,1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(2,'Hudan Syarifuddin,S.Sos.,M.Si.','Kepala Dinas Komunikasi, Informatika, Statistik dan Persandian','https://diskominfo.probolinggokab.go.id/slider_img/slider_kadis_hudan.jpg',NULL,NULL,2,1,'2026-08-03 00:29:22','2026-08-03 00:29:22');
/*!40000 ALTER TABLE `hero_sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2026_08_03_000001_create_site_settings_table',1),(6,'2026_08_03_000002_create_navigation_menus_table',1),(7,'2026_08_03_000003_create_hero_sliders_table',1),(8,'2026_08_03_000004_create_news_items_table',1),(9,'2026_08_03_000005_create_sidebar_widgets_table',1),(10,'2026_08_03_000006_create_related_links_table',1),(11,'2026_08_03_000007_create_pages_table',1),(12,'2026_08_03_000008_create_public_documents_table',1),(13,'2026_08_03_000009_create_contact_messages_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `navigation_menus`
--

DROP TABLE IF EXISTS `navigation_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `navigation_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `parent_id` bigint unsigned DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `navigation_menus_parent_id_foreign` (`parent_id`),
  CONSTRAINT `navigation_menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `navigation_menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `navigation_menus`
--

LOCK TABLES `navigation_menus` WRITE;
/*!40000 ALTER TABLE `navigation_menus` DISABLE KEYS */;
INSERT INTO `navigation_menus` VALUES (1,'HOME','/',NULL,1,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(2,'PROFIL','#',NULL,2,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(3,'Struktur Organisasi','/halaman/struktur-organisasi',2,1,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(4,'Visi Misi','/halaman/visi-misi',2,2,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(5,'Tugas dan Fungsi','/halaman/tugas-dan-fungsi',2,3,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(6,'Survei Kepuasan Masyarakat','/halaman/survei-kepuasan-masyarakat',2,4,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(7,'LAYANAN','#',NULL,3,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(8,'Standar Pelayanan Publik','/halaman/standar-pelayanan-publik',7,1,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(9,'Pelayanan Pengelolaan Domain dan Hosting','/halaman/pelayanan-pengelolaan-domain-dan-hosting',7,2,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(10,'Pelayanan Permintaan Data Statistik Sektoral','/halaman/pelayanan-permintaan-data-statistik',7,3,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(11,'Pelayanan Penerbitan Tanda Tangan Elektronik','/halaman/pelayanan-tte',7,4,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(12,'DOKUMEN','#',NULL,4,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(13,'Perencanaan Kinerja','/dokumen',12,1,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(14,'Pengukuran Kinerja','/dokumen',12,2,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(15,'Pelaporan Kinerja','/dokumen',12,3,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(16,'Evaluasi Kinerja','/dokumen',12,4,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(17,'INFORMASI','#',NULL,5,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(18,'Berita','/informasi',17,1,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(19,'PPID','https://ppid.probolinggokab.go.id/',17,2,'_blank',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(20,'Video','/informasi',17,3,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(21,'Galery','/informasi',17,4,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(22,'HUBUNGI','#',NULL,6,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(23,'Lapor SP4N','/kontak',22,1,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(24,'Kontak','/kontak',22,2,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(25,'LOGIN','/login',NULL,7,'_self',1,'2026-08-03 00:29:22','2026-08-03 00:29:22');
/*!40000 ALTER TABLE `navigation_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_items`
--

DROP TABLE IF EXISTS `news_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Berita',
  `published_at` date DEFAULT NULL,
  `views` int NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_items_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_items`
--

LOCK TABLES `news_items` WRITE;
/*!40000 ALTER TABLE `news_items` DISABLE KEYS */;
INSERT INTO `news_items` VALUES (1,'Perkuat Keamanan Digital, Dinas Kominfo Perpanjang Perjanjian Kerja Sama (PKS) dengan BSSN tentang Pemanfaatan Sertifikat Elektronik','perkuat-keamanan-digital-dinas-kominfo-perpanjang-pks-bssn','Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Probolinggo resmi memperpanjang kerja sama dengan BSSN.','Probolinggo - Dalam rangka memperkuat tata kelola keamanan informasi dan legalitas dokumen digital di lingkungan Pemerintah Kabupaten Probolinggo...','https://diskominfo.probolinggokab.go.id//storage/photos/shares/6a573b881488c.jpeg','Berita','2026-07-08',142,1,'2026-08-03 00:29:23','2026-08-03 00:29:23'),(2,'BPS Kabupaten Probolinggo Gelar FGD Evaluasi PSS dan Penyusunan DDA 2025','bps-kabupaten-probolinggo-gelar-fgd-evaluasi-pss-dan-penyusunan-dda-2025','FGD Evaluasi Penyelenggaraan Statistik Sektoral dan penyusunan Daerah Dalam Angka 2025 bersama seluruh OPD.','Kraksaan - Badan Pusat Statistik bekerjasama dengan Dinas Kominfo menggelar FGD evaluasi data sektoral...','https://diskominfo.probolinggokab.go.id//storage/photos/shares/WhatsApp Image 2025-11-21 at 11.09.23.jpeg','Berita','2025-11-21',98,1,'2026-08-03 00:29:23','2026-08-03 00:29:23');
/*!40000 ALTER TABLE `news_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Struktur Organisasi','struktur-organisasi','<h2>Struktur Organisasi Dinas Kominfo</h2><p>Berdasarkan Peraturan Bupati Probolinggo, struktur organisasi Dinas Komunikasi, Informatika, Statistik dan Persandian terdiri dari Kepala Dinas, Sekretariat, Bidang Informasi dan Komunikasi Publik, Bidang Aplikasi Informatika, serta Bidang Statistik dan Persandian.</p>',1,'2026-08-03 00:29:23','2026-08-03 00:29:23'),(2,'Visi Misi','visi-misi','<h2>Visi & Misi Instansi</h2><p><strong>Visi:</strong> Terwujudnya Tata Kelola Pemerintahan Berbasis Digital yang Transparan, Akuntabel, dan Pelayanan Informasi Publik Unggul.</p><p><strong>Misi:</strong> 1. Meningkatkan infrastruktur teknologi informasi. 2. Mewujudkan keterbukaan informasi publik. 3. Memperkuat sistem statistik dan keamanan siber.</p>',1,'2026-08-03 00:29:23','2026-08-03 00:29:23'),(3,'Tugas dan Fungsi','tugas-dan-fungsi','<h2>Tugas dan Fungsi Utama</h2><p>Dinas Komunikasi, Informatika, Statistik dan Persandian mempunyai tugas membantu Bupati melaksanakan urusan pemerintahan yang menjadi kewenangan Daerah dan tugas pembantuan di bidang komunikasi, informatika, statistik dan persandian.</p>',1,'2026-08-03 00:29:23','2026-08-03 00:29:23'),(4,'Standar Pelayanan Publik','standar-pelayanan-publik','<h2>Standar Pelayanan Publik Diskominfo</h2><p>Pelayanan publik diselenggarakan dengan asas kepastian hukum, keterbukaan, akuntabilitas, dan fasilitas yang memadai untuk masyarakat Kabupaten Probolinggo.</p>',1,'2026-08-03 00:29:23','2026-08-03 00:29:23');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public_documents`
--

DROP TABLE IF EXISTS `public_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `public_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Perencanaan Kinerja',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public_documents`
--

LOCK TABLES `public_documents` WRITE;
/*!40000 ALTER TABLE `public_documents` DISABLE KEYS */;
INSERT INTO `public_documents` VALUES (1,'Rencana Strategis (Renstra) Diskominfo 2023-2026','Perencanaan Kinerja',NULL,'https://diskominfo.probolinggokab.go.id/dokumen/renstra.pdf',45,'2026-08-03 00:29:23','2026-08-03 00:29:23'),(2,'Laporan Kinerja Instansi Pemerintah (LKjIP) Tahun 2025','Pelaporan Kinerja',NULL,'https://diskominfo.probolinggokab.go.id/dokumen/lkjip2025.pdf',88,'2026-08-03 00:29:23','2026-08-03 00:29:23');
/*!40000 ALTER TABLE `public_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `related_links`
--

DROP TABLE IF EXISTS `related_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `related_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `related_links`
--

LOCK TABLES `related_links` WRITE;
/*!40000 ALTER TABLE `related_links` DISABLE KEYS */;
INSERT INTO `related_links` VALUES (1,'SP4N Lapor','https://diskominfo.probolinggokab.go.id//storage/photos/shares/Logo-s4pan.jpg','https://www.lapor.go.id/',1,1,'2026-08-03 00:29:23','2026-08-03 00:29:23'),(2,'Kominfo Jatim','https://diskominfo.probolinggokab.go.id//storage/photos/shares/Logo-kominfojatim.png','https://kominfo.jatimprov.go.id/',2,1,'2026-08-03 00:29:23','2026-08-03 00:29:23');
/*!40000 ALTER TABLE `related_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sidebar_widgets`
--

DROP TABLE IF EXISTS `sidebar_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sidebar_widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sidebar_widgets`
--

LOCK TABLES `sidebar_widgets` WRITE;
/*!40000 ALTER TABLE `sidebar_widgets` DISABLE KEYS */;
INSERT INTO `sidebar_widgets` VALUES (1,'Maklumat Pelayanan','https://diskominfo.probolinggokab.go.id//storage/photos/4/maklumat pelayanan diskominfo.jpg',NULL,1,1,'2026-08-03 00:29:23','2026-08-03 00:29:23'),(2,'Kepala Dinas','https://diskominfo.probolinggokab.go.id//storage/photos/shares/_DSC1270.JPG.jpeg',NULL,2,1,'2026-08-03 00:29:23','2026-08-03 00:29:23');
/*!40000 ALTER TABLE `sidebar_widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_title','Website Resmi | Dinas Komunikasi informatika, statistik dan persandian',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(2,'site_description','Website Resmi Dinas Komunikasi informatika, statistik dan persandian Kabupaten Probolinggo',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(3,'agency_name','Dinas Komunikasi informatika, statistik dan persandian',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(4,'regency_name','Kabupaten Probolinggo',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(5,'logo_frontend','https://diskominfo.probolinggokab.go.id/backend/gambar/logo_frontend.png',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(6,'logo_backend','https://diskominfo.probolinggokab.go.id/backend/gambar/logo_backend.png',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(7,'logo_berakhlak','https://diskominfo.probolinggokab.go.id/frontend/images/img-berakhlak.png',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(8,'qr_code_survey','https://diskominfo.probolinggokab.go.id/backend/gambar/qr_code_kominfo.png',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(9,'address','Jl. Panglima Sudirman No. 134 lt. 3 - Kraksaan - Probolinggo',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(10,'phone','0335 844554',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(11,'email','diskominfo@probolinggokab.go.id',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(12,'survey_url','https://sukma.jatimprov.go.id/fe/survey?idUser=2676',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(13,'instagram_url','https://www.instagram.com/diskominfokabprobolinggo/',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22'),(14,'copyright_text','DISKOMINFO - Kabupaten Probolinggo © 2025. All Rights Reserved',NULL,'text','2026-08-03 00:29:22','2026-08-03 00:29:22');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'super_admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin Diskominfo','admin@diskominfo.go.id',NULL,'$2y$12$uSFUHbondEQXb2BN.p8n6ONvapc6obZv1kE1S0f4fqOrHYx3lJfce','super_admin',NULL,'2026-08-03 00:29:22','2026-08-03 00:29:22'),(2,'Anggota Staf Diskominfo','anggota@diskominfo.go.id',NULL,'$2y$12$YdIU0TfRqxQcfBjQz6oy/eFRzaQ/YuYaGiZAXks185RM/6UV4r9Ta','anggota',NULL,'2026-08-03 00:29:22','2026-08-03 00:29:22');
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

-- Dump completed on 2026-08-03 14:35:39
