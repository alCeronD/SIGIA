# Historia de Usuario: Modificar Módulo

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-GM02`               |
| **Nombre**                   | Modificar Módulo        |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | MEDIA (2)               |
| **Requerimientos asociados** | `RFU-GM02`              |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

- **Como** SUPER ADMINISTRADOR
- **Necesito** Necesito modificar información de los módulos
- **Para** Con el fin de mantener la información de los módulos actualizada

---

## Criterios de Aceptación

- [ ] El sistema debe visualizar un formulario con los siguientes campos: nombre del módulo, icono y descripción.
- [ ] Los campos marcados con asterisco son obligatorios y no deben enviarse vacíos.
- [ ] Los campos deben tener un límite de caracteres permitido.
- [ ] Al modificar la información del módulo, el nombre debe ser único.
- [ ] En caso de que el super administrador actualice el nombre del módulo, el sistema deberá validar que dicho nombre no esté previamente registrado en la base de datos para evitar duplicados.
- [ ] En caso de ajustar el nombre del módulo, el sistema deberá validar que el nuevo módulo ya esté instalado en el servidor físico; si no se encuentra instalado, el sistema mostrará un mensaje indicando la razón por la cual no se puede editar.
