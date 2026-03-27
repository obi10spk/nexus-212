/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for Linux (x86_64)
--
-- Host: mysql-unitybatallon212.alwaysdata.net    Database: unitybatallon212_bd
-- ------------------------------------------------------
-- Server version	11.4.9-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
SET net_read_timeout=600;

--
-- Table structure for table `avisos`
--

DROP TABLE IF EXISTS `avisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `avisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `rango` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_discord` varchar(100) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `motivo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avisos`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `avisos` WRITE;
/*!40000 ALTER TABLE `avisos` DISABLE KEYS */;
INSERT INTO `avisos` VALUES
(2,'2025-05-17','SDO','Paco','','Leve','Actitudes troll dentro del servidor dia 17/05/2025'),
(3,'2025-06-21','CBO 2º','Nemesis','juan.de.dios212','Leve','Faltas de respeto a otras legiones dia 21/06/2025 y Ban 22/06/2025'),
(4,'2026-02-27','SDO2º','Sadie','._ezio_auditore_.','Muy Grave','Actitudes troll y comentarios fuera de lugar, se fue de la 212th dia 27/02');
/*!40000 ALTER TABLE `avisos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `control_cambios`
--

DROP TABLE IF EXISTS `control_cambios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `control_cambios` (
  `seccion` varchar(50) NOT NULL,
  `ultima_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`seccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_cambios`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `control_cambios` WRITE;
/*!40000 ALTER TABLE `control_cambios` DISABLE KEYS */;
INSERT INTO `control_cambios` VALUES
('air','2026-03-27 11:38:41'),
('avisos','2026-03-27 11:39:56'),
('cbo','2026-03-27 11:37:52'),
('elites_act','2026-03-27 11:39:34'),
('elites_fut','2026-03-27 12:06:33'),
('subofis','2026-03-27 11:42:54');
/*!40000 ALTER TABLE `control_cambios` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `futuros_elites`
--

DROP TABLE IF EXISTS `futuros_elites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `futuros_elites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo` varchar(10) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `mes_anterior` varchar(10) DEFAULT NULL,
  `mes_actual` varchar(10) DEFAULT NULL,
  `resumen_mes` varchar(50) DEFAULT '-',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `futuros_elites`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `futuros_elites` WRITE;
/*!40000 ALTER TABLE `futuros_elites` DISABLE KEYS */;
INSERT INTO `futuros_elites` VALUES
(1,'ASE','Roman','1','-',''),
(2,'ASE','Gearshift','2','-',''),
(3,'APE','Scorch','1','-',''),
(4,'RNE','Jey','1','1',''),
(5,'RNE','Longshot','-','-',''),
(6,'ARC','Nekoma','1','1','');
/*!40000 ALTER TABLE `futuros_elites` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `miembros_air`
--

DROP TABLE IF EXISTS `miembros_air`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `miembros_air` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` enum('Comandantes','Oficiales','Suboficiales','Tropas') NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_discord` varchar(100) DEFAULT NULL,
  `aviso_1` date DEFAULT NULL,
  `aviso_2` date DEFAULT NULL,
  `resumen` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `miembros_air`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `miembros_air` WRITE;
/*!40000 ALTER TABLE `miembros_air` DISABLE KEYS */;
INSERT INTO `miembros_air` VALUES
(1,'Comandantes','Andi','shooterandi',NULL,NULL,'ACTIVO'),
(2,'Comandantes','Luca','francauron',NULL,NULL,'ACTIVO'),
(3,'Oficiales','Roman','therexguay',NULL,NULL,'ACTIVO'),
(4,'Oficiales','Nekoma','serzua',NULL,NULL,'ACTIVO'),
(5,'Oficiales','Borg','nono1360',NULL,NULL,'ACTIVO'),
(6,'Suboficiales','Jey','joan9',NULL,NULL,'ACTIVO'),
(7,'Suboficiales','Scorch','pelayo_5x',NULL,NULL,'ACTIVO'),
(8,'Suboficiales','Krypto','samuplay05',NULL,NULL,'ACTIVO'),
(10,'Suboficiales','Scott','gonzaloll',NULL,NULL,'ACTIVO'),
(11,'Tropas','Micro','vince_43967',NULL,NULL,'ACTIVO'),
(12,'Tropas','Beastboy','asimanbotijo',NULL,NULL,'ACTIVO'),
(13,'Tropas','Astro','astro1649',NULL,NULL,'ACTIVO'),
(14,'Tropas','Cotto','nnikotina',NULL,NULL,'INACTIVO'),
(15,'Tropas','Freigmare','freigmare','2026-03-22',NULL,'AVISO'),
(16,'Tropas','Ardiox','elardiox2000','2026-03-22',NULL,'AVISO'),
(17,'Tropas','Carva','tf_arturo_161',NULL,NULL,'ACTIVO'),
(18,'Tropas','Ryan','peina1238','2026-03-22',NULL,'AVISO'),
(19,'Tropas','Kyle','btk4004',NULL,NULL,'ACTIVO'),
(20,'Tropas','Sharp','yavi0293',NULL,NULL,'INACTIVO'),
(21,'Tropas','Milton','milton0614','2026-03-22',NULL,'AVISO'),
(22,'Tropas','Llorente','patojr2011_92995',NULL,NULL,'ACTIVO'),
(23,'Tropas','Carlos','carletes777',NULL,NULL,'ACTIVO'),
(24,'Tropas','Chyychah','ellay.','2026-03-22',NULL,'AVISO'),
(25,'Tropas','Harshey','zjacobs_','2026-03-22',NULL,'AVISO'),
(31,'Tropas','Gero','obiispk',NULL,NULL,'ACTIVO');
/*!40000 ALTER TABLE `miembros_air` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `miembros_cbo`
--

DROP TABLE IF EXISTS `miembros_cbo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `miembros_cbo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rango_actual` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_discord` varchar(100) DEFAULT NULL,
  `ultimo_ascenso` date DEFAULT NULL,
  `ent_liderados` int(11) DEFAULT 0,
  `misiones_asistidas` int(11) DEFAULT 0,
  `resumen_mensual` varchar(50) DEFAULT '-',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `miembros_cbo`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `miembros_cbo` WRITE;
/*!40000 ALTER TABLE `miembros_cbo` DISABLE KEYS */;
INSERT INTO `miembros_cbo` VALUES
(1,'CBO 2º','Carlitos','carletes777','2026-03-01',0,0,'hola'),
(2,'CBO 2º','Beastboy','asimanbotijo','2025-11-16',0,0,''),
(3,'CBO 2º','Nemesis','jd.nemesis','2025-05-07',0,0,''),
(4,'CBO','Carva','tf_arturo_161','2026-03-01',0,0,'hola'),
(5,'CBO','Aru','james_draw9','2026-02-13',0,0,'hola'),
(7,'CBO','Slider','garfitos.','2026-02-04',0,0,'hola'),
(8,'CBO','Galamian','galamian','2026-03-25',0,0,'hola si'),
(9,'CBO','Mape','matapepes','2026-03-26',0,0,'hola bro');
/*!40000 ALTER TABLE `miembros_cbo` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `miembros_elites`
--

DROP TABLE IF EXISTS `miembros_elites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `miembros_elites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo` varchar(10) DEFAULT NULL,
  `nombre_ic` varchar(100) DEFAULT NULL,
  `nombre_dis` varchar(100) DEFAULT NULL,
  `activo` varchar(2) DEFAULT NULL,
  `mes_anterior` varchar(10) DEFAULT NULL,
  `mes_actual` varchar(10) DEFAULT NULL,
  `resumen_mes` varchar(50) DEFAULT '-',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `miembros_elites`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `miembros_elites` WRITE;
/*!40000 ALTER TABLE `miembros_elites` DISABLE KEYS */;
INSERT INTO `miembros_elites` VALUES
(1,'AS','SGT Scott','gonzaloll','SI','9','-','Mal'),
(2,'AS','CBOM Kyle','btk4004','SI','8.5','7.0','-'),
(3,'AS','CBO Aru','james_draw9','NO','6.5','-','-'),
(4,'AP','STTE Borg','nono1360','SI','8.5','-','-'),
(5,'AP','CBO Carva','tf_arturo_161','SI','8','-','-'),
(6,'RN','SGT 1º Krypto','samuplay05','NO','-','-','-');
/*!40000 ALTER TABLE `miembros_elites` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `miembros_sgt`
--

DROP TABLE IF EXISTS `miembros_sgt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `miembros_sgt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rango_actual` varchar(20) DEFAULT NULL,
  `fecha_rango_actual` date DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `id_discord` varchar(50) DEFAULT NULL,
  `steam_64` varchar(30) DEFAULT NULL,
  `ent_mes_anterior` int(11) DEFAULT 0,
  `ent_mes_actual` int(11) DEFAULT 0,
  `mis_mes_anterior` int(11) DEFAULT 0,
  `mis_mes_actual` int(11) DEFAULT 0,
  `rango_anterior` varchar(20) DEFAULT NULL,
  `fecha_rango_anterior` date DEFAULT NULL,
  `resumen_mensual` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `miembros_sgt`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `miembros_sgt` WRITE;
/*!40000 ALTER TABLE `miembros_sgt` DISABLE KEYS */;
INSERT INTO `miembros_sgt` VALUES
(1,'CMD 1º','2026-01-04','Luca','francauron','76561198907634930',2,0,0,0,'CMD','2025-11-03','-'),
(2,'CMD 1º','2026-01-04','Andi','shooterandi','76561199127782138',1,2,0,0,'CMD','2025-11-15','-'),
(3,'CPT','2025-03-02','Nekoma','serzua','76561199542981067',4,0,3,0,'TTE 1º','2025-12-27','Inactivo'),
(4,'TTE 2º','2025-01-28','Roman','therexguay','76561199076524734',0,3,0,2,'TTE','2025-11-29','Regular'),
(5,'TTE','2026-02-28','Cal','nono1360','76561198348588111',4,0,3,0,'SGTM','2025-12-27','Inactivo'),
(6,'SGT 1º','2025-12-27','Jey','joan9','76561198145123580',4,6,3,5,'SGT 2º','2025-11-03','Bien'),
(7,'SGT 1º','2026-01-28','Ryven','samuplay05','76561199575587388',2,2,0,3,'SGT 2º','2025-11-03','Regular'),
(8,'SGT 1º','2026-01-28','Scorch','pelayo_5x','76561199221021982',4,6,3,3,'SGT 2º','2025-11-29','Bien'),
(9,'SGT','2026-01-28','Gero','obiispk','76561199089192806',9,6,4,4,'CBOM','2025-07-19','Bien'),
(10,'SGT','2026-01-28','Scott','gonzaloll','76561198132856593',6,6,4,2,'CBOM','2024-08-19','Regular');
/*!40000 ALTER TABLE `miembros_sgt` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `seguridad_acceso`
--

DROP TABLE IF EXISTS `seguridad_acceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguridad_acceso` (
  `id` int(11) NOT NULL,
  `password_maestra` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguridad_acceso`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `seguridad_acceso` WRITE;
/*!40000 ALTER TABLE `seguridad_acceso` DISABLE KEYS */;
INSERT INTO `seguridad_acceso` VALUES
(1,'1234');
/*!40000 ALTER TABLE `seguridad_acceso` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-03-27 13:18:19
