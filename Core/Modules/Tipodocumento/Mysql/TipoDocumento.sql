# Tabla que sirve para crear el modulo.

CREATE TABLE `tipo_documento` (
  `tp_id` int(11) NOT NULL COMMENT 'Identificador único del tipo de documento',
  `tp_sigla` varchar(15) NOT NULL COMMENT 'Sigla del tipo de documento (ej. CC, TI, CE)',
  `tp_nombre` varchar(100) DEFAULT NULL COMMENT 'Nombre completo del tipo de documento',
  `tp_status` tinyint(1) NOT NULL COMMENT 'Estado del tipo de documento, 1 activo, 0 inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Define los tipos de documentos válidos para los usuarios';

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO
    `tipo_documento` (
        `tp_id`,
        `tp_sigla`,
        `tp_nombre`,
        `tp_status`
    )
VALUES (
        1,
        'CC',
        'Cédula de Ciudania',
        1
    ),
    (2, 'CE', 'Cédulas', 1),
    (3, 'TI', 'Tarjeta de Iden', 1),
    (4, 'PAS', 'Pasaporte', 1),
    (5, 'RC', 'Registro Civil', 1),
    (
        20,
        'NIT',
        'Número De Ident',
        1
    ),
    (
        21,
        'CC DIG',
        'Cedula de ciudadanía digital.',
        1
    );

# Alters

ALTER TABLE `tipo_documento`
  MODIFY `tp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tipo de documento', AUTO_INCREMENT=32;

ALTER TABLE `tipo_documento` ADD PRIMARY KEY (`tp_id`);

-- Funciones para tipo de documento
INSERT INTO
    funciones (
        nombre_funcion,
        nombre_funcion_user,
        id_modulo,
        tp_funcion
    )
VALUES (
        'renderViewTp',
        'Ver tipo documento',
        15,
        1
    );

INSERT INTO
    funciones (
        nombre_funcion,
        nombre_funcion_user,
        id_modulo,
        tp_funcion
    )
VALUES (
        'getData',
        'Select tipo documento',
        15,
        2
    );

INSERT INTO
    funciones (
        nombre_funcion,
        nombre_funcion_user,
        id_modulo,
        tp_funcion
    )
VALUES (
        'createDepartment',
        'Crear Tipo documento',
        15,
        2
    );

INSERT INTO
    funciones (
        nombre_funcion,
        nombre_funcion_user,
        id_modulo,
        tp_funcion
    )
VALUES (
        'changeStatus',
        'Cambiar estado',
        15,
        2
    );

INSERT INTO
    funciones (
        nombre_funcion,
        nombre_funcion_user,
        id_modulo,
        tp_funcion
    )
VALUES (
        'deleteItem',
        'Eliminar Tipo documento',
        15,
        2
    );

INSERT INTO
    funciones (
        nombre_funcion,
        nombre_funcion_user,
        id_modulo,
        tp_funcion
    )
VALUES (
        'updateItem',
        'Actualizar Tipo documento',
        15,
        2
    );