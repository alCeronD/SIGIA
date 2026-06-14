
CREATE TABLE `marcas` (
  `ma_id` int(11) NOT NULL COMMENT 'Identificador único de la marca',
  `ma_nombre` varchar(50) NOT NULL COMMENT 'Nombre de la marca',
  `ma_descripcion` varchar(200) NOT NULL COMMENT 'Descripción detallada de la marca',
  `ma_status` tinyint(1) NOT NULL COMMENT 'Estado de la marca, 1 activo, 0 inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que almacena las marcas asociadas a los elementos de la central didáctica';


INSERT INTO `marcas` (`ma_id`, `ma_nombre`, `ma_descripcion`, `ma_status`) VALUES
(1, 'No aplica', 'Elemento sin marca definida', 1),
(2, 'Canon', '', 1),
(3, 'Sony', '', 1),
(4, 'Panasonic', '', 1);


  ALTER TABLE `marcas`
  ADD PRIMARY KEY (`ma_id`);


ALTER TABLE `marcas`
  MODIFY `ma_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la marca', AUTO_INCREMENT=5;

