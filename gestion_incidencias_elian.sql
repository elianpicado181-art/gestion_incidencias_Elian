-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-10-2026 a las 05:29:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_incidencias_elian`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id` int(11) NOT NULL,
  `nombre_reporta` varchar(100) NOT NULL,
  `laboratorio` varchar(100) NOT NULL,
  `equipo_afectado` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('Baja','Media','Alta','Crítica') NOT NULL DEFAULT 'Media',
  `estado` enum('Pendiente','En proceso','Resuelta') NOT NULL DEFAULT 'Pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `incidencias`
--

INSERT INTO `incidencias` (`id`, `nombre_reporta`, `laboratorio`, `equipo_afectado`, `descripcion`, `prioridad`, `estado`, `fecha_registro`) VALUES
(3, 'Juan Perez', 'Laboratorio 2', 'teclado', 'Fallan teclas al utilizarlas', 'Crítica', 'En proceso', '2026-10-13 03:02:01'),
(4, 'Elian Picado Fonseca', 'Laboratorio 1', 'Proyector', 'No enciende', 'Media', 'Pendiente', '2026-10-13 03:35:33'),
(6, 'Carol Fernandez', 'Laboratorio 4', 'Teclado de laptop', 'No le funcionan varias teclas', 'Alta', 'Resuelta', '2026-10-13 03:52:12'),
(7, 'Brayan Valverde', 'Laboratorio 6', 'Computadora 8', 'No carga y se apaga al quitar el cargador', 'Baja', 'Pendiente', '2026-10-13 04:01:18'),
(9, 'Pancho Salazar', 'Laboratorio 1', 'computadora 07', 'No enciende', 'Alta', 'Pendiente', '2026-10-13 05:41:57'),
(10, 'Carlos Bolaños', 'Laboratorio 3', 'Teclado de computadora', 'No sirven algunas teclas', 'Baja', 'En proceso', '2026-10-13 05:54:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `fecha_creacion`) VALUES
(1, 'admin', '$2y$10$oOkDml1N..M2avG9h20Va.p5x3CMQCVRnmDFmuudj6JHnCSZzDOlK', '2026-10-13 05:22:24');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
