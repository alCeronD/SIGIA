-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2025 a las 23:07:43
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sigia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `ar_cod` int(11) NOT NULL,
  `ar_nombre` varchar(30) NOT NULL,
  `ar_descripcion` varchar(300) DEFAULT NULL,
  `ar_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`ar_cod`, `ar_nombre`, `ar_descripcion`, `ar_status`) VALUES
(1, 'Multimedia', 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium doloremque molestiae corrup', 1),
(2, 'Fotografía', 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium doloremque molestiae corrupti voluptates iste eum illo dignissimos, quia essorem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium doloremque molestiae corrupti voluptates iste eum illo dignissimos, quia esse quasi quo', 1),
(3, 'General', 'orem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium doloremque molestiae corrupti voluptates iste eum illo dignissimos, quia esse quasi quo ipsa officiis quod provident quis sint vitae in dicta! Lorem ipsum dolor, sit amet consectetur adipisicing elit. Atque velit, cum, sed doloremor', 1),
(4, 'Aulas De Computo', 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium doloremque molestiae corrup', 1),
(5, 'Area de diseño', 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium doloremque molestiae corrup', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `btr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `ca_id` int(11) NOT NULL,
  `ca_nombre` varchar(50) NOT NULL,
  `ca_descripcion` varchar(200) NOT NULL,
  `ca_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`ca_id`, `ca_nombre`, `ca_descripcion`, `ca_status`) VALUES
(3, 'elemento utilidad', 'asd', 1),
(4, 'sony', 'asd', 1),
(6, 'prueba informacionss', 'asdss', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elementos`
--

CREATE TABLE `elementos` (
  `elm_cod` int(11) NOT NULL,
  `elm_placa` int(11) DEFAULT NULL,
  `elm_nombre` varchar(100) NOT NULL,
  `elm_existencia` int(11) DEFAULT NULL,
  `elm_uni_medida` int(11) DEFAULT NULL,
  `elm_cod_tp_elemento` int(11) NOT NULL,
  `elm_cod_estado` int(11) NOT NULL,
  `elm_area_cod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `elementos`
--

INSERT INTO `elementos` (`elm_cod`, `elm_placa`, `elm_nombre`, `elm_existencia`, `elm_uni_medida`, `elm_cod_tp_elemento`, `elm_cod_estado`, `elm_area_cod`) VALUES
(125, 1001, 'Computadora portátil HP', 1, 1, 1, 1, 1),
(126, 1002, 'Proyector Epson', 1, 1, 1, 1, 2),
(127, 1003, 'Monitor Samsung 24\"', 1, 1, 1, 1, 4),
(128, 1004, 'Teclado mecánico Logitech', 1, 1, 1, 1, 5),
(129, 1005, 'Ratón inalámbrico', 1, 1, 1, 3, 1),
(130, 1006, 'Impresora laser Brother', 1, 1, 1, 3, 2),
(131, 1007, 'Tablet Samsung Galaxy', 1, 1, 1, 3, 4),
(132, 1008, 'Cámara web Logitech', 1, 1, 1, 1, 5),
(133, 1009, 'Parlantes Bose', 1, 1, 1, 1, 1),
(134, 1010, 'Auriculares Sony', 1, 1, 1, 1, 2),
(135, 1011, 'Switch Cisco 24 puertos', 1, 1, 1, 3, 4),
(136, 1012, 'Cable HDMI 2 metros', 1, 1, 1, 1, 5),
(137, 1013, 'Estabilizador APC 1000VA', 1, 1, 1, 1, 1),
(138, 1014, 'Unidad USB 64GB', 1, 1, 1, 3, 2),
(139, 1015, 'Router TP-Link', 1, 1, 1, 1, 4),
(140, 1016, 'Base para laptop', 1, 1, 1, 1, 5),
(141, 1017, 'Disco duro externo 1TB', 1, 1, 1, 3, 1),
(142, 1018, 'Proyector portátil LG', 1, 1, 1, 1, 2),
(143, 1019, 'Cable VGA 3 metros', 1, 1, 1, 1, 4),
(144, 1020, 'Micrófono condensador', 1, 1, 1, 1, 5),
(145, 1021, 'Soporte para monitor', 1, 1, 1, 3, 1),
(146, 1022, 'Teclado inalámbrico Microsoft', 1, 1, 1, 1, 2),
(147, 1023, 'Ratón óptico HP', 1, 1, 1, 1, 4),
(148, 1024, 'Hub USB 4 puertos', 1, 1, 1, 1, 5),
(149, 1025, 'Pantalla táctil Dell', 1, 1, 1, 1, 1),
(150, 1026, 'Cámara de seguridad IP', 1, 1, 1, 1, 2),
(151, 1027, 'Laptop Lenovo ThinkPad', 1, 1, 1, 1, 4),
(152, 1028, 'Proyector 4K', 1, 1, 1, 1, 5),
(153, 1029, 'Router inalámbrico Netgear', 1, 1, 1, 3, 1),
(154, 1030, 'Micrófono inalámbrico Shure', 1, 1, 1, 1, 2),
(155, 3001, 'Papel A4 paquete', 48, 2, 2, 1, 3),
(156, 3002, 'Tinta para impresora negra', 30, 2, 2, 1, 3),
(157, 3003, 'Tinta para impresora color', 25, 2, 2, 1, 3),
(158, 3004, 'Marcadores permanentes', 28, 1, 2, 1, 3),
(159, 3005, 'Bolígrafos azules', 18, 1, 2, 1, 3),
(160, 3006, 'Resaltadores fluorescentes', 60, 1, 2, 1, 3),
(161, 3007, 'Cinta adhesiva', 2, 2, 2, 1, 3),
(162, 3008, 'Grapas para engrampadora', 87, 2, 2, 1, 3),
(163, 3009, 'Hojas para notas adhesivas', 80, 2, 2, 1, 3),
(164, 3010, 'Cartuchos de tinta HP', 0, 1, 2, 1, 3),
(165, 3011, 'Papel bond blanco', 40, 2, 2, 1, 3),
(166, 3012, 'Papel bond color', 42, 2, 2, 1, 3),
(167, 3013, 'Toners para impresora', 18, 1, 2, 1, 3),
(168, 3014, 'Papel carbón', 27, 2, 2, 1, 3),
(169, 3015, 'Papel fotográfico', 25, 2, 2, 1, 3),
(170, 3016, 'Borradores para pizarras', 3, 1, 2, 1, 3),
(171, 3017, 'Cintas correctoras', 2, 2, 2, 1, 3),
(172, 3018, 'Clips metálicos', 21, 1, 2, 1, 3),
(173, 3019, 'Cintas para embalaje', 0, 2, 2, 1, 3),
(174, 3020, 'Tijeras', 19, 1, 2, 1, 3),
(175, 3021, 'Gomas de borrar', 37, 1, 2, 1, 3),
(176, 3022, 'Perforadoras de papel', 10, 1, 2, 1, 3),
(177, 3023, 'Agendas para anotaciones', 6, 1, 2, 3, 3),
(178, 3024, 'Sobres tamaño carta', 88, 2, 2, 1, 3),
(179, 3025, 'Carpetas plásticas', 1, 2, 2, 1, 3),
(180, 3026, 'Papel reciclado', 67, 2, 2, 1, 3),
(181, 3027, 'Marcadores de pizarra blanca', 45, 1, 2, 1, 3),
(182, 3028, 'Clips plásticos', 64, 1, 2, 1, 3),
(183, 3029, 'Cinta doble faz', 1, 2, 2, 1, 3),
(184, 3030, 'Sellos de goma', 25, 1, 2, 1, 3),
(187, 46575467, 'Cargador hp 107', 1, 1, 1, 1, 2),
(188, 1, 'camara informacion', 1, 2, 1, 1, 1),
(189, 333, 'cinca adeciva', 32, 1, 2, 1, 3),
(190, 3534543, 'Información de elementos', 1, 1, 1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_salidas`
--

CREATE TABLE `entradas_salidas` (
  `ent_sal_cod` int(11) NOT NULL,
  `ent_sal_cantidad` int(11) DEFAULT NULL,
  `ent_fech_registro` timestamp NULL DEFAULT NULL,
  `entr_tp_movmnt` int(11) DEFAULT NULL,
  `ent_id_usu` int(11) DEFAULT NULL,
  `ent_sal_cod_elemtn` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradas_salidas`
--

INSERT INTO `entradas_salidas` (`ent_sal_cod`, `ent_sal_cantidad`, `ent_fech_registro`, `entr_tp_movmnt`, `ent_id_usu`, `ent_sal_cod_elemtn`) VALUES
(51, 12, '2025-06-19 16:02:52', 2, 106, 171),
(52, 1, '2025-06-19 16:05:30', 2, 108, 171),
(53, 2, '2025-06-19 16:05:30', 2, 108, 182),
(54, 12, '2025-06-19 16:12:57', 2, 116, 171),
(55, 3, '2025-06-19 16:16:26', 2, 108, 161),
(56, 3, '2025-06-19 16:19:48', 2, 108, 183),
(57, 1, '2025-06-19 16:27:34', 2, 108, 183),
(58, 3, '2025-06-19 16:29:32', 2, 112, 171),
(59, 12, '2025-06-19 17:31:13', 2, 107, 172),
(60, 3, '2025-06-19 17:31:13', 2, 107, 182),
(61, 1, '2025-06-19 17:43:02', 2, 113, 171),
(62, 1, '2025-06-19 17:43:26', 2, 111, 159),
(63, 3, '2025-06-19 17:44:59', 2, 106, 171),
(64, 2, '2025-06-19 17:51:52', 2, 108, 171),
(65, 3, '2025-06-19 18:06:52', 2, 111, 159),
(66, 2, '2025-06-19 19:09:54', 2, 107, 159),
(67, 1, '2025-06-19 19:09:54', 2, 107, 164),
(68, 12, '2025-06-19 19:09:54', 2, 107, 172),
(69, 3, '2025-06-19 19:10:45', 2, 108, 165),
(70, 2, '2025-06-19 19:10:45', 2, 108, 172),
(71, 12, '2025-06-19 19:10:45', 2, 108, 175),
(72, 2, '2025-06-19 21:45:52', 2, 107, 172),
(73, 2, '2025-06-19 21:47:43', 2, 108, 161),
(74, 3, '2025-06-19 21:47:43', 2, 108, 177),
(75, 2, '2025-06-19 21:53:41', 2, 117, 172),
(76, 1, '2025-06-19 21:53:41', 2, 117, 174),
(77, 3, '2025-06-19 23:13:47', 2, 108, 171),
(78, 12, '2025-06-19 23:13:47', 2, 108, 182),
(79, 3, '2025-06-19 23:15:10', 2, 145, 172),
(80, 2, '2025-06-19 23:15:10', 2, 145, 175),
(81, 22, '2025-06-19 23:18:20', 2, 108, 172),
(82, 2, '2025-06-19 23:20:21', 2, 108, 172),
(83, 3, '2025-06-19 23:20:21', 2, 108, 175),
(84, 3, '2025-06-19 23:21:24', 2, 107, 182),
(85, 2, '2025-06-19 23:39:43', 2, 107, 159),
(86, 12, '2025-06-19 23:39:43', 2, 107, 175),
(87, 3, '2025-06-19 23:40:02', 2, 106, 172),
(88, 2, '2025-06-19 23:46:13', 2, 108, 161),
(89, 2, '2025-06-19 23:46:13', 2, 108, 175),
(90, 12, '2025-06-20 02:42:24', 2, 115, 168),
(91, 3, '2025-06-20 02:42:24', 2, 115, 180),
(92, 2, '2025-06-20 03:23:28', 2, 145, 175),
(93, 3, '2025-06-20 03:23:28', 2, 145, 182),
(94, 2, '2025-06-20 21:05:08', 2, 108, 155),
(95, 3, '2025-06-20 21:05:08', 2, 108, 162),
(96, 12, '2025-06-20 21:05:08', 2, 108, 182);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_elementos`
--

CREATE TABLE `estados_elementos` (
  `est_el_cod` int(11) NOT NULL,
  `est_nombre` varchar(30) NOT NULL,
  `est_descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_elementos`
--

INSERT INTO `estados_elementos` (`est_el_cod`, `est_nombre`, `est_descripcion`) VALUES
(1, 'Disponible', NULL),
(2, 'Mantenimiento', NULL),
(3, 'Prestado', NULL),
(4, 'Inhabilitado', NULL),
(5, 'Solicitado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_prestamos`
--

CREATE TABLE `estados_prestamos` (
  `es_pr_cod` int(11) NOT NULL,
  `es_pr_nombre` varchar(30) NOT NULL,
  `es_pr_descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_prestamos`
--

INSERT INTO `estados_prestamos` (`es_pr_cod`, `es_pr_nombre`, `es_pr_descripcion`) VALUES
(1, 'Validado', NULL),
(2, 'Rechazado', NULL),
(3, 'Por validar', NULL),
(4, 'Finalizado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_usuarios`
--

CREATE TABLE `estados_usuarios` (
  `est_id` int(11) NOT NULL,
  `est_nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_usuarios`
--

INSERT INTO `estados_usuarios` (`est_id`, `est_nombre`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `ma_id` int(11) NOT NULL,
  `ma_nombre` varchar(50) NOT NULL,
  `ma_descripcion` varchar(200) NOT NULL,
  `ma_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`ma_id`, `ma_nombre`, `ma_descripcion`, `ma_status`) VALUES
(13, 'canon', 'orem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium doloremque molestiae corrupti voluptates iste eum illo dignissimos, quia esse quasi quo ipsa officiis quod provident quis sint vitae', 1),
(15, 'Sony', 'o ipsa officiis quod provident quis sint vitae', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `per_id` int(11) NOT NULL,
  `per_funcion` varchar(22) DEFAULT NULL,
  `per_nmrbr_permiso` varchar(50) DEFAULT NULL,
  `per_modulo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`per_id`, `per_funcion`, `per_nmrbr_permiso`, `per_modulo`) VALUES
(1, 'SELECT', 'Ver información', 'USUARIOS'),
(2, 'UPDATE', 'Actualizar información', 'ELEMENTOS'),
(3, 'DELETE', 'Eliminar información', 'PRESTAMOS'),
(4, 'CREATE', 'Agregar información', 'CARGA MASIVA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `pres_cod` int(11) NOT NULL,
  `pres_fch_slcitud` datetime DEFAULT NULL,
  `pres_fch_reserva` date DEFAULT NULL,
  `pres_hor_inicio` time DEFAULT NULL,
  `pres_hor_fin` time DEFAULT NULL,
  `pres_fch_entrega` date DEFAULT NULL,
  `pres_observacion` text DEFAULT NULL,
  `pres_destino` varchar(30) DEFAULT NULL,
  `pres_estado` int(11) DEFAULT NULL,
  `tp_pres` int(11) DEFAULT NULL,
  `pres_rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`pres_cod`, `pres_fch_slcitud`, `pres_fch_reserva`, `pres_hor_inicio`, `pres_hor_fin`, `pres_fch_entrega`, `pres_observacion`, `pres_destino`, `pres_estado`, `tp_pres`, `pres_rol`) VALUES
(210, '2025-06-19 18:39:43', '2025-06-19', NULL, NULL, '2025-06-20', '', 'externo', 4, 2, 2),
(211, '2025-06-19 18:40:02', '2025-06-19', NULL, NULL, '2025-06-20', '', 'externo', 4, 2, 2),
(212, '2025-06-19 18:46:13', '2025-06-19', NULL, NULL, '0000-00-00', 'pruebas de informacion', 'externo', 4, 2, 2),
(213, '2025-06-19 21:42:24', '2025-06-19', NULL, NULL, '2025-06-20', 'Solicitud de elementos para el señor en el area de fotografía.', 'externo', 4, 2, 2),
(214, '2025-06-19 22:23:28', '2025-06-12', NULL, NULL, '2025-06-21', '', 'externo', 4, 2, 2),
(215, '2025-06-19 23:12:58', '2025-06-21', NULL, NULL, '2025-06-26', 'pruebas de solicitud, nos e que hacer aca.', 'centro', 4, 2, 4),
(216, '2025-06-19 23:14:13', '2025-06-21', NULL, NULL, '2025-06-21', 'asdasd', 'centro', 3, 2, 4),
(217, '2025-06-20 00:05:02', '2025-06-20', NULL, NULL, '2025-06-21', 'pruebas de solicitud, nos e que hacer aca.', 'centro', 3, 2, 4),
(218, '2025-06-20 15:00:52', '2025-06-21', NULL, NULL, '2025-06-20', 'Informaci[on del destino.', 'centro', 4, 2, 4),
(219, '2025-06-20 15:18:13', '2025-06-21', NULL, NULL, '2025-06-23', 'prueba de información.', 'centro', 3, 2, 4),
(220, '2025-06-20 16:05:08', '2025-06-20', NULL, NULL, '2025-06-21', '', 'externo', 1, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos_elementos`
--

CREATE TABLE `prestamos_elementos` (
  `pres_el_cod` int(11) NOT NULL,
  `pres_cod` int(11) DEFAULT NULL,
  `pres_el_usu_id` int(11) NOT NULL,
  `pres_el_elem_cod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos_elementos`
--

INSERT INTO `prestamos_elementos` (`pres_el_cod`, `pres_cod`, `pres_el_usu_id`, `pres_el_elem_cod`) VALUES
(422, NULL, 111, 127),
(423, NULL, 111, 129),
(424, NULL, 111, 125),
(425, NULL, 111, 128),
(426, NULL, 110, 126),
(427, NULL, 110, 132),
(428, NULL, 109, 130),
(429, NULL, 109, 134),
(430, NULL, 109, 125),
(431, NULL, 109, 128),
(432, NULL, 109, 125),
(433, NULL, 109, 128),
(434, NULL, 107, 128),
(435, NULL, 107, 131),
(436, NULL, 107, 134),
(437, NULL, 107, 159),
(438, NULL, 107, 164),
(439, NULL, 107, 172),
(440, NULL, 108, 130),
(441, NULL, 108, 137),
(442, NULL, 108, 143),
(443, NULL, 108, 145),
(444, NULL, 108, 165),
(445, NULL, 108, 172),
(446, NULL, 108, 175),
(447, NULL, 107, 130),
(448, NULL, 107, 134),
(449, NULL, 107, 140),
(450, NULL, 107, 144),
(451, NULL, 107, 172),
(452, NULL, 108, 129),
(453, NULL, 108, 132),
(454, NULL, 108, 134),
(455, NULL, 108, 161),
(456, NULL, 108, 177),
(457, NULL, 106, 129),
(458, NULL, 106, 132),
(459, NULL, 106, 134),
(460, NULL, 117, 131),
(461, NULL, 117, 147),
(462, NULL, 117, 150),
(463, NULL, 117, 172),
(464, NULL, 117, 174),
(465, NULL, 108, 128),
(466, NULL, 108, 130),
(467, NULL, 108, 134),
(468, NULL, 108, 171),
(469, NULL, 108, 182),
(470, NULL, 145, 127),
(471, NULL, 145, 131),
(472, NULL, 145, 134),
(473, NULL, 145, 172),
(474, NULL, 145, 175),
(475, NULL, 108, 130),
(476, NULL, 108, 133),
(477, NULL, 108, 129),
(478, NULL, 108, 133),
(479, NULL, 108, 134),
(480, NULL, 108, 172),
(481, NULL, 108, 129),
(482, NULL, 108, 133),
(483, NULL, 108, 134),
(484, NULL, 108, 172),
(485, NULL, 108, 175),
(486, NULL, 107, 127),
(487, NULL, 107, 132),
(488, NULL, 107, 182),
(489, 210, 107, 128),
(490, 210, 107, 130),
(491, 210, 107, 134),
(492, 210, 107, 159),
(493, 210, 107, 175),
(494, 211, 106, 132),
(495, 211, 106, 133),
(496, 211, 106, 137),
(497, 211, 106, 172),
(498, 212, 108, 141),
(499, 212, 108, 144),
(500, 212, 108, 150),
(501, 212, 108, 153),
(502, 212, 108, 161),
(503, 212, 108, 175),
(504, 213, 115, 125),
(505, 213, 115, 130),
(506, 213, 115, 168),
(507, 213, 115, 180),
(508, 214, 145, 128),
(509, 214, 145, 130),
(510, 214, 145, 134),
(511, 214, 145, 175),
(512, 214, 145, 182),
(513, 215, 145, 133),
(514, 215, 145, 170),
(515, 216, 145, 129),
(516, 216, 145, 141),
(517, 217, 145, 145),
(518, 217, 145, 153),
(519, 217, 145, 177),
(520, 218, 145, 149),
(521, 218, 145, 126),
(522, 219, 145, 130),
(523, 220, 108, 131),
(524, 220, 108, 135),
(525, 220, 108, 138),
(526, 220, 108, 155),
(527, 220, 108, 162),
(528, 220, 108, 182);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rl_id` int(11) NOT NULL,
  `rl_nombre` varchar(100) NOT NULL,
  `rl_descripcion` text DEFAULT NULL,
  `rl_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rl_id`, `rl_nombre`, `rl_descripcion`, `rl_status`) VALUES
(1, 'Almacenista', 'romque molestiae corrupti volupcing elit. Atque velit, cum, sed dolorem', 1),
(2, 'Administrador', '111', 1),
(3, 'SubDirector', '123123', 1),
(4, 'Instructor', '1412312', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `rlp_id` int(11) NOT NULL,
  `rlp_id_permiso` int(11) DEFAULT NULL,
  `rlp_id_rl` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_permisos`
--

INSERT INTO `roles_permisos` (`rlp_id`, `rlp_id_permiso`, `rlp_id_rl`) VALUES
(1, 1, 2),
(2, 3, 4),
(4, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `tp_id` int(11) NOT NULL,
  `tp_sigla` varchar(15) NOT NULL,
  `tp_nombre` varchar(15) DEFAULT NULL,
  `tp_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`tp_id`, `tp_sigla`, `tp_nombre`, `tp_status`) VALUES
(1, 'CC', 'Cédula de Ciuda', 1),
(2, 'CE', 'Cédula', 1),
(3, 'TI', 'Tarjeta de Iden', 1),
(4, 'PAS', 'Pasaporte', 1),
(5, 'RC', 'Registro Civil', 1),
(6, 'NIT', 'Número de Ident', 1),
(7, 'RUT', 'Registro Único ', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_elemento`
--

CREATE TABLE `tipo_elemento` (
  `tp_el_cod` int(11) NOT NULL,
  `tp_el_nombre` varchar(30) NOT NULL,
  `tp_el_descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_elemento`
--

INSERT INTO `tipo_elemento` (`tp_el_cod`, `tp_el_nombre`, `tp_el_descripcion`) VALUES
(1, 'Devolutivo', NULL),
(2, 'Consumible', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_movimiento`
--

CREATE TABLE `tipo_movimiento` (
  `cod_tp` int(11) NOT NULL,
  `cod_tp_nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_movimiento`
--

INSERT INTO `tipo_movimiento` (`cod_tp`, `cod_tp_nombre`) VALUES
(1, 'Entrada'),
(2, 'Salida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_prestamo`
--

CREATE TABLE `tipo_prestamo` (
  `tp_pre` int(11) NOT NULL,
  `tp_nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_prestamo`
--

INSERT INTO `tipo_prestamo` (`tp_pre`, `tp_nombre`) VALUES
(1, 'Solicitud'),
(2, 'Reserva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usu_id` int(11) NOT NULL,
  `usu_docum` int(11) NOT NULL,
  `usu_nombres` varchar(50) DEFAULT NULL,
  `usu_apellidos` varchar(50) DEFAULT NULL,
  `usu_password` varchar(200) DEFAULT NULL,
  `usu_email` varchar(50) DEFAULT NULL,
  `usu_direccion` varchar(100) DEFAULT NULL,
  `usu_telefono` varchar(50) DEFAULT NULL,
  `usu_id_estado` int(11) DEFAULT NULL,
  `usu_tp_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usu_id`, `usu_docum`, `usu_nombres`, `usu_apellidos`, `usu_password`, `usu_email`, `usu_direccion`, `usu_telefono`, `usu_id_estado`, `usu_tp_id`) VALUES
(106, 123, 'Jhon', 'doe', '$2y$10$AHMDSF3OgELz.5iF5KdhJ.trlu0aRFLhXYVuZpbKcj9NIYo1RuKzi', 'jhondoe@gmail.com', '', '123465', 1, 1),
(107, 100001, 'Juan', 'Pérez', '1234', 'juan.perez@example.com', 'Calle 1', '3000000001', 1, 1),
(108, 100002, 'María', 'López', 'abcd', 'maria.lopez@example.com', 'Calle 2', '3000000002', 1, 2),
(109, 100003, 'Alejandro', 'Cruz', 'pass123', 'pedro.gomez@example.com', 'Calle 3', '3000000003', 1, 1),
(110, 100004, 'Laura', 'Martínez', 'qwerty', 'laura.martinez@example.com', 'Calle 4', '3000000004', 1, 3),
(111, 100005, 'Carlos', 'Ruiz', '123456', 'carlos.ruiz@example.com', 'Calle 5', '3000000005', 1, 1),
(112, 100006, 'Ana', 'Fernández', 'hello', 'ana.fernandez@example.com', 'Calle 6', '3000000006', 1, 2),
(113, 100007, 'Luis', 'Sánchez', 'testpass', 'luis.sanchez@example.com', 'Calle 7', '3000000007', 1, 1),
(114, 100008, 'Sofía', 'Ramírez', '2024', 'sofia.ramirez@example.com', 'Calle 8', '3000000008', 1, 3),
(115, 100009, 'Miguel', 'Torres', 'contraseña', 'miguel.torres@example.com', 'Calle 9', '3000000009', 1, 1),
(116, 100010, 'Lucía', 'González', 'password', 'lucia.gonzalez@example.com', 'Calle 10', '3000000010', 1, 2),
(117, 100011, 'Jorge', 'Morales', 'letmein', 'jorge.morales@example.com', 'Calle 11', '3000000011', 1, 1),
(118, 100012, 'Elena', 'Castro', 'admin123', 'elena.castro@example.com', 'Calle 12', '3000000012', 1, 2),
(119, 100013, 'Andrés', 'Rojas', 'keypass', 'andres.rojas@example.com', 'Calle 13', '3000000013', 1, 3),
(120, 100014, 'Paula', 'Vega', '9999', 'paula.vega@example.com', 'Calle 14', '3000000014', 1, 1),
(121, 100015, 'Fernando', 'Silva', 'access', 'fernando.silva@example.com', 'Calle 15', '3000000015', 1, 1),
(122, 100016, 'Camila', 'Navarro', 'camila', 'camila.navarro@example.com', 'Calle 16', '3000000016', 1, 2),
(123, 100017, 'Ricardo', 'Mendoza', 'test123', 'ricardo.mendoza@example.com', 'Calle 17', '3000000017', 1, 3),
(124, 100018, 'Valentina', 'Cortés', 'mypwd', 'valentina.cortes@example.com', 'Calle 18', '3000000018', 1, 1),
(125, 100019, 'Daniel', 'Ortega', 'danielpass', 'daniel.ortega@example.com', 'Calle 19', '3000000019', 1, 2),
(126, 100020, 'Juliana', 'Herrera', 'juliana1', 'juliana.herrera@example.com', 'Calle 20', '3000000020', 1, 3),
(127, 100021, 'Alberto', 'García', 'abc123', 'alberto.garcia@example.com', 'Calle 21', '3000000021', 1, 1),
(128, 100022, 'Beatriz', 'Molina', 'passw0rd', 'beatriz.molina@example.com', 'Calle 22', '3000000022', 1, 2),
(129, 100023, 'Carlos', 'Paredes', 'letmein123', 'carlos.paredes@example.com', 'Calle 23', '3000000023', 1, 3),
(130, 100024, 'Diana', 'Ríos', 'mypassword', 'diana.rios@example.com', 'Calle 24', '3000000024', 1, 1),
(131, 100025, 'Esteban', 'Cruz', 'test2025', 'esteban.cruz@example.com', 'Calle 25', '3000000025', 1, 2),
(132, 100026, 'Florencia', 'Soto', 'florencia1', 'florencia.soto@example.com', 'Calle 26', '3000000026', 1, 3),
(133, 100027, 'Gabriel', 'Vargas', 'gabriel!', 'gabriel.vargas@example.com', 'Calle 27', '3000000027', 1, 1),
(134, 100028, 'Helena', 'Navarro', 'helena2025', 'helena.navarro@example.com', 'Calle 28', '3000000028', 1, 2),
(135, 100029, 'Ignacio', 'Mendoza', 'ignacio', 'ignacio.mendoza@example.com', 'Calle 29', '3000000029', 1, 3),
(136, 100030, 'Jimena', 'Lopez', 'jimena123', 'jimena.lopez@example.com', 'Calle 30', '3000000030', 1, 1),
(137, 555, 'alejandro', 'ceron', '$2y$10$zZDMorvOwpCJH5D6VvMb6ORv6IePjNCom6D3Prsq9pF57bR9eqr5i', 'lalejandrocd1@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', 1, 3),
(138, 123, 'alejandro', 'Pérez', '$2y$10$Kbs/gKo1R2DqeI/HL8N5Du8qIrcJYfkTdPiHrJK8iA8ZMsCP0SaoS', 'juan.perez@example.com', 'calle 2 d oeste # 74 e 02', '3000000001', 1, 3),
(145, 444, 'dato dummy', 'ceron', '$2y$10$.hmra.OAsqGED038f9bsOORL.cNnzCgGr.i/0xVEA.fuWukkUCSU6', 'lalejandrocd1@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', 1, 3),
(146, 0, 'dasdasd', 'ceron', '$2y$10$U5wJ2COSZ3aABWj.rOegwOv3aOf.1u44iQTlelHao123ggZHHAftK', 'lalejandrocd1@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', 1, 3),
(147, 500000, 'dasdasd', 'ceron', '$2y$10$xo.C3p25NfRxVyEf.HUbleet/pF.3R23vX0X9KVLBiiXgaAUUemoK', 'lalejandrocd1@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `usr_id` int(11) NOT NULL,
  `usr_usu_id` int(11) DEFAULT NULL,
  `usr_rl_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`usr_id`, `usr_usu_id`, `usr_rl_id`) VALUES
(1, 106, 2),
(1126, 107, 3),
(1127, 108, 4),
(1128, 109, 1),
(1129, 110, 3),
(1130, 111, 4),
(1131, 112, 1),
(1132, 113, 3),
(1133, 114, 4),
(1134, 115, 1),
(1135, 116, 3),
(1136, 117, 4),
(1137, 118, 1),
(1138, 119, 3),
(1139, 120, 4),
(1140, 121, 1),
(1141, 122, 3),
(1142, 123, 4),
(1143, 124, 1),
(1144, 125, 3),
(1145, 126, 4),
(1146, 127, 1),
(1147, 128, 3),
(1148, 129, 4),
(1149, 130, 1),
(1150, 131, 3),
(1151, 132, 4),
(1152, 133, 1),
(1153, 134, 3),
(1154, 135, 4),
(1155, 136, 1),
(1158, 138, 1),
(1165, 145, 4),
(1166, 146, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`ar_cod`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`btr_id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`ca_id`);

--
-- Indices de la tabla `elementos`
--
ALTER TABLE `elementos`
  ADD PRIMARY KEY (`elm_cod`),
  ADD KEY `fk_elm_cod_estado` (`elm_cod_estado`),
  ADD KEY `fk_elm_cod_tp_elemento` (`elm_cod_tp_elemento`),
  ADD KEY `fk_ar_cod` (`elm_area_cod`);

--
-- Indices de la tabla `entradas_salidas`
--
ALTER TABLE `entradas_salidas`
  ADD PRIMARY KEY (`ent_sal_cod`),
  ADD KEY `fk_ent_sal_cod_elemnt` (`ent_sal_cod_elemtn`),
  ADD KEY `entr_tp_movmnt` (`entr_tp_movmnt`),
  ADD KEY `ent_id_usu` (`ent_id_usu`);

--
-- Indices de la tabla `estados_elementos`
--
ALTER TABLE `estados_elementos`
  ADD PRIMARY KEY (`est_el_cod`);

--
-- Indices de la tabla `estados_prestamos`
--
ALTER TABLE `estados_prestamos`
  ADD PRIMARY KEY (`es_pr_cod`);

--
-- Indices de la tabla `estados_usuarios`
--
ALTER TABLE `estados_usuarios`
  ADD PRIMARY KEY (`est_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`ma_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`per_id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`pres_cod`),
  ADD KEY `fk_pres_estado` (`pres_estado`),
  ADD KEY `fk_pres_tipo` (`tp_pres`),
  ADD KEY `pres_rol` (`pres_rol`);

--
-- Indices de la tabla `prestamos_elementos`
--
ALTER TABLE `prestamos_elementos`
  ADD PRIMARY KEY (`pres_el_cod`),
  ADD KEY `fk_pres_usu_id` (`pres_el_usu_id`),
  ADD KEY `fk_pres_elm_cod` (`pres_el_elem_cod`),
  ADD KEY `pres_cod` (`pres_cod`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rl_id`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`rlp_id`),
  ADD KEY `fk_id_permiso` (`rlp_id_permiso`),
  ADD KEY `fk_id_rol` (`rlp_id_rl`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`tp_id`);

--
-- Indices de la tabla `tipo_elemento`
--
ALTER TABLE `tipo_elemento`
  ADD PRIMARY KEY (`tp_el_cod`);

--
-- Indices de la tabla `tipo_movimiento`
--
ALTER TABLE `tipo_movimiento`
  ADD PRIMARY KEY (`cod_tp`);

--
-- Indices de la tabla `tipo_prestamo`
--
ALTER TABLE `tipo_prestamo`
  ADD PRIMARY KEY (`tp_pre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usu_id`),
  ADD KEY `fk_usu_id_estado` (`usu_id_estado`),
  ADD KEY `fk_usu_tp_id` (`usu_tp_id`),
  ADD KEY `usu_docum` (`usu_docum`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD PRIMARY KEY (`usr_id`),
  ADD KEY `usr_rl_id` (`usr_rl_id`),
  ADD KEY `usr_usu_id` (`usr_usu_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `ar_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `btr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `elementos`
--
ALTER TABLE `elementos`
  MODIFY `elm_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT de la tabla `entradas_salidas`
--
ALTER TABLE `entradas_salidas`
  MODIFY `ent_sal_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `estados_elementos`
--
ALTER TABLE `estados_elementos`
  MODIFY `est_el_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados_prestamos`
--
ALTER TABLE `estados_prestamos`
  MODIFY `es_pr_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados_usuarios`
--
ALTER TABLE `estados_usuarios`
  MODIFY `est_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `ma_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `per_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `pres_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT de la tabla `prestamos_elementos`
--
ALTER TABLE `prestamos_elementos`
  MODIFY `pres_el_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=529;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  MODIFY `rlp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `tp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `tipo_elemento`
--
ALTER TABLE `tipo_elemento`
  MODIFY `tp_el_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_prestamo`
--
ALTER TABLE `tipo_prestamo`
  MODIFY `tp_pre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1168;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `elementos`
--
ALTER TABLE `elementos`
  ADD CONSTRAINT `fk_ar_cod` FOREIGN KEY (`elm_area_cod`) REFERENCES `areas` (`ar_cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_elm_cod_estado` FOREIGN KEY (`elm_cod_estado`) REFERENCES `estados_elementos` (`est_el_cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_elm_cod_tp_elemento` FOREIGN KEY (`elm_cod_tp_elemento`) REFERENCES `tipo_elemento` (`tp_el_cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas_salidas`
--
ALTER TABLE `entradas_salidas`
  ADD CONSTRAINT `fk_ent_sal_cod_elemnt` FOREIGN KEY (`ent_sal_cod_elemtn`) REFERENCES `elementos` (`elm_cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tp_mvto` FOREIGN KEY (`entr_tp_movmnt`) REFERENCES `tipo_movimiento` (`cod_tp`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tp_mvto_usuId` FOREIGN KEY (`ent_id_usu`) REFERENCES `usuarios` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `fk_pres_estado` FOREIGN KEY (`pres_estado`) REFERENCES `estados_prestamos` (`es_pr_cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pres_rol` FOREIGN KEY (`pres_rol`) REFERENCES `roles` (`rl_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pres_tipo` FOREIGN KEY (`tp_pres`) REFERENCES `tipo_prestamo` (`tp_pre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos_elementos`
--
ALTER TABLE `prestamos_elementos`
  ADD CONSTRAINT `fk_pres_elm_cod` FOREIGN KEY (`pres_el_elem_cod`) REFERENCES `elementos` (`elm_cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pres_usu_id` FOREIGN KEY (`pres_el_usu_id`) REFERENCES `usuarios` (`usu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamos_elementos_ibfk_1` FOREIGN KEY (`pres_cod`) REFERENCES `prestamos` (`pres_cod`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `fk_id_permiso` FOREIGN KEY (`rlp_id_permiso`) REFERENCES `permisos` (`per_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_rol` FOREIGN KEY (`rlp_id_rl`) REFERENCES `roles` (`rl_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usu_id_estado` FOREIGN KEY (`usu_id_estado`) REFERENCES `estados_usuarios` (`est_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usu_tp_id` FOREIGN KEY (`usu_tp_id`) REFERENCES `tipo_documento` (`tp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `fk_rl_id` FOREIGN KEY (`usr_rl_id`) REFERENCES `roles` (`rl_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usu_id` FOREIGN KEY (`usr_usu_id`) REFERENCES `usuarios` (`usu_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
