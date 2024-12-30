-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: polbeiro
-- ------------------------------------------------------
-- Server version	9.1.0

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
-- Table structure for table `platos`
--

DROP TABLE IF EXISTS `platos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `platos` (
  `id_plato` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen_principal` varchar(255) NOT NULL,
  `imagen_secundaria` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_plato`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platos`
--

LOCK TABLES `platos` WRITE;
/*!40000 ALTER TABLE `platos` DISABLE KEYS */;
INSERT INTO `platos` VALUES (1,'TAPA DE PULPO','Deliciosa tapa de pulpo fresco.',2.60,'Assets/IMG/PRODUCTOS/1. Pulpo Tapa.webp','Assets/IMG/PRODUCTOS/1.1 Pulpo Tapa.webp',NULL),(2,'BROCHETA DE PULPO','Brocheta de pulpo a la parrilla.',4.90,'Assets/IMG/PRODUCTOS/2. Pulpo Pincho.webp','Assets/IMG/PRODUCTOS/2.1 Pulpo Pincho.webp',NULL),(3,'PULPO A LA GALLEGA','Pulpo tradicional con pimentón y aceite de oliva.',5.60,'Assets/IMG/PRODUCTOS/3. Pulpo Gallega.webp','Assets/IMG/PRODUCTOS/3.1 Pulpo Gallega.webp',NULL),(4,'ENSALADA DE PULPO','Fresca ensalada con pulpo y vegetales.',4.75,'Assets/IMG/PRODUCTOS/4. Ensalada de Pulpo.webp','Assets/IMG/PRODUCTOS/4.1 Ensalada de Pulpo.webp',NULL),(5,'PULPO POKE','Poke bowl con pulpo marinado.',6.00,'Assets/IMG/PRODUCTOS/5. Pulpo Poke.webp','Assets/IMG/PRODUCTOS/5.1 Pulpo Poke.webp',NULL),(6,'PULPO BURGER','Hamburguesa de pulpo con alioli.',6.80,'Assets/IMG/PRODUCTOS/6. Pulpo Burger.webp','Assets/IMG/PRODUCTOS/6.1 Pulpo Burger.webp',NULL),(7,'PIZZA DE PULPO','Pizza artesanal con pulpo y queso.',8.50,'Assets/IMG/PRODUCTOS/7. Pizza Pulpo.webp','Assets/IMG/PRODUCTOS/7.1 Pizza Pulpo.webp',NULL),(8,'NIGIRI DE PULPO','Nigiri de pulpo fresco estilo japonés.',6.20,'Assets/IMG/PRODUCTOS/8. Nigiri Pulpo.webp','Assets/IMG/PRODUCTOS/8.1 Nigiri Pulpo.webp',NULL),(9,'CALAMARDITOS','Calamares fritos perfectos para compartir.',2.60,'Assets/IMG/PRODUCTOS/9. Calamarditos.webp','Assets/IMG/PRODUCTOS/9.1 Calamarditos.webp',NULL),(10,'BAO PULPO','Bao relleno de pulpo y vegetales.',4.90,'Assets/IMG/PRODUCTOS/10. Bao Pulpo.webp','Assets/IMG/PRODUCTOS/10.1 Bao Pulpo.webp',NULL),(11,'ENSALADILLA DE PULPO','Ensaladilla rusa con un toque de pulpo.',5.60,'Assets/IMG/PRODUCTOS/11. Ensaladilla Pulpo.webp','Assets/IMG/PRODUCTOS/11.1 Ensaladilla Pulpo.webp',NULL),(12,'TACO DE PULPO','Taco mexicano con pulpo y guacamole.',4.75,'Assets/IMG/PRODUCTOS/12. Taco Pulpo.webp','Assets/IMG/PRODUCTOS/12.1 Taco Pulpo.webp',NULL),(13,'PULPO EN TEMPURA','Pulpo crujiente en tempura ligera.',6.00,'Assets/IMG/PRODUCTOS/13. Pulpo Tempura.webp','Assets/IMG/PRODUCTOS/13.1 Pulpo Tempura.webp',NULL),(14,'CROQUETAS DE PULPO','Croquetas cremosas de pulpo.',6.80,'Assets/IMG/PRODUCTOS/14. Croquetas.webp','Assets/IMG/PRODUCTOS/14.1 Croquetas.webp',NULL),(15,'EMPANADA DE PULPO','Empanada gallega rellena de pulpo.',8.50,'Assets/IMG/PRODUCTOS/15. Empanada.webp','Assets/IMG/PRODUCTOS/15.1 Empanada.webp',NULL),(16,'CARPACCIO DE PULPO','Fino carpaccio de pulpo con cítricos.',6.20,'Assets/IMG/PRODUCTOS/16. Carpaccio.webp','Assets/IMG/PRODUCTOS/16.1 Carpaccio.webp',NULL);
/*!40000 ALTER TABLE `platos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-19 18:50:15
