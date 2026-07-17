-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-05-2026 a las 01:53:09
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prog2_2026_1_n`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

DROP TABLE IF EXISTS `equipos`;
CREATE TABLE IF NOT EXISTS `equipos` (
  `equipo_id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(45) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`equipo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`equipo_id`, `ciudad`, `nombre`) VALUES
(1, 'San Antonio', 'Spurs'),
(2, 'Los Angeles', 'Lakers'),
(3, 'Los Angeles', 'Clippers'),
(4, 'Boston', 'Celtics'),
(5, 'Houston', 'Rockets'),
(6, 'Denver', 'Nuggets'),
(7, 'Toronto', 'Raptors');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

DROP TABLE IF EXISTS `noticias`;
CREATE TABLE IF NOT EXISTS `noticias` (
  `noticia_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_fk` int UNSIGNED NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `sinopsis` varchar(200) NOT NULL,
  `cuerpo` text NOT NULL,
  `fecha_publicacion` datetime NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `imagen_descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`noticia_id`),
  KEY `fecha_publicacion_INDEX` (`fecha_publicacion` DESC),
  KEY `fk_noticias_usuarios1_idx` (`usuario_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`noticia_id`, `usuario_fk`, `titulo`, `sinopsis`, `cuerpo`, `fecha_publicacion`, `imagen`, `imagen_descripcion`) VALUES
(1, 1, 'Ginóbili sigue rompiendo récords', 'Emanuel \'Manu\' Ginóbili viene rompiendo algunos récords tanto de su equipo como de la liga.', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?', '2019-02-19 15:15:15', 'manu.jpg', 'Manu Ginóbili'),
(3, 1, 'Houston Rockets lidera la conferencia', 'De la mano de James Harden, los Rockets se apuntan como candidatos para ganar los playoff.', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?', '2019-02-27 09:52:46', 'rockets-logo.jpg', 'Houston Rockets Logo'),
(4, 1, 'Toronto Raptors queda primero en el Este', 'Los Raptors de Lowry y DeRozan se quedan con el primer lugar de su conferencia.', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?', '2019-03-01 19:11:43', 'raptors-logo.jpg', 'Toronto Raptors Logo'),
(5, 1, 'Denver se queda corto por un partido', 'Emanuel \'Manu\' Ginóbili viene rompiendo algunos récords tanto de su equipo como de la liga.', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?', '2019-03-01 22:53:24', 'nuggets-logo.jpg', 'Denver Nuggets Logo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias_tienen_equipos`
--

DROP TABLE IF EXISTS `noticias_tienen_equipos`;
CREATE TABLE IF NOT EXISTS `noticias_tienen_equipos` (
  `noticia_fk` int UNSIGNED NOT NULL,
  `equipo_fk` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`noticia_fk`,`equipo_fk`),
  KEY `fk_noticias_has_equipos_equipos1_idx` (`equipo_fk`),
  KEY `fk_noticias_has_equipos_noticias1_idx` (`noticia_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `noticias_tienen_equipos`
--

INSERT INTO `noticias_tienen_equipos` (`noticia_fk`, `equipo_fk`) VALUES
(1, 1),
(1, 2),
(3, 2),
(1, 3),
(4, 4),
(1, 5),
(3, 5),
(5, 6),
(4, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `email`, `password`, `nombre`, `apellido`) VALUES
(1, 'sara@za.com', 'asdasd', 'Sara', 'Za'),
(2, 'pepe@trueno.com', 'asdasd', 'Pepe', 'Trueno'),
(3, 'jperez@gmail.com', 'asdasd', NULL, NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `fk_noticias_usuarios1` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`);

--
-- Filtros para la tabla `noticias_tienen_equipos`
--
ALTER TABLE `noticias_tienen_equipos`
  ADD CONSTRAINT `fk_noticias_has_equipos_equipos1` FOREIGN KEY (`equipo_fk`) REFERENCES `equipos` (`equipo_id`),
  ADD CONSTRAINT `fk_noticias_has_equipos_noticias1` FOREIGN KEY (`noticia_fk`) REFERENCES `noticias` (`noticia_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
