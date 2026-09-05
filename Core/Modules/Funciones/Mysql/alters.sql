ALTER TABLE funciones
ADD COLUMN nameController VARCHAR(100) NOT NULL AFTER nombre_funcion_user;

# Permitir nulos en el campo NameController
ALTER TABLE funciones MODIFY nameController VARCHAR(255) NULL;

ALTER TABLE funciones
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE funciones
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE funciones
ADD COLUMN `is_main_view` tinyint(1) DEFAULT NULL COMMENT '1: Principal, 0: Secundaria, NULL: No aplica (función lógica)' AFTER `tp_funcion`;