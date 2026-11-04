# Historia de Usuario: Eliminar Módulo

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-GM04`               |
| **Nombre**                   | Eliminar Módulo         |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | MEDIA (2)               |
| **Requerimientos asociados** | `RFU-GM04`              |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

- **Como** SUPER ADMINISTRADOR
- **Necesito** Necesito eliminar un módulo de mi sistema
- **Para** Para así mantener al día la base de datos de los módulos que pertenecen al sistema

---

## Criterios de Aceptación

- [ ] Antes de ejecutar la eliminación, el sistema debe mostrar un modal de pre-confirmación indicando si el usuario está seguro de realizar la acción.
- [ ] Se debe validar que el directorio del módulo de manera física exista en el sistema antes de procesar la eliminación.
- [ ] En caso de que el módulo a eliminar no exista en la base de datos, el sistema debe enviar un mensaje indicando que el módulo proporcionado no existe.
