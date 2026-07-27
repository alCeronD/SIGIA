ALTER TABLE modulos RENAME COLUMN cod_nombre_m TO nombre_modulo;

ALTER TABLE modulos RENAME COLUMN cod_descript TO descripcion;

# Se agrega la columna como NOT NULL Para si o si permitir que solo se acepten valores 1 o 0, no permitir valores nulos.
ALTER TABLE modulos ADD COLUMN status_modulo TINYINT NOT NULL;

# Agregar campo unico a nombre_modulo para evitar duplicados.
ALTER TABLE modulos ADD UNIQUE `nombre_modulo` (`nombre_modulo`);