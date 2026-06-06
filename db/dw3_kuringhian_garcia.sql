-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 06-06-2026 a las 21:44:58
-- Versión del servidor: 8.0.44
-- Versión de PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dw3_kuringhian_garcia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `categoria_id` int UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`categoria_id`, `nombre`) VALUES
(4, 'Cartas'),
(2, 'Clasico'),
(1, 'Estrategia'),
(5, 'Misterio'),
(3, 'Rompecabezas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

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

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`producto_id`, `nombre`, `precio`, `descripcion_corta`, `descripcion`, `imagen`, `usuario_fk`, `fecha_alta`) VALUES
(1, 'T.E.G.', 46999.00, 'Plan tactico y conquista de territorios para jugar en grupo.', 'Juego de estrategia por turnos donde cada jugador busca dominar el mapa con decisiones de ataque y defensa.', 'imgs/teg.webp', 1, '2026-06-06 18:43:38'),
(2, 'Monopoly', 39999.00, 'Compra, venta y negociacion de propiedades para toda la familia.', 'Juego clasico donde el objetivo es administrar dinero, comprar calles y dejar sin fondos a los rivales.', 'imgs/monopoly.webp', 1, '2026-06-06 18:43:38'),
(3, 'Rompecabezas Starry Sky', 21999.00, 'Puzzle de 1000 piezas para disfrutar solo o en familia.', 'Rompecabezas inspirado en una obra clasica, ideal para practicar paciencia y concentracion.', 'imgs/rompecabezas.webp', 1, '2026-06-06 18:43:38'),
(4, 'No Lo Testeamos Ni Un Poco', 27999.00, 'Juego de cartas caotico y rapido para reuniones con amigos.', 'Partidas dinamicas con cartas impredecibles, ideal para grupos que buscan humor y diversion.', 'imgs/no_lo_testeamos_ni_un_poco.webp', 1, '2026-06-06 18:43:38'),
(5, 'Burako', 25999.00, 'Version de buraco para 2 o mas jugadores.', 'Juego de combinaciones y estrategia liviana donde gana quien administra mejor sus cartas.', 'imgs/burako.webp', 1, '2026-06-06 18:43:38'),
(6, 'Clue', 32999.00, 'Descubri al culpable, el arma y el lugar antes que los demas.', 'Juego de deduccion y misterio donde tenes que reunir pistas y razonar para resolver el crimen.', 'imgs/clue.webp', 1, '2026-06-06 18:43:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_tienen_categorias`
--

CREATE TABLE `productos_tienen_categorias` (
  `producto_fk` int UNSIGNED NOT NULL,
  `categoria_fk` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos_tienen_categorias`
--

INSERT INTO `productos_tienen_categorias` (`producto_fk`, `categoria_fk`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 4),
(6, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `email`, `password`, `nombre`, `apellido`) VALUES
(1, 'admin@galmir.local', 'admin123', 'Admin', 'Galmir');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoria_id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `idx_fecha_alta` (`fecha_alta` DESC),
  ADD KEY `fk_productos_usuario` (`usuario_fk`);

--
-- Indices de la tabla `productos_tienen_categorias`
--
ALTER TABLE `productos_tienen_categorias`
  ADD PRIMARY KEY (`producto_fk`,`categoria_fk`),
  ADD KEY `fk_ptc_categoria` (`categoria_fk`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoria_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `producto_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_usuario` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`);

--
-- Filtros para la tabla `productos_tienen_categorias`
--
ALTER TABLE `productos_tienen_categorias`
  ADD CONSTRAINT `fk_ptc_categoria` FOREIGN KEY (`categoria_fk`) REFERENCES `categorias` (`categoria_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ptc_producto` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
