-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2024 a las 17:44:04
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
  `precio` int(11) DEFAULT NULL,
  `umbral_reabastecimiento` int(11) NOT NULL DEFAULT 5,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `acompaniamiento`
--

INSERT INTO `acompaniamiento` (`id_acompaniamiento`, `nombre_acompaniamiento`, `cantidad`, `precio`, `umbral_reabastecimiento`, `imagen`) VALUES
(1, 'Papas Fritas', 100, 1990, 5, 'papas_fritas.jpg'),
(2, 'Aros de Cebolla', 100, 2490, 5, 'aros_cebolla.jpg'),
(4, 'Nuggets de Pollo', 100, 1490, 5, 'nuggets_pollo.jpg'),
(5, 'Palitos de Ajo', 100, 1990, 5, 'palitos_ajo.jpg'),
(6, 'Alitas de Pollo', 100, 2990, 5, 'alitas_pollo.jpg'),
(7, 'Emapanaditas de Queso', 100, 2490, 5, 'empanadas_queso.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aderezo`
--

CREATE TABLE `aderezo` (
  `id_aderezo` int(11) NOT NULL,
  `nombre_aderezo` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `umbral_reabastecimiento` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aderezo`
--

INSERT INTO `aderezo` (`id_aderezo`, `nombre_aderezo`, `cantidad`, `umbral_reabastecimiento`) VALUES
(1, 'Salsa BBQ', 100, 5),
(2, 'Mayonesa Casera', 100, 5),
(3, 'Ketchup', 100, 5),
(4, 'Mostaza Dijon', 100, 5),
(5, 'Salsa Picante', 100, 5),
(6, 'Salsa Teriyaki', 100, 5),
(7, 'Mayonesa de Ajo', 100, 5),
(8, 'Pesto', 100, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bebida`
--

CREATE TABLE `bebida` (
  `id_bebida` int(11) NOT NULL,
  `nombre_bebida` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `umbral_reabastecimiento` int(11) NOT NULL DEFAULT 5,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bebida`
--

INSERT INTO `bebida` (`id_bebida`, `nombre_bebida`, `cantidad`, `precio`, `umbral_reabastecimiento`, `imagen`) VALUES
(1, 'Coca Cola', 100, 1990, 5, 'coca_cola.jpg'),
(2, 'Fanta', 100, 1990, 5, 'fanta.jpg'),
(3, 'Sprite', 100, 1990, 5, 'sprite.jpg'),
(4, 'Pepsi', 100, 1990, 5, 'pepsi.jpg'),
(5, 'Red Bull', 100, 2490, 5, 'red_bull.jpg'),
(6, 'Monster', 100, 2490, 5, 'monster.jpg'),
(7, 'Jugo de Naranja', 100, 1490, 5, 'jugo_naranja.jpg'),
(8, 'Jugo de Frutilla', 100, 1490, 5, 'jugo_frutilla.jpg'),
(9, 'Agua Gasificada', 100, 990, 5, 'agua_gasificada.jpg');

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
  `precio` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combo`
--

INSERT INTO `combo` (`id_combo`, `nombre_combo`, `descripcion`, `precio`, `imagen`) VALUES
(10, 'Combo Clásico', 'Un combo tradicional con una deliciosa hamburguesa clásica, acompañada de papas fritas y una bebida refrescante.', 7500, NULL),
(11, 'Combo Deluxe', 'Disfruta de una experiencia completa con una hamburguesa BBQ, aros de cebolla y una Fanta.', 8000, NULL),
(12, 'Combo Familiar', 'Perfecto para compartir, incluye varias hamburguesas y acompañamientos.', 25000, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_acompaniamiento`
--

CREATE TABLE `combo_acompaniamiento` (
  `id_combo` int(11) NOT NULL,
  `id_acompaniamiento` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combo_acompaniamiento`
--

INSERT INTO `combo_acompaniamiento` (`id_combo`, `id_acompaniamiento`, `cantidad`) VALUES
(10, 1, 1),
(11, 2, 1),
(12, 1, 2),
(12, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_bebida`
--

CREATE TABLE `combo_bebida` (
  `id_combo` int(11) NOT NULL,
  `id_bebida` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combo_bebida`
--

INSERT INTO `combo_bebida` (`id_combo`, `id_bebida`, `cantidad`) VALUES
(10, 1, 1),
(11, 2, 1),
(12, 1, 2),
(12, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_hamburguesa`
--

CREATE TABLE `combo_hamburguesa` (
  `id_combo` int(11) NOT NULL,
  `id_hamburguesa` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combo_hamburguesa`
--

INSERT INTO `combo_hamburguesa` (`id_combo`, `id_hamburguesa`, `cantidad`) VALUES
(10, 1, 1),
(11, 2, 1),
(12, 1, 2),
(12, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_postre`
--

CREATE TABLE `combo_postre` (
  `id_combo` int(11) NOT NULL,
  `id_postre` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`id_direccion`, `calle`, `numero`, `ciudad`) VALUES
(5, 'Rodrigo de Quiroga', 306, 'Cañete'),
(6, 'Janequeo', 874, 'Concepción'),
(7, 'adsfasdf', NULL, 'asdfasdf'),
(9, 'pasaje ocho', 1115, 'san pedro de la paz'),
(10, 'pasaje ocho', NULL, 'Concepción'),
(11, 'pasaje ocho', NULL, 'Concepción');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion_usuario`
--

CREATE TABLE `direccion_usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_direccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion_usuario`
--

INSERT INTO `direccion_usuario` (`id_usuario`, `id_direccion`) VALUES
(11, 9),
(12, 5),
(12, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hamburguesa`
--

CREATE TABLE `hamburguesa` (
  `id_hamburguesa` int(11) NOT NULL,
  `nombre_hamburguesa` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hamburguesa`
--

INSERT INTO `hamburguesa` (`id_hamburguesa`, `nombre_hamburguesa`, `descripcion`, `precio`, `imagen`) VALUES
(1, 'Hamburguesa Clásica', 'Carne de res, lechuga, tomate y queso cheddar.', 4990, 'clasica.jpg'),
(2, 'Hamburguesa BBQ Bacon', 'Carne de res, queso cheddar, tocino y salsa BBQ.', 5990, 'bbq_bacon.jpg'),
(4, 'Hamburguesa Hawaiana', 'Carne de res, queso suizo, piña a la parrilla, jamón, y salsa de teriyaki.', 5990, 'hawaiana.jpg'),
(5, 'Hamburguesa Vegetariana', 'Hamburguesa de garbanzos, queso de cabra, aguacate, tomate, y mayonesa de ajo.', 5490, 'vegetariana.jpg'),
(6, 'Hamburguesa con Champiñones', 'Carne de res, queso suizo, champiñones salteados, cebolla caramelizada, y mayonesa de ajo.', 5990, 'champiniones.jpg'),
(7, 'Hamburguesa de Pollo BBQ', 'Pechuga de pollo a la parrilla, queso cheddar, lechuga, tomate, y salsa BBQ.', 5990, 'pollo_bbq.jpg'),
(8, 'Hamburguesa Italiana', 'Carne de res, mozzarella, tomate, albahaca fresca, y pesto.', 6490, 'italiana.jpg');

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
(2, 1),
(4, 6),
(5, 7),
(6, 7),
(7, 1),
(8, 8);

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
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(1, 4, 1),
(1, 5, 1),
(2, 1, 1),
(2, 2, 1),
(2, 3, 1),
(2, 6, 1),
(4, 1, 1),
(4, 2, 1),
(4, 10, 1),
(4, 11, 1),
(5, 1, 1),
(5, 5, 1),
(5, 13, 1),
(5, 14, 1),
(5, 15, 1),
(6, 1, 1),
(6, 2, 1),
(6, 8, 1),
(6, 9, 1),
(6, 10, 1),
(7, 1, 1),
(7, 3, 1),
(7, 4, 1),
(7, 5, 1),
(7, 16, 1),
(8, 1, 1),
(8, 2, 1),
(8, 17, 1),
(8, 18, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingrediente`
--

CREATE TABLE `ingrediente` (
  `id_ingrediente` int(11) NOT NULL,
  `nombre_ingrediente` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `umbral_reabastecimiento` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingrediente`
--

INSERT INTO `ingrediente` (`id_ingrediente`, `nombre_ingrediente`, `cantidad`, `umbral_reabastecimiento`) VALUES
(1, 'Pan Brioche', 100, 2),
(2, 'Carne de Res', 100, 5),
(3, 'Queso Cheddar', 100, 5),
(4, 'Lechuga', 100, 5),
(5, 'Tomate', 100, 5),
(6, 'Tocino', 100, 5),
(7, 'Pepinillos', 100, 5),
(8, 'Cebolla', 100, 5),
(9, 'Champiñones', 100, 5),
(10, 'Queso Suizo', 100, 5),
(11, 'Piña', 100, 5),
(12, 'Jamón', 100, 5),
(13, 'Hamburguesa de Garbanzos', 100, 5),
(14, 'Queso de Cabra', 100, 5),
(15, 'Palta', 100, 5),
(16, 'Pechuga de Pollo', 100, 5),
(17, 'Queso Mozzarella', 100, 5),
(18, 'Albahaca', 100, 5);

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

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`id_pago`, `tipo_tarjeta`, `numero_tarjeta`, `fecha_expiracion`, `cvv`, `nombre_titular`) VALUES
(5, 'debito', '1111111111111111', '2031-12-01', '123', 'javier'),
(6, 'debito', '', '0000-00-00', NULL, ''),
(7, 'debito', '', '0000-00-00', NULL, ''),
(8, 'debito', '', '0000-00-00', NULL, '');

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
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expiry` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expiry`, `created_at`) VALUES
(1, 'swolf@ing.ucsc.cl', 'e245bbedbbe801edc39f231f083e76064969f17d831fe83237a51f75070d389f671cb86412d0fe566f69198837d123af82d9', '2024-11-15 17:14:11', '2024-11-14 16:14:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_promocion` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `estado_pedido` enum('en_preparacion','en_reparto','entregado') DEFAULT NULL,
  `id_direccion` int(11) DEFAULT NULL,
  `id_metodo_pago` int(11) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_usuario`, `id_promocion`, `total`, `estado_pedido`, `id_direccion`, `id_metodo_pago`, `fecha_pedido`, `monto`) VALUES
(14, 14, NULL, NULL, 'entregado', 10, NULL, '2024-11-02 00:14:25', 9270.00),
(15, 14, NULL, NULL, 'entregado', 11, NULL, '2024-11-02 00:17:20', 10570.00),
(16, 11, NULL, NULL, 'entregado', 9, NULL, '2024-11-02 00:31:41', 7280.00),
(17, 11, NULL, NULL, 'en_reparto', 9, NULL, '2024-11-02 00:38:22', 5990.00),
(18, 11, NULL, NULL, 'en_preparacion', 9, NULL, '2024-11-04 13:36:42', NULL),
(19, 11, NULL, NULL, 'en_preparacion', 9, NULL, '2024-11-04 13:41:20', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_acompaniamiento`
--

CREATE TABLE `pedido_acompaniamiento` (
  `id_pedido` int(11) NOT NULL,
  `id_acompaniamiento` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_acompaniamiento`
--

INSERT INTO `pedido_acompaniamiento` (`id_pedido`, `id_acompaniamiento`, `cantidad`, `precio`) VALUES
(14, 4, 1, 1300.00),
(15, 4, 1, 1300.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_bebida`
--

CREATE TABLE `pedido_bebida` (
  `id_pedido` int(11) NOT NULL,
  `id_bebida` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_bebida`
--

INSERT INTO `pedido_bebida` (`id_pedido`, `id_bebida`, `cantidad`, `precio`) VALUES
(14, 1, 1, 1290.00),
(15, 1, 1, 1290.00),
(16, 1, 1, 1290.00);

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
  `id_hamburguesa` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_hamburguesa`
--

INSERT INTO `pedido_hamburguesa` (`id_pedido`, `id_hamburguesa`, `cantidad`, `precio`) VALUES
(14, 2, 1, 5990.00),
(15, 2, 1, 5990.00),
(16, 2, 1, 5990.00),
(17, 2, 1, 5990.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_postre`
--

CREATE TABLE `pedido_postre` (
  `id_pedido` int(11) NOT NULL,
  `id_postre` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_postre`
--

INSERT INTO `pedido_postre` (`id_pedido`, `id_postre`, `cantidad`, `precio`) VALUES
(14, 1, 1, 1990.00),
(15, 1, 1, 1990.00);

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
(3, 'ver_usuarios', 'Permite ver la lista de usuarios.'),
(4, 'crear_usuario', 'Permite crear nuevos usuarios.'),
(5, 'editar_usuario', 'Permite editar la información de un usuario.'),
(6, 'eliminar_usuario', 'Permite eliminar usuarios del sistema.'),
(7, 'iniciar_sesion', 'Permite que el usuario inicie sesión.'),
(8, 'cerrar_sesion', 'Permite que el usuario cierre sesión.'),
(9, 'ver_roles', 'Permite ver la lista de roles.'),
(10, 'crear_rol', 'Permite crear nuevos roles.'),
(11, 'editar_rol', 'Permite editar la información de un rol.'),
(12, 'eliminar_rol', 'Permite eliminar roles.'),
(13, 'ver_permisos', 'Permite ver la lista de permisos disponibles.'),
(14, 'asignar_permiso', 'Permite asignar o quitar permisos a roles.'),
(15, 'crear_permiso', 'Permite crear nuevos permisos.'),
(16, 'editar_permiso', 'Permite editar la información de un permiso.'),
(17, 'eliminar_permiso', 'Permite eliminar permisos.'),
(18, 'ver_productos', 'Permite ver la lista de productos.'),
(19, 'crear_producto', 'Permite añadir un nuevo producto al menú.'),
(20, 'editar_producto', 'Permite editar la información de un producto.'),
(21, 'eliminar_producto', 'Permite eliminar un producto del sistema.'),
(22, 'ver_pedidos', 'Permite ver la lista de pedidos.'),
(23, 'crear_pedido', 'Permite crear un nuevo pedido.'),
(24, 'editar_pedido', 'Permite editar un pedido (como su estado).'),
(25, 'eliminar_pedido', 'Permite eliminar un pedido.'),
(27, 'actualizar_inventario', 'Permite actualizar el inventario de productos.'),
(28, 'notificar_inventario_bajo', 'Permite notificaciones de inventario bajo.'),
(29, 'generar_reporte_inventario', 'Permite generar reportes de inventario.'),
(30, 'ver_reportes', 'Permite ver los reportes del sistema.'),
(31, 'generar_reporte_ventas', 'Permite generar reportes de ventas.'),
(32, 'exportar_reporte', 'Permite exportar reportes en formato PDF.'),
(33, 'ver_recompensas', 'Permite ver recompensas del usuario.'),
(34, 'asignar_puntos', 'Permite asignar puntos de recompensa.'),
(35, 'canjear_puntos', 'Permite canjear puntos por descuentos.'),
(36, 'ver_direcciones', 'Permite ver direcciones de envío guardadas.'),
(37, 'agregar_direccion', 'Permite agregar una nueva dirección de envío.'),
(38, 'editar_direccion', 'Permite editar una dirección de envío.'),
(39, 'eliminar_direccion', 'Permite eliminar una dirección de envío.'),
(40, 'ver_sugerencias', 'Permite ver sugerencias de productos basadas en el historial de compras.'),
(41, 'ver_estado_despacho', 'Permite ver el estado de despacho de un pedido.'),
(42, 'actualizar_estado_despacho', 'Permite actualizar el estado de despacho.'),
(43, 'ver_metodos_pago', 'Permite ver métodos de pago guardados.'),
(44, 'agregar_metodo_pago', 'Permite agregar un nuevo método de pago.'),
(45, 'editar_metodo_pago', 'Permite editar un método de pago.'),
(46, 'eliminar_metodo_pago', 'Permite eliminar un método de pago.'),
(47, 'ver_promociones', 'Permite ver promociones activas.'),
(48, 'crear_promocion', 'Permite crear una nueva promoción.'),
(49, 'editar_promocion', 'Permite editar una promoción.'),
(50, 'eliminar_promocion', 'Permite eliminar una promoción.'),
(51, 'aplicar_cupon', 'Permite aplicar un cupón de descuento a un pedido.'),
(52, 'valorar_producto', 'Permite a los usuarios valorar productos.'),
(53, 'moderar_comentarios', 'Permite a los administradores moderar valoraciones y comentarios.'),
(54, 'enviar_notificacion', 'Permite al sistema enviar notificaciones automáticas.'),
(55, 'ver_notificaciones', 'Permite al usuario ver sus notificaciones.'),
(60, 'ver_acompaniamiento', 'Permite ver el mantenedor de acompañamientos'),
(61, 'ver_aderezos', 'Permite ver el mantenedor de aderezos'),
(62, 'ver_bebidas', 'Permite ver el mantenedor de bebidas'),
(63, 'ver_combos', 'Permite ver el mantenedor de combos'),
(64, 'ver_hamburguesas', 'Permite ver el mantenedor de hamburguesas'),
(65, 'ver_ingredientes', 'Permite ver el mantenedor de ingredientes'),
(66, 'ver_postres', 'Permite ver el mantenedor de postres'),
(67, 'ver_stock', 'Permite ver el mantenedor de stock'),
(68, 'ver_mantenedores', 'Permite ver el listado de mantenedores');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postre`
--

CREATE TABLE `postre` (
  `id_postre` int(11) NOT NULL,
  `nombre_postre` varchar(30) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `umbral_reabastecimiento` int(11) NOT NULL DEFAULT 5,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `postre`
--

INSERT INTO `postre` (`id_postre`, `nombre_postre`, `cantidad`, `precio`, `umbral_reabastecimiento`, `imagen`) VALUES
(1, 'Helado de Vainilla', 100, 1990, 5, 'helado_vainilla.jpg'),
(2, 'Helado de Chocolate', 100, 1990, 5, 'helado_chocolate.jpg'),
(3, 'Helado de Fresa', 100, 1990, 5, 'helado_fresa.jpg'),
(4, 'Muffin de Frutos Rojos', 100, 1490, 5, 'muffin_frutos_rojos.jpg'),
(5, 'Muffin de Chocolate', 100, 1490, 5, 'muffin_chocolate.jpg'),
(6, 'Brownie de Chocolate', 100, 1990, 5, 'brownie_chocolate.jpg'),
(7, 'Gelatina de Fresa', 100, 990, 5, 'gelatina_fresa.jpg'),
(8, 'Ensalada de Frutas', 100, 1490, 5, 'ensalada_frutas.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `id_promocion` int(11) NOT NULL,
  `nombre_promocion` varchar(255) NOT NULL,
  `descripcion_promocion` text NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `porcentaje_descuento` int(11) NOT NULL CHECK (`porcentaje_descuento` between 1 and 100),
  `id_hamburguesa` int(11) DEFAULT NULL,
  `id_postre` int(11) DEFAULT NULL,
  `id_bebida` int(11) DEFAULT NULL,
  `id_acompaniamiento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`id_promocion`, `nombre_promocion`, `descripcion_promocion`, `fecha_inicio`, `fecha_fin`, `porcentaje_descuento`, `id_hamburguesa`, `id_postre`, `id_bebida`, `id_acompaniamiento`) VALUES
(1, 'Promoción Clásica', '20% de descuento en la hamburguesa clásica', '2024-11-14 13:22:00', '2024-11-15 13:22:00', 20, 1, NULL, NULL, NULL);

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
  `descripcion_rol` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`, `descripcion_rol`) VALUES
(1, 'Admin', 'poder de todo'),
(2, 'Cliente', 'Funcionalidades básicas para realizar un pedido, editar su perfil.'),
(4, 'Admin despacho', 'permite ver los pedidos '),
(5, 'admin stock', 'control de stock');

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
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(1, 42),
(1, 43),
(1, 44),
(1, 45),
(1, 46),
(1, 47),
(1, 48),
(1, 49),
(1, 50),
(1, 51),
(1, 52),
(1, 53),
(1, 54),
(1, 55),
(1, 60),
(1, 61),
(1, 62),
(1, 63),
(1, 64),
(1, 65),
(1, 66),
(1, 67),
(1, 68),
(2, 7),
(2, 8),
(2, 18),
(2, 22),
(2, 23),
(2, 33),
(2, 35),
(2, 36),
(2, 37),
(2, 38),
(2, 39),
(2, 40),
(2, 41),
(2, 43),
(2, 44),
(2, 45),
(2, 46),
(2, 47),
(2, 51),
(2, 52),
(2, 55),
(4, 22),
(4, 68),
(5, 67),
(5, 68);

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
(10, 'jasmito', 'peres', 'xavito.lol.video@gmail.com', '$2y$10$11DwaD8q8fNGg2M6bPzOIet2h6acpkYlwFxjfZflKvhMh5QN/zPdy', '222233', '2024-10-27', NULL),
(11, 'javier', 'Chavez', 'jchavezcontreras@admin.cl', '$2y$10$Heuspsz9hTT4znk0tdpmhOMnICZpdFOm.aaZrBKFmZg6.aFTNzK6i', '975243342', '2024-10-27', NULL),
(12, 'Sergio', 'Wolf', 'swolf@ing.ucsc.cl', '$2y$10$Y5Te/tSJtmyOT9MKKxOsReVW5zaUYjijo2U5WIcICOCoMRi2.XN4.', '984690389', '2024-10-28', NULL),
(13, 'javier', 'Chavez', 'despacho@despacho.cl', '$2y$10$C60LusjRKSZeXhBh8oqybupeMptHEwuroeO.3494x0kSYl0vavyx2', '975243342', '2024-11-01', NULL),
(14, 'javier', 'Chavez', 'xavito.lol.videos@gmail.cl', '$2y$10$nwgztZbXpv8A99rd9IiMJ.fCjYwU.vnjqSncr1R8vCHfrXNB9PbAu', '975243342', '2024-11-01', NULL),
(15, 'Prueba', 'Roles', 'pruebaroles@prueba.com', '$2y$10$w1v8FOfYWWmfPuBmpBjfL.F0RtM6QbBJLHoJ0r8fQ.i7f90lh3p7.', '12345678', '2024-11-14', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_metodo_pago`
--

CREATE TABLE `usuario_metodo_pago` (
  `id_usuario` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_metodo_pago`
--

INSERT INTO `usuario_metodo_pago` (`id_usuario`, `id_pago`) VALUES
(11, 5);

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
(10, 2),
(11, 1),
(12, 4),
(13, 4),
(14, 2),
(15, 5);

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
  `fecha_valoracion` date DEFAULT NULL,
  `id_hamburguesa` int(11) DEFAULT NULL
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
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id_promocion`),
  ADD KEY `id_hamburguesa` (`id_hamburguesa`),
  ADD KEY `id_postre` (`id_postre`),
  ADD KEY `id_bebida` (`id_bebida`),
  ADD KEY `id_acompaniamiento` (`id_acompaniamiento`);

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
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_hamburguesa` (`id_hamburguesa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acompaniamiento`
--
ALTER TABLE `acompaniamiento`
  MODIFY `id_acompaniamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `aderezo`
--
ALTER TABLE `aderezo`
  MODIFY `id_aderezo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `bebida`
--
ALTER TABLE `bebida`
  MODIFY `id_bebida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `boleta`
--
ALTER TABLE `boleta`
  MODIFY `id_boleta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `combo`
--
ALTER TABLE `combo`
  MODIFY `id_combo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `hamburguesa`
--
ALTER TABLE `hamburguesa`
  MODIFY `id_hamburguesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ingrediente`
--
ALTER TABLE `ingrediente`
  MODIFY `id_ingrediente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `postre`
--
ALTER TABLE `postre`
  MODIFY `id_postre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `id_promocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `recompensa`
--
ALTER TABLE `recompensa`
  MODIFY `id_recompensa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

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
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `promocion_ibfk_1` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesa` (`id_hamburguesa`) ON DELETE CASCADE,
  ADD CONSTRAINT `promocion_ibfk_2` FOREIGN KEY (`id_postre`) REFERENCES `postre` (`id_postre`) ON DELETE CASCADE,
  ADD CONSTRAINT `promocion_ibfk_3` FOREIGN KEY (`id_bebida`) REFERENCES `bebida` (`id_bebida`) ON DELETE CASCADE,
  ADD CONSTRAINT `promocion_ibfk_4` FOREIGN KEY (`id_acompaniamiento`) REFERENCES `acompaniamiento` (`id_acompaniamiento`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `valoracion_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `valoracion_ibfk_3` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesa` (`id_hamburguesa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
