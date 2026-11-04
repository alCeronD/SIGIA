# Historia de Usuario: Modificar Rol

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-R02`                |
| **Nombre**                   | Modificar rol           |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | MEDIA (2)               |
| **Requerimientos asociados** | `RFU-3`                 |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

- **Como** Super Administrador / Administrador
- **Necesito** Necesito modificar la información de los roles registrados en el sistema
- **Para** Con el fin de mantener la información de los roles actualizada

---

## Criterios de Aceptación

- [ ] El sistema debe visualizar un formulario editable con los siguientes campos: nombre del rol, descripción.
- [ ] Los campos marcados con asterisco (*) son obligatorios y no deben enviarse vacíos.
- [ ] Todos los campos deben mantener la restricción de límite de caracteres.
- [ ] Al modificar la información del rol, se debe validar que el nuevo nombre no se encuentre ya asignado a otro rol registrado en la base de datos.
