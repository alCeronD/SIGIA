# Modificaciones del modulo usuarios, estas modificaciones corresponden a ajustes a la base de datos LUEGO DE haber desarrollado y entregado la primera version del modulo.
CREATE TABLE IF NOT EXISTS logs_users ();

# MODIFICACIONES A LA TABLA USUARIOS.
ALTER TABLE usuarios
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE usuarios
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;