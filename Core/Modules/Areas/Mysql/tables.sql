CREATE TABLE IF NOT EXISTS `areas` (
  `ar_cod` int(11) NOT NULL COMMENT 'Código primario del area',
  `ar_nombre` varchar(30) NOT NULL COMMENT 'Nombre del area',
  `ar_descripcion` varchar(300) DEFAULT NULL COMMENT 'Descripción del area',
  `ar_status` tinyint(1) NOT NULL COMMENT 'Estado del area, activo 1, Inactivo 0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que almacena los distintos departamentos asociados a los elementos de la central didáctica';

INSERT INTO `areas` (`ar_cod`, `ar_nombre`, `ar_descripcion`, `ar_status`) VALUES
(1, 'Sonidos', '', 1),
(2, 'Luz', '', 1),
(3, 'General', '', 1),
(4, 'Fotografia', '', 1),
(5, 'Iluminación', '', 1),
(6, 'Cámaras', '', 1);