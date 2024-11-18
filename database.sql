-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: db:3306
-- Tiempo de generación: 14-11-2024 a las 22:04:54
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
  `idLibro` int(11) NOT NULL,
  `titulo` text NOT NULL,
  `autor` text NOT NULL,
  `f_publicacion` text NOT NULL,
  `ISBN` text NOT NULL,
  `n_paginas` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`idLibro`, `titulo`, `autor`, `f_publicacion`, `ISBN`, `n_paginas`) VALUES
(1, 'zkHwCu9D/LhrhAC793KmYfuIz4xAgymlAAV+pioThe8=', '3k4iQrdYqwA3g0Wmj4HQ2yWcTmzYUWcPy+a3a0LfoUQ=', 'b+gJIzB8jEKZM0hJA0Oj2g==', 'OFEYzuPORtiL+xC/wC904A==', 'tvGcYD5jOxfXpRRhAC3rpA=='),
(17, 'GEcdS5z4wUcbejyBRkLf+fkyZ1vQ2OaHeimCUpo4LPM=', 'z+tHEYM0kos/Er3uqyM3Yg==', '6C0SjLyK4RqEQ7I5jK/bgA==', 'QlIPb+ZuNvUTmngTx0vSYg==', 'aoCUfLITOXwHpo9KrPZc0g=='),
(18, '9Bc1Dfnc/YSY3hImUUzRJVa0jfoOlZL0dt71Re7ym7k=', 'iwdRdiJn/i6fSHxxBxmn0A==', 'Cgx+jOofXfPp+MP9giUtKw==', 'ciQhHbesfrVR5RaJnveOIw==', 'zzGKrYTtLGrs16QYuQz19Q=='),
(20, 'y1IWIn5BCQHMzQLKgjRCcegOC1jCl3nLGU01ZmjIx0t29VC+AfkjxPoPms0le6Ul', 'MJdzzW2tZIuwWeA5kd+Y5w==', 'rnSVmJJFkD3tj7WtlEbvZw==', '9rV2LU7XLlcACe6Nfgrevg==', 'XpmmGYu/Sk1PczBzOhPq6g==');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `numeroDNI` text NOT NULL,
  `letraDNI` text NOT NULL,
  `telefono` text NOT NULL,
  `nacimiento` text NOT NULL,
  `email` text NOT NULL,
  `usuario` text NOT NULL,
  `contrasena` text NOT NULL,
  `salt` text NOT NULL,
  `tipo` text DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `apellido`, `numeroDNI`, `letraDNI`, `telefono`, `nacimiento`, `email`, `usuario`, `contrasena`, `salt`, `tipo`) VALUES
(467913, 'M/CPPLjMNMhQapUzwt0/lA==', 'M/CPPLjMNMhQapUzwt0/lA==', 'tzVvh9ZzZK431yxqe/aEtA==', '/jeahZ6NB23r+b0sAiNyrw==', 'oBVSrYigpp6Ld96jt3K+XQ==', 'kjecf34FuTT0E7lldump7Q==', '3MPUsYiFc4y7a/VMAvxKPg==', 'M/CPPLjMNMhQapUzwt0/lA==', '3442ca9c2c37ae246209fbb7eb08dea4119298318b2633a6ff74c1c05fa8b3a4', '1fc8b42e1448273f2a5de0fb2dc24c4584354f32f55e55e622b1e424f3a6dddc6d5fec560353393eae1bd404c14b230c8ed7d6328be6dd2fc33ce29fc073bed4888074db983836cfdb247725daabec93044783c1ec50c84a416e6eab6d22d8f0f3f25064d9644d2e5885d7625322b446f4c5cb2700dd43ad9039c4fb623746e119574a04bdb8b806c18e31e62d1279aaa27ec8890d5f4523a50a9ca5a573f5bac26f8cb587bcddfae3fc9be214799b819428db54aa510dc9da70e19d8d41cb236d020b02cdaaba3e830504f37cf72f9a459d733e7f3fed5c3c7d3b4e270d257deffabe9dd3d2db7d80a2824e8d6487dde79788daa09bd3cf1caf4a41b5a4b9', 'admin');


ALTER TABLE `libro`
  ADD PRIMARY KEY (`idLibro`);


ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
