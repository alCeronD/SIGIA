-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-08-2025 a las 04:14:24
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
-- Base de datos: `sigia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `ar_cod` int(11) NOT NULL COMMENT 'Código primario del area',
  `ar_nombre` varchar(30) NOT NULL COMMENT 'Nombre del area',
  `ar_descripcion` varchar(300) DEFAULT NULL COMMENT 'Descripción del area',
  `ar_status` tinyint(1) NOT NULL COMMENT 'Estado del area, activo 1, Inactivo 0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que almacena los distintos departamentos asociados a los elementos de la central didáctica';

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`ar_cod`, `ar_nombre`, `ar_descripcion`, `ar_status`) VALUES
(1, 'Sonidos', '', 1),
(2, 'Luz', '', 1),
(3, 'General', '', 1),
(4, 'Fotografia', '', 1),
(5, 'Iluminación', '', 1),
(6, 'Cámaras', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `ca_id` int(11) NOT NULL COMMENT 'codigo de identificador de la categoria',
  `ca_nombre` varchar(50) NOT NULL COMMENT 'nombre de la categoria',
  `ca_descripcion` varchar(200) NOT NULL COMMENT 'descripción de la categoria',
  `ca_status` tinyint(1) NOT NULL COMMENT 'estado de la categoria, 1 activo, 0 inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que clasifica los elementos de la central didactica en diferentes categorías para su organización y control.';

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`ca_id`, `ca_nombre`, `ca_descripcion`, `ca_status`) VALUES
(1, 'Soporte', '', 1),
(2, 'Iluminación Fría', '', 1),
(3, 'Iluminación Cálida', '', 1),
(4, 'Video Cámara', '', 1),
(5, 'Cámaras', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `cod_compra` int(11) NOT NULL COMMENT 'codigo autoincrementable identificador de la existencia',
  `co_cod_elm` int(11) DEFAULT NULL COMMENT 'codigo del elemento asociado a la existencia modificada',
  `co_cantidad` int(12) DEFAULT NULL COMMENT 'cantidad registrada adicional',
  `co_tp_movimiento` int(11) DEFAULT NULL COMMENT 'tipo de movimiento, si es compra o reembolzo',
  `co_descripcion` varchar(300) DEFAULT NULL COMMENT 'descripción en caso de ser necesaria',
  `co_fecha_compra` datetime DEFAULT NULL COMMENT 'fecha de registro del proceso.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registra las compras y/o reembolsos de elementos, incluyendo cantidad, fecha y tipo de movimiento relacionado y código del elemento identificador';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elementos`
--

CREATE TABLE `elementos` (
  `elm_cod` int(11) NOT NULL COMMENT 'codigo primario autoincrementable',
  `elm_placa` int(11) DEFAULT NULL COMMENT 'placa identificadora del elemento',
  `elm_serie` varchar(40) DEFAULT NULL COMMENT 'serial interno identificador',
  `elm_nombre` varchar(100) NOT NULL COMMENT 'nombre del elemento',
  `elm_existencia` int(11) DEFAULT NULL COMMENT 'cantidad actual en el almacen',
  `elm_fecha_registro` date NOT NULL DEFAULT current_timestamp() COMMENT 'fecha en la cual se registro el elemento en la base de datos',
  `elm_sugerencia` varchar(100) DEFAULT NULL COMMENT 'campo de sugerencia en caso de que elemento requiera una anotación de su uso u algún otro elemento.',
  `elm_observacion` varchar(100) DEFAULT NULL COMMENT 'campo de observación en caso de ser necesraio su observación.',
  `elm_uni_medida` int(11) DEFAULT NULL COMMENT 'unidad de medida del elemento, galon, caja, unidad, entre otros.',
  `elm_cod_tp_elemento` int(11) DEFAULT NULL COMMENT 'tipo de elemento, devolutivo o consumible',
  `elm_cod_estado` int(11) DEFAULT NULL COMMENT 'estado actual del elemento, dependiendo de su id se define, los valores estan en la tabla estados_elementos',
  `elm_area_cod` int(11) DEFAULT NULL COMMENT 'area del elemento, sus valores relacionados con tabla areas.',
  `elm_ma_cod` int(11) DEFAULT NULL COMMENT 'marca del elemento, su valor relacional esta en la tabla marcas.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que almacena los elementos físicos, su identificación, estado, departamento e información relevante.';

--
-- Volcado de datos para la tabla `elementos`
--

INSERT INTO `elementos` (`elm_cod`, `elm_placa`, `elm_serie`, `elm_nombre`, `elm_existencia`, `elm_fecha_registro`, `elm_sugerencia`, `elm_observacion`, `elm_uni_medida`, `elm_cod_tp_elemento`, `elm_cod_estado`, `elm_area_cod`, `elm_ma_cod`) VALUES
(1, 922917451, '922917451', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'FALTA MANIVELA', 4, 1, 1, 6, 1),
(2, 92293396, '92293396', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'FALTA MANIVELA', 4, 1, 1, 6, 1),
(3, 922919603, '922919603', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'COMPLETO', 4, 1, 1, 6, 1),
(4, 922917452, '922917452', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'COMPLETO', 4, 1, 1, 6, 1),
(5, 107685076, '107685076', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'COMPLETO', 4, 1, 1, 6, 1),
(6, 92293395, '92293395', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'FALTA TORINILLO PARA LA PLATINA', 4, 1, 1, 6, 1),
(7, 922917453, '922917453', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'FALTA PLATINA', 4, 1, 1, 6, 1),
(8, 922917453, '922917453', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'FALTA PLATINA', 4, 1, 1, 6, 1),
(9, 92293397, '92293397', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'COMPLETO', 4, 1, 1, 6, 1),
(10, 100189225, '100189225', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'SIN CABEZAL (DEVOLVER)', 4, 1, 1, 6, 1),
(11, 100189226, '100189226', 'TRIPODE DE CABEZA FLUIDA', 1, '0000-00-00', 'LLEVAR LA PLATINA', 'FALTA SEGURO PARA PLATINA', 4, 1, 1, 6, 1),
(12, 922919586, '922919586-1', 'LUZ LED BICROMATICA', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTA MARIPOSA - FALTA CABLE DE PODER', 4, 1, 1, 5, 1),
(13, 922919586, '922919586-2', 'LUZ LED BICROMATICA', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTA DIFUSOR', 4, 1, 1, 5, 1),
(14, 922919586, '922919586-3', 'LUZ LED BICROMATICA', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'COMPLETO', 4, 1, 1, 5, 1),
(15, 922919586, '922919586-4', 'LUZ LED BICROMATICA', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTA MARIPOSA', 4, 1, 1, 5, 1),
(16, 922919587, '922919587-1', 'LUZ LED BICROMATICA', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'COMPLETO', 4, 1, 1, 5, 1),
(17, 922919587, '922919587-2', 'LUZ LED BICROMATICA', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'COMPLETO', 4, 1, 1, 5, 1),
(18, 922919587, '922919587-3', 'LUZ LED BICROMATICA', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTA CABLE', 4, 1, 1, 5, 1),
(19, 922919587, '922919587-4', 'LUZ LED BICROMATICA', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'COMPLETO', 4, 1, 1, 5, 1),
(20, 92293941, '92293941-1', 'SCOUP FLUORECENTE', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTAN 5 BOMBILLOS - FALTA MANILA', 4, 1, 1, 5, 1),
(21, 92293941, '92293941-2', 'SCOUP FLUORECENTE', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTAN 4 BOMBILLOS', 4, 1, 1, 5, 1),
(22, 92293941, '92293941-3', 'SCOUP FLUORECENTE', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTAN 4 BOMBILLOS', 4, 1, 1, 5, 1),
(23, 92293941, '92293941-4', 'SCOUP FLUORECENTE', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALLAN 2 PLAFONES', 4, 1, 1, 5, 1),
(24, 922917412, '922917412-1', 'SCOUP FLUORECENTE', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTAN 2 BOBILLOS', 4, 1, 1, 5, 1),
(25, 922917412, '922917412-2', 'SCOUP FLUORECENTE', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTA 1 BOMBILLO', 4, 1, 1, 5, 1),
(26, 922917413, '922917413-1', 'SCOUP FLUORECENTE', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'COMPLETO', 4, 1, 1, 5, 1),
(27, 922917413, '922917413-2', 'SCOUP FLUORECENTE', 1, '0000-00-00', 'REVISAR MALETIN CON CARGADOR Y LLEVAR TRIPODE DE LUZ', 'FALTAN 3 BOMBILLOS - DISFUSOR', 4, 1, 1, 5, 1),
(28, 922917402, '922917402', 'LUZ LED BLANCAS', 1, '0000-00-00', 'LLEVAR TRIPODE DE LUZ CÁLIDA Y CABLE DE PODER', 'COMPLETA', 4, 1, 1, 5, 1),
(29, 922917403, '922917403', 'LUZ LED BLANCAS', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'COMPLETA', 4, 1, 1, 5, 1),
(30, 922919588, '922919588-1', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'COMPLETA', 4, 1, 1, 5, 1),
(31, 922919589, '922919589-1', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'COMPLETA', 4, 1, 1, 5, 1),
(32, 922919589, '922919589-2', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'FALTA 1 BOMBILLO', 4, 1, 1, 5, 1),
(33, 922919589, '922919589-3', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'COMPLETA', 4, 1, 1, 5, 1),
(34, 922919590, '922919590-1', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'FALTA 1 BOMBILLO', 4, 1, 1, 5, 1),
(35, 922919590, '922919590-2', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'COMPLETA', 4, 1, 1, 5, 1),
(36, 922919590, '922919590-3', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'COMPLETO', 4, 1, 1, 5, 1),
(37, 922919588, '922919588-2', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'COMPLETO', 4, 1, 1, 5, 1),
(38, 922919588, '922919588-3', 'CARRY LIGHT', 1, '0000-00-00', 'LLEVAR CABLE DE PODER Y TRIPODE DE LUZ', 'COMPLETO', 4, 1, 1, 5, 1),
(39, 922917415, '922917415', 'PAR LED', 1, '0000-00-00', 'LLEVAR CABLE DE PODER', 'COMPLETA', 4, 1, 1, 5, 1),
(40, 922917416, '922917416', 'PAR LED', 1, '0000-00-00', 'LLEVAR CABLE DE PODER', 'COMPLETA', 4, 1, 1, 5, 1),
(41, 922917417, '922917417', 'PAR LED', 1, '0000-00-00', 'LLEVAR CABLE DE PODER', 'COMPLETA', 4, 1, 1, 5, 1),
(42, 922917404, '922917404', 'BASE/SOPORTE', 1, '0000-00-00', 'REVISAR EL CLAMP', 'FALTA MANIVELA', 4, 1, 1, 6, 1),
(43, 922919598, '922919598', 'BASE/SOPORTE', 1, '0000-00-00', 'REVISAR EL CLAMP', 'FALTA MANIVELA', 4, 1, 1, 6, 1),
(44, 922919599, '922919599', 'BASE/SOPORTE', 1, '0000-00-00', 'REVISAR EL CLAMP', 'COMPLETA', 4, 1, 1, 6, 1),
(45, 922919600, '922919600', 'BASE/SOPORTE', 1, '0000-00-00', 'REVISAR EL CLAMP', 'COMPLETA', 4, 1, 1, 6, 1),
(46, 9229191, '9229191', 'LUZ HMI', 1, '0000-00-00', 'DEBE LLEVAR REGULADOR', 'COMPLETA', 4, 1, 1, 5, 1),
(47, 9229192, '9229192', 'LUZ HMI', 1, '0000-00-00', 'DEBE LLEVAR REGULADOR', 'FALLA DEL BALASTRO', 4, 1, 1, 5, 1),
(48, 9229193, '9229193', 'LUZ HMI', 1, '0000-00-00', 'DEBE LLEVAR REGULADOR', 'FALTA BOMBILLO', 4, 1, 1, 5, 1),
(49, 9229194, '9229194', 'LUZ HMI', 1, '0000-00-00', 'DEBE LLEVAR REGULADOR', 'COMPLETA', 4, 1, 1, 5, 1),
(50, 92293286, '92293286', 'SMITH-VICTOR', 1, '0000-00-00', 'LLEVAR TRIPODE DE LUZ CÁLIDA Y CABLE DE PODER', 'FALTA BOMBILLO', 4, 1, 1, 5, 1),
(51, 92293287, '92293287', 'SMITH-VICTOR', 1, '0000-00-00', 'LLEVAR TRIPODE DE LUZ CÁLIDA Y CABLE DE PODER', 'FALTA BOMBILLO', 4, 1, 1, 5, 1),
(52, 922919535, '922919535', 'CÁMARA VIDEO PANASONIC', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(53, 922919536, '922919536', 'CÁMARA VIDEO PANASONIC', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(54, 922919537, '922919537', 'CÁMARA VIDEO PANASONIC', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(55, 922917450, '922917450', 'CÁMARA VIDEO SONY', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(56, 922919611, '922919611', 'CÁMARA VIDEO SONY', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(57, 922919539, '922919539', 'CÁMARA VIDEO SONY', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(58, 922919540, '922919540', 'CÁMARA VIDEO SONY', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(59, 922919542, '922919542', 'CÁMARA VIDEO SONY', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(60, 922917094, '922917094', 'CÁMARA FOTO CANON', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'FALTA CARGADOR', 4, 1, 1, 6, 1),
(61, 922917093, '922917093', 'CÁMARA FOTO CANON', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'FALTA CARGADOR', 4, 1, 1, 6, 1),
(62, 922917096, '922917096', 'CÁMARA FOTO NIKON', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'COMPLETO', 4, 1, 1, 6, 1),
(63, 92294487, '92294487', 'CÁMARA FOTO CANON', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'XS', 4, 1, 1, 6, 1),
(64, 92294488, '92294488', 'CÁMARA FOTO CANON', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'FALTA LENTE', 4, 1, 1, 6, 1),
(65, 92294489, '92294489', 'CÁMARA FOTO CANON', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'XS', 4, 1, 1, 6, 1),
(66, 1076144983, '1076144983', 'CÁMARA VIDEO SONY', 1, '0000-00-00', '', 'DEVOLUCION', 4, 1, 1, 6, 1),
(67, 93112759, '93112759', 'CÁMARA VIDEO SONY', 1, '0000-00-00', '', 'DEVOLUCION', 4, 1, 1, 6, 1),
(68, 92293393, '92293393', 'CÁMARA VIDEO DVCAM', 1, '0000-00-00', '', 'DEVOLUCION', 4, 1, 1, 6, 1),
(69, 92293392, '92293392', 'CÁMARA VIDEO DVCAM', 1, '0000-00-00', '', 'COMPLETA proyecto', 4, 1, 1, 6, 1),
(70, 92293394, '92293394', 'CÁMARA VIDEO DVCAM', 1, '0000-00-00', ' ', 'DEVOLUCION', 4, 1, 1, 6, 1),
(71, 92297900, '92297900', 'CÁMARA VIDEO PANASONIC', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'DEVOLUCION', 4, 1, 1, 6, 1),
(72, 92297898, '92297898', 'CÁMARA VIDEO PANASONIC', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'DEVOLUCION', 4, 1, 1, 6, 1),
(73, 92297899, '92297899', 'CÁMARA VIDEO PANASONIC', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'DEVOLUCION', 4, 1, 1, 6, 1),
(74, 100189107, '100189107', 'CÁMARA VIDEO PANASONIC (HANDICAM)', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'DEVOLUCION', 4, 1, 1, 6, 1),
(75, 100189081, '100189081', 'CÁMARA VIDEO PANASONIC (HANDICAM)', 1, '0000-00-00', 'RECUERDE LLEVAR SD ', 'DEVOLUCION', 4, 1, 1, 6, 1),
(76, 100120741, '100120741', 'CÁMARA VIDEO BETACAM', 1, '0000-00-00', ' ', 'COMPLETO proyecto', 4, 1, 1, 6, 1),
(77, 1076182658, '1076182658', 'CÁMARA VIDEO DVCAM', 1, '0000-00-00', ' ', 'COMPLETO proyecto', 4, 1, 1, 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_salidas`
--

CREATE TABLE `entradas_salidas` (
  `ent_sal_cod` int(11) NOT NULL COMMENT 'Código único de la entrada o salida',
  `ent_sal_cantidad` int(11) DEFAULT NULL COMMENT 'Cantidad de elementos en la transacción',
  `ent_fech_registro` timestamp NULL DEFAULT NULL COMMENT 'Fecha y hora de registro de la transacción',
  `ent_sal_observacion` text DEFAULT NULL COMMENT 'Observaciones adicionales sobre la entrada o salida',
  `entr_tp_movmnt` int(11) DEFAULT NULL COMMENT 'Tipo de movimiento (entrada o salida), clave foránea a tipo_movimiento',
  `ent_id_usu` int(11) DEFAULT NULL COMMENT 'ID del usuario que realizó el movimiento, clave foránea a usuarios',
  `ent_sal_cod_elemtn` int(11) DEFAULT NULL COMMENT 'Código del elemento involucrado, clave foránea a elementos',
  `ent_sal_cod_prestamo` int(11) DEFAULT NULL COMMENT 'Código del préstamo asociado, si aplica, clave foránea a prestamos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registra los movimientos de entrada y salida de elementos relacionados a los prestamos en la central didáctica';

--
-- Volcado de datos para la tabla `entradas_salidas`
--

INSERT INTO `entradas_salidas` (`ent_sal_cod`, `ent_sal_cantidad`, `ent_fech_registro`, `ent_sal_observacion`, `entr_tp_movmnt`, `ent_id_usu`, `ent_sal_cod_elemtn`, `ent_sal_cod_prestamo`) VALUES
(1, 1, '2025-08-12 02:05:57', 'información', 2, 109, 1, 612),
(2, 1, '2025-08-12 02:05:57', 'información', 2, 109, 12, 612),
(3, 1, '2025-08-12 02:05:57', 'información', 2, 109, 20, 612),
(4, 1, '2025-08-12 02:05:57', 'información', 2, 109, 28, 612),
(5, 1, '2025-08-12 02:05:57', 'información', 2, 109, 33, 612),
(6, 1, '2025-08-12 02:05:57', 'información', 2, 109, 40, 612),
(7, 1, '2025-08-12 02:05:57', 'información', 2, 109, 48, 612),
(8, 1, '2025-08-12 02:05:57', 'información', 2, 109, 52, 612);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_elementos`
--

CREATE TABLE `estados_elementos` (
  `est_el_cod` int(11) NOT NULL COMMENT 'Código único del estado del elemento',
  `est_nombre` varchar(30) NOT NULL COMMENT 'Nombre del estado del elemento (ej. Disponible, Mantenimiento, Prestado, Reservado).',
  `est_descripcion` varchar(100) DEFAULT NULL COMMENT 'Descripción detallada del estado del elemento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que almacena los diferentes estados posibles de los elementos del inventario';

--
-- Volcado de datos para la tabla `estados_elementos`
--

INSERT INTO `estados_elementos` (`est_el_cod`, `est_nombre`, `est_descripcion`) VALUES
(1, 'Disponible', NULL),
(2, 'Mantenimiento', NULL),
(3, 'Prestado', NULL),
(4, 'Inhabilitado', NULL),
(5, 'Reservado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_prestamos`
--

CREATE TABLE `estados_prestamos` (
  `es_pr_cod` int(11) NOT NULL COMMENT 'Código único del estado del préstamo',
  `es_pr_nombre` varchar(30) NOT NULL COMMENT 'Nombre del estado del préstamo (ej. Por validar, Cancelado, Finalizado, Validado)',
  `es_pr_descripcion` varchar(100) DEFAULT NULL COMMENT 'Descripción detallada del estado (ej. Préstamo aprobado y en curso, pendiente de validación, etc.)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que almacena los diferentes estados posibles de un préstamo (ej. Por validar, Cancelado, Finalizado, Validado)';

--
-- Volcado de datos para la tabla `estados_prestamos`
--

INSERT INTO `estados_prestamos` (`es_pr_cod`, `es_pr_nombre`, `es_pr_descripcion`) VALUES
(1, 'Validado', NULL),
(2, 'Rechazado', NULL),
(3, 'Por validar', NULL),
(4, 'Finalizado', NULL),
(5, 'Cancelado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_usuarios`
--

CREATE TABLE `estados_usuarios` (
  `est_id` int(11) NOT NULL COMMENT 'Código único del estado del usuario',
  `est_nombre` varchar(50) DEFAULT NULL COMMENT 'Nombre del estado del usuario (ej. Activo, Inactivo)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que define los posibles estados que puede tener un usuario dentro del sistema';

--
-- Volcado de datos para la tabla `estados_usuarios`
--

INSERT INTO `estados_usuarios` (`est_id`, `est_nombre`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones`
--

CREATE TABLE `funciones` (
  `id_funcion` int(11) NOT NULL COMMENT 'id representativo primario de la tabla funciones',
  `nombre_funcion` varchar(50) DEFAULT NULL COMMENT 'Nombre de la función del controlador.',
  `nombre_funcion_user` varchar(32) DEFAULT NULL COMMENT 'Nombre de la función amigable para el usuario.',
  `id_modulo` int(11) DEFAULT NULL COMMENT 'Modulo al que pertenece la función.',
  `tp_funcion` int(11) DEFAULT NULL COMMENT 'Tipo de la función siendo render para visualizar vistas o logic de solo lógica.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funciones`
--

INSERT INTO `funciones` (`id_funcion`, `nombre_funcion`, `nombre_funcion_user`, `id_modulo`, `tp_funcion`) VALUES
(22, 'dashboard', 'Vista Principal', 8, 1),
(23, 'renderViewArea', 'Consultar Departamentos', 2, 1),
(24, 'renderViewTp', 'Consultar Tipos documento', 2, 1),
(25, 'renderViewMarca', 'Consultar Marcas', 2, 1),
(27, 'updateRow', 'Actualizar ', 2, 2),
(28, 'deleteRow', 'Inhabilitar', 2, 2),
(29, 'addRow', 'Insertar', 2, 2),
(31, 'consultCategoriasView', 'Ver Categorias', 10, 1),
(34, 'createCategoria', 'Insertar', 10, 2),
(35, 'updateCategoria', 'Actualizar', 10, 2),
(36, 'deleteCategoria', 'Inhabilitar', 10, 2),
(46, 'renderViewElements', 'Consultar Elementos', 3, 1),
(50, 'addElement', 'Insertar', 3, 2),
(52, 'editarElemento', 'Actualizar', 3, 2),
(53, 'cambiarEstadoElemento', 'Inhabilitar', 3, 2),
(54, 'editarExistencia', 'Actualizar Existencia', 3, 2),
(59, 'genReporteView', 'Ver Reportes', 4, 1),
(64, 'generarReporteExcel', 'Generar Reporte Individual', 4, 2),
(65, 'generarReporteTrazabilidad', 'Generar Reporte Entrada Salida', 4, 2),
(66, 'generarReportePorPlaca', 'Generar Reporte Elementos', 4, 2),
(67, 'reservaView', 'Registrar Reservas ', 5, 1),
(68, 'consultaReservaView', 'Consultar Reservas ', 5, 1),
(72, 'setReserva', 'Registrar (Acción)', 5, 2),
(73, 'setSolicitud', 'Validar Solicitudes(Acción)', 5, 2),
(74, 'setEndReserva', 'Validar Devoluciones(Acción)', 5, 2),
(77, 'mostrarRoles', 'Ver Roles', 7, 1),
(78, 'registrarRol', 'Registrar', 7, 2),
(79, 'editarRol', 'Actualizar', 7, 2),
(80, 'statusRoles', 'Inhabilitar', 7, 2),
(82, 'registrarPrestamosView', 'Registrar Solicitudes', 6, 1),
(83, 'consultarPrestamosView', 'Consultar Solicitudes', 6, 1),
(84, 'registrarPrestamo', 'Registrar', 6, 2),
(85, 'verDetallePrestamo', 'Ver Detalle', 6, 2),
(87, 'cancelarPrestamo', 'Inhabilitar', 6, 2),
(88, 'userView', 'Vista Crear Usuario', 1, 1),
(89, 'createUser', 'Agregar Usuarios', 1, 2),
(90, 'consultUser', 'Consultar Usuarios', 1, 1),
(91, 'updateUserJSON', 'Actualizar Usuarios', 1, 2),
(94, 'cambiarEstadoUsuarioJSON', 'Inhabilitar Usuario', 1, 2),
(95, 'actualizarDatosView', 'Visualizar Datos Personales', 1, 1),
(96, 'updateUserInfo', 'Actualizar Datos Personales', 1, 2),
(97, 'assingRoles', 'Asignar Roles', 7, 2),
(98, 'setPermisos', 'Establecer Permisos', 7, 2),
(99, 'filtrarElementosAjax', 'Filter', 4, 2),
(100, 'filtrarTrazabilidadAjax', 'Filtrar Entradas Y Salidas', 4, 2),
(101, 'filtrarPorPlacaAjax', 'Filtrar Por Placa', 4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `ma_id` int(11) NOT NULL COMMENT 'Identificador único de la marca',
  `ma_nombre` varchar(50) NOT NULL COMMENT 'Nombre de la marca',
  `ma_descripcion` varchar(200) NOT NULL COMMENT 'Descripción detallada de la marca',
  `ma_status` tinyint(1) NOT NULL COMMENT 'Estado de la marca, 1 activo, 0 inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que almacena las marcas asociadas a los elementos de la central didáctica';

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`ma_id`, `ma_nombre`, `ma_descripcion`, `ma_status`) VALUES
(1, 'No aplica', 'Elemento sin marca definida', 1),
(2, 'Canon', '', 1),
(3, 'Sony', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_m` int(11) NOT NULL,
  `cod_nombre_m` varchar(30) NOT NULL,
  `icono` varchar(30) DEFAULT NULL,
  `cod_descript` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_m`, `cod_nombre_m`, `icono`, `cod_descript`) VALUES
(1, 'usuarios', 'person', ''),
(2, 'configModules', 'settings', ''),
(3, 'elementos', 'local_see', ''),
(4, 'reportes', 'bar_chart', ''),
(5, 'reservaPrestamos', 'assignment', ''),
(6, 'solicitudPrestamos', 'storage', ''),
(7, 'Roles', 'supervisor_account', ''),
(8, 'dashboard', 'home', ''),
(10, 'Categorias', 'widgets', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `per_id` int(11) NOT NULL COMMENT 'Identificador único del permiso',
  `per_funcion` varchar(22) DEFAULT NULL COMMENT 'Nombre de la función específica del permiso',
  `per_nmrbr_permiso` varchar(50) DEFAULT NULL COMMENT 'Nombre completo o descriptivo del permiso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Define los permisos disponibles en el sistema según funciones y módulos';

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`per_id`, `per_funcion`, `per_nmrbr_permiso`) VALUES
(1, 'SELECT', 'Ver información'),
(2, 'UPDATE', 'Actualizar información'),
(3, 'DELETE', 'Eliminar información'),
(4, 'CREATE', 'Agregar información');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `pres_cod` int(11) NOT NULL COMMENT 'Código identificador del préstamo',
  `pres_fch_slcitud` datetime DEFAULT NULL COMMENT 'Fecha y hora en que se registra la reserva',
  `pres_fch_reserva` date DEFAULT NULL COMMENT 'Fecha programada para la reserva',
  `pres_hor_inicio` time DEFAULT NULL COMMENT 'Hora de inicio de la reserva',
  `pres_hor_fin` time DEFAULT NULL COMMENT 'Hora de finalización de la reserva',
  `pres_fch_entrega` date DEFAULT NULL COMMENT 'Fecha de entrega devolución de los elementos',
  `pres_observacion` text DEFAULT NULL COMMENT 'Observaciones asociadas al préstamo',
  `pres_destino` varchar(30) DEFAULT NULL COMMENT 'Destino o propósito del préstamo',
  `pres_estado` int(11) DEFAULT NULL COMMENT 'Estado actual del préstamo, clave foránea a estados_prestamos',
  `tp_pres` int(11) DEFAULT NULL COMMENT 'Tipo de préstamo, clave foránea a tipo_prestamo',
  `pres_rol` int(11) DEFAULT NULL COMMENT 'Rol del usuario que realiza la solicitud'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registra las reservas previas e inmediatas de préstamo de elementos';

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`pres_cod`, `pres_fch_slcitud`, `pres_fch_reserva`, `pres_hor_inicio`, `pres_hor_fin`, `pres_fch_entrega`, `pres_observacion`, `pres_destino`, `pres_estado`, `tp_pres`, `pres_rol`) VALUES
(612, '2025-08-11 21:05:57', '2025-08-12', NULL, NULL, '2025-08-15', 'información', 'Calle 9 #23 -35 ', 3, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos_elementos`
--

CREATE TABLE `prestamos_elementos` (
  `pres_el_cod` int(11) NOT NULL COMMENT 'Código único del registro de préstamo de elemento',
  `pres_cod` int(11) DEFAULT NULL COMMENT 'Código de reserva asociada',
  `pres_el_usu_id` int(11) NOT NULL COMMENT 'ID del usuario que registra la reserva del elemento',
  `pres_el_elem_cod` int(11) DEFAULT NULL COMMENT 'Código de reserva asociada',
  `pres_el_cantidad` int(11) NOT NULL COMMENT 'Cantidad de elementos reservados'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relaciona los elementos involucrados en cada reserva y sus cantidades';

--
-- Volcado de datos para la tabla `prestamos_elementos`
--

INSERT INTO `prestamos_elementos` (`pres_el_cod`, `pres_cod`, `pres_el_usu_id`, `pres_el_elem_cod`, `pres_el_cantidad`) VALUES
(1805, 612, 109, 1, 1),
(1806, 612, 109, 12, 1),
(1807, 612, 109, 20, 1),
(1808, 612, 109, 28, 1),
(1809, 612, 109, 33, 1),
(1810, 612, 109, 40, 1),
(1811, 612, 109, 48, 1),
(1812, 612, 109, 52, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rl_id` int(11) NOT NULL COMMENT 'Identificador único del rol',
  `rl_nombre` varchar(100) NOT NULL COMMENT 'Nombre del rol',
  `rl_descripcion` text DEFAULT NULL COMMENT 'Descripción del rol',
  `rl_status` tinyint(1) NOT NULL COMMENT 'Estado del rol, 1 activo, 0 inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que define los roles de usuario dentro del sistema';

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rl_id`, `rl_nombre`, `rl_descripcion`, `rl_status`) VALUES
(1, 'Almacenista', 'Almacenista', 1),
(2, 'Administrador', 'Nuevo administrador .', 1),
(4, 'Instructor', '', 1),
(12, 'Aprendiz', 'Solo puede acceder a este bloque.', 1),
(16, 'Coordinador', '', 1),
(20, 'Pruebas', 'Rol de prueba para comprender el comportamiento de los modulos según su caso.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_funciones`
--

CREATE TABLE `roles_funciones` (
  `rlp_id` int(11) NOT NULL COMMENT 'Identificador único de la relación rol-permiso',
  `rlp_id_rl` int(11) DEFAULT NULL COMMENT 'ID identificador del rol',
  `rlp_id_funcion` int(11) DEFAULT NULL COMMENT 'ID identificador de la función.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relaciona roles del sistema con los permisos que les corresponden';

--
-- Volcado de datos para la tabla `roles_funciones`
--

INSERT INTO `roles_funciones` (`rlp_id`, `rlp_id_rl`, `rlp_id_funcion`) VALUES
(145, 2, 22),
(146, 4, 22),
(147, 2, 77),
(150, 4, 82),
(152, 4, 84),
(153, 4, 85),
(154, 4, 87),
(155, 4, 83),
(156, 4, 95),
(157, 4, 96),
(165, 16, 22),
(173, 2, 97),
(186, 16, 59),
(187, 16, 64),
(188, 16, 65),
(189, 16, 66),
(190, 16, 95),
(191, 16, 96),
(192, 16, 99),
(193, 16, 100),
(194, 16, 101),
(203, 2, 46),
(204, 2, 50),
(205, 2, 52),
(206, 2, 53),
(207, 2, 54),
(213, 2, 78),
(214, 2, 79),
(215, 2, 80),
(221, 2, 98),
(222, 2, 88),
(223, 2, 89),
(224, 2, 90),
(225, 2, 91),
(226, 2, 94),
(227, 2, 95),
(228, 2, 96),
(229, 2, 23),
(230, 2, 24),
(231, 2, 25),
(232, 2, 31),
(233, 2, 34),
(234, 2, 35),
(235, 2, 36),
(240, 2, 29),
(241, 2, 27),
(242, 2, 28),
(253, 12, 22),
(254, 12, 82),
(255, 12, 83),
(256, 12, 84),
(257, 12, 85),
(258, 12, 87),
(259, 12, 95),
(262, 20, 22),
(263, 2, 100),
(264, 2, 101),
(265, 2, 59),
(266, 2, 64),
(267, 2, 65),
(268, 2, 66),
(269, 2, 99),
(280, 2, 67),
(281, 2, 68),
(282, 2, 72),
(283, 2, 73),
(284, 2, 74),
(285, 2, 82),
(286, 2, 83),
(287, 2, 84),
(288, 2, 85),
(289, 2, 87);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `tp_id` int(11) NOT NULL COMMENT 'Identificador único del tipo de documento',
  `tp_sigla` varchar(15) NOT NULL COMMENT 'Sigla del tipo de documento (ej. CC, TI, CE)',
  `tp_nombre` varchar(100) DEFAULT NULL COMMENT 'Nombre completo del tipo de documento',
  `tp_status` tinyint(1) NOT NULL COMMENT 'Estado del tipo de documento, 1 activo, 0 inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Define los tipos de documentos válidos para los usuarios';

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`tp_id`, `tp_sigla`, `tp_nombre`, `tp_status`) VALUES
(1, 'CC', 'Cédula de Ciudania', 1),
(2, 'CE', 'Cédulas', 1),
(3, 'TI', 'Tarjeta de Iden', 1),
(4, 'PAS', 'Pasaporte', 1),
(5, 'RC', 'Registro Civil', 1),
(20, 'NIT', 'Número De Ident', 1),
(21, 'CC DIG', 'Cedula de ciudadanía digital.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_elemento`
--

CREATE TABLE `tipo_elemento` (
  `tp_el_cod` int(11) NOT NULL COMMENT 'Identificador único del tipo de elemento',
  `tp_el_nombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de elemento',
  `tp_el_descripcion` varchar(100) DEFAULT NULL COMMENT 'Descripción del tipo de elemento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene los diferentes tipos de elementos del inventario (devolutivo, consumible)';

--
-- Volcado de datos para la tabla `tipo_elemento`
--

INSERT INTO `tipo_elemento` (`tp_el_cod`, `tp_el_nombre`, `tp_el_descripcion`) VALUES
(1, 'Devolutivo', NULL),
(2, 'Consumible', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_funcion`
--

CREATE TABLE `tipo_funcion` (
  `id_tp_funcion` int(11) NOT NULL COMMENT 'Valor auto increment del tipo de función',
  `nombre_tp_funcion` varchar(50) DEFAULT NULL COMMENT 'Nombre de la función para su clasificación.',
  `desc_tp_funcion` varchar(50) DEFAULT NULL COMMENT 'Tipo de la función para clasificar cual función que hace, una siendo lógica y otra de render vista.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_funcion`
--

INSERT INTO `tipo_funcion` (`id_tp_funcion`, `nombre_tp_funcion`, `desc_tp_funcion`) VALUES
(1, 'Render', 'Funcion de tipo renderizado que permite renderizar'),
(2, 'Logica', 'Función de tipo lógica, no muestra ninguna vista.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_movimiento`
--

CREATE TABLE `tipo_movimiento` (
  `cod_tp` int(11) NOT NULL COMMENT 'Identificador único del tipo de movimiento',
  `cod_tp_nombre` varchar(20) DEFAULT NULL COMMENT 'Nombre del tipo de movimiento (ej. Entrada, Salida, Compra)',
  `cod_tp_descrip` varchar(200) DEFAULT NULL COMMENT 'Descripción detallada del tipo de movimiento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene los diferentes tipos de movimientos que pueden realizarse sobre los elementos, como entrada, salida, deposito, por validar salida, entre otros';

--
-- Volcado de datos para la tabla `tipo_movimiento`
--

INSERT INTO `tipo_movimiento` (`cod_tp`, `cod_tp_nombre`, `cod_tp_descrip`) VALUES
(1, 'Deposito', 'Tipo de movimiento en el cual se agrega una existencia adicional al elemento'),
(2, 'Salida', NULL),
(3, 'Por validar salida', NULL),
(4, 'Entrada', NULL),
(5, 'Regresión', 'tipo de movimiento que permite devolver aquellas existencias.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_prestamo`
--

CREATE TABLE `tipo_prestamo` (
  `tp_pre` int(11) NOT NULL COMMENT 'Identificador único del tipo de prestamo',
  `tp_nombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de préstamo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Define las diferentes modalidades de préstamo (reserva inmediata, reserva previa)';

--
-- Volcado de datos para la tabla `tipo_prestamo`
--

INSERT INTO `tipo_prestamo` (`tp_pre`, `tp_nombre`) VALUES
(1, 'Reserva Inmediata'),
(2, 'Reserva Previa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_unidad`
--

CREATE TABLE `tipo_unidad` (
  `cod_tp_uni` int(11) NOT NULL COMMENT 'Código único de la unidad de medida',
  `nombre_tp_uni` varchar(20) NOT NULL COMMENT 'Nombre de la unidad de medida',
  `descrip_tp_uni` varchar(100) NOT NULL COMMENT 'Descripción de la unidad de medida'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Define las unidades de medida para los elementos (unidad, caja, galón, etc.)';

--
-- Volcado de datos para la tabla `tipo_unidad`
--

INSERT INTO `tipo_unidad` (`cod_tp_uni`, `nombre_tp_uni`, `descrip_tp_uni`) VALUES
(1, 'Unidad', 'Clasificado como undidad de elemento.'),
(2, 'Caja', 'Elementos que dentro de su caja contienen las respectivas unidades.'),
(3, 'Galon', ''),
(4, 'No aplica', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usu_id` int(11) NOT NULL COMMENT 'Identificador único del usuario',
  `usu_docum` int(11) NOT NULL COMMENT 'Número de documento de identidad del usuario',
  `usu_nombres` varchar(50) DEFAULT NULL COMMENT 'Nombres del usuario',
  `usu_apellidos` varchar(50) DEFAULT NULL COMMENT 'Apellidos del usuario',
  `usu_password` varchar(200) DEFAULT NULL COMMENT 'Contraseña encriptada del usuario',
  `usu_email` varchar(50) DEFAULT NULL COMMENT 'Correo electrónico del usuario',
  `usu_direccion` varchar(100) DEFAULT NULL COMMENT 'Dirección de residencia del usuario',
  `usu_telefono` varchar(50) DEFAULT NULL COMMENT 'Número de teléfono del usuario',
  `usu_observacion` varchar(100) DEFAULT NULL COMMENT 'Observación del usuario en caso de ser requerido.',
  `usu_id_estado` int(11) DEFAULT NULL COMMENT 'Estado del usuario, clave foránea a estados_usuarios',
  `usu_tp_id` int(11) DEFAULT NULL COMMENT 'Tipo de documento del usuario, clave foránea a tipo_documento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la información personal y de contacto de los usuarios del sistema';

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usu_id`, `usu_docum`, `usu_nombres`, `usu_apellidos`, `usu_password`, `usu_email`, `usu_direccion`, `usu_telefono`, `usu_observacion`, `usu_id_estado`, `usu_tp_id`) VALUES
(106, 123, 'Jhon', 'doe nuevo', '$2y$10$lqtlzfSwmgBii6QKERQjk.Q0pDEZ3E47UehNQQiacUJKHOkRMle0q', 'jhondoe@gmail.com', 'Calle 2', '4444122', NULL, 2, 1),
(107, 100001, 'Juan', 'Pérez', '1234', 'juan.perez@example.com', 'Calle 1', '3000000001', NULL, 1, 1),
(108, 100002, 'María', 'López', '$2y$10$T.fGRHivXg7ytOibxiSMyOGvB3rvQ6Nq4z2IiWuilXYFZCJSe6Fwm', 'maria.lopez@example.com', 'Calle 2', '3000000002', NULL, 1, 2),
(109, 100003, 'Alejandro', 'Rojas', 'pass123', 'rjAlejandrocd@gmail.com', 'Av 32 N # 83 - 103', '3000000003', NULL, 1, 1),
(110, 100004, 'Laura', 'Martínez', 'qwerty', 'laura.martinez@example.com', 'Calle 4', '3000000004', NULL, 1, 3),
(111, 100005, 'Carlos', 'Ruiz', '123456', 'carlos.ruiz@example.com', 'Calle 5', '3000000005', NULL, 1, 1),
(112, 100006, 'Ana', 'Fernández', 'hello', 'ana.Isa@gmail.com', 'Calle 6', '3000000006', NULL, 1, 2),
(113, 100007, 'Luis', 'Sánchez', 'testpass', 'luis.sanchez@example.com', 'Calle 7', '3000000007', NULL, 1, 1),
(114, 100008, 'Sofía', 'Ramírez', '2024', 'sofia.ramirez@example.com', 'Calle 8', '3000000008', NULL, 1, 3),
(115, 100009, 'Miguel', 'Torres', 'contraseña', 'miguel.torres@example.com', 'Calle 9', '3000000009', NULL, 2, 1),
(116, 100010, 'Lucía', 'González', 'password', 'lucia.gonzalez@example.com', 'Calle 10', '3000000010', NULL, 1, 2),
(117, 100011, 'Jorge', 'Morales', 'letmein', 'jorge.morales@example.com', 'Calle 11', '3000000011', NULL, 1, 1),
(118, 100012, 'Elena', 'Castro', 'admin123', 'elena.castro@example.com', 'Calle 12', '3000000012', NULL, 2, 2),
(119, 100013, 'Andrés', 'Rojas', 'keypass', '2_8@gmail.com', 'Calle 13', '3000000013', NULL, 1, 3),
(120, 100014, 'Paula', 'Vega', '9999', 'paula.vega@example.com', 'Calle 14', '3000000014', NULL, 1, 1),
(121, 100015, 'Fernando', 'Silva', 'access', 'fernando.silva@example.com', 'Calle 15', '3000000015', NULL, 2, 1),
(122, 100016, 'Camila', 'Navarro', 'camila', 'camila.navarro@example.com', 'Calle 16', '3000000016', NULL, 1, 2),
(123, 100017, 'Ricardo', 'Mendoza', 'test123', 'ricardo.mendoza@example.com', 'Calle 17', '3000000017', NULL, 2, 3),
(124, 100018, 'Valentina', 'Cortés', '$2y$10$tlqIP2iHW4mtaddKPm9z/OwnJwTF.rWSkVbvejn0IAjtdzPgisENa', 'valentina.cortes@example.com', 'Calle 18', '3000000018', NULL, 1, 1),
(125, 100019, 'Daniel', 'Ortega', 'danielpass', 'daniel.ortega@example.com', 'Calle 19', '3000000019', NULL, 1, 2),
(126, 100020, 'Juliana', 'Herrera', 'juliana1', 'juliana.herrera@example.com', 'Calle 20', '3000000020', NULL, 1, 3),
(127, 100021, 'Alberto', 'García', 'abc123', 'alberto.garcia@example.com', 'Calle 21', '3000000021', NULL, 2, 1),
(128, 100022, 'Beatriz', 'Molina', 'passw0rd', 'beatriz.molina@example.com', 'Calle 22', '3000000022', NULL, 1, 2),
(129, 100023, 'Carlos', 'Paredes', 'letmein123', 'carlos.paredes@example.com', 'Calle 23', '3000000023', NULL, 1, 3),
(130, 100024, 'Diana', 'Ríos', 'mypassword', 'diana.rios@example.com', 'Calle 24', '3000000024', NULL, 2, 1),
(131, 100025, 'Esteban', 'Cruz', 'test2025', 'esteban.cruz@example.com', 'Calle 25', '3000000025', NULL, 1, 2),
(132, 100026, 'Florencia', 'Soto', 'florencia1', 'florencia.soto@example.com', 'Calle 26', '3000000026', NULL, 1, 3),
(133, 100027, 'Gabriel', 'Vargas', '$2y$10$fJWee3EyFDrZ0U.xVhEdZ.dtu0TNdB8u1l6lbOUw2jl05WYSk/pTm', 'gabriel.vargas@example.com', 'Calle 27', '3000000027', NULL, 1, 1),
(134, 100028, 'Helena', 'Navarro', 'helena2025', 'helena.navarro@example.com', 'Calle 28', '3000000028', NULL, 1, 2),
(135, 100029, 'Ignacio', 'Mendoza', 'ignacio', 'ignacio.mendoza@example.com', 'Calle 29', '3000000029', NULL, 1, 3),
(136, 100030, 'Jimena', 'Lopez Pumarejo', 'jimena123', 'jimena.lopez@gmail.com', 'Calle 93 B # 13-03', '3000005230', NULL, 1, 1),
(137, 555, 'alejandro', 'ceron', '$2y$10$zZDMorvOwpCJH5D6VvMb6ORv6IePjNCom6D3Prsq9pF57bR9eqr5i', '2_1@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', NULL, 1, 3),
(138, 100000, 'alejandro', 'Pérez', '$2y$10$Kbs/gKo1R2DqeI/HL8N5Du8qIrcJYfkTdPiHrJK8iA8ZMsCP0SaoS', 'juan.perez@example.com', 'calle 2 d oeste # 74 e 02', '3000000001', NULL, 1, 3),
(147, 500000, 'dasdasd', 'ceron', '$2y$10$xo.C3p25NfRxVyEf.HUbleet/pF.3R23vX0X9KVLBiiXgaAUUemoK', '3_2@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', NULL, 1, 3),
(148, 29114652, 'alejandro', 'ceron', '$2y$10$GO3TlYxTUJgVnIXNYDqTiu.Homl29S7YcuErpTKlIeU1z53W17RBG', '2_3@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', NULL, 1, 3),
(149, 1107528994, 'Luis Alberto Pozada', 'Gutierrez Brown', '$2y$10$bIQddMZHOJu4sNQ0RlArg.3KxrLkngGv5G57pg6Q8MdJreCuYsS9S', 'luisAl.gz@gmail.com', 'calle 73 #32 -321', '3226855437', NULL, 1, 3),
(150, 1193439741, 'Edward', 'Fernandez', '$2y$10$/tXlxO3K4WQieMTze0gcAuQXfFsSBSs89JOuAtAVzwCfFN7T8Qese', 'edwardFer@gmail.com', 'Calle 93 B # 13-03', '5536735', NULL, 1, 3),
(151, 55555555, 'Faker', 'Human', '$2y$10$HnjmFwEfXqtQZaC3vzBo3..vTN73qtuvLi.WFvj.DMRV9UKwYAPXS', '5_4@gmail.com', 'calle 2 d oeste # 74 e 02', '44345345', NULL, 1, 3),
(152, 658234, 'Eric', 'Carman', '$2y$10$FCZPGmrhUeb2z5CmCfP5fO0vcwFJPzrx5OmUTZsz3zpK4M2suviFu', 'carman@gmail.com', 'Calle 93 B # 13-03', '53455432452', NULL, 1, 3),
(153, 523434, 'patricia', 'gonzales', '$2y$10$YaMgoRG8a59lMgSYmhUwde8Dj5gKf.58RjMnGGPoI3geuy4H/CHEC', 'patric@gmail.com', 'Calle 93 B # 13-03', '53455432452', NULL, 1, 3),
(154, 23582394, 'Alexander', 'gonzales', '$2y$10$WLJU12ALtQbJGFxirSNGaOJqCMew9wyCROomd2DHoHfLgVnv4copG', 'alex_g@gmail.com', 'Calle 93 B # 13-03', '53455432452', NULL, 1, 3),
(155, 4234234, 'Fernando', 'uticaria', '$2y$10$QksEBkNcAK15ktSZSdMnSuvHHsLaINCRwGaRiMuhRQfwHxde2fcUK', 'fernandoutil@gmail.com', 'Calle 93 B # 13-03', '5234234', NULL, 1, 3),
(156, 436634, 'Diana marcela', 'Gutierrez', '$2y$10$ySel.GVOu2vEbiqYu13.BeCAZXt64QluXfXPKtwbvMTrYY4EoTqqO', 'marceD@gmail.com', 'Calle #4 - 32 -23', '34056738', NULL, 1, 3),
(157, 45234324, 'María', 'López', '$2y$10$G4ofjuHI5hKeCzTc1bPa5uyJyPwRURHfo6VAwFHZaD1Rrw7ofj0sy', 'maria.Lopez32@gmail.com', 'Calle 2', '3000000002', NULL, 1, 3),
(158, 565464645, 'María', 'López', '$2y$10$a3t1Scz9gQCMxTfxAWo2fu./YV1QvUzcIyEaKAL9QDpjSQq5kqgH2', 'hello@gmail.com', 'Calle 2', '3000000002', '', 1, 4),
(159, 2147483647, 'María', 'López', '$2y$10$QS92GIOg4MIi2c8649SDPeALoKcL0pjK8agxV0Wv3mdB.yQ5qKXju', 'hello@gmail.com', 'Calle 2', '3000000002', '', 1, 4),
(160, 2147483647, 'María', 'López', '$2y$10$XXqBDZ/eti1NqtBnIoy7QuM.5Pu8ZJw0JQFPQiPMW0nLhdEaeWvjC', 'hello@gmail.com', 'Calle 2', '3000000002', 'sdfsdf', 1, 3),
(161, 2147483647, 'María', 'López', '$2y$10$Nuos/m5VHOaP4rT7wVDQ1uoP2icgUgjIkESoE7I6uc28wdVRgDVim', 'helloWorldPrueba@gmail.com', 'Calle 2', '3000000002', ' prueba nueva enviando el usuariio.', 1, 2),
(162, 9999999, 'María', 'López', '$2y$10$HQeEWgvHLL2gEdkPf0eJoOWqn7DXwLRQctnRZjg3cl4/OgovU3irW', 'helloMaria@gmail.com', 'Calle 2', '3000000002', 'aaaa', 1, 1),
(163, 4545454, 'Mariana', 'Rivera', '$2y$10$/OEJEHDK4I3BM5YcG.ZwdOeF.uiD1SGIEUTbzN0uDIe5nSGanq8Dy', 'mr.rivera@gmail.com', 'Calle 2', '3000000002', ' prueba nueva enviando el usuariio.', 1, 20),
(164, 658585, 'Isabella', 'Rivera', '$2y$10$wSluFY9xJQ/4qnKnSZ7PVuN.OT3GA8YP4bjrcC5XtZzWPq.uIpohO', 'hello@gmail.com', 'Calle 2', '3000000002', 'Solicito nuevo usuario.', 1, 4),
(165, 65858533, 'Isabella', 'Rivera', '$2y$10$g8zveEh4fHUpE60A46YrO.nzVsc.0jcIJeDAGuySjBwLv3Zq6YMmy', 'hello@gmail.com', 'Calle 2', '3000000002', 'Prueba de integración adicional.', 1, 5),
(166, 10001922, 'alejandro', 'ceron', '$2y$10$6N3vmSHd7fgub1YBdHLk6urmGfjK4EsIr0iZQ5IQkgE8BmPtk8GFq', '4_5@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', 'hola mundo', 1, 2),
(167, 10002922, 'alejandro', 'ceron', '$2y$10$94XiXQaiJnh753Rw.IwIfOB5llWPRxdYLbtWNh6mQlbtQ9sS3NURW', '4_6@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', '', 1, 5),
(168, 10002923, 'alejandro', 'ceron', '$2y$10$7bNsFRSivJgEVGv7pOFwuejdP/XQDj7CVxxQRAv6Lu4TJVNZMAT36', '4_7@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', '', 1, 4),
(169, 100003, 'alejandro', 'ceron d', '$2y$10$K0XKfn2Lc/lKNP7R4NcOSOmCvDxfu7yRj205Bou4/dUTvdBGmvsOy', 'lalejandrcd1@gmail.com', 'calle 2 d oeste # 74 e 02', '3322443', 'dd', 2, 1),
(170, 100011, 'alejandro', 'ceron', '$2y$10$L0u4YSSLqaLF/4./yuBznOFIS.UjtCtjeqWYRpWAd1GYMuS3vM.la', '2_9@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', '', 1, 2),
(171, 2147483647, 'alejandro', 'ceron', '$2y$10$hPtw.4P20KAWdwBwPQzrGuOElrYBVfz3dSliIaQjOC0X/GDpSdVAK', '1_10@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', 'asdasdasd', 1, 2),
(172, 10344, 'alejandro', 'ceron', '$2y$10$1IVNZDufxJoTBITCyZundO5vE/If0TTA2ZN8rqzP4sJ1Rt7IW5bvS', '1_11@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', '', 1, 4),
(173, 100666, 'alejandro', 'ceron ocoro', '$2y$10$HPHJ9MUV9GCaAxuMzm5qAuVqg0hnJf4cgZjHnzSPIhJBrsRP3c/ni', '4_12@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', 'asddd', 1, 3),
(174, 100667, 'alejandro', 'ceron', '$2y$10$xdqXglY9B85CBc6ah43PaOfDLoGFmOUe.1CFCfpPrzKRpQm3uTtni', '3_13@gmail.com', 'calle 2 d oeste # 74 e 02', '3322', 'ddd', 1, 2),
(175, 100667, 'jhon doe', 'pro', '$2y$10$fZr14Dw3Q3Hfii/1yRZkVO9p.QakhNbOkb7NV3WWp6GtsahvXArKG', '1_14@gmail.com', 'Calle 5', '3000000005', '', 1, 5),
(176, 14664, 'paola', 'gris', '$2y$10$mD7tGTXzVZpOGwgvttjlv.lO33nmpFqUjgr47QeOfp31y8Hgtuiry', 'paola.gris@gmail.com', 'Calle 9 # 32 - 12', '3000000005', 'ddd', 1, 5),
(177, 16810948, 'Diana Patricia', 'Gonzales P', '$2y$10$JObXt4vaTkGWX2b0cnF90u.TusZNOLAb0PJ6yGBC.142/L0XerA9i', 'diana.Patricia@soysena.edu.co', 'Calle 9 # 32 - 12', '3646743918', '', 1, 1),
(178, 53243333, 'Alejandro', 'Rojas', '$2y$10$ZJulVCW8adsxSLKWkA7FxOtO5e9OmEY85VaOFK8fnSi0RQYcAF4im', 'rjdddd@gmail.com', 'Av 32 N # 83 - 103', '3000000003', 'hola mundo', 1, 1),
(179, 2147483647, 'Alejandro', 'Rojas', '$2y$10$aQ1cnxj15GKRy58vZrP5B.tIvTmeARXLSVbqPP0ti/BCuE1gA1kB2', 'rjAocd@gmail.com', 'Av 32 N # 83 - 103', '3000000003', 'información adicional', 1, 3),
(180, 26432544, 'Alejandro', 'Rojas', '$2y$10$1b1u8jpkG5TvArDRykIF8u9EbNoQ6pAVbyTZBlCJgNvH2EMWTa1ni', 'rjrojasaprendiz@gmail.com', 'Av 32 N # 83 - 103', '3000000003', 'hola mundo.', 1, 2),
(181, 2147483647, 'Ana Liliana', 'Fernández', '$2y$10$0WFTjjeeOkPN07QeoX.U3eP42tG20BUMuNKbwv4ACqALRbO7KpMGm', 'ALANA@gmail.com', 'Calle 6 # 344 - 32', '3000000006', 'DDDDD', 2, 1),
(182, 5534032, 'Fernando Collazos', 'Oliveria', '$2y$10$m9rm/rHBXkIGNMljTahnD.6cH0/gl6TE1ErjhSCrpJ7sT5FLwO2Xq', 'fernandoOlv@gmail.com', 'Av 32 N # 83 - 103', '4123123213', 'Es el usuario coordinador de la central didáctica.', 1, 1),
(183, 595747474, 'Adddro', 'Rojas', '$2y$10$nQl07mG65UqEl3OAm7xxtegzVxsh.vGiRJZbdq/OY13sf8IhPl6US', 'rjAaddro332d@gmail.com', 'Av 32 N # 83 - 103', '3000000003', 'usuario de prueba.', 1, 1),
(184, 2643333, 'Alax', 'Brahim ', '$2y$10$43ijWw2hW9kl22KUzYSU9e3A52T9Z2RsHpkLkM1hD7EN6qF9pktgC', 'brahiamApex@gmail.com', 'Av 32 N # 9 - 11', '30000355', 'Es musulman, pd: explota.', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `usr_id` int(11) NOT NULL COMMENT 'Código único de la relación usuario-rol ​:contentReference[oaicite:0]{index=0}​',
  `usr_usu_id` int(11) DEFAULT NULL,
  `usr_rl_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relaciona usuarios con los roles que tienen asignados dentro del sistema';

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`usr_id`, `usr_usu_id`, `usr_rl_id`) VALUES
(1, 106, 2),
(1127, 108, 4),
(1128, 109, 4),
(1130, 111, 4),
(1131, 112, 1),
(1133, 114, 4),
(1134, 115, 1),
(1136, 117, 4),
(1137, 118, 1),
(1138, 119, 12),
(1139, 120, 4),
(1140, 121, 1),
(1142, 123, 4),
(1143, 124, 4),
(1145, 126, 4),
(1146, 127, 1),
(1149, 130, 1),
(1151, 132, 4),
(1152, 133, 12),
(1154, 135, 4),
(1155, 136, 12),
(1158, 138, 4),
(1168, 148, 4),
(1169, 149, 2),
(1170, 150, 4),
(1172, 152, 4),
(1173, 153, 4),
(1174, 154, 4),
(1175, 155, 4),
(1176, 156, 4),
(1177, 157, 12),
(1180, 160, 4),
(1181, 161, 4),
(1182, 162, 4),
(1183, 163, 12),
(1184, 164, 4),
(1185, 165, 2),
(1188, 168, 2),
(1189, 169, 1),
(1190, 170, 4),
(1192, 172, 12),
(1193, 173, 4),
(1195, 175, 4),
(1196, 176, 4),
(1197, 177, 4),
(1198, 178, 12),
(1199, 179, 12),
(1200, 180, 12),
(1201, 181, 1),
(1202, 182, 16),
(1203, 183, 4),
(1204, 184, 12);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`ar_cod`),
  ADD UNIQUE KEY `ar_nombre` (`ar_nombre`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`ca_id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`cod_compra`),
  ADD KEY `fk_co_tp_movimiento` (`co_tp_movimiento`),
  ADD KEY `fk_cod_elm` (`co_cod_elm`);

--
-- Indices de la tabla `elementos`
--
ALTER TABLE `elementos`
  ADD PRIMARY KEY (`elm_cod`),
  ADD KEY `elm_placa` (`elm_placa`),
  ADD KEY `elm_serie` (`elm_serie`),
  ADD KEY `fk_cod_tp_elm` (`elm_cod_tp_elemento`),
  ADD KEY `fk_cod_estado` (`elm_cod_estado`),
  ADD KEY `fk_cod_area` (`elm_area_cod`),
  ADD KEY `fk_cod_ma` (`elm_ma_cod`),
  ADD KEY `fk_uni_medida` (`elm_uni_medida`);

--
-- Indices de la tabla `entradas_salidas`
--
ALTER TABLE `entradas_salidas`
  ADD PRIMARY KEY (`ent_sal_cod`),
  ADD KEY `entr_tp_movmnt` (`entr_tp_movmnt`),
  ADD KEY `ent_id_usu` (`ent_id_usu`),
  ADD KEY `fk_cod_prestamo` (`ent_sal_cod_prestamo`) USING BTREE,
  ADD KEY `fk_ent_sal_cod_elm` (`ent_sal_cod_elemtn`);

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
-- Indices de la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD PRIMARY KEY (`id_funcion`),
  ADD KEY `id_modulo` (`id_modulo`),
  ADD KEY `tp_funcion` (`tp_funcion`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`ma_id`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_m`);

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
  ADD PRIMARY KEY (`pres_el_cod`) USING BTREE,
  ADD KEY `pres_cod` (`pres_cod`) USING BTREE,
  ADD KEY `fk_pres_usu_id` (`pres_el_usu_id`) USING BTREE,
  ADD KEY `fk_pres_elm_cod` (`pres_el_elem_cod`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rl_id`);

--
-- Indices de la tabla `roles_funciones`
--
ALTER TABLE `roles_funciones`
  ADD PRIMARY KEY (`rlp_id`),
  ADD KEY `rlp_id_rl` (`rlp_id_rl`),
  ADD KEY `rlp_id_funcion` (`rlp_id_funcion`);

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
-- Indices de la tabla `tipo_funcion`
--
ALTER TABLE `tipo_funcion`
  ADD PRIMARY KEY (`id_tp_funcion`);

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
-- Indices de la tabla `tipo_unidad`
--
ALTER TABLE `tipo_unidad`
  ADD PRIMARY KEY (`cod_tp_uni`);

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
  MODIFY `ar_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código primario del area', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ca_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'codigo de identificador de la categoria', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `cod_compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'codigo autoincrementable identificador de la existencia', AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `elementos`
--
ALTER TABLE `elementos`
  MODIFY `elm_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'codigo primario autoincrementable', AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT de la tabla `entradas_salidas`
--
ALTER TABLE `entradas_salidas`
  MODIFY `ent_sal_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código único de la entrada o salida', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estados_elementos`
--
ALTER TABLE `estados_elementos`
  MODIFY `est_el_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código único del estado del elemento', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados_prestamos`
--
ALTER TABLE `estados_prestamos`
  MODIFY `es_pr_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código único del estado del préstamo', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estados_usuarios`
--
ALTER TABLE `estados_usuarios`
  MODIFY `est_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código único del estado del usuario', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `funciones`
--
ALTER TABLE `funciones`
  MODIFY `id_funcion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id representativo primario de la tabla funciones', AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `ma_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la marca', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_m` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `per_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del permiso', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `pres_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código identificador del préstamo', AUTO_INCREMENT=613;

--
-- AUTO_INCREMENT de la tabla `prestamos_elementos`
--
ALTER TABLE `prestamos_elementos`
  MODIFY `pres_el_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código único del registro de préstamo de elemento', AUTO_INCREMENT=1813;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rl_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del rol', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `roles_funciones`
--
ALTER TABLE `roles_funciones`
  MODIFY `rlp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la relación rol-permiso', AUTO_INCREMENT=290;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `tp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tipo de documento', AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `tipo_elemento`
--
ALTER TABLE `tipo_elemento`
  MODIFY `tp_el_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tipo de elemento', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_funcion`
--
ALTER TABLE `tipo_funcion`
  MODIFY `id_tp_funcion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Valor auto increment del tipo de función', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_prestamo`
--
ALTER TABLE `tipo_prestamo`
  MODIFY `tp_pre` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tipo de prestamo', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_unidad`
--
ALTER TABLE `tipo_unidad`
  MODIFY `cod_tp_uni` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código único de la unidad de medida', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario', AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código único de la relación usuario-rol ​:contentReference[oaicite:0]{index=0}​', AUTO_INCREMENT=1205;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `fk_co_tp_movimiento` FOREIGN KEY (`co_tp_movimiento`) REFERENCES `tipo_movimiento` (`cod_tp`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_elm` FOREIGN KEY (`co_cod_elm`) REFERENCES `elementos` (`elm_cod`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `elementos`
--
ALTER TABLE `elementos`
  ADD CONSTRAINT `fk_cod_area` FOREIGN KEY (`elm_area_cod`) REFERENCES `areas` (`ar_cod`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_estado` FOREIGN KEY (`elm_cod_estado`) REFERENCES `estados_elementos` (`est_el_cod`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_ma` FOREIGN KEY (`elm_ma_cod`) REFERENCES `marcas` (`ma_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_tp_elm` FOREIGN KEY (`elm_cod_tp_elemento`) REFERENCES `tipo_elemento` (`tp_el_cod`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uni_medida` FOREIGN KEY (`elm_uni_medida`) REFERENCES `tipo_unidad` (`cod_tp_uni`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas_salidas`
--
ALTER TABLE `entradas_salidas`
  ADD CONSTRAINT `fk_ent_sal_cod_elm` FOREIGN KEY (`ent_sal_cod_elemtn`) REFERENCES `elementos` (`elm_cod`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ent_sal_cod_pres` FOREIGN KEY (`ent_sal_cod_prestamo`) REFERENCES `prestamos` (`pres_cod`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tp_mvto` FOREIGN KEY (`entr_tp_movmnt`) REFERENCES `tipo_movimiento` (`cod_tp`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tp_mvto_usuId` FOREIGN KEY (`ent_id_usu`) REFERENCES `usuarios` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD CONSTRAINT `fk_id_modulo` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_m`),
  ADD CONSTRAINT `fk_tp_funcion` FOREIGN KEY (`tp_funcion`) REFERENCES `tipo_funcion` (`id_tp_funcion`);

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
  ADD CONSTRAINT `fk_pres_elm_cod` FOREIGN KEY (`pres_el_elem_cod`) REFERENCES `elementos` (`elm_cod`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pres_usu_id` FOREIGN KEY (`pres_el_usu_id`) REFERENCES `usuarios` (`usu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamos_elementos_ibfk_1` FOREIGN KEY (`pres_cod`) REFERENCES `prestamos` (`pres_cod`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `roles_funciones`
--
ALTER TABLE `roles_funciones`
  ADD CONSTRAINT `fk_function` FOREIGN KEY (`rlp_id_funcion`) REFERENCES `funciones` (`id_funcion`),
  ADD CONSTRAINT `fk_rol` FOREIGN KEY (`rlp_id_rl`) REFERENCES `roles` (`rl_id`);

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
