# Historia de Usuario: Activar / Inhabilitar Módulo

| Campo                        | Detalle                      |
| :--------------------------- | :--------------------------- |
| **ID Historia de Usuario**   | `HU-GM03`                    |
| **Nombre**                   | Activar / Inhabilitar Módulo |
| **Responsables**             | Alejandro Ceron Delgado      |
| **Prioridad**                | MEDIA (2)                    |
| **Requerimientos asociados** | `RFU-GM03`                   |
| **Elaborado por**            | Alejandro Ceron Delgado      |

---

## Descripción

- **Como** SUPER ADMINISTRADOR
- **Necesito** Necesito activar o desactivar un módulo
- **Para** Para así validar el acceso al módulo dependiendo del rol

---

## Criterios de Aceptación

- [ ] En caso de que el módulo esté inactivo y un usuario intente acceder a él (incluso si su rol tiene permisos concedidos), el sistema deberá mostrar un mensaje de alerta indicando que el módulo está desactivado.
- [ ] El sistema debe permitir cambiar el estado del módulo entre Activo e Inactivo de forma clara.
