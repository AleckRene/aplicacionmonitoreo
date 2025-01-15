-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-01-2025 a las 20:48:58
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
-- Base de datos: `aplicacionmonitoreo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesibilidad_calidad`
--

CREATE TABLE `accesibilidad_calidad` (
  `id` int(10) NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `accesibilidad_servicios` enum('Nada accesibles','Poco accesibles','Moderadamente accesibles','Accesibles','Muy accesibles') NOT NULL,
  `actitud_personal` enum('Muy inapropiada','Inapropiada','Neutral','Apropiada','Muy apropiada') NOT NULL,
  `tarifas_ocultas` enum('Nunca','Raramente','Ocasionalmente','Frecuentemente','Siempre') NOT NULL,
  `factores_mejora` text DEFAULT NULL,
  `disponibilidad_herramientas` enum('Muy deficiente','Deficiente','Regular','Buena','Excelente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `accesibilidad_calidad`
--

INSERT INTO `accesibilidad_calidad` (`id`, `usuario_id`, `accesibilidad_servicios`, `actitud_personal`, `tarifas_ocultas`, `factores_mejora`, `disponibilidad_herramientas`) VALUES
(0, 1, 'Nada accesibles', 'Muy inapropiada', 'Ocasionalmente', 'SADFAF', 'Muy deficiente'),
(7, 1, 'Nada accesibles', 'Inapropiada', 'Raramente', 'asdfsfasfsdaf', 'Muy deficiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos_salud`
--

CREATE TABLE `eventos_salud` (
  `id` int(11) NOT NULL,
  `nombre_evento` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` date NOT NULL,
  `acciones` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos_salud`
--

INSERT INTO `eventos_salud` (`id`, `nombre_evento`, `descripcion`, `fecha`, `acciones`, `created_at`) VALUES
(1, 'Vacunación comunitaria', 'cfdfbvdx', '2024-10-02', 'zxvxcvzx', '2024-12-27 01:54:00'),
(2, 'sdafsads', 'xzxc', '2024-12-30', 'zccxcz', '2025-01-04 02:00:25'),
(3, 'dafaf', 'zcvczvzx', '2024-12-31', 'zcxvzvzx', '2025-01-04 06:20:59'),
(4, 'afsdsd', 'adfasf', '2024-12-31', 'safafd', '2025-01-04 06:28:37'),
(5, 'afsdsd', 'adfasf', '2024-12-31', 'safafd', '2025-01-04 06:28:39'),
(6, 'afsdsd', 'adfasf', '2024-12-31', 'safafd', '2025-01-04 06:28:40'),
(7, 'afsdsd', 'adfasf', '2024-12-31', 'safafd', '2025-01-04 06:28:41'),
(8, 'afsdsd', 'adfasf', '2024-12-31', 'safafd', '2025-01-04 06:28:42'),
(9, 'sdafsads', 'sdf', '2024-12-31', 'adfaf', '2025-01-04 06:29:56'),
(10, 'sdafsads', 'sdf', '2024-12-31', 'adfaf', '2025-01-04 06:38:08'),
(11, 'sdafsads', 'sdf', '2024-12-31', 'adfaf', '2025-01-04 06:38:09'),
(12, 'sdafsads', 'sdf', '2024-12-31', 'adfaf', '2025-01-04 06:38:10'),
(13, 'sdafsads', 'sdf', '2024-12-31', 'adfaf', '2025-01-04 06:38:11'),
(14, 'sdafsads', 'ZX', '2024-12-30', 'ZCC', '2025-01-04 06:46:44'),
(15, 'sdafsads', 'afagah', '2024-12-30', 'asdfaf', '2025-01-04 14:54:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `indicadores_uso`
--

CREATE TABLE `indicadores_uso` (
  `id` int(11) NOT NULL,
  `numero_usuarios` int(11) NOT NULL,
  `nivel_actividad` int(11) NOT NULL,
  `frecuencia_recomendaciones` int(11) NOT NULL,
  `calidad_uso` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `indicadores_uso`
--

INSERT INTO `indicadores_uso` (`id`, `numero_usuarios`, `nivel_actividad`, `frecuencia_recomendaciones`, `calidad_uso`, `created_at`) VALUES
(1, 2, 1, 1, 1, '2024-12-27 01:52:59'),
(2, 2, 2, 3, 2, '2025-01-04 02:23:24'),
(3, 2, 2, 3, 2, '2025-01-04 02:31:38'),
(4, 1, 1, 1, 1, '2025-01-04 02:32:41'),
(5, 2, 1, 1, 2, '2025-01-04 02:43:11'),
(6, 2, 2, 1, 1, '2025-01-04 02:45:19'),
(7, 2, 1, 3, 2, '2025-01-04 02:49:56'),
(8, 3, 2, 1, 2, '2025-01-04 02:52:33'),
(9, 4, 2, 2, 3, '2025-01-04 02:53:35'),
(10, 3, 1, 2, 1, '2025-01-04 03:17:21'),
(11, 6, 2, 2, 2, '2025-01-04 03:28:19'),
(12, 4, 2, 2, 2, '2025-01-04 06:20:10'),
(13, 5, 3, 1, 1, '2025-01-04 06:28:58'),
(14, 5, 3, 1, 1, '2025-01-04 06:29:01'),
(15, 5, 3, 1, 1, '2025-01-04 06:29:19'),
(16, 4, 2, 2, 1, '2025-01-04 06:29:30'),
(17, 4, 2, 3, 4, '2025-01-04 06:41:38'),
(18, 4, 2, 2, 2, '2025-01-04 08:50:33'),
(19, 1, 1, 4, 2, '2025-01-04 14:53:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `necesidades_comunitarias`
--

CREATE TABLE `necesidades_comunitarias` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `acciones` text NOT NULL,
  `area_prioritaria` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `necesidades_comunitarias`
--

INSERT INTO `necesidades_comunitarias` (`id`, `descripcion`, `acciones`, `area_prioritaria`, `created_at`) VALUES
(1, 'sdczxc', 'Xzcxzc', 'ZXcZC', '2024-12-27 01:54:29'),
(2, 'zzacZ', 'ZcxzC', 'ZXCZC', '2025-01-04 02:00:49'),
(3, 'safsfd', 'asfasd', 'asf', '2025-01-04 02:09:42'),
(4, 'zcXxz', 'CxZC', 'zxc', '2025-01-04 04:04:42'),
(5, 'safdf', 'adafd', 'asf', '2025-01-04 07:10:05'),
(6, 'adfadfdsf', 'adfadfaf', 'zxc', '2025-01-04 14:55:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participacion_comunitaria`
--

CREATE TABLE `participacion_comunitaria` (
  `id` int(11) NOT NULL,
  `nivel_participacion` int(11) NOT NULL,
  `grupos_comprometidos` text NOT NULL,
  `estrategias_mejora` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participacion_comunitaria`
--

INSERT INTO `participacion_comunitaria` (`id`, `nivel_participacion`, `grupos_comprometidos`, `estrategias_mejora`, `created_at`) VALUES
(1, 1, 'dsgdfg', 'zfzxbx', '2024-12-27 01:53:32'),
(2, 1, 'dassdf', 'asfdsfa', '2025-01-04 01:59:49'),
(3, 1, 'ssdfa', 'zxvf', '2025-01-04 02:06:11'),
(4, 1, 'ssdfa', 'zxvf', '2025-01-04 02:07:19'),
(5, 1, 'adsfaf', 'asdfadf', '2025-01-04 03:33:27'),
(6, 1, 'adsfaf', 'asdfadf', '2025-01-04 03:35:09'),
(7, 2, 'fdssafd', 'asdfasf', '2025-01-04 03:35:21'),
(8, 2, 'fdssafd', 'asdfasf', '2025-01-04 03:43:12'),
(9, 1, 'sdafa', 'vzvz', '2025-01-04 06:20:34'),
(10, 1, 'dfasf', 'dfasfaf', '2025-01-04 14:54:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `percepcion_servicios`
--

CREATE TABLE `percepcion_servicios` (
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `calidad_servicio` enum('Muy mala','Mala','Regular','Buena','Muy buena') NOT NULL,
  `servicios_mejorar` text DEFAULT NULL,
  `cambios_recientes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `percepcion_servicios`
--

INSERT INTO `percepcion_servicios` (`usuario_id`, `calidad_servicio`, `servicios_mejorar`, `cambios_recientes`) VALUES
(1, 'Muy mala', 'fdsfds', 'sdafdfa'),
(1, 'Mala', 'afsfs', 'asfdasf'),
(1, 'Mala', 'dfasdf', 'asdfafa'),
(1, 'Muy mala', 'czxcx', 'zcZXcxz'),
(1, 'Mala', 'FDSFA', 'SFDAF');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roleID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `name`, `email`, `password`, `roleID`, `created_at`) VALUES
(1, 'Aleck Rene Perez Zeledon', 'aleckpz@yahoo.es', '$2y$10$XX0vP5bSizP0mSBeOYUhQuj3Vj8IuPxy9AR5iyb2GztkDK8GGVZvi', 5907, '2024-12-26 22:26:55'),
(3, 'Mario', 'aljheri87387725@gmail.com', '$2y$10$kN5vmCB9s/QEHLpPXxNssugOAv.P4hxqMBAMEc8wMiBGfyRjEWP0i', 2125, '2025-01-02 07:44:30'),
(4, 'Camilo', 'cal87@gmail.com', '$2y$10$i43LBe9QQLjjSpwiz8wjxu2hpcUeYHw6JNuuazknYhVu3KwEVpe42', 3571, '2025-01-03 23:58:00'),
(5, 'Javier', 'jls@gmail.com', '$2y$10$4hI8aaXY.9L9TnQz0WcYXueCjkgsLdfZexsiWymFSmvykISVeiZkS', 6876, '2025-01-04 00:10:58'),
(6, 'Marcos', 'Mlak@gmail.com', '$2y$10$E01uvSSoRVxlbgQvTpy3xe0IM8RCZZ30VxzkD/66WM1i68xMAgoDy', 9444, '2025-01-04 00:18:56'),
(7, 'Mario', 'Mzp@gmail.com', '$2y$10$ocHBIursiMV/03UPTXU.cu7OYMqdsyeiLceIxS4/1JCMvsjT/ELw.', 1633, '2025-01-04 01:54:49'),
(8, 'Alan', 'alan@gmail.com', '$2y$10$dtXlzjugB77xRrTrDvyvhePbrqoTf5Zw4H/3rZq.IMoTeOlCORLHO', 3287, '2025-01-04 06:19:38'),
(9, 'Anabel Ruiz', 'anaru87@hotmail.com', '$2y$10$RhnVItpMzoXS3V94rL3kXOfFc24d/fu/9rZsaWd2xmn.j8pEI2mKK', 1587, '2025-01-04 09:09:10'),
(10, 'María Isabel', 'marisa@hotmail.com', '$2y$10$eqU42ztM4HU3BLIceukHvOVzOfyX.IiV5g/fKSsbCAwmoD/AE4g5e', 2042, '2025-01-04 10:29:03'),
(11, 'Karla Fonseca', 'Karla8796@gmail.com', '$2y$10$f8eeS3/.X/A6NsubYW7IX.5wdh6RNmvg5p4/VIi3QVDH/EvOf2rm.', 4735, '2025-01-04 13:11:35'),
(12, 'kamila', 'kami25@yahoo.es', '$2y$10$EaCGvSdvD9y6tEH5oLQJquNVHmiHc35zZ94PAgYt/wQg4km414Fhy', 6066, '2025-01-04 13:48:46'),
(13, 'Ariel López', 'alop89@gmail.com', '$2y$10$4QLZNf7soWCMjleuDav7AObXVvUirDHWk1HoelPXwEKGYqh5H0VhW', 857, '2025-01-04 14:46:08');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesibilidad_calidad`
--
ALTER TABLE `accesibilidad_calidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `eventos_salud`
--
ALTER TABLE `eventos_salud`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `indicadores_uso`
--
ALTER TABLE `indicadores_uso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `necesidades_comunitarias`
--
ALTER TABLE `necesidades_comunitarias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `participacion_comunitaria`
--
ALTER TABLE `participacion_comunitaria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `percepcion_servicios`
--
ALTER TABLE `percepcion_servicios`
  ADD KEY `percepcion_servicios_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `eventos_salud`
--
ALTER TABLE `eventos_salud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `indicadores_uso`
--
ALTER TABLE `indicadores_uso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `necesidades_comunitarias`
--
ALTER TABLE `necesidades_comunitarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `participacion_comunitaria`
--
ALTER TABLE `participacion_comunitaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
