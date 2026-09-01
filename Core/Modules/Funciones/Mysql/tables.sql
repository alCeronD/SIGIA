CREATE TABLE IF NOT EXISTS `funciones` (
    `id_funcion` int(11) NOT NULL COMMENT 'id representativo primario de la tabla funciones',
    `nombre_funcion` varchar(50) DEFAULT NULL COMMENT 'Nombre de la función del controlador.',
    `nombre_funcion_user` varchar(32) DEFAULT NULL COMMENT 'Nombre de la función amigable para el usuario.',
    `id_modulo` int(11) DEFAULT NULL COMMENT 'Modulo al que pertenece la función.',
    `tp_funcion` int(11) DEFAULT NULL COMMENT 'Tipo de la función siendo render para visualizar vistas o logic de solo lógica.'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `roles_funciones` (
    `rlp_id` int(11) NOT NULL COMMENT 'Identificador único de la relación rol-permiso',
    `rlp_id_rl` int(11) DEFAULT NULL COMMENT 'ID identificador del rol',
    `rlp_id_funcion` int(11) DEFAULT NULL COMMENT 'ID identificador de la función.'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'Relaciona roles del sistema con los permisos que les corresponden';

--
-- Indices de la tabla `funciones`
--
ALTER TABLE `funciones`
ADD PRIMARY KEY (`id_funcion`),
ADD KEY `id_modulo` (`id_modulo`),
ADD KEY `tp_funcion` (`tp_funcion`);

ALTER TABLE `roles_funciones`
ADD PRIMARY KEY (`rlp_id`),
ADD KEY `rlp_id_rl` (`rlp_id_rl`),
ADD KEY `rlp_id_funcion` (`rlp_id_funcion`);

--
-- AUTO_INCREMENT de la tabla `roles_funciones`
--
ALTER TABLE `roles_funciones`
MODIFY `rlp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la relación rol-permiso',
AUTO_INCREMENT = 297;

--
-- Filtros para la tabla `roles_funciones`
--
ALTER TABLE `roles_funciones`
ADD CONSTRAINT `fk_function` FOREIGN KEY (`rlp_id_funcion`) REFERENCES `funciones` (`id_funcion`),
ADD CONSTRAINT `fk_rol` FOREIGN KEY (`rlp_id_rl`) REFERENCES `roles` (`rl_id`);