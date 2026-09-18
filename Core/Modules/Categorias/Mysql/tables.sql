CREATE TABLE IF NOT EXISTS `categorias` (
    `ca_id` int(11) NOT NULL COMMENT 'codigo de identificador de la categoria',
    `ca_nombre` varchar(50) NOT NULL COMMENT 'nombre de la categoria',
    `ca_descripcion` varchar(200) NOT NULL COMMENT 'descripción de la categoria',
    `ca_status` tinyint(1) NOT NULL COMMENT 'estado de la categoria, 1 activo, 0 inactivo'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'Tabla que clasifica los elementos de la central didactica en diferentes categorías para su organización y control.';

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria` ADD PRIMARY KEY (`ca_id`);
--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
MODIFY `ca_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'codigo de identificador de la categoria',
AUTO_INCREMENT = 7;

-- INSERTS

INSERT INTO
    `categoria` (
        `ca_id`,
        `ca_nombre`,
        `ca_descripcion`,
        `ca_status`
    )
VALUES (
        1,
        'No aplica',
        'Categoría No aplica',
        1
    ),
    (
        2,
        'Soporte',
        'Categoría Soporte',
        1
    ),
    (
        3,
        'Iluminación Fría',
        'Categoría Iluminación Fría',
        1
    ),
    (
        4,
        'Iluminación Cálida',
        'Categoría Iluminación Cálida',
        1
    ),
    (
        5,
        'Video Cámara',
        'Categoría Video Cámara',
        1
    ),
    (
        6,
        'Cámaras',
        'Categoría Cámaras',
        1
    );