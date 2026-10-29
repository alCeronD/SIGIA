# Historia de Usuario: Asociar Funciones al Módulo

| Campo                        | Detalle                     |
| :--------------------------- | :-------------------------- |
| **ID Historia de Usuario**   | `HU-GM05`                   |
| **Nombre**                   | Asociar Funciones al Módulo |
| **Responsables**             | Alejandro Ceron Delgado     |
| **Prioridad**                | MEDIA (2)                   |
| **Requerimientos asociados** | `RFU-GM05`                  |
| **Elaborado por**            | Alejandro Ceron Delgado     |

---

## Descripción

- **Como** SUPER ADMINISTRADOR
- **Necesito** Necesito asociar funciones al módulo mediante un formulario
- **Para** Para así validar e implementar los permisos de acceso a los roles del sistema, limitando los accesos según el rol del usuario

---

## Criterios de Aceptación

- [ ] Los campos marcados con asteriscos deben ser obligatorios.
- [ ] Si el usuario selecciona la opción "render", el sistema desplegará 2 campos adicionales: `VISTA PRINCIPAL O VISTA SECUNDARIA` y `RUTA DE ACCESO`.
- [ ] La `RUTA DE ACCESO` requerirá de forma obligatoria los campos: `MÓDULO`, `CONTROLADOR` y `FUNCIÓN`.
- [ ] El sistema debe validar que la función exista en el controlador especificado.
- [ ] Una vez completado el registro, el sistema debe mostrar un mensaje notificando que la funcionalidad ha sido registrada con éxito.
