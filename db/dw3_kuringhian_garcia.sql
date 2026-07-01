SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

CREATE TABLE `categorias` (
  `categoria_id` int UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categorias` (`categoria_id`, `nombre`) VALUES
(4, 'Cartas'),
(2, 'Clásico'),
(1, 'Estrategia'),
(5, 'Misterio'),
(3, 'Rompecabezas');

CREATE TABLE `productos` (
  `producto_id` int UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion_corta` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_fk` int UNSIGNED NOT NULL,
  `fecha_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `productos` (`producto_id`, `nombre`, `precio`, `descripcion_corta`, `descripcion`, `imagen`, `usuario_fk`, `fecha_alta`) VALUES
(1, 'T.E.G.', 46999.00, 'Plan táctico y conquista de territorios para jugar en grupo.', 'Juego de estrategia por turnos donde cada jugador busca dominar el mapa con decisiones de ataque y defensa.', 'imgs/teg.webp', 1, '2026-06-06 18:43:38'),
(2, 'Monopoly', 39999.00, 'Compra, venta y negociación de propiedades para toda la familia.', 'Juego clásico donde el objetivo es administrar dinero, comprar calles y dejar sin fondos a los rivales.', 'imgs/monopoly.webp', 1, '2026-06-06 18:43:38'),
(3, 'Rompecabezas Starry Sky', 21999.00, 'Puzzle de 1000 piezas para disfrutar solo o en familia.', 'Rompecabezas inspirado en una obra clásica, ideal para practicar paciencia y concentración.', 'imgs/rompecabezas.webp', 1, '2026-06-06 18:43:38'),
(4, 'No Lo Testeamos Ni Un Poco', 27999.00, 'Juego de cartas caótico y rápido para reuniones con amigos.', 'Partidas dinámicas con cartas impredecibles, ideal para grupos que buscan humor y diversión.', 'imgs/no_lo_testeamos_ni_un_poco.webp', 1, '2026-06-06 18:43:38'),
(5, 'Burako', 25999.00, 'Versión de buraco para 2 o más jugadores.', 'Juego de combinaciones y estrategia liviana donde gana quien administra mejor sus cartas.', 'imgs/burako.webp', 1, '2026-06-06 18:43:38'),
(6, 'Clue', 32999.00, 'Descubrí al culpable, el arma y el lugar antes que los demás.', 'Juego de deducción y misterio donde tenés que reunir pistas y razonar para resolver el crimen.', 'imgs/clue.webp', 1, '2026-06-06 18:43:38');

CREATE TABLE `productos_tienen_categorias` (
  `producto_fk` int UNSIGNED NOT NULL,
  `categoria_fk` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `productos_tienen_categorias` (`producto_fk`, `categoria_fk`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 4),
(6, 5);

CREATE TABLE `usuarios` (
  `usuario_id` int UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`usuario_id`, `email`, `password`, `nombre`, `apellido`) VALUES
(1, 'admin@galmir.local', '$2y$10$69jvgm2s9KH7L5TWb3Ii0Oc5pixmrpbWT3exKoWNZtrrvOsoCOZyi', 'Admin', 'Galmir');

ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoria_id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

ALTER TABLE `productos`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `idx_fecha_alta` (`fecha_alta` DESC),
  ADD KEY `fk_productos_usuario` (`usuario_fk`);

ALTER TABLE `productos_tienen_categorias`
  ADD PRIMARY KEY (`producto_fk`,`categoria_fk`),
  ADD KEY `fk_ptc_categoria` (`categoria_fk`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `categorias`
  MODIFY `categoria_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `productos`
  MODIFY `producto_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `usuarios`
  MODIFY `usuario_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_usuario` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`);

ALTER TABLE `productos_tienen_categorias`
  ADD CONSTRAINT `fk_ptc_categoria` FOREIGN KEY (`categoria_fk`) REFERENCES `categorias` (`categoria_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ptc_producto` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`) ON DELETE CASCADE;

COMMIT;
