# Historia de Usuario: Eliminar Función Asociada al Módulo

| Campo                        | Detalle                             |
| :--------------------------- | :---------------------------------- |
| **ID Historia de Usuario**   | `HU-GM07`                           |
| **Nombre**                   | Eliminar Función Asociada al Módulo |
| **Responsables**             | Alejandro Ceron Delgado             |
| **Prioridad**                | BAJA (3)                            |
| **Requerimientos asociados** | `RFU-GM07`                          |
| **Elaborado por**            | Alejandro Ceron Delgado             |

---

## Descripción

- **Como** SUPER ADMINISTRADOR
- **Necesito** Necesito eliminar funciones asociadas al módulo
- **Para** Para mantener al día los permisos de acceso del módulo con el fin de asignar dicha funcionalidad al ROL correspondiente

---

## Criterios de Aceptación

- [ ] Al ejecutar la acción, el sistema debe mostrar un modal de pre-confirmación con 2 botones de acción: "Guardar" y "Cancelar".
- [ ] Si el usuario pulsa "Guardar", el sistema debe validar que el módulo y la funcionalidad existan en la base de datos, ejecutar la eliminación y mostrar una alerta confirmando la eliminación.
- [ ] Si el usuario pulsa "Cancelar", el sistema debe cerrar el modal sin ejecutar ningún proceso ni modificar la base de datos.
