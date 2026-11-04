# Historia de Usuario: Crear Usuario

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-U01`                |
| **Nombre**                   | Crear Usuario           |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | ALTA (1)                |
| **Requerimientos asociados** | `RFU-1`                 |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

* **Como** Como Almacenista/administrador
* **Necesito** Necesito registrar un usuario a la base de datos mediante un formulario con campos específicos
* **Para** Para que el usuario pueda realizar reservas previas o asociar una reserva inmediata

---

## Criterios de Aceptación

* [ ] El sistema debe permitir registrar al usuario con los siguientes campos: tipo de documento, número de identificación, nombres, apellidos, teléfonos, dirección, correo electrónico, contraseña, rol.
* [ ] Los registros: tipo de documento y rol deben ser extraidos del modulo de tipo_documento y modulo roles.
* [ ] El sistema me notifica mediante un mensaje de alerta que el usuario ha sido creado exitosamente
* [ ] En caso de que el usuario ya haya sido creado previamente, el usuario debe notificarme mediante un mensaje de alerta que el número de documento ya ha sido creado en la base de datos
* [ ] Los campos marcados con asteriscos son obligatorios: número de identificación, nombre, apellido, teléfono, rol.
* [ ] Cuando se registra el usuario, el estado del mismo por defecto es activo.
* [ ] No puedo registrar caracteres especiales en los campos nombre, número de documento, apellidos y teléfono.
