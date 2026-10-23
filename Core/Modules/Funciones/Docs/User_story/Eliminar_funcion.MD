# Historia de Usuario: Eliminar Función

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-R1`                 |
| **Nombre**                   | Eliminar Función        |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | BAJA (3)                |
| **Requerimientos asociados** | `RF-R1`                 |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

- **Como** Super Administrador
- **Necesito** Eliminar una función del sistema
- **Para** Mantener al día las funciones del sistema relacionadas con el controlador

---

## Criterios de Aceptación

- [ ] Al ejecutar la acción de eliminar, el sistema debe mostrar una ventana modal de pre-confirmación antes de procesar la solicitud.
- [ ] Si el usuario hace clic en "Aceptar", el sistema debe validar que la funcionalidad exista en la base de datos antes de proceder con la eliminación.
- [ ] Si el usuario hace clic en "Cancelar", el proceso no debe ejecutarse y la ventana modal de pre-confirmación debe cerrarse.
