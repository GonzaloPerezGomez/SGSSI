-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: db:3306
-- Tiempo de generación: 26-09-2024 a las 22:38:48
-- Versión del servidor: 10.8.2-MariaDB-1:10.8.2+maria~focal
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `idLibro` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `titulo` varchar(40) NOT NULL,
  `autor` varchar(20) NOT NULL,
  `f_publicacion` varchar(10) NOT NULL,
  `ISBN` char(13) NOT NULL,
  `n_paginas` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`titulo`, `autor`, `f_publicacion`, `ISBN`, `n_paginas`) VALUES
('Nacidos de la Bruma', 'Brandon Sanderson', '2014-09-01', '123456789123', 500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `numeroDNI` text NOT NULL,
  `letraDNI` text NOT NULL,
  `telefono` text,
  `nacimiento` text,
  `email` text NOT NULL,
  `usuario` text NOT NULL,
  `contrasena` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombre`, `apellido`, `numeroDNI`, `letraDNI`, `telefono`, `nacimiento`, `email`, `usuario`, `contrasena`) VALUES ('admin', 'admin', '12345678', 'z', '123456789', '1800-01-01', 'admin@gmail.com', 'admin', '1234');


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`idLibro`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
