# Historia de Usuario: Crear Rol

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-R01`                |
| **Nombre**                   | Crear rol               |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | ALTA (1)                |
| **Requerimientos asociados** | `RFU-2`                 |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

- **Como** Super Administrador / Administrador
- **Necesito** Necesito crear un rol mediante un formulario
- **Para** Para así gestionar la trazabilidad y el acceso a los diferentes módulos del sistema mediante validaciones internas

---

## Criterios de Aceptación

- [ ] El sistema debe visualizar un formulario con los siguientes campos: nombre del rol, descripción.
- [ ] Los campos marcados con asterisco (*) son obligatorios y no deben enviarse vacíos.
- [ ] Todos los campos de texto deben contar con un límite máximo de caracteres configurado.
- [ ] Al intentar crear el rol, el sistema debe validar previamente que el nombre del rol no exista en la base de datos para evitar registros duplicados.
