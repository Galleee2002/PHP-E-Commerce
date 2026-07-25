
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
DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `categoria_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`categoria_id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (4,'Cartas'),(2,'Cl??sico'),(1,'Estrategia'),(5,'Misterio'),(3,'Rompecabezas');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras` (
  `compra_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_fk` int(10) unsigned NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`compra_id`),
  KEY `fk_compras_usuario` (`usuario_fk`),
  CONSTRAINT `fk_compras_usuario` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `compras` WRITE;
/*!40000 ALTER TABLE `compras` DISABLE KEYS */;
INSERT INTO `compras` VALUES (1,2,'2026-07-10 15:30:00',86998.00),(2,2,'2026-07-25 12:38:56',126997.00),(3,3,'2026-07-25 12:42:16',46999.00),(4,2,'2026-07-25 13:02:15',46999.00);
/*!40000 ALTER TABLE `compras` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `compras_tienen_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras_tienen_productos` (
  `compra_fk` int(10) unsigned NOT NULL,
  `producto_fk` int(10) unsigned NOT NULL,
  `cantidad` int(10) unsigned NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`compra_fk`,`producto_fk`),
  KEY `fk_ctp_producto` (`producto_fk`),
  CONSTRAINT `fk_ctp_compra` FOREIGN KEY (`compra_fk`) REFERENCES `compras` (`compra_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ctp_producto` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `compras_tienen_productos` WRITE;
/*!40000 ALTER TABLE `compras_tienen_productos` DISABLE KEYS */;
INSERT INTO `compras_tienen_productos` VALUES (1,1,1,46999.00),(1,2,1,39999.00),(2,1,1,46999.00),(2,2,2,39999.00),(3,1,1,46999.00),(4,1,1,46999.00);
/*!40000 ALTER TABLE `compras_tienen_productos` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `producto_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion_corta` varchar(500) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `usuario_fk` int(10) unsigned NOT NULL,
  `fecha_alta` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`producto_id`),
  KEY `idx_fecha_alta` (`fecha_alta`),
  KEY `fk_productos_usuario` (`usuario_fk`),
  CONSTRAINT `fk_productos_usuario` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'T.E.G.',46999.00,'Plan t??ctico y conquista de territorios para jugar en grupo.','Juego de estrategia por turnos donde cada jugador busca dominar el mapa con decisiones de ataque y defensa.','imgs/teg.webp',1,'2026-06-06 18:43:38'),(2,'Monopoly',39999.00,'Compra, venta y negociaci??n de propiedades para toda la familia.','Juego cl??sico donde el objetivo es administrar dinero, comprar calles y dejar sin fondos a los rivales.','imgs/monopoly.webp',1,'2026-06-06 18:43:38'),(3,'Rompecabezas Starry Sky',21999.00,'Puzzle de 1000 piezas para disfrutar solo o en familia.','Rompecabezas inspirado en una obra cl??sica, ideal para practicar paciencia y concentraci??n.','imgs/rompecabezas.webp',1,'2026-06-06 18:43:38'),(4,'No Lo Testeamos Ni Un Poco',27999.00,'Juego de cartas ca??tico y r??pido para reuniones con amigos.','Partidas din??micas con cartas impredecibles, ideal para grupos que buscan humor y diversi??n.','imgs/no_lo_testeamos_ni_un_poco.webp',1,'2026-06-06 18:43:38'),(5,'Burako',25999.00,'Versi??n de buraco para 2 o m??s jugadores.','Juego de combinaciones y estrategia liviana donde gana quien administra mejor sus cartas.','imgs/burako.webp',1,'2026-06-06 18:43:38'),(6,'Clue',32999.00,'Descubr?? al culpable, el arma y el lugar antes que los dem??s.','Juego de deducci??n y misterio donde ten??s que reunir pistas y razonar para resolver el crimen.','imgs/clue.webp',1,'2026-06-06 18:43:38');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `productos_tienen_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos_tienen_categorias` (
  `producto_fk` int(10) unsigned NOT NULL,
  `categoria_fk` int(10) unsigned NOT NULL,
  PRIMARY KEY (`producto_fk`,`categoria_fk`),
  KEY `fk_ptc_categoria` (`categoria_fk`),
  CONSTRAINT `fk_ptc_categoria` FOREIGN KEY (`categoria_fk`) REFERENCES `categorias` (`categoria_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ptc_producto` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `productos_tienen_categorias` WRITE;
/*!40000 ALTER TABLE `productos_tienen_categorias` DISABLE KEYS */;
INSERT INTO `productos_tienen_categorias` VALUES (1,1),(2,2),(3,3),(4,4),(5,4),(6,5);
/*!40000 ALTER TABLE `productos_tienen_categorias` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `usuario_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `rol` enum('comun','admin') NOT NULL DEFAULT 'comun',
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin@galmir.local','$2y$10$69jvgm2s9KH7L5TWb3Ii0Oc5pixmrpbWT3exKoWNZtrrvOsoCOZyi','Admin','Galmir','admin'),(2,'usuario@galmir.local','$2y$12$1v99.fQp/pQKYgraqywQC.dbg9XJpiMhTLiCRBbVoYYS6kkRKHN26','Usuario','Prueba','comun'),(3,'test@gmail.com','$2y$10$k5hfhpmanvrW68NNCMFWe.//iTFjGk0q0FdLCHkJFpWO78vghoprK','Gael','Garcia','comun');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

