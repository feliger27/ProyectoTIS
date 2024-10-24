-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2024 a las 02:55:10
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
-- Base de datos: `hamburgeeks`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acompaniamiento`
--

CREATE TABLE `acompaniamiento` (
  `id_acompaniamiento` int(11) NOT NULL,
  `nombre_acompaniamiento` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `acompaniamiento`
--

INSERT INTO `acompaniamiento` (`id_acompaniamiento`, `nombre_acompaniamiento`, `cantidad`, `precio`) VALUES
(1, 'Nuggets', 70, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aderezo`
--

CREATE TABLE `aderezo` (
  `id_aderezo` int(11) NOT NULL,
  `nombre_aderezo` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aderezo`
--

INSERT INTO `aderezo` (`id_aderezo`, `nombre_aderezo`, `cantidad`) VALUES
(1, 'mayonesa', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bebida`
--

CREATE TABLE `bebida` (
  `id_bebida` int(11) NOT NULL,
  `nombre_bebida` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bebida`
--

INSERT INTO `bebida` (`id_bebida`, `nombre_bebida`, `cantidad`, `precio`) VALUES
(1, 'Fanta', 68, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleta`
--

CREATE TABLE `boleta` (
  `id_boleta` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_pago` int(11) DEFAULT NULL,
  `fecha_generacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo`
--

CREATE TABLE `combo` (
  `id_combo` int(11) NOT NULL,
  `nombre_combo` varchar(100) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_acompaniamiento`
--

CREATE TABLE `combo_acompaniamiento` (
  `id_combo` int(11) NOT NULL,
  `id_acompaniamiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_bebida`
--

CREATE TABLE `combo_bebida` (
  `id_combo` int(11) NOT NULL,
  `id_bebida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_hamburguesa`
--

CREATE TABLE `combo_hamburguesa` (
  `id_combo` int(11) NOT NULL,
  `id_hamburguesa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_postre`
--

CREATE TABLE `combo_postre` (
  `id_combo` int(11) NOT NULL,
  `id_postre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `codigo_postal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion_usuario`
--

CREATE TABLE `direccion_usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_direccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hamburguesa`
--

CREATE TABLE `hamburguesa` (
  `id_hamburguesa` int(11) NOT NULL,
  `nombre_hamburguesa` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hamburguesa`
--

INSERT INTO `hamburguesa` (`id_hamburguesa`, `nombre_hamburguesa`, `descripcion`, `precio`) VALUES
(1, 'hamburguesa hyper tocino', 'tocino doble', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hamburguesa_aderezo`
--

CREATE TABLE `hamburguesa_aderezo` (
  `id_hamburguesa` int(11) NOT NULL,
  `id_aderezo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hamburguesa_aderezo`
--

INSERT INTO `hamburguesa_aderezo` (`id_hamburguesa`, `id_aderezo`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hamburguesa_ingrediente`
--

CREATE TABLE `hamburguesa_ingrediente` (
  `id_hamburguesa` int(11) NOT NULL,
  `id_ingrediente` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hamburguesa_ingrediente`
--

INSERT INTO `hamburguesa_ingrediente` (`id_hamburguesa`, `id_ingrediente`, `cantidad`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingrediente`
--

CREATE TABLE `ingrediente` (
  `id_ingrediente` int(11) NOT NULL,
  `nombre_ingrediente` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingrediente`
--

INSERT INTO `ingrediente` (`id_ingrediente`, `nombre_ingrediente`, `cantidad`) VALUES
(1, 'pan', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `id_pago` int(11) NOT NULL,
  `tipo_tarjeta` enum('credito','debito') DEFAULT NULL,
  `numero_tarjeta` varchar(16) DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `cvv` varchar(4) DEFAULT NULL,
  `nombre_titular` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL,
  `mensaje` varchar(255) DEFAULT NULL,
  `fecha_envio` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_promocion` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `estado_pedido` enum('en_preparacion','en_reparto','entregado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_acompaniamiento`
--

CREATE TABLE `pedido_acompaniamiento` (
  `id_pedido` int(11) NOT NULL,
  `id_acompaniamiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_bebida`
--

CREATE TABLE `pedido_bebida` (
  `id_pedido` int(11) NOT NULL,
  `id_bebida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_combo`
--

CREATE TABLE `pedido_combo` (
  `id_pedido` int(11) NOT NULL,
  `id_combo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_hamburguesa`
--

CREATE TABLE `pedido_hamburguesa` (
  `id_pedido` int(11) NOT NULL,
  `id_hamburguesa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_postre`
--

CREATE TABLE `pedido_postre` (
  `id_pedido` int(11) NOT NULL,
  `id_postre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `nombre_permiso` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id_permiso`, `nombre_permiso`, `descripcion`) VALUES
(2, 'editar mantenedores', 'editar mantenedores y su gestion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postre`
--

CREATE TABLE `postre` (
  `id_postre` int(11) NOT NULL,
  `nombre_postre` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `postre`
--

INSERT INTO `postre` (`id_postre`, `nombre_postre`, `cantidad`, `precio`) VALUES
(1, 'Helado de fresa', 80, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `id_promocion` int(11) NOT NULL,
  `codigo_promocion` varchar(20) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `porcentaje_descuento` int(11) DEFAULT NULL,
  `condiciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recompensa`
--

CREATE TABLE `recompensa` (
  `id_recompensa` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `cantidad_recompensada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
(1, 'Admin', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id_rol`, `id_permiso`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id_sucursal` int(11) NOT NULL,
  `id_direccion` int(11) DEFAULT NULL,
  `nombre_sucursal` varchar(100) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `estado_sucursal` enum('abierto','cerrado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(30) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `contrasenia` varchar(255) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `fecha_registro` date DEFAULT curdate(),
  `puntos_recompensa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `correo_electronico`, `contrasenia`, `telefono`, `fecha_registro`, `puntos_recompensa`) VALUES
(1, 'javier', 'Chavez', 'xavito.lol.videos@gmail.com', '$2y$10$ZCbkeWUabBAdd1NzZzNqS.K2Fefz50Jevb4K7ACaVi2yzdbNLolRe', '975243342', '2024-10-10', NULL),
(2, 'javier', 'Chavez', 'xavito.lol.videos@gmail.com', '$2y$10$ePAJ6HxcHWr.B2Oth6rOWecoR59NlpMf.I9bax6snD6QSP4ejSWGK', '975243342', '2024-10-10', NULL),
(3, 'javier', 'Chavez', 'xavito.lol.videos@gmail.com', '$2y$10$f0nRcgxN7jcYRbvDnHasiuHcM7ErJT4B520JyTIUU9au/dt75WIsm', '975243342', '2024-10-10', NULL),
(4, 'javier', 'Chavez', 'xavito.lol.videos@gmail.com', '$2y$10$6u5sIdvbuWVYBEPxVnZ8juQ2KChkeg2hpY4WJ9Pikhy3h5p3kCZ4y', '975243342', '2024-10-10', NULL),
(5, 'javier', 'Chavez', 'xavito.lol.videos@gmail.com', '$2y$10$zpnAblug0nPxeMKdkC35fOQjPDYIo9x7QL00BxD/T4BwSeJZcAWC2', '975243342', '2024-10-10', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_metodo_pago`
--

CREATE TABLE `usuario_metodo_pago` (
  `id_usuario` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_notificacion`
--

CREATE TABLE `usuario_notificacion` (
  `id_usuario` int(11) NOT NULL,
  `id_notificacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`id_usuario`, `id_rol`) VALUES
(4, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoracion`
--

CREATE TABLE `valoracion` (
  `id_valoracion` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `cantidad_estrellas` int(11) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `fecha_valoracion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acompaniamiento`
--
ALTER TABLE `acompaniamiento`
  ADD PRIMARY KEY (`id_acompaniamiento`);

--
-- Indices de la tabla `aderezo`
--
ALTER TABLE `aderezo`
  ADD PRIMARY KEY (`id_aderezo`);

--
-- Indices de la tabla `bebida`
--
ALTER TABLE `bebida`
  ADD PRIMARY KEY (`id_bebida`);

--
-- Indices de la tabla `boleta`
--
ALTER TABLE `boleta`
  ADD PRIMARY KEY (`id_boleta`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_pago` (`id_pago`);

--
-- Indices de la tabla `combo`
--
ALTER TABLE `combo`
  ADD PRIMARY KEY (`id_combo`);

--
-- Indices de la tabla `combo_acompaniamiento`
--
ALTER TABLE `combo_acompaniamiento`
  ADD PRIMARY KEY (`id_combo`,`id_acompaniamiento`),
  ADD KEY `id_acompaniamiento` (`id_acompaniamiento`);

--
-- Indices de la tabla `combo_bebida`
--
ALTER TABLE `combo_bebida`
  ADD PRIMARY KEY (`id_combo`,`id_bebida`),
  ADD KEY `id_bebida` (`id_bebida`);

--
-- Indices de la tabla `combo_hamburguesa`
--
ALTER TABLE `combo_hamburguesa`
  ADD PRIMARY KEY (`id_combo`,`id_hamburguesa`),
  ADD KEY `id_hamburguesa` (`id_hamburguesa`);

--
-- Indices de la tabla `combo_postre`
--
ALTER TABLE `combo_postre`
  ADD PRIMARY KEY (`id_combo`,`id_postre`),
  ADD KEY `id_postre` (`id_postre`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`id_direccion`);

--
-- Indices de la tabla `direccion_usuario`
--
ALTER TABLE `direccion_usuario`
  ADD PRIMARY KEY (`id_usuario`,`id_direccion`),
  ADD KEY `id_direccion` (`id_direccion`);

--
-- Indices de la tabla `hamburguesa`
--
ALTER TABLE `hamburguesa`
  ADD PRIMARY KEY (`id_hamburguesa`);

--
-- Indices de la tabla `hamburguesa_aderezo`
--
ALTER TABLE `hamburguesa_aderezo`
  ADD PRIMARY KEY (`id_hamburguesa`,`id_aderezo`),
  ADD KEY `id_aderezo` (`id_aderezo`);

--
-- Indices de la tabla `hamburguesa_ingrediente`
--
ALTER TABLE `hamburguesa_ingrediente`
  ADD PRIMARY KEY (`id_hamburguesa`,`id_ingrediente`),
  ADD KEY `id_ingrediente` (`id_ingrediente`);

--
-- Indices de la tabla `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`id_ingrediente`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id_notificacion`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_promocion` (`id_promocion`);

--
-- Indices de la tabla `pedido_acompaniamiento`
--
ALTER TABLE `pedido_acompaniamiento`
  ADD PRIMARY KEY (`id_pedido`,`id_acompaniamiento`),
  ADD KEY `id_acompaniamiento` (`id_acompaniamiento`);

--
-- Indices de la tabla `pedido_bebida`
--
ALTER TABLE `pedido_bebida`
  ADD PRIMARY KEY (`id_pedido`,`id_bebida`),
  ADD KEY `id_bebida` (`id_bebida`);

--
-- Indices de la tabla `pedido_combo`
--
ALTER TABLE `pedido_combo`
  ADD PRIMARY KEY (`id_pedido`,`id_combo`),
  ADD KEY `id_combo` (`id_combo`);

--
-- Indices de la tabla `pedido_hamburguesa`
--
ALTER TABLE `pedido_hamburguesa`
  ADD PRIMARY KEY (`id_pedido`,`id_hamburguesa`),
  ADD KEY `id_hamburguesa` (`id_hamburguesa`);

--
-- Indices de la tabla `pedido_postre`
--
ALTER TABLE `pedido_postre`
  ADD PRIMARY KEY (`id_pedido`,`id_postre`),
  ADD KEY `id_postre` (`id_postre`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `postre`
--
ALTER TABLE `postre`
  ADD PRIMARY KEY (`id_postre`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`id_promocion`);

--
-- Indices de la tabla `recompensa`
--
ALTER TABLE `recompensa`
  ADD PRIMARY KEY (`id_recompensa`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `id_permiso` (`id_permiso`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id_sucursal`),
  ADD KEY `id_direccion` (`id_direccion`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `usuario_metodo_pago`
--
ALTER TABLE `usuario_metodo_pago`
  ADD PRIMARY KEY (`id_usuario`,`id_pago`),
  ADD KEY `id_pago` (`id_pago`);

--
-- Indices de la tabla `usuario_notificacion`
--
ALTER TABLE `usuario_notificacion`
  ADD PRIMARY KEY (`id_usuario`,`id_notificacion`),
  ADD KEY `id_notificacion` (`id_notificacion`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `valoracion`
--
ALTER TABLE `valoracion`
  ADD PRIMARY KEY (`id_valoracion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acompaniamiento`
--
ALTER TABLE `acompaniamiento`
  MODIFY `id_acompaniamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `aderezo`
--
ALTER TABLE `aderezo`
  MODIFY `id_aderezo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `bebida`
--
ALTER TABLE `bebida`
  MODIFY `id_bebida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `boleta`
--
ALTER TABLE `boleta`
  MODIFY `id_boleta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `combo`
--
ALTER TABLE `combo`
  MODIFY `id_combo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hamburguesa`
--
ALTER TABLE `hamburguesa`
  MODIFY `id_hamburguesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ingrediente`
--
ALTER TABLE `ingrediente`
  MODIFY `id_ingrediente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `postre`
--
ALTER TABLE `postre`
  MODIFY `id_postre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `id_promocion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recompensa`
--
ALTER TABLE `recompensa`
  MODIFY `id_recompensa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `valoracion`
--
ALTER TABLE `valoracion`
  MODIFY `id_valoracion` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boleta`
--
ALTER TABLE `boleta`
  ADD CONSTRAINT `boleta_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `boleta_ibfk_2` FOREIGN KEY (`id_pago`) REFERENCES `metodo_pago` (`id_pago`);

--
-- Filtros para la tabla `combo_acompaniamiento`
--
ALTER TABLE `combo_acompaniamiento`
  ADD CONSTRAINT `combo_acompaniamiento_ibfk_1` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`),
  ADD CONSTRAINT `combo_acompaniamiento_ibfk_2` FOREIGN KEY (`id_acompaniamiento`) REFERENCES `acompaniamiento` (`id_acompaniamiento`);

--
-- Filtros para la tabla `combo_bebida`
--
ALTER TABLE `combo_bebida`
  ADD CONSTRAINT `combo_bebida_ibfk_1` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`),
  ADD CONSTRAINT `combo_bebida_ibfk_2` FOREIGN KEY (`id_bebida`) REFERENCES `bebida` (`id_bebida`);

--
-- Filtros para la tabla `combo_hamburguesa`
--
ALTER TABLE `combo_hamburguesa`
  ADD CONSTRAINT `combo_hamburguesa_ibfk_1` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`),
  ADD CONSTRAINT `combo_hamburguesa_ibfk_2` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesa` (`id_hamburguesa`);

--
-- Filtros para la tabla `combo_postre`
--
ALTER TABLE `combo_postre`
  ADD CONSTRAINT `combo_postre_ibfk_1` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`),
  ADD CONSTRAINT `combo_postre_ibfk_2` FOREIGN KEY (`id_postre`) REFERENCES `postre` (`id_postre`);

--
-- Filtros para la tabla `direccion_usuario`
--
ALTER TABLE `direccion_usuario`
  ADD CONSTRAINT `direccion_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `direccion_usuario_ibfk_2` FOREIGN KEY (`id_direccion`) REFERENCES `direccion` (`id_direccion`);

--
-- Filtros para la tabla `hamburguesa_aderezo`
--
ALTER TABLE `hamburguesa_aderezo`
  ADD CONSTRAINT `hamburguesa_aderezo_ibfk_1` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesa` (`id_hamburguesa`),
  ADD CONSTRAINT `hamburguesa_aderezo_ibfk_2` FOREIGN KEY (`id_aderezo`) REFERENCES `aderezo` (`id_aderezo`);

--
-- Filtros para la tabla `hamburguesa_ingrediente`
--
ALTER TABLE `hamburguesa_ingrediente`
  ADD CONSTRAINT `hamburguesa_ingrediente_ibfk_1` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesa` (`id_hamburguesa`),
  ADD CONSTRAINT `hamburguesa_ingrediente_ibfk_2` FOREIGN KEY (`id_ingrediente`) REFERENCES `ingrediente` (`id_ingrediente`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_promocion`) REFERENCES `promocion` (`id_promocion`);

--
-- Filtros para la tabla `pedido_acompaniamiento`
--
ALTER TABLE `pedido_acompaniamiento`
  ADD CONSTRAINT `pedido_acompaniamiento_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_acompaniamiento_ibfk_2` FOREIGN KEY (`id_acompaniamiento`) REFERENCES `acompaniamiento` (`id_acompaniamiento`);

--
-- Filtros para la tabla `pedido_bebida`
--
ALTER TABLE `pedido_bebida`
  ADD CONSTRAINT `pedido_bebida_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_bebida_ibfk_2` FOREIGN KEY (`id_bebida`) REFERENCES `bebida` (`id_bebida`);

--
-- Filtros para la tabla `pedido_combo`
--
ALTER TABLE `pedido_combo`
  ADD CONSTRAINT `pedido_combo_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_combo_ibfk_2` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`);

--
-- Filtros para la tabla `pedido_hamburguesa`
--
ALTER TABLE `pedido_hamburguesa`
  ADD CONSTRAINT `pedido_hamburguesa_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_hamburguesa_ibfk_2` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesa` (`id_hamburguesa`);

--
-- Filtros para la tabla `pedido_postre`
--
ALTER TABLE `pedido_postre`
  ADD CONSTRAINT `pedido_postre_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_postre_ibfk_2` FOREIGN KEY (`id_postre`) REFERENCES `postre` (`id_postre`);

--
-- Filtros para la tabla `recompensa`
--
ALTER TABLE `recompensa`
  ADD CONSTRAINT `recompensa_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `recompensa_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE,
  ADD CONSTRAINT `rol_permiso_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id_permiso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD CONSTRAINT `sucursal_ibfk_1` FOREIGN KEY (`id_direccion`) REFERENCES `direccion` (`id_direccion`);

--
-- Filtros para la tabla `usuario_metodo_pago`
--
ALTER TABLE `usuario_metodo_pago`
  ADD CONSTRAINT `usuario_metodo_pago_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `usuario_metodo_pago_ibfk_2` FOREIGN KEY (`id_pago`) REFERENCES `metodo_pago` (`id_pago`);

--
-- Filtros para la tabla `usuario_notificacion`
--
ALTER TABLE `usuario_notificacion`
  ADD CONSTRAINT `usuario_notificacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `usuario_notificacion_ibfk_2` FOREIGN KEY (`id_notificacion`) REFERENCES `notificacion` (`id_notificacion`);

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `usuario_rol_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_rol_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE;

--
-- Filtros para la tabla `valoracion`
--
ALTER TABLE `valoracion`
  ADD CONSTRAINT `valoracion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `valoracion_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
