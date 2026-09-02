ALTER TABLE funciones
ADD COLUMN nameController VARCHAR(100) NOT NULL AFTER nombre_funcion_user;

# Permitir nulos en el campo NameController
ALTER TABLE funciones MODIFY nameController VARCHAR(255) NULL;

ALTER TABLE funciones
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE funciones
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE funciones
ADD COLUMN is_main_view TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1: Es la vista principal/landing del módulo, 0: Vista secundaria o interna' AFTER `tp_funcion`;