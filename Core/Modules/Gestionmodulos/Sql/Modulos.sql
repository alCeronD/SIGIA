# Tabla modulos
CREATE TABLE IF NOT EXISTS `modulos` (
    `id_m` int(11) NOT NULL,
    `cod_nombre_m` varchar(30) NOT NULL,
    `icono` varchar(30) DEFAULT NULL,
    `cod_descript` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO
    `modulos` (
        `id_m`,
        `cod_nombre_m`,
        `icono`,
        `cod_descript`
    )
VALUES (1, 'usuarios', 'person', ''),
    (
        2,
        'configModules',
        'settings',
        ''
    ),
    (
        3,
        'elementos',
        'local_see',
        ''
    ),
    (
        4,
        'reportes',
        'bar_chart',
        ''
    ),
    (
        5,
        'reservaPrestamos',
        'assignment',
        ''
    ),
    (
        6,
        'solicitudPrestamos',
        'storage',
        ''
    ),
    (
        7,
        'Roles',
        'supervisor_account',
        ''
    ),
    (8, 'dashboard', 'home', ''),
    (
        10,
        'Categorias',
        'widgets',
        ''
    );

ALTER TABLE `modulos` ADD PRIMARY KEY (`id_m`);

ALTER TABLE `modulos`
MODIFY `id_m` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 13;

ALTER TABLE `modulos` ADD PRIMARY KEY (`id_m`);