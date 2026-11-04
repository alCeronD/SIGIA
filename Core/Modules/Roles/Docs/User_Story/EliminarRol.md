
# Historia de Usuario: Eliminar Rol

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-R04`                |
| **Nombre**                   | Eliminar rol            |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | BAJA (3)                |
| **Requerimientos asociados** | `RFU-5`                 |
| **Elaborado por**            | Alejandro Ceron Delgado |

---

## Descripción

- **Como** Super Administrador
- **Necesito** Necesito eliminar un rol del sistema
- **Para** Para así descartar diferentes accesos al sistema en caso de que estos ya no sean requeridos

---

## Criterios de Aceptación

- [ ] Al ejecutar la acción de eliminar, el sistema debe desplegar una ventana modal de pre-confirmación antes de realizar el borrado.
- [ ] En caso de que el usuario haga clic en "Aceptar", el sistema debe validar que el rol NO tenga funciones o usuarios asociados.
- [ ] Si el rol tiene funcionalidades o usuarios asociados, el sistema no permitirá la eliminación y mostrará un mensaje de alerta indicando que debe desvincular las dependencias antes de continuar.
- [ ] En caso de que el usuario haga clic en "Cancelar", el proceso se cancelará y la ventana modal se cerrará sin realizar cambios.
