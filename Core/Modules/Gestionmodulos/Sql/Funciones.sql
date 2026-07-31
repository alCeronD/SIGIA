CREATE TABLE IF NOT EXISTS `funciones` (
    `id_funcion` int(11) NOT NULL COMMENT 'id representativo primario de la tabla funciones',
    `nombre_funcion` varchar(50) DEFAULT NULL COMMENT 'Nombre de la función del controlador.',
    `nombre_funcion_user` varchar(32) DEFAULT NULL COMMENT 'Nombre de la función amigable para el usuario.',
    `id_modulo` int(11) DEFAULT NULL COMMENT 'Modulo al que pertenece la función.',
    `tp_funcion` int(11) DEFAULT NULL COMMENT 'Tipo de la función siendo render para visualizar vistas o logic de solo lógica.'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

ALTER TABLE `funciones`
ADD PRIMARY KEY (`id_funcion`),
ADD KEY `id_modulo` (`id_modulo`),
ADD KEY `tp_funcion` (`tp_funcion`);