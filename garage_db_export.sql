/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: garage_db
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

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
-- Current Database: `garage_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `garage_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `garage_db`;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','garagiste') DEFAULT 'garagiste',
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES
(1,'Administrateur Garage','admin@garage.fr','$2y$10$s6hwTwRBo1wlcg6kMD0XnuN4VS6OijNLuOT0HXk36/HFgZEShzMlG','admin','2026-06-16 15:36:40');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demandes_nettoyage`
--

DROP TABLE IF EXISTS `demandes_nettoyage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `demandes_nettoyage` (
  `id_demande_nettoyage` int(11) NOT NULL AUTO_INCREMENT,
  `id_service` int(11) DEFAULT NULL,
  `nom_client` varchar(120) NOT NULL,
  `email_client` varchar(150) NOT NULL,
  `telephone_client` varchar(30) NOT NULL,
  `vehicule` varchar(150) DEFAULT NULL,
  `date_souhaitee` date DEFAULT NULL,
  `message` text DEFAULT NULL,
  `statut` enum('nouvelle','confirmee','refusee','realisee','annulee') DEFAULT 'nouvelle',
  `note_admin` text DEFAULT NULL,
  `date_demande` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_demande_nettoyage`),
  KEY `id_service` (`id_service`),
  CONSTRAINT `demandes_nettoyage_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `services_nettoyage` (`id_service`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demandes_nettoyage`
--

LOCK TABLES `demandes_nettoyage` WRITE;
/*!40000 ALTER TABLE `demandes_nettoyage` DISABLE KEYS */;
INSERT INTO `demandes_nettoyage` VALUES
(1,1,'MOI','moi@mail.com','000000','Zafira','2026-06-22','','nouvelle',NULL,'2026-06-17 12:40:23');
/*!40000 ALTER TABLE `demandes_nettoyage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demandes_pieces`
--

DROP TABLE IF EXISTS `demandes_pieces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `demandes_pieces` (
  `id_demande_piece` int(11) NOT NULL AUTO_INCREMENT,
  `id_piece` int(11) DEFAULT NULL,
  `nom_client` varchar(120) NOT NULL,
  `email_client` varchar(150) NOT NULL,
  `telephone_client` varchar(30) NOT NULL,
  `marque_vehicule` varchar(100) DEFAULT NULL,
  `modele_vehicule` varchar(100) DEFAULT NULL,
  `annee_vehicule` varchar(20) DEFAULT NULL,
  `piece_recherchee` varchar(150) NOT NULL,
  `reference_piece` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `statut` enum('nouvelle','en_cours','trouvee','impossible','terminee') DEFAULT 'nouvelle',
  `note_admin` text DEFAULT NULL,
  `date_demande` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_demande_piece`),
  KEY `id_piece` (`id_piece`),
  CONSTRAINT `demandes_pieces_ibfk_1` FOREIGN KEY (`id_piece`) REFERENCES `pieces` (`id_piece`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demandes_pieces`
--

LOCK TABLES `demandes_pieces` WRITE;
/*!40000 ALTER TABLE `demandes_pieces` DISABLE KEYS */;
INSERT INTO `demandes_pieces` VALUES
(1,NULL,'Thibault SANGLIER','allo@gmail.com','06000000','BMX','C70','2015','Disques de frein Volkswagen Golf','FR-VW-GOLF-DISQ','Bonjour, je suis intéressé par la pièce suivante : Disques de frein Volkswagen Golf, référence FR-VW-GOLF-DISQ, affichée au prix de 75 €. Merci de me recontacter pour confirmer la disponibilité.','en_cours','commande préparer paré pour livraison','2026-06-16 16:54:03');
/*!40000 ALTER TABLE `demandes_pieces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages_contact`
--

DROP TABLE IF EXISTS `messages_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages_contact` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `nom_client` varchar(120) NOT NULL,
  `email_client` varchar(150) NOT NULL,
  `telephone_client` varchar(30) DEFAULT NULL,
  `sujet` enum('vehicule','nettoyage','piece','autre') DEFAULT 'autre',
  `message` text NOT NULL,
  `statut` enum('nouveau','lu','traite') DEFAULT 'nouveau',
  `date_message` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages_contact`
--

LOCK TABLES `messages_contact` WRITE;
/*!40000 ALTER TABLE `messages_contact` DISABLE KEYS */;
INSERT INTO `messages_contact` VALUES
(1,'Thibault SANGLIER','lalala@gmail.com','030300303','vehicule','Bonjour votre clio est superbe','nouveau','2026-06-17 08:34:21'),
(2,'Thibault SANGLIER','lalala@gmail.com','030300303','vehicule','Bonjour votre clio est superbe','nouveau','2026-06-17 08:52:37');
/*!40000 ALTER TABLE `messages_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pieces`
--

DROP TABLE IF EXISTS `pieces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pieces` (
  `id_piece` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `categorie` enum('moteur','carrosserie','freinage','optique','interieur','suspension','autre') NOT NULL,
  `marque` varchar(100) DEFAULT NULL,
  `modele_compatible` varchar(150) DEFAULT NULL,
  `reference_piece` varchar(100) DEFAULT NULL,
  `etat` enum('neuf','occasion','reconditionne') DEFAULT 'occasion',
  `prix` decimal(10,2) DEFAULT NULL,
  `quantite` int(11) DEFAULT 1,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `statut` enum('disponible','indisponible') DEFAULT 'disponible',
  `date_ajout` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_piece`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pieces`
--

LOCK TABLES `pieces` WRITE;
/*!40000 ALTER TABLE `pieces` DISABLE KEYS */;
INSERT INTO `pieces` VALUES
(1,'Alternateur Renault Clio IV','moteur','Renault','Clio IV','ALT-REN-CLIO4-120','occasion',129.00,1,'Alternateur contrôlé pour Renault Clio IV.',NULL,'disponible','2026-06-16 15:36:59'),
(2,'Pare-chocs avant Peugeot 208','carrosserie','Peugeot','208','PC-PEU-208-AV','occasion',180.00,1,'Pare-chocs avant à repeindre selon couleur véhicule.',NULL,'disponible','2026-06-16 15:36:59'),
(3,'Phare avant droit Citroën C3','optique','Citroën','C3','PH-CIT-C3-AVD','occasion',95.00,1,'Optique avant droit compatible Citroën C3.',NULL,'disponible','2026-06-16 15:36:59'),
(4,'Disques de frein Volkswagen Golf','freinage','Volkswagen','Golf','FR-VW-GOLF-DISQ','neuf',75.00,2,'Jeu de disques de frein avant.',NULL,'disponible','2026-06-16 15:36:59'),
(5,'Rétroviseur gauche Mercedes Classe A','carrosserie','Mercedes','Classe A','RET-MER-CLA-G','occasion',145.00,1,'Rétroviseur extérieur gauche électrique.',NULL,'disponible','2026-06-16 15:36:59'),
(6,'Siège avant Renault Mégane','interieur','Renault','Mégane','SIE-REN-MEG-AV','occasion',210.00,1,'Siège conducteur avant pour Renault Mégane.',NULL,'disponible','2026-06-16 15:36:59');
/*!40000 ALTER TABLE `pieces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services_nettoyage`
--

DROP TABLE IF EXISTS `services_nettoyage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services_nettoyage` (
  `id_service` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(120) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_depart` decimal(10,2) DEFAULT NULL,
  `duree_estimee` varchar(100) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_service`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services_nettoyage`
--

LOCK TABLES `services_nettoyage` WRITE;
/*!40000 ALTER TABLE `services_nettoyage` DISABLE KEYS */;
INSERT INTO `services_nettoyage` VALUES
(1,'Nettoyage intérieur','interieur','Aspiration, plastiques, sièges, tapis et vitres intérieures.',49.00,'1h à 2h',1,'2026-06-16 15:37:07'),
(2,'Nettoyage extérieur','exterieur','Lavage carrosserie, jantes, vitres extérieures et finition.',39.00,'45 min à 1h30',1,'2026-06-16 15:37:07'),
(3,'Nettoyage complet','complet','Nettoyage intérieur et extérieur complet.',89.00,'2h à 3h',1,'2026-06-16 15:37:07'),
(4,'Detailing premium','detailing-premium','Nettoyage approfondi avec finitions premium.',NULL,'Sur devis',1,'2026-06-16 15:37:07');
/*!40000 ALTER TABLE `services_nettoyage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statistiques_site`
--

DROP TABLE IF EXISTS `statistiques_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `statistiques_site` (
  `id_stat` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(150) NOT NULL,
  `ip_visiteur` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `date_visite` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_stat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistiques_site`
--

LOCK TABLES `statistiques_site` WRITE;
/*!40000 ALTER TABLE `statistiques_site` DISABLE KEYS */;
/*!40000 ALTER TABLE `statistiques_site` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicule_images`
--

DROP TABLE IF EXISTS `vehicule_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicule_images` (
  `id_image` int(11) NOT NULL AUTO_INCREMENT,
  `id_vehicule` int(11) NOT NULL,
  `chemin_image` varchar(255) NOT NULL,
  `image_principale` tinyint(1) DEFAULT 0,
  `date_ajout` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_image`),
  KEY `id_vehicule` (`id_vehicule`),
  CONSTRAINT `vehicule_images_ibfk_1` FOREIGN KEY (`id_vehicule`) REFERENCES `vehicules` (`id_vehicule`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicule_images`
--

LOCK TABLES `vehicule_images` WRITE;
/*!40000 ALTER TABLE `vehicule_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicule_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicules`
--

DROP TABLE IF EXISTS `vehicules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicules` (
  `id_vehicule` int(11) NOT NULL AUTO_INCREMENT,
  `marque` varchar(100) NOT NULL,
  `modele` varchar(100) NOT NULL,
  `version` varchar(150) DEFAULT NULL,
  `annee` int(11) DEFAULT NULL,
  `kilometrage` int(11) DEFAULT NULL,
  `carburant` enum('essence','diesel','hybride','electrique','autre') NOT NULL,
  `boite` enum('manuelle','automatique') NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image_principale` varchar(255) DEFAULT NULL,
  `statut` enum('disponible','vendu','reserve') DEFAULT 'disponible',
  `mis_en_avant` tinyint(1) DEFAULT 0,
  `date_ajout` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_vehicule`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicules`
--

LOCK TABLES `vehicules` WRITE;
/*!40000 ALTER TABLE `vehicules` DISABLE KEYS */;
INSERT INTO `vehicules` VALUES
(1,'Mercedes','Classe V','220 CDI',2019,85000,'diesel','automatique',39990.00,'Véhicule spacieux, idéal famille ou transport professionnel.','uploads/vehicules/vehicule_1781708392_83df88fe71.png','disponible',1,'2026-06-16 15:36:50'),
(2,'Citroën','C5 Shine','BlueHDi',2020,62000,'diesel','automatique',18490.00,'Berline confortable avec bon niveau d’équipement.',NULL,'disponible',1,'2026-06-16 15:36:50'),
(3,'Renault','Clio V','TCe 100',2021,45000,'essence','manuelle',13990.00,'Citadine économique et polyvalente.',NULL,'disponible',1,'2026-06-16 15:36:50'),
(4,'Toyota','Corolla','Hybride',2022,38000,'hybride','automatique',22990.00,'Compacte hybride fiable et économique.',NULL,'reserve',0,'2026-06-16 15:36:50'),
(5,'Peugeot','3008','BlueHDi',2018,96000,'diesel','manuelle',16990.00,'SUV familial confortable.',NULL,'vendu',0,'2026-06-16 15:36:50'),
(6,'Renault','Mégane E-Tech','EV60',2023,18000,'electrique','automatique',28990.00,'Véhicule électrique récent et bien équipé.',NULL,'disponible',0,'2026-06-16 15:36:50');
/*!40000 ALTER TABLE `vehicules` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-17 17:05:06
