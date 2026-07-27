CREATE TABLE IF NOT EXISTS `roles_funciones` (
    `rlp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la relación rol-permiso',
    `rlp_id_rl` int(11) DEFAULT NULL COMMENT 'ID identificador del rol',
    `rlp_id_funcion` int(11) DEFAULT NULL COMMENT 'ID identificador de la función.',
    `created_at` timestamp NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`rlp_id`),
    KEY `rlp_id_rl` (`rlp_id_rl`),
    KEY `fk_function` (`rlp_id_funcion`),
    CONSTRAINT `fk_function` FOREIGN KEY (`rlp_id_funcion`) REFERENCES `funciones` (`id_funcion`),
    CONSTRAINT `fk_rol` FOREIGN KEY (`rlp_id_rl`) REFERENCES `roles` (`rl_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 492 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'Relaciona roles del sistema con los permisos que les corresponden'