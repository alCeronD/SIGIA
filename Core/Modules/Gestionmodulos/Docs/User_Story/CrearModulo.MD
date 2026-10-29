# Historia de Usuario: Crear Módulo

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-GM01`               |
| **Nombre**                   | Crear Módulo            |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | ALTA (1)                |
| **Requerimientos asociados** | `RFU-GM01`              |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

- **Como** SUPER ADMINISTRADOR
- **Necesito** Necesito crear un módulo
- **Para** Para así mantener un control de los módulos disponibles del sistema y poder realizar acciones de validaciones

---

## Criterios de Aceptación

- [ ] El sistema debe visualizar un formulario con los siguientes campos: nombre del módulo, icono y descripción.
- [ ] Los campos marcados con asterisco son obligatorios y no deben enviarse vacíos.
- [ ] Los campos deben tener un límite de caracteres permitido.
- [ ] Al crear el módulo, el nombre del módulo debe ser igual al que se encuentra creado de manera física en el sistema.
- [ ] Antes de crear el módulo, se debe validar que este ya esté creado de forma física en el sistema; de lo contrario, el sistema devolverá un mensaje de error indicando que el módulo no está creado en el servidor físico del producto.
