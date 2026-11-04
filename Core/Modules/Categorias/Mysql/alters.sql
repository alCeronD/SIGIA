#Alters tabla categorias

# RENOMBRAMOS TABLA DE categoria a categorias
alter table categoria rename categorias;

ALTER TABLE categorias
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE categorias
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

# Agregar como unico el campo nombre, evitamos duplicados
ALTER TABLE categorias ADD UNIQUE (ca_nombre);