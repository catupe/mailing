-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-03-2017 a las 12:40:37
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mailing`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campania`
--

CREATE TABLE `campania` (
  `id` int(11) NOT NULL,
  `fecha_alta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_fin` timestamp NULL DEFAULT NULL,
  `activa` smallint(6) NOT NULL DEFAULT '1',
  `fecha_comienzo_campania` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_fin_campania` timestamp NULL DEFAULT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `activo` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destinatario`
--

CREATE TABLE `destinatario` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_campania`
--

CREATE TABLE `item_campania` (
  `id_campania` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `activo` smallint(6) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mailing`
--

CREATE TABLE `mailing` (
  `id` int(11) NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `cuerpo` varchar(255) NOT NULL,
  `adjunto` varchar(255) NOT NULL,
  `id_campania` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `campania`
--
ALTER TABLE `campania`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `destinatario`
--
ALTER TABLE `destinatario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `item_campania`
--
ALTER TABLE `item_campania`
  ADD KEY `id_campania` (`id_campania`,`id_destinatario`),
  ADD KEY `iddestinatario` (`id_destinatario`);

--
-- Indices de la tabla `mailing`
--
ALTER TABLE `mailing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_campania` (`id_campania`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `campania`
--
ALTER TABLE `campania`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `destinatario`
--
ALTER TABLE `destinatario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `mailing`
--
ALTER TABLE `mailing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `item_campania`
--
ALTER TABLE `item_campania`
  ADD CONSTRAINT `idcampania` FOREIGN KEY (`id_campania`) REFERENCES `campania` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `iddestinatario` FOREIGN KEY (`id_destinatario`) REFERENCES `destinatario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mailing`
--
ALTER TABLE `mailing`
  ADD CONSTRAINT `idcamania` FOREIGN KEY (`id_campania`) REFERENCES `campania` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
