-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-12-2024 a las 23:13:39
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
(1, 'Papas Fritas', 98, 1990, 5, 'papas_fritas.jpg'),
(2, 'Aros de Cebolla', 100, 2490, 5, 'aros_cebolla.jpg'),
(4, 'Nuggets de Pollo', 98, 1490, 5, 'nuggets_pollo.jpg'),
(5, 'Palitos de Ajo', 100, 1990, 5, 'palitos_ajo.jpg'),
(6, 'Alitas de Pollo', 100, 2990, 5, 'alitas_pollo.jpg'),
(7, 'Emapanaditas de Queso', 98, 2490, 5, 'empanadas_queso.jpg');

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
(7, 'Mayonesa de Ajo', 98, 5),
(8, 'Pesto', 98, 5);

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
(1, 'Coca Cola', 82, 1990, 5, 'coca_cola.jpg'),
(2, 'Fanta', 98, 1990, 5, 'fanta.jpg'),
(3, 'Sprite', 90, 1990, 5, 'sprite.jpg'),
(4, 'Pepsi', 100, 1990, 5, 'pepsi.jpg'),
(5, 'Red Bull', 98, 2490, 5, 'red_bull.jpg'),
(6, 'Monster', 98, 2490, 5, 'monster.jpg'),
(7, 'Jugo de Naranja', 100, 1490, 5, 'jugo_naranja.jpg'),
(8, 'Jugo de Frutilla', 100, 1490, 5, 'jugo_frutilla.jpg'),
(9, 'Agua Gasificada', 87, 990, 5, 'agua_gasificada.jpg');

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
(13, 'Combo Clásico', 'Disfruta del clásico sabor que nunca pasa de moda con nuestro Combo Clásico, que incluye una deliciosa hamburguesa clásica con ingredientes frescos, crujientes papas fritas, una refrescante Coca-Cola y un cremoso helado de vainilla para el toque dulce per', 9990, 'clasico.jpg'),
(14, 'Combo Vegetariano', 'El Combo Vegetariano es la opción perfecta para quienes buscan sabor sin carne. Incluye una deliciosa hamburguesa vegetariana con ingredientes frescos, unas irresistibles empanaditas de queso, agua gasificada para refrescarte, y una dulce gelatina de fres', 8990, 'vegetariano.jpg'),
(15, 'Combo Infantil', 'El Combo Infantil está diseñado para los pequeños con grandes antojos. Incluye una deliciosa hamburguesa clásica en tamaño ideal, crujientes nuggets de pollo, una refrescante Sprite, y un helado de fresa para el toque dulce que tanto les encanta.', 9490, 'infantil.jpg'),
(16, 'Combo Duo Clásico', 'Comparte el sabor clásico con nuestro Combo Duo Clásico, perfecto para dos. Incluye dos hamburguesas clásicas, unas deliciosas empanaditas de queso, papas fritas crujientes, dos refrescantes Coca-Colas, y un helado de fresa y otro de vainilla para un dulc', 19990, 'duo_clasico.jpg'),
(17, 'Combo Doble Energía', 'Recarga energías con el Combo Doble Energía, que combina una jugosa hamburguesa BBQ Bacon y una deliciosa hamburguesa de pollo BBQ, acompañadas de aros de cebolla, papas fritas, una Monster, un Red Bull y dos irresistibles brownies de chocolate para cerra', 21990, 'doble_energia.jpg'),
(18, 'Combo Familiar', 'El Combo Familiar es la elección ideal para compartir momentos inolvidables. Incluye cuatro hamburguesas únicas: BBQ Bacon, Clásica, con Champiñones e Italiana, acompañadas de aros de cebolla, empanaditas de queso, dos porciones de papas fritas, refrescos', 39990, 'familiar.jpg');

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
(13, 1, 1),
(14, 7, 1),
(15, 4, 1),
(16, 1, 1),
(16, 7, 1),
(17, 1, 1),
(17, 2, 1),
(18, 1, 2),
(18, 2, 1),
(18, 7, 1);

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
(13, 1, 1),
(14, 9, 1),
(15, 3, 1),
(16, 1, 2),
(17, 5, 1),
(17, 6, 1),
(18, 1, 2),
(18, 2, 1),
(18, 3, 1);

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
(13, 1, 1),
(14, 5, 1),
(15, 1, 1),
(16, 1, 2),
(17, 2, 1),
(17, 7, 1),
(18, 1, 1),
(18, 2, 1),
(18, 6, 1),
(18, 8, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_postre`
--

CREATE TABLE `combo_postre` (
  `id_combo` int(11) NOT NULL,
  `id_postre` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combo_postre`
--

INSERT INTO `combo_postre` (`id_combo`, `id_postre`, `cantidad`) VALUES
(13, 1, 1),
(14, 7, 1),
(15, 3, 1),
(16, 1, 1),
(16, 3, 1),
(17, 6, 2),
(18, 1, 1),
(18, 2, 1),
(18, 4, 1),
(18, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero` int(11) NOT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `depto_oficina_piso` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`id_direccion`, `calle`, `numero`, `ciudad`, `depto_oficina_piso`) VALUES
(5, 'Rodrigo de Quiroga', 306, 'Cañete', NULL),
(6, 'Janequeo', 874, 'Concepción', NULL),
(13, 'amiens', 748, 'concepcion', '74'),
(14, 'amiens', 748, 'concepcion', '123'),
(15, 'amiens', 748, 'concepcion', '748'),
(16, 'direccion de prueba', 123, 'concepcion', '7');

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
(11, 13),
(11, 14),
(12, 5),
(12, 6),
(16, 15),
(17, 16);

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
(7, 'Hamburguesa de Pollo BBQ', 'Pechuga de pollo a la parrilla, queso cheddar, lechuga, tomate, y salsa BBQ.', 5490, 'pollo_bbq.jpg'),
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
(1, 'Pan Brioche', 28, 2),
(2, 'Carne de Res', 43, 5),
(3, 'Queso Cheddar', 55, 5),
(4, 'Lechuga', 69, 5),
(5, 'Tomate', 56, 5),
(6, 'Tocino', 86, 5),
(7, 'Pepinillos', 100, 5),
(8, 'Cebolla', 96, 5),
(9, 'Champiñones', 96, 5),
(10, 'Queso Suizo', 95, 5),
(11, 'Piña', 99, 5),
(12, 'Jamón', 100, 5),
(13, 'Hamburguesa de Garbanzos', 87, 5),
(14, 'Queso de Cabra', 87, 5),
(15, 'Palta', 87, 5),
(16, 'Pechuga de Pollo', 97, 5),
(17, 'Queso Mozzarella', 90, 5),
(18, 'Albahaca', 90, 5);

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
(1, 'swolf@ing.ucsc.cl', 'e245bbedbbe801edc39f231f083e76064969f17d831fe83237a51f75070d389f671cb86412d0fe566f69198837d123af82d9', '2024-11-15 17:14:11', '2024-11-14 16:14:11'),
(3, 'feligerbier@gmail.com', '94a1e25dd5f4f949adf0830de68c31a65a8224621dfd92e796649c8949cdc441aa38cb3cecb411f1fb1bf6bad0a89327e9bf', '2024-12-03 03:14:58', '2024-12-02 02:14:58'),
(4, 'feligerbier@gmail.com', 'a75c3e98a67b74b5e2846ae5a19c705d294db8ba211800f0d522eddd45787a136c3d7468e203504652810de90d1dada3cbb5', '2024-12-03 03:16:57', '2024-12-02 02:16:57'),
(6, 'feligerbier@gmail.com', 'a49bfba511978ba99d47316a9215a406a5473d9c06180717be0a9af2f6c24e20f2fd1618475e803d5851c984f9e681130f85', '2024-12-03 03:20:51', '2024-12-02 02:20:51'),
(7, 'feligerbier@gmail.com', '06c99db7d0d363ec3faa636b7fd021ab89cb578d50043a4445871fad20b2d82830b71fba3de7b6cc24d2a10dba3527b77810', '2024-12-03 03:22:40', '2024-12-02 02:22:40'),
(8, 'feligerbier@gmail.com', '1b404ae2eaa317e6dee03356ca361449e364d767bd538c166095f8fe8cf2a19c62f1f3170de4d673225044110bea5b234888', '2024-12-03 03:23:44', '2024-12-02 02:23:44'),
(9, 'feligerbier@gmail.com', 'da35fe5949c268e16bdcba6edffb6d9e3b2cd05f8155af78ae9f8a29cf5b84988a56f79f3c654babee64bf78e8ac0546f90b', '2024-12-03 03:23:50', '2024-12-02 02:23:50'),
(10, 'feligerbier@gmail.com', 'ae04bbb90ce32e22abf635b47e5ffa46500ebacd7f925d2c907c2bbf4916beedc02f708104a361ef019165512d978fa95c66', '2024-12-03 03:23:59', '2024-12-02 02:23:59'),
(11, 'feligerbier@gmail.com', '5bd48468a1141b3e5882eee36c989faaa81cb56d0d8ba657dda91be14d6dc080f4c330c354bb752a3bf3f6925fac69207666', '2024-12-03 03:25:35', '2024-12-02 02:25:35'),
(12, 'feligerbier@gmail.com', '44a8d94229f640daf6b57ec4476931b2cfcccd65d94140619dd91e27b83f1a021956dcceb3c7ac645e738d5621d97af356b7', '2024-12-03 03:26:12', '2024-12-02 02:26:12'),
(13, 'feligerbier@gmail.com', '95dc832f988b72688900b29e0e6f798caf87dff0b2bce359e02a8280c1fe4797a0dfa91ee20df760d808d9423e39e2a30c30', '2024-12-03 03:26:24', '2024-12-02 02:26:24'),
(14, 'feligerbier@gmail.com', 'f3cd55f32c3e4702aa003b495f18de546296708b64afa993616b4f6b5f833b1efb600f67458f7c67c8f88b9421f2ba2423fc', '2024-12-03 03:26:29', '2024-12-02 02:26:29'),
(15, 'feligerbier@gmail.com', '8c7590c29c9a4652e71a0b4a8245f7168f265aed39897e16cbe356280d01c1a54ce5893b7fe6f0b8be2ec3c7449445b910d5', '2024-12-03 03:28:01', '2024-12-02 02:28:01'),
(16, 'feligerbier@gmail.com', '8fc5a3ee7ae41b4ec729c555137b39fa35095127c015eb83d26546ce294590e2a188156e19517dc7215acc0d7e1eb6ecc63d', '2024-12-03 03:28:07', '2024-12-02 02:28:07'),
(17, 'feligerbier@gmail.com', '86385ed63140a5eca93f53ca67634e5d624787b5a66b639b7a5eb20ad5ead9d22966d99822fba7e155b4b5b3943833f1bd08', '2024-12-03 03:28:45', '2024-12-02 02:28:45'),
(18, 'feligerbier@gmail.com', '680f6f110ef6fc3939f92402ca301d892f65cc125f76a2ed113bcf3f842f0642671043ee6dd44c52504cf913efb25c728ef5', '2024-12-03 03:29:11', '2024-12-02 02:29:11'),
(19, 'feligerbier@gmail.com', '2f015411da483314fcdec0095655110a0dc17c770853f79aa381a2254781017f64e7bb000030aadd18c0958ec903e8de58f0', '2024-12-03 03:32:03', '2024-12-02 02:32:03'),
(22, 'feligerbier@gmail.com', '6b59e61649a18256d53a46416e7301db50f01921b5c92807725d155c098c8c43f61c35e4ee32caaffb365fee2cdce9a894e6', '2024-12-03 03:38:39', '2024-12-02 02:38:39'),
(24, 'feligerbier@gmail.com', '4f8ab5d3af58ea420d127e6129427da619b30e102d35934d7d03c01e15b976fb8c821b306fc3975109ed92a23a3d765f1441', '2024-12-03 21:32:30', '2024-12-02 20:32:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `estado_pedido` enum('en_preparacion','en_reparto','entregado') DEFAULT NULL,
  `id_direccion` int(11) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL,
  `puntos_utilizados` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_usuario`, `estado_pedido`, `id_direccion`, `fecha_pedido`, `monto_total`, `puntos_utilizados`) VALUES
(14, 14, 'entregado', 10, '2024-11-02 00:14:25', 9270.00, 0),
(15, 14, 'entregado', 11, '2024-11-02 00:17:20', 10570.00, 0),
(16, 11, 'entregado', 9, '2024-11-02 00:31:41', 7280.00, 0),
(17, 11, 'entregado', 9, '2024-11-02 00:38:22', 5990.00, 0),
(18, 11, 'entregado', 9, '2024-11-04 13:36:42', NULL, 0),
(19, 11, 'entregado', 9, '2024-11-04 13:41:20', NULL, 0),
(33, 11, 'entregado', 9, '2024-11-30 15:39:03', 13235.00, 0),
(39, 11, 'entregado', 9, '2024-11-30 16:18:42', 8990.00, 0),
(40, 11, 'en_preparacion', 9, '2024-11-30 16:20:22', 149.00, 0),
(41, 11, 'en_preparacion', 9, '2024-11-30 16:21:14', 8990.00, 0),
(45, 11, 'en_preparacion', 9, '2024-11-30 16:30:21', 1990.00, 0),
(47, 11, 'en_preparacion', 9, '2024-11-30 16:42:38', 2490.00, 0),
(48, 11, 'en_preparacion', 9, '2024-11-30 16:43:16', 4990.00, 0),
(49, 11, 'en_preparacion', 9, '2024-11-30 16:43:37', 1990.00, 0),
(50, 11, 'en_preparacion', 9, '2024-11-30 16:44:03', 1990.00, 0),
(51, 11, 'en_preparacion', 9, '2024-11-30 16:44:22', 9490.00, 0),
(52, 11, 'en_preparacion', 9, '2024-11-30 16:48:04', 9990.00, 0),
(53, 11, 'en_preparacion', 9, '2024-11-30 16:51:14', 9490.00, 0),
(54, 11, 'entregado', 9, '2024-11-30 16:52:08', 9490.00, 0),
(55, 11, 'en_preparacion', 9, '2024-11-30 16:52:41', 39990.00, 0),
(56, 11, 'en_preparacion', 9, '2024-11-30 16:53:06', 5990.00, 0),
(57, 11, 'en_preparacion', 9, '2024-11-30 16:56:01', 9990.00, 0),
(58, 11, 'en_preparacion', 9, '2024-11-30 16:56:23', 9990.00, 0),
(59, 11, 'en_preparacion', 9, '2024-11-30 16:56:39', 39990.00, 0),
(60, 11, 'en_preparacion', 9, '2024-11-30 16:57:11', 5384.00, 0),
(61, 11, 'en_preparacion', 9, '2024-11-30 16:57:27', 5384.00, 0),
(62, 11, 'en_preparacion', 9, '2024-11-30 16:59:03', 16450.00, 0),
(66, 11, 'en_preparacion', 9, '2024-11-30 17:05:05', 5990.00, 0),
(67, 11, 'en_preparacion', 9, '2024-11-30 17:07:06', 5990.00, 0),
(68, 11, 'en_preparacion', 9, '2024-11-30 17:07:18', 8990.00, 0),
(69, 11, 'en_preparacion', 9, '2024-11-30 17:09:25', 8990.00, 0),
(70, 11, 'en_preparacion', 9, '2024-11-30 17:09:33', 8990.00, 0),
(71, 11, 'en_preparacion', 9, '2024-11-30 17:13:02', 8990.00, 0),
(72, 11, 'entregado', 9, '2024-11-30 17:13:08', 8990.00, 0),
(73, 11, 'en_preparacion', 9, '2024-11-30 17:15:54', 5990.00, 0),
(74, 11, 'en_preparacion', 9, '2024-11-30 17:17:00', 5990.00, 0),
(75, 11, 'en_preparacion', 9, '2024-11-30 17:18:32', 5990.00, 0),
(76, 11, 'en_preparacion', 9, '2024-11-30 17:21:07', 5990.00, 0),
(77, 11, 'en_preparacion', 9, '2024-11-30 17:26:45', 5990.00, 0),
(78, 11, 'en_preparacion', 9, '2024-11-30 17:26:57', 5990.00, 0),
(79, 11, 'en_preparacion', 9, '2024-11-30 17:27:28', 5990.00, 0),
(80, 11, 'en_preparacion', 9, '2024-11-30 17:28:38', 8990.00, 0),
(81, 11, 'en_preparacion', 9, '2024-11-30 17:29:26', 8990.00, 0),
(82, 11, 'en_preparacion', 9, '2024-12-01 20:12:32', 28470.00, 1),
(83, 11, 'entregado', 9, '2024-12-01 21:01:56', 12384.00, 10000),
(84, 16, 'entregado', 15, '2024-12-01 22:05:30', 57970.00, 0),
(85, 16, 'entregado', 15, '2024-12-01 22:08:18', 4990.00, 2898),
(86, 11, 'en_preparacion', 9, '2024-12-01 22:26:16', 35225.00, 0),
(87, 11, 'entregado', 9, '2024-12-01 22:43:38', 149.00, 0),
(88, 11, 'en_preparacion', 9, '2024-12-01 23:01:52', 9490.00, 0),
(89, 11, 'entregado', 9, '2024-12-01 23:10:19', 23235.00, 0),
(90, 16, 'entregado', 15, '2024-12-02 00:56:21', 29980.00, 0),
(91, 11, 'entregado', 9, '2024-12-02 01:10:06', 9490.00, 0),
(92, 11, 'entregado', 9, '2024-12-02 13:53:49', 21990.00, 0),
(93, 11, 'entregado', 9, '2024-12-02 13:59:52', 9490.00, 0),
(94, 16, 'entregado', 15, '2024-12-02 14:01:41', 5490.00, 0),
(95, 11, 'entregado', 9, '2024-12-02 16:07:36', 9990.00, 0),
(96, 11, 'en_preparacion', 9, '2024-12-02 17:18:47', 8990.00, 0),
(97, 11, 'en_preparacion', 13, '2024-12-02 17:28:50', 18980.00, 0),
(98, 11, 'en_reparto', 13, '2024-12-02 17:44:04', 9990.00, 0),
(99, 17, 'en_preparacion', 16, '2024-12-02 18:33:56', 25470.00, 0),
(100, 17, 'en_reparto', 16, '2024-12-02 18:35:23', 8990.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_acompaniamiento`
--

CREATE TABLE `pedido_acompaniamiento` (
  `id_pedido` int(11) NOT NULL,
  `id_acompaniamiento` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_promocion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_acompaniamiento`
--

INSERT INTO `pedido_acompaniamiento` (`id_pedido`, `id_acompaniamiento`, `cantidad`, `precio`, `id_promocion`) VALUES
(14, 4, 1, 1300.00, NULL),
(15, 4, 1, 1300.00, NULL),
(40, 4, 1, 149.00, 8),
(45, 1, 1, 1990.00, NULL),
(47, 2, 1, 2490.00, NULL),
(60, 1, 1, 1990.00, NULL),
(60, 4, 1, 149.00, 8),
(61, 1, 1, 1990.00, NULL),
(61, 4, 1, 149.00, 8),
(62, 5, 1, 1990.00, NULL),
(83, 4, 1, 149.00, 8),
(87, 4, 1, 149.00, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_bebida`
--

CREATE TABLE `pedido_bebida` (
  `id_pedido` int(11) NOT NULL,
  `id_bebida` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_promocion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_bebida`
--

INSERT INTO `pedido_bebida` (`id_pedido`, `id_bebida`, `cantidad`, `precio`, `id_promocion`) VALUES
(14, 1, 1, 1290.00, NULL),
(15, 1, 1, 1290.00, NULL),
(16, 1, 1, 1290.00, NULL),
(49, 1, 1, 1990.00, NULL),
(62, 1, 1, 1990.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_combo`
--

CREATE TABLE `pedido_combo` (
  `id_pedido` int(11) NOT NULL,
  `id_combo` int(11) NOT NULL,
  `id_promocion` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_combo`
--

INSERT INTO `pedido_combo` (`id_pedido`, `id_combo`, `id_promocion`, `cantidad`, `precio`) VALUES
(41, 14, NULL, 1, 8990.00),
(51, 15, NULL, 1, 9490.00),
(52, 13, NULL, 1, 9990.00),
(53, 15, NULL, 1, 9490.00),
(54, 15, NULL, 1, 9490.00),
(55, 18, NULL, 1, 39990.00),
(57, 13, NULL, 1, 9990.00),
(58, 13, NULL, 1, 9990.00),
(59, 18, NULL, 1, 39990.00),
(68, 14, NULL, 1, 8990.00),
(69, 14, NULL, 1, 8990.00),
(70, 14, NULL, 1, 8990.00),
(71, 14, NULL, 1, 8990.00),
(72, 14, NULL, 1, 8990.00),
(80, 14, NULL, 1, 8990.00),
(81, 14, NULL, 1, 8990.00),
(82, 15, NULL, 3, 9490.00),
(83, 14, NULL, 1, 8990.00),
(84, 14, NULL, 2, 8990.00),
(84, 18, NULL, 1, 39990.00),
(86, 13, NULL, 1, 9990.00),
(86, 17, NULL, 1, 21990.00),
(88, 15, NULL, 1, 9490.00),
(89, 16, NULL, 1, 19990.00),
(90, 13, NULL, 1, 9990.00),
(90, 16, NULL, 1, 19990.00),
(91, 15, NULL, 1, 9490.00),
(92, 17, NULL, 1, 21990.00),
(93, 15, NULL, 1, 9490.00),
(95, 13, NULL, 1, 9990.00),
(96, 14, NULL, 1, 8990.00),
(97, 13, NULL, 1, 9990.00),
(97, 14, NULL, 1, 8990.00),
(98, 13, NULL, 1, 9990.00),
(99, 15, NULL, 2, 9490.00),
(100, 14, NULL, 1, 8990.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_hamburguesa`
--

CREATE TABLE `pedido_hamburguesa` (
  `id_pedido` int(11) NOT NULL,
  `id_hamburguesa` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_promocion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_hamburguesa`
--

INSERT INTO `pedido_hamburguesa` (`id_pedido`, `id_hamburguesa`, `cantidad`, `precio`, `id_promocion`) VALUES
(14, 2, 1, 5990.00, NULL),
(15, 2, 1, 5990.00, NULL),
(16, 2, 1, 5990.00, NULL),
(17, 2, 1, 5990.00, NULL),
(48, 1, 1, 4990.00, NULL),
(56, 2, 1, 5990.00, NULL),
(60, 8, 1, 3245.00, 7),
(61, 8, 1, 3245.00, 7),
(62, 1, 1, 4990.00, NULL),
(62, 6, 1, 5990.00, NULL),
(66, 2, 1, 5990.00, NULL),
(67, 2, 1, 5990.00, NULL),
(73, 2, 1, 5990.00, NULL),
(74, 2, 1, 5990.00, NULL),
(75, 2, 1, 5990.00, NULL),
(76, 4, 1, 5990.00, NULL),
(77, 2, 1, 5990.00, NULL),
(78, 2, 1, 5990.00, NULL),
(79, 2, 1, 5990.00, NULL),
(83, 8, 1, 3245.00, 7),
(85, 1, 1, 4990.00, NULL),
(86, 8, 1, 3245.00, 7),
(89, 8, 1, 3245.00, 7),
(94, 7, 1, 5490.00, NULL),
(99, 8, 2, 3245.00, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_postre`
--

CREATE TABLE `pedido_postre` (
  `id_pedido` int(11) NOT NULL,
  `id_postre` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_promocion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_postre`
--

INSERT INTO `pedido_postre` (`id_pedido`, `id_postre`, `cantidad`, `precio`, `id_promocion`) VALUES
(14, 1, 1, 1990.00, NULL),
(15, 1, 1, 1990.00, NULL),
(50, 1, 1, 1990.00, NULL),
(62, 4, 1, 1490.00, NULL);

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
(1, 'Helado de Vainilla', 87, 1990, 5, 'helado_vainilla.jpg'),
(2, 'Helado de Chocolate', 98, 1990, 5, 'helado_chocolate.jpg'),
(3, 'Helado de Fresa', 90, 1990, 5, 'helado_fresa.jpg'),
(4, 'Muffin de Frutos Rojos', 97, 1490, 5, 'muffin_frutos_rojos.jpg'),
(5, 'Muffin de Chocolate', 98, 1490, 5, 'muffin_chocolate.jpg'),
(6, 'Brownie de Chocolate', 96, 1990, 5, 'brownie_chocolate.jpg'),
(7, 'Gelatina de Fresa', 87, 990, 5, 'gelatina_fresa.jpg'),
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
  `id_acompaniamiento` int(11) DEFAULT NULL,
  `id_combo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`id_promocion`, `nombre_promocion`, `descripcion_promocion`, `fecha_inicio`, `fecha_fin`, `porcentaje_descuento`, `id_hamburguesa`, `id_postre`, `id_bebida`, `id_acompaniamiento`, `id_combo`) VALUES
(2, 'Promoción Clásica', '¡No te pierdas nuestra promoción de la Rica Hamburguesa Clásica! Degusta esta irresistible hamburguesa con jugosa carne de res a la parrilla, frescas hojas de lechuga, rodajas de tomate maduro y crujientes aros de cebolla, todo coronado con una suave capa de queso cheddar derretido y nuestra salsa especial en un pan brioche ligeramente tostado.', '2024-11-19 00:28:00', '2024-11-20 00:28:00', 10, 1, NULL, NULL, NULL, NULL),
(3, 'Hamburguesa BBQ Bacon', 'asdfasdfasdf', '2024-11-18 00:33:00', '2024-11-27 00:33:00', 50, 2, NULL, NULL, NULL, NULL),
(4, 'Papas Fritas', 'qrqwerqwer', '2024-11-20 12:50:00', '2024-11-29 12:50:00', 90, NULL, NULL, NULL, 1, NULL),
(5, 'Combo Familiar', 'asfdasdfasdfa', '2024-11-20 14:15:00', '2024-11-29 14:15:00', 50, NULL, NULL, NULL, NULL, 18),
(6, 'Bebida Energizante', 'adjfnaksljdbfasldfbaldjasldfjbaskldjfasd', '2024-11-20 21:46:00', '2024-11-24 21:46:00', 50, NULL, NULL, 5, NULL, NULL),
(7, 'Hamburguesa Italiana', 'probando probando', '2024-11-29 18:27:00', '2024-12-31 18:27:00', 50, 8, NULL, NULL, NULL, NULL),
(8, 'Nuggets de Pollo', 'descuento para mascota', '2024-11-29 22:16:00', '2024-12-25 22:16:00', 90, NULL, NULL, NULL, 4, NULL);

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
(10, 'jasmito', 'peres', 'xavito.lol.video@gmail.com', '$2y$10$11DwaD8q8fNGg2M6bPzOIet2h6acpkYlwFxjfZflKvhMh5QN/zPdy', '222233', '2024-10-27', 0),
(11, 'javier', 'Chavez', 'jchavezcontreras@admin.cl', '$2y$10$Heuspsz9hTT4znk0tdpmhOMnICZpdFOm.aaZrBKFmZg6.aFTNzK6i', '975243342', '2024-10-27', 8667),
(12, 'Sergio', 'Wolf', 'swolf@ing.ucsc.cl', '$2y$10$Y5Te/tSJtmyOT9MKKxOsReVW5zaUYjijo2U5WIcICOCoMRi2.XN4.', '984690389', '2024-10-28', 0),
(13, 'javier', 'Chavez', 'despacho@despacho.cl', '$2y$10$C60LusjRKSZeXhBh8oqybupeMptHEwuroeO.3494x0kSYl0vavyx2', '975243342', '2024-11-01', 0),
(14, 'javier', 'Chavez', 'xavito.lol.videos@gmail.cl', '$2y$10$nwgztZbXpv8A99rd9IiMJ.fCjYwU.vnjqSncr1R8vCHfrXNB9PbAu', '975243342', '2024-11-01', 0),
(15, 'Prueba', 'Roles', 'pruebaroles@prueba.com', '$2y$10$w1v8FOfYWWmfPuBmpBjfL.F0RtM6QbBJLHoJ0r8fQ.i7f90lh3p7.', '12345678', '2024-11-14', 0),
(16, 'Felipe', 'Gerbier', 'feligerbier@gmail.com', '$2y$10$mjKJiT5BGgBkAOPnuDUxyuxaqI/XJuBx1h.GKJ5h3B5I.g7cx1BtK', '983179683', '2024-12-01', 1877),
(17, 'Presentacion', 'Final', 'presentacion@presentacion.cl', '$2y$10$JrK7btweTIJpqJulL.6V6eJcboBfoLhBIjYfdb54chM/td7raYEz2', '12345678', '2024-12-02', 1722);

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
(12, 2),
(13, 4),
(14, 2),
(15, 5),
(16, 4),
(17, 2);

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
  `id_hamburguesa` int(11) DEFAULT NULL,
  `id_postre` int(11) DEFAULT NULL,
  `id_bebida` int(11) DEFAULT NULL,
  `id_acompaniamiento` int(11) DEFAULT NULL,
  `id_combo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `valoracion`
--

INSERT INTO `valoracion` (`id_valoracion`, `id_usuario`, `id_pedido`, `cantidad_estrellas`, `comentario`, `fecha_valoracion`, `id_hamburguesa`, `id_postre`, `id_bebida`, `id_acompaniamiento`, `id_combo`) VALUES
(1, 11, 17, 5, 'buena hamburguesa', '2024-12-02', 2, NULL, NULL, NULL, NULL),
(2, 11, 89, 5, 'Muy buena hamburguesa', '2024-12-02', 8, NULL, NULL, NULL, 16),
(3, 16, 94, 5, 'Rica Hamburguesa', '2024-12-02', 7, NULL, NULL, NULL, NULL),
(4, 11, 18, 5, 'Muy buena hamburguesa', '2024-12-02', NULL, NULL, NULL, NULL, NULL),
(5, 11, 16, 5, 'muy buena hamburguesa', '2024-12-02', 2, NULL, 1, NULL, NULL);

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
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedido_acompaniamiento`
--
ALTER TABLE `pedido_acompaniamiento`
  ADD PRIMARY KEY (`id_pedido`,`id_acompaniamiento`),
  ADD KEY `id_acompaniamiento` (`id_acompaniamiento`),
  ADD KEY `fk_pedido_acompaniamiento_promocion` (`id_promocion`);

--
-- Indices de la tabla `pedido_bebida`
--
ALTER TABLE `pedido_bebida`
  ADD PRIMARY KEY (`id_pedido`,`id_bebida`),
  ADD KEY `id_bebida` (`id_bebida`),
  ADD KEY `fk_pedido_bebida_promocion` (`id_promocion`);

--
-- Indices de la tabla `pedido_combo`
--
ALTER TABLE `pedido_combo`
  ADD PRIMARY KEY (`id_pedido`,`id_combo`),
  ADD KEY `id_combo` (`id_combo`),
  ADD KEY `fk_pedido_combo_promocion` (`id_promocion`);

--
-- Indices de la tabla `pedido_hamburguesa`
--
ALTER TABLE `pedido_hamburguesa`
  ADD PRIMARY KEY (`id_pedido`,`id_hamburguesa`),
  ADD KEY `id_hamburguesa` (`id_hamburguesa`),
  ADD KEY `fk_pedido_hamburguesa_promocion` (`id_promocion`);

--
-- Indices de la tabla `pedido_postre`
--
ALTER TABLE `pedido_postre`
  ADD PRIMARY KEY (`id_pedido`,`id_postre`),
  ADD KEY `id_postre` (`id_postre`),
  ADD KEY `fk_pedido_postre_promocion` (`id_promocion`);

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
  ADD KEY `id_acompaniamiento` (`id_acompaniamiento`),
  ADD KEY `fk_id_combo` (`id_combo`);

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
  MODIFY `id_combo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

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
  MODIFY `id_promocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `valoracion`
--
ALTER TABLE `valoracion`
  MODIFY `id_valoracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boleta`
--
ALTER TABLE `boleta`
  ADD CONSTRAINT `boleta_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

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
  ADD CONSTRAINT `fk_pedido_acompaniamiento_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promocion` (`id_promocion`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_acompaniamiento_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_acompaniamiento_ibfk_2` FOREIGN KEY (`id_acompaniamiento`) REFERENCES `acompaniamiento` (`id_acompaniamiento`);

--
-- Filtros para la tabla `pedido_bebida`
--
ALTER TABLE `pedido_bebida`
  ADD CONSTRAINT `fk_pedido_bebida_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promocion` (`id_promocion`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_bebida_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_bebida_ibfk_2` FOREIGN KEY (`id_bebida`) REFERENCES `bebida` (`id_bebida`);

--
-- Filtros para la tabla `pedido_combo`
--
ALTER TABLE `pedido_combo`
  ADD CONSTRAINT `fk_pedido_combo_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promocion` (`id_promocion`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_combo_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_combo_ibfk_2` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`);

--
-- Filtros para la tabla `pedido_hamburguesa`
--
ALTER TABLE `pedido_hamburguesa`
  ADD CONSTRAINT `fk_pedido_hamburguesa_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promocion` (`id_promocion`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_hamburguesa_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_hamburguesa_ibfk_2` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesa` (`id_hamburguesa`);

--
-- Filtros para la tabla `pedido_postre`
--
ALTER TABLE `pedido_postre`
  ADD CONSTRAINT `fk_pedido_postre_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promocion` (`id_promocion`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_postre_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `pedido_postre_ibfk_2` FOREIGN KEY (`id_postre`) REFERENCES `postre` (`id_postre`);

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `fk_id_combo` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`),
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
