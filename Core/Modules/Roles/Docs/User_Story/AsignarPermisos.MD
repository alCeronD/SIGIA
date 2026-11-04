
# Historia de Usuario: Asignar Permisos

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-R05`                |
| **Nombre**                   | Asignar permisos        |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | ALTA (1)                |
| **Requerimientos asociados** | `RFU-6`                 |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

- **Como** Super Administrador / Administrador
- **Necesito** Necesito asignar permisos a un rol determinado
- **Para** Para así definir y controlar el acceso a los diferentes módulos del sistema

---

## Criterios de Aceptación

- [ ] Se debe disponer de un formulario o interfaz separada con el listado de los diferentes módulos y sus funcionalidades para la asignación de permisos.
- [ ] El usuario con rol "Super Administrador" puede asignar permisos a todos los roles del sistema.
- [ ] El usuario con rol "Administrador" solo podrá asignar permisos a roles que pertenezcan a una jerarquía inferior.