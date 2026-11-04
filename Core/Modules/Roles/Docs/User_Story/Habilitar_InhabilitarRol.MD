
# Historia de Usuario: Habilitar/Inhabilitar Rol

| Campo                        | Detalle                   |
| :--------------------------- | :------------------------ |
| **ID Historia de Usuario**   | `HU-R03`                  |
| **Nombre**                   | Habilitar/Inhabilitar rol |
| **Responsables**             | Alejandro Ceron Delgado   |
| **Prioridad**                | MEDIA (2)                 |
| **Requerimientos asociados** | `RFU-4`                   |
| **Elaborado por**            | Alejandro Ceron Delgado   |

---

## Descripción

- **Como** Super Administrador
- **Necesito** Necesito activar o desactivar un rol en la plataforma
- **Para** Para así restringir o validar el acceso a los módulos dependiendo del estado del rol

---

## Criterios de Aceptación

- [ ] El sistema debe permitir cambiar el estado de un rol entre "Activo" e "Inactivo".
- [ ] Si un rol o módulo se encuentra inactivo, los usuarios asociados a dicho rol deberán visualizar un mensaje en pantalla indicando que no tienen acceso permitido.
- [ ] La restricción de rol inactivo debe aplicarse tanto para la autenticación (inicio de sesión) como para el acceso a módulos internos.
