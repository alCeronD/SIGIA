-- TABLA MODULO USUARIOS
-
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'Contiene la información personal y de contacto de los usuarios del sistema';

-- USUARIO GENERICO CON ROL ADMINISTRADOR.
INSERT INTO
    `usuarios` (
        `usu_id`,
        `usu_docum`,
        `usu_nombres`,
        `usu_apellidos`,
        `usu_password`,
        `usu_email`,
        `usu_direccion`,
        `usu_telefono`,
        `usu_observacion`,
        `usu_id_estado`,
        `usu_tp_id`
    )
VALUES (
        149,
        1107528994,
        'Luis Alberto Pozada',
        'Gutierrez Brown',
        '$2y$10$bIQddMZHOJu4sNQ0RlArg.3KxrLkngGv5G57pg6Q8MdJreCuYsS9S',
        'luisAl.gz@gmail.com',
        'calle 73 #32 -321',
        '3226855437',
        NULL,
        1,
        3
    );

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
ADD PRIMARY KEY (`usu_id`),
ADD KEY `fk_usu_id_estado` (`usu_id_estado`),
ADD KEY `fk_usu_tp_id` (`usu_tp_id`),
ADD KEY `usu_docum` (`usu_docum`);

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario',
AUTO_INCREMENT = 185;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
ADD CONSTRAINT `fk_usu_id_estado` FOREIGN KEY (`usu_id_estado`) REFERENCES `estados_usuarios` (`est_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_usu_tp_id` FOREIGN KEY (`usu_tp_id`) REFERENCES `tipo_documento` (`tp_id`) ON DELETE CASCADE ON UPDATE CASCADE;