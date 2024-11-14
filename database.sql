-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Oct 29, 2024 at 02:47 PM
-- Server version: 10.8.2-MariaDB-1:10.8.2+maria~focal
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `libro`
--

CREATE TABLE `libro` (
  `idLibro` int(11) NOT NULL,
  `titulo` varchar(40) NOT NULL,
  `autor` varchar(20) NOT NULL,
  `f_publicacion` varchar(10) NOT NULL,
  `ISBN` char(13) NOT NULL,
  `n_paginas` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `libro`
--

INSERT INTO `libro` (`idLibro`, `titulo`, `autor`, `f_publicacion`, `ISBN`, `n_paginas`) VALUES
(1, 'Nacidos de la Bruma', 'Brandon Sanderson', '2014-09-01', '9788419260246', '500'),
(17, 'Los Juegos del Hambre', 'Suzanne Collins', '2008-09-14', '9788413144856', '400'),
(18, 'El Asesinato de Roger Ackroyd', 'Agatha Christie', '1926-06-12', '9788467052978', '272'),
(20, 'Harry Potter y la piedra filosofal', 'J K Rowling', '1997-06-26', '9788478884452', '264');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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



DELIMITER //

CREATE TRIGGER before_insert_usuarios
BEFORE INSERT ON usuarios
FOR EACH ROW
BEGIN
    DECLARE nuevo_id INT;
    DECLARE id_unico BOOLEAN DEFAULT FALSE;

    -- Generar un ID aleatorio hasta encontrar uno que no esté en uso
    WHILE NOT id_unico DO
        SET nuevo_id = FLOOR(100000 + RAND() * 899999); -- Cambia el rango según tu preferencia

        -- Verificar si el ID ya existe en la tabla usuarios
        IF (SELECT COUNT(*) FROM usuarios WHERE idUsuario = nuevo_id) = 0 THEN
            SET id_unico = TRUE;
        END IF;
    END WHILE;

    SET NEW.idUsuario = nuevo_id;
END //

DELIMITER ;



--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `apellido`, `numeroDNI`, `letraDNI`, `telefono`, `nacimiento`, `email`, `usuario`, `contrasena`, `salt`, `tipo`) VALUES
(5, 'Gonzalo', 'Perez', '50634325', 'D', '688889338', '2003-11-30', 'gonzaloperezgo@gmail.com', 'GPerez', '6b2ad1ba6fb9cfb950848fa91f207b294d8f8ea99946f9a3e459dc61803eace3', 'f2fffe6378fdde57946ef500be0a5563a7c0146d4323da7f651c63a21d8b98007a8aa7f40ccd9b4ee4d8065ee84fec3cea6847000f0484d67c92208ef6e585255094c0f4dcc49a0cf27f29c7a94a6ad4f579ceb1ec7c5c337a3c72b7ff69821676f4fb01eaeb3ef1939cc8b2a5abdc73d082501d9afd476887d31da5387a5cbda60b32f797b56893d52164271d0e338c7ac2c161c61ba4b24e32d8d3df5092b653fee1ce16df0ac9bda90a37ec4e6ab02488f57f814a19bf3d45a59d8c815e1482b3b55a377f7ec8c619d8435e23d472cf45223e6da2cc05781fd7cb5598a00174fafb82ea12be1c30006754b8ce36536b394798dab037d00a936abd94a569', 'user'),
(6, 'admin', 'admin', '12345678', 'Z', '555666777', '2000-01-01', 'correo@gmail.com', 'admin', '96a04458c7150c028219e1a82d47b777463f8beefd2abd535c914087784ec718', '3577d55152b6fb04ea21433c36822871c93bbdd3a75680e1db43bb1926b27bb178c479a5aba30a92fa1ea1fabb13f170157c7d023ee0d6849ad6fb236a1dc8b9cf6086fb4d5238b1138943d8a76cbcc47e0aa999c8ba7da954094de08319ee3a19da4ed2f011e0f3bd2891050853b19bde4ee6f8b705d408f4c52eb995e2fcbf88ebb0276fac25bdc5c97ab1618557d033d440c29d91a47dd23151760dd748ee705648a67f504cab29b7861909b016d53d71b06d01e9afb959413f0bac59c523e6da5cc5d605a7f3cb8fcc631e99550ffef52f2e2bb4824924a773bedd8e995708bd69ae622c2c581b56ffef8b17c5d632af96b26b88e8df60719ee64f00b1', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`idLibro`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `libro`
--
ALTER TABLE `libro`
  MODIFY `idLibro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
