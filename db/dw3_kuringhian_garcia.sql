SET NAMES utf8mb4;
SET TIME_ZONE = '+00:00';
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET SQL_NOTES = 0;

CREATE DATABASE IF NOT EXISTS `dw3_kuringhian_garcia`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `dw3_kuringhian_garcia`;

DROP TABLE IF EXISTS `usuarios`;

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

INSERT INTO `usuarios` (`usuario_id`, `email`, `password`, `nombre`, `apellido`, `rol`) VALUES
  (1, 'admin@galmir.local', '$2y$10$69jvgm2s9KH7L5TWb3Ii0Oc5pixmrpbWT3exKoWNZtrrvOsoCOZyi', 'Admin', 'Galmir', 'admin'),
  (2, 'usuario@galmir.local', '$2y$12$1v99.fQp/pQKYgraqywQC.dbg9XJpiMhTLiCRBbVoYYS6kkRKHN26', 'Usuario', 'Prueba', 'comun'),
  (3, 'test@gmail.com', '$2y$10$k5hfhpmanvrW68NNCMFWe.//iTFjGk0q0FdLCHkJFpWO78vghoprK', 'Gael', 'Garcia', 'comun');

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `categoria_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`categoria_id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categorias` (`categoria_id`, `nombre`) VALUES
  (4, 'Cartas'),
  (2, 'Clásico'),
  (1, 'Estrategia'),
  (5, 'Misterio'),
  (3, 'Rompecabezas');

DROP TABLE IF EXISTS `productos`;

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

INSERT INTO `productos` (`producto_id`, `nombre`, `precio`, `descripcion_corta`, `descripcion`, `imagen`, `usuario_fk`, `fecha_alta`) VALUES
  (1, 'T.E.G.', 46999.00, 'Plan táctico y conquista de territorios para jugar en grupo.', 'Juego de estrategia por turnos donde cada jugador busca dominar el mapa con decisiones de ataque, defensa y alianzas. Vas a repartir ejércitos, planificar movimientos y pelear por objetivos secretos mientras el tablero cambia de dueño a cada ronda. Ideal para noches largas con amigos: exige pensar con anticipación, negociar y adaptarse cuando el plan se complica.', 'imgs/teg.webp', 1, '2026-06-06 18:43:38'),
  (2, 'Monopoly', 39999.00, 'Compra, venta y negociación de propiedades para toda la familia.', 'El clásico de compra y venta de propiedades donde administrás dinero, cerrás tratos y construís tu imperio inmobiliario. Cada vuelta por el tablero puede dejarte más cerca de la victoria o al borde de la quiebra, según cómo negocies y dónde caigas. Perfecto para jugar en familia o con amigos: combina suerte, estrategia liviana y mucha tensión en cada subasta.', 'imgs/monopoly.webp', 1, '2026-06-06 18:43:38'),
  (3, 'Rompecabezas Starry Sky', 21999.00, 'Puzzle de 1000 piezas para disfrutar solo o en familia.', 'Rompecabezas de 1000 piezas inspirado en un cielo estrellado, pensado para armar con calma y disfrutar del proceso. Las formas y tonos te invitan a concentrarte, ordenar por colores y ver cómo la imagen toma forma poco a poco. Ideal para una tarde tranquila solo o en compañía: un plan relajado, sin apuro, que termina en una pieza lista para mostrar.', 'imgs/rompecabezas.webp', 1, '2026-06-06 18:43:38'),
  (4, 'No Lo Testeamos Ni Un Poco', 27999.00, 'Juego de cartas caótico y rápido para reuniones con amigos.', 'Partidas cortas, reglas absurdas y cartas que dan vuelta la mesa en segundos. Cada ronda suma humor, decisiones rápidas y situaciones imposibles de anticipar, perfectas para grupos que buscan reírse sin pensarlo demasiado. Llevalo a juntadas, afters o noches improvisadas: se explica al toque y mantiene a todos enganchados hasta la última carta.', 'imgs/no_lo_testeamos_ni_un_poco.webp', 1, '2026-06-06 18:43:38'),
  (5, 'Burako', 25999.00, 'Versión de buraco para 2 o más jugadores.', 'Juego de combinaciones y estrategia liviana basado en el buraco, donde gana quien administra mejor su mano y arma series o tríos a tiempo. Tenés que decidir cuándo arriesgar, cuándo retener fichas y cómo aprovechar lo que dejan los demás sobre la mesa. Buena opción para partidas de ritmo constante, fáciles de seguir y con espacio para mejorar a medida que jugás más.', 'imgs/burako.webp', 1, '2026-06-06 18:43:38'),
  (6, 'Clue', 32999.00, 'Descubrí al culpable, el arma y el lugar antes que los demás.', 'Juego de deducción y misterio en el que tenés que descubrir quién cometió el crimen, con qué arma y en qué habitación. Vas moviéndote por la mansión, haciendo preguntas y tachando sospechosos hasta armar la hipótesis correcta. Ideal si te gusta pensar, observar detalles y competir contra el reloj (y contra el resto) para ser el primero en resolver el caso.', 'imgs/clue.webp', 1, '2026-06-06 18:43:38');

DROP TABLE IF EXISTS `productos_tienen_categorias`;

CREATE TABLE `productos_tienen_categorias` (
  `producto_fk` int(10) unsigned NOT NULL,
  `categoria_fk` int(10) unsigned NOT NULL,
  PRIMARY KEY (`producto_fk`, `categoria_fk`),
  KEY `fk_productos_tienen_categorias_categoria` (`categoria_fk`),
  CONSTRAINT `fk_productos_tienen_categorias_categoria` FOREIGN KEY (`categoria_fk`) REFERENCES `categorias` (`categoria_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_productos_tienen_categorias_producto` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `productos_tienen_categorias` (`producto_fk`, `categoria_fk`) VALUES
  (1, 1),
  (2, 2),
  (3, 3),
  (4, 4),
  (5, 4),
  (6, 5);

DROP TABLE IF EXISTS `compras`;

CREATE TABLE `compras` (
  `compra_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_fk` int(10) unsigned NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`compra_id`),
  KEY `fk_compras_usuario` (`usuario_fk`),
  CONSTRAINT `fk_compras_usuario` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `compras` (`compra_id`, `usuario_fk`, `fecha`, `total`) VALUES
  (1, 2, '2026-07-10 15:30:00', 86998.00),
  (2, 2, '2026-07-25 12:38:56', 126997.00),
  (3, 3, '2026-07-25 12:42:16', 46999.00),
  (4, 2, '2026-07-25 13:02:15', 46999.00);

DROP TABLE IF EXISTS `compras_tienen_productos`;

CREATE TABLE `compras_tienen_productos` (
  `compra_fk` int(10) unsigned NOT NULL,
  `producto_fk` int(10) unsigned NOT NULL,
  `cantidad` int(10) unsigned NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`compra_fk`, `producto_fk`),
  KEY `fk_compras_tienen_productos_producto` (`producto_fk`),
  CONSTRAINT `fk_compras_tienen_productos_compra` FOREIGN KEY (`compra_fk`) REFERENCES `compras` (`compra_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_compras_tienen_productos_producto` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `compras_tienen_productos` (`compra_fk`, `producto_fk`, `cantidad`, `precio_unitario`) VALUES
  (1, 1, 1, 46999.00),
  (1, 2, 1, 39999.00),
  (2, 1, 1, 46999.00),
  (2, 2, 2, 39999.00),
  (3, 1, 1, 46999.00),
  (4, 1, 1, 46999.00);

SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
SET SQL_NOTES = 1;
