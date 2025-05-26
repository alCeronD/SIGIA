-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-05-2025 a las 03:12:02
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
  `ar_descripcion` varchar(100) DEFAULT NULL,
  `ar_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`ar_cod`, `ar_nombre`, `ar_descripcion`, `ar_status`) VALUES
(1, 'Multimedia', 'hello world multimedia', 1),
(2, 'Fotografía', 'hello world3ss', 0),
(3, 'General', 'elementos conss\n', 0),
(4, 'Aulas De Computo', 'asdads', 1);

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
(5, 3001, 'Cámara Canon EOS 90D', 2, 1, 1, 1, 2),
(6, 3002, 'Micrófono de solapa Rode', 5, 1, 1, 1, 1),
(7, 3003, 'Trípode profesional Manfrotto', 4, 1, 1, 1, 2),
(8, 3004, 'Cinta gaffer negra 30m', 20, 1, 2, 1, 1),
(9, 3005, 'Proyector Epson PowerLite', 3, 1, 1, 2, 1),
(10, 3006, 'Marcadores Acrílicos', 100, 1, 2, 1, 3),
(11, 3007, 'Bombilla Flash Godox', 30, 1, 2, 1, 3),
(12, 3008, 'Teclado Logitech K120', 10, 1, 1, 1, 4),
(13, 3009, 'Monitor LG 24” Full HD', 5, 1, 1, 2, 4),
(14, 3010, 'Mouse inalámbrico Genius', 15, 1, 1, 1, 4),
(15, 3011, 'Lente Canon 50mm f/1.8', 3, 1, 1, 3, 2),
(16, 3012, 'Cables HDMI 2m', 25, 1, 2, 1, 1),
(17, 3013, 'Audífonos cerrados Sony', 6, 1, 1, 1, 1),
(18, 3014, 'Tinta para impresora Epson', 50, 1, 2, 1, 3),
(19, 3015, 'Iluminador LED Neewer', 7, 1, 1, 1, 2),
(20, 3016, 'Memorias SD 64GB Sandisk', 20, 1, 2, 1, 2),
(21, 3017, 'Pantalla de proyección portátil', 2, 1, 1, 1, 1),
(22, 3018, 'Paquete de hojas fotográficas A4', 200, 1, 2, 1, 3),
(23, 3019, 'Mini cámara GoPro Hero', 4, 1, 1, 1, 2),
(24, 3020, 'Cable USB-C para cámaras', 40, 1, 2, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_salidas`
--

CREATE TABLE `entradas_salidas` (
  `ent_sal_cod` int(11) NOT NULL,
  `ent_sal_cantidad` int(11) NOT NULL,
  `ent_fech_registro` date DEFAULT NULL,
  `entr_tp_movmnt` varchar(100) DEFAULT '',
  `ent_desc_mvnto` varchar(100) NOT NULL,
  `ent_sal_cod_elemtn` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 'Inhabilitado', NULL);

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
(3, 'Por validar', NULL);

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
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `ma_id` int(11) NOT NULL,
  `ma_nombre` varchar(50) NOT NULL,
  `ma_descripcion` varchar(200) NOT NULL,
  `ma_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `pres_fch_slcitud` date NOT NULL,
  `pres_fch_reserva` date NOT NULL,
  `pres_hor_inicio` date NOT NULL,
  `pres_hor_fin` date NOT NULL,
  `pres_fch_entrega` date NOT NULL,
  `pres_observacion` text NOT NULL,
  `pres_destino` varchar(30) NOT NULL,
  `pres_estado` int(11) NOT NULL,
  `tp_pres` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rl_id` int(11) NOT NULL,
  `rl_nombre` varchar(100) NOT NULL,
  `rl_descripcion` varchar(100) DEFAULT NULL,
  `rl_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rl_id`, `rl_nombre`, `rl_descripcion`, `rl_status`) VALUES
(1, 'Almacenista', NULL, 1),
(2, 'Administrador', NULL, 1),
(3, 'SubDirector', NULL, 0),
(4, 'Instructor', NULL, 1);

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
(3, 2, 1),
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
(1, 'CC', 'Cédula de Ciuda', 0),
(2, 'CE', 'Cédula de Extra', 0),
(3, 'TI', 'Tarjeta de Iden', 0),
(4, 'PAS', 'Pasaporte', 0),
(5, 'RC', 'Registro Civil', 0),
(6, 'NIT', 'Número de Ident', 0),
(7, 'RUT', 'Registro Único ', 0);

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
  `usu_password` varchar(50) DEFAULT NULL,
  `usu_email` varchar(50) DEFAULT NULL,
  `usu_direccion` varchar(100) NOT NULL,
  `usu_telefono` varchar(50) DEFAULT NULL,
  `usu_id_estado` int(11) DEFAULT NULL,
  `usu_tp_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usu_id`, `usu_docum`, `usu_nombres`, `usu_apellidos`, `usu_password`, `usu_email`, `usu_direccion`, `usu_telefono`, `usu_id_estado`, `usu_tp_id`) VALUES
(1, 0, 'Jhon', 'Doe', 'jhondoe123', 'jhondoe@gmail.com', '', '123465', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `usr_id` int(11) NOT NULL,
  `usr_usu_id` int(11) NOT NULL,
  `usr_rl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`usr_id`, `usr_usu_id`, `usr_rl_id`) VALUES
(1, 1, 4),
(2, 1, 1);

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
  ADD KEY `fk_ent_sal_cod_elemnt` (`ent_sal_cod_elemtn`);

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
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
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
  ADD KEY `fk_pres_tipo` (`tp_pres`);

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
  ADD KEY `fk_usu_tp_id` (`usu_tp_id`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD PRIMARY KEY (`usr_id`),
  ADD KEY `fk_usr_usu_id` (`usr_usu_id`),
  ADD KEY `fk_usr_rl_id` (`usr_rl_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `ar_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `btr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ca_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `elementos`
--
ALTER TABLE `elementos`
  MODIFY `elm_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `entradas_salidas`
--
ALTER TABLE `entradas_salidas`
  MODIFY `ent_sal_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estados_elementos`
--
ALTER TABLE `estados_elementos`
  MODIFY `est_el_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estados_prestamos`
--
ALTER TABLE `estados_prestamos`
  MODIFY `es_pr_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estados_usuarios`
--
ALTER TABLE `estados_usuarios`
  MODIFY `est_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `ma_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `per_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `pres_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `prestamos_elementos`
--
ALTER TABLE `prestamos_elementos`
  MODIFY `pres_el_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  MODIFY `rlp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `tp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `fk_ent_sal_cod_elemnt` FOREIGN KEY (`ent_sal_cod_elemtn`) REFERENCES `elementos` (`elm_cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `fk_pres_estado` FOREIGN KEY (`pres_estado`) REFERENCES `estados_prestamos` (`es_pr_cod`) ON DELETE CASCADE ON UPDATE CASCADE,
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
  ADD CONSTRAINT `fk_usr_rl_id` FOREIGN KEY (`usr_rl_id`) REFERENCES `roles` (`rl_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usr_usu_id` FOREIGN KEY (`usr_usu_id`) REFERENCES `usuarios` (`usu_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
