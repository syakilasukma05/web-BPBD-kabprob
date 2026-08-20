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
INSERT INTO `hero_sliders` VALUES (1,'Selamat Datang di Portal Resmi BPBD','Badan Penanggulangan Bencana Daerah Kabupaten Probolinggo - Tangguh, Siap, Sigap!','https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1600&auto=format&fit=crop','Lapor Bencana Darurat','/kontak',1,1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(2,'Pusdalops PB Siaga 24 Jam','Pemantauan Peringatan Dini Cuaca, Erupsi Bromo, & Evakuasi Darurat Bencana Kebencanaan','https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1600&auto=format&fit=crop','Lihat Informasi Peringatan','/informasi',2,1,'2026-08-03 00:38:39','2026-08-03 00:38:39');
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
INSERT INTO `navigation_menus` VALUES (1,'HOME','/',NULL,1,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(2,'PROFIL','#',NULL,2,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(3,'Struktur Organisasi BPBD','/halaman/struktur-organisasi',2,1,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(4,'Visi Misi Kebencanaan','/halaman/visi-misi',2,2,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(5,'Tugas dan Fungsi BPBD','/halaman/tugas-dan-fungsi',2,3,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(6,'Peta Rawan Bencana Daerah','/halaman/peta-rawan-bencana',2,4,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(7,'LAYANAN','#',NULL,3,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(8,'Layanan Pusdalops PB (24 Jam)','/halaman/layanan-pusdalops',7,1,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(9,'Sistem Peringatan Dini (EWS)','/halaman/peringatan-dini-ews',7,2,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(10,'Standar Pelayanan Penanggulangan Bencana','/halaman/standar-pelayanan-publik',7,3,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(11,'Permohonan Bantuan Logistik & Tenda','/halaman/bantuan-logistik',7,4,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(12,'DOKUMEN','#',NULL,4,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(13,'Kajian Risiko Bencana (KRB)','/dokumen',12,1,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(14,'Rencana Penanggulangan Bencana (RPB)','/dokumen',12,2,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(15,'Renstra & Laporan Kinerja BPBD','/dokumen',12,3,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(16,'SOP Tanggap Darurat Bencana','/dokumen',12,4,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(17,'INFORMASI','#',NULL,5,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(18,'Berita Kebencanaan','/informasi',17,1,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(19,'Peringatan Dini Cuaca BMKG','https://www.bmkg.go.id/',17,2,'_blank',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(20,'Status Gunung Bromo','/informasi',17,3,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(21,'Galeri Posko Kebencanaan','/informasi',17,4,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(22,'HUBUNGI','#',NULL,6,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(23,'Lapor Bencana 24 Jam','/kontak',22,1,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(24,'Kontak Pusdalops BPBD','/kontak',22,2,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(25,'LOGIN','/login',NULL,7,'_self',1,'2026-08-03 00:38:39','2026-08-03 00:38:39');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_items`
--

LOCK TABLES `news_items` WRITE;
/*!40000 ALTER TABLE `news_items` DISABLE KEYS */;
INSERT INTO `news_items` VALUES (1,'BPBD Kabupaten Probolinggo Tingkatkan Kesiapsiagaan Posko Pemantauan Gunung Bromo Level II Waspada','bpbd-kabupaten-probolinggo-tingkatkan-kesiapsiagaan-posko-gunung-bromo','Tim Pusdalops PB BPBD terus melakukan koordinasi dengan PVMBG terkait aktivitas vulkanik Gunung Bromo.','Probolinggo - Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Probolinggo mengimbau wisatawan dan warga lereng Gunung Bromo untuk tetap tenang namun waspada...','https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=800&auto=format&fit=crop','Peringatan Dini','2026-08-01',312,1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(2,'Tim Reaksi Cepat (TRC) BPBD Distribusikan Bantuan Air Bersih dan Logistik Korban Banjir Dringu','trc-bpbd-distribusikan-bantuan-air-bersih-korban-banjir-dringu','Penanganan tanggap darurat banjir luapan sungai Dringu dengan mendirikan tenda pengungsian & dapur umum.','Dringu - Personel TRC BPBD Kabupaten Probolinggo bergerak cepat membantu evakuasi warga terdampak genangan air...','https://images.unsplash.com/photo-1547683905-f686c993aae5?q=80&w=800&auto=format&fit=crop','Tanggap Darurat','2026-07-28',245,1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(3,'BPBD Gelar Simulasi Mitigasi Bencana Tanah Longsor Bersama Relawan Desa Tangguh Bencana (Destana)','bpbd-gelar-simulasi-mitigasi-bencana-tanah-longsor-destana','Pelatihan evakuasi mandiri bagi masyarakat wilayah rawan bencana longsor di Kecamatan Sukapura & Tiris.','Sukapura - Edukasi dan gladi penanggulangan bencana tanah longsor dilaksanakan untuk memperkuat respon masyarakat desa...','https://images.unsplash.com/photo-1588681664899-f142ff2dc9b1?q=80&w=800&auto=format&fit=crop','Mitigasi Bencana','2026-07-15',189,0,'2026-08-03 00:38:39','2026-08-03 00:38:39');
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
INSERT INTO `pages` VALUES (1,'Struktur Organisasi BPBD','struktur-organisasi','<h2>Struktur Organisasi BPBD Kabupaten Probolinggo</h2><p>Badan Penanggulangan Bencana Daerah terdiri dari unsur Pengarah dan unsur Pelaksana. Unsur Pelaksana dipimpin oleh Kepala Pelaksana yang membawahi Sekretariat, Bidang Pencegahan dan Kesiapsiagaan, Bidang Kedaruratan dan Logistik, serta Bidang Rehabilitasi dan Rekonstruksi.</p>',1,'2026-08-03 00:38:40','2026-08-03 00:38:40'),(2,'Visi Misi Kebencanaan','visi-misi','<h2>Visi & Misi Kebencanaan</h2><p><strong>Visi:</strong> Terwujudnya Kabupaten Probolinggo yang Tangguh Bencana dan Siap Siaga Menghadapi Ancaman Darurat Kebencanaan.</p><p><strong>Misi:</strong> 1. Melindungi masyarakat dari ancaman bencana melalui pengurangan risiko bencana. 2. Membangun penanggulangan bencana yang terencana, terpadu, dan terkoordinasi. 3. Memperkuat kapasitas Pusdalops & TRC BPBD.</p>',1,'2026-08-03 00:38:40','2026-08-03 00:38:40'),(3,'Tugas dan Fungsi BPBD','tugas-dan-fungsi','<h2>Tugas dan Fungsi BPBD</h2><p>BPBD mempunyai tugas menetapkan pedoman dan pengarahan terhadap penanggulangan bencana yang mencakup pencegahan bencana, penanganan darurat, rehabilitasi, serta rekonstruksi secara adil dan terkoordinasi.</p>',1,'2026-08-03 00:38:40','2026-08-03 00:38:40'),(4,'Peta Rawan Bencana','peta-rawan-bencana','<h2>Peta Kawasan Rawan Bencana (KRB)</h2><p>Kabupaten Probolinggo memiliki beragam potensi ancaman kebencanaan seperti Erupsi Gunung Bromo (Sukapura), Tanah Longsor (Tiris, Krucil), Banjir Bandang (Dringu, Gading), dan Gelombang Pasang Pantai Utara.</p>',1,'2026-08-03 00:38:40','2026-08-03 00:38:40');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public_documents`
--

LOCK TABLES `public_documents` WRITE;
/*!40000 ALTER TABLE `public_documents` DISABLE KEYS */;
INSERT INTO `public_documents` VALUES (1,'Dokumen Rencana Penanggulangan Bencana (RPB) Kabupaten Probolinggo 2024-2029','Perencanaan Kinerja',NULL,'https://bpbd.probolinggokab.go.id/dokumen/rpb_probolinggo.pdf',128,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(2,'Kajian Risiko Bencana (KRB) Tanah Longsor & Banjir Kabupaten Probolinggo','Pengukuran Kinerja',NULL,'https://bpbd.probolinggokab.go.id/dokumen/krb_probolinggo.pdf',210,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(3,'Laporan Akuntabilitas Kinerja BPBD Kabupaten Probolinggo Tahun 2025','Pelaporan Kinerja',NULL,'https://bpbd.probolinggokab.go.id/dokumen/lakip_bpbd_2025.pdf',95,'2026-08-03 00:38:39','2026-08-03 00:38:39');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `related_links`
--

LOCK TABLES `related_links` WRITE;
/*!40000 ALTER TABLE `related_links` DISABLE KEYS */;
INSERT INTO `related_links` VALUES (1,'BNPB Indonesia','https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png','https://bnpb.go.id/',1,1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(2,'BPBD Jatim','https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png','https://bpbd.jatimprov.go.id/',2,1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(3,'BMKG Pusat','https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png','https://bmkg.go.id/',3,1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(4,'PVMBG Magma Indonesia','https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png','https://magma.esdm.go.id/',4,1,'2026-08-03 00:38:39','2026-08-03 00:38:39');
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
INSERT INTO `sidebar_widgets` VALUES (1,'Call Center Emergency BPBD 24 Jam','https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?q=80&w=600&auto=format&fit=crop','/kontak',1,1,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(2,'Peta Kawasan Rawan Bencana (KRB)','https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=600&auto=format&fit=crop','/halaman/peta-rawan-bencana',2,1,'2026-08-03 00:38:39','2026-08-03 00:38:39');
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
INSERT INTO `site_settings` VALUES (1,'site_title','Website Resmi | Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Probolinggo',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(2,'site_description','Website Resmi BPBD Kabupaten Probolinggo - Pusat Informasi Kebencanaan, Peringatan Dini, dan Pelayanan Tanggap Darurat Bencana.',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(3,'agency_name','Badan Penanggulangan Bencana Daerah (BPBD)',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(4,'regency_name','Kabupaten Probolinggo',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(5,'logo_frontend','https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(6,'logo_backend','https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(7,'logo_berakhlak','https://diskominfo.probolinggokab.go.id/frontend/images/img-berakhlak.png',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(8,'qr_code_survey','https://diskominfo.probolinggokab.go.id/backend/gambar/qr_code_kominfo.png',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(9,'address','Jl. Raden Wijaya No. 1, Dringu - Probolinggo, Jawa Timur 67271',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(10,'phone','(0335) 422113 / Call Center 24 Jam: 0812-3123-911',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(11,'email','bpbd@probolinggokab.go.id',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(12,'survey_url','https://sukma.jatimprov.go.id/',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(13,'instagram_url','https://www.instagram.com/bpbdkabprobolinggo/',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39'),(14,'copyright_text','BPBD - Kabupaten Probolinggo © 2026. All Rights Reserved. Siap, Sigap, Tanggap Bencana!',NULL,'text','2026-08-03 00:38:39','2026-08-03 00:38:39');
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
INSERT INTO `users` VALUES (1,'Super Admin BPBD','admin@bpbd.probolinggokab.go.id',NULL,'$2y$12$LWBwcXYvKfyvdgX47a8chO/B53Fn0WFoEU0E1Gyz61gNoJrmjZRoS','super_admin',NULL,'2026-08-03 00:38:39','2026-08-03 00:38:39'),(2,'Staf Pusdalops BPBD','anggota@bpbd.probolinggokab.go.id',NULL,'$2y$12$C0shMLC.oRhuQEcaJZVxVuewWL9U7x8KyjP5rTM8ckEMbNNYHim.e','anggota',NULL,'2026-08-03 00:38:39','2026-08-03 00:38:39');
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

-- Dump completed on 2026-08-03 14:38:49
