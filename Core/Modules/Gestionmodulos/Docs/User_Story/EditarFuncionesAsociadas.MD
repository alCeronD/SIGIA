# Historia de Usuario: Editar Función Asociada al Módulo

| Campo                        | Detalle                           |
| :--------------------------- | :-------------------------------- |
| **ID Historia de Usuario**   | `HU-GM06`                         |
| **Nombre**                   | Editar Función Asociada al Módulo |
| **Responsables**             | Alejandro Ceron Delgado           |
| **Prioridad**                | MEDIA (2)                         |
| **Requerimientos asociados** | `RFU-GM06`                        |
| **Elaborado por**            | Alejandro Ceron Delgado           |

---

## Descripción

- **Como** SUPER ADMINISTRADOR
- **Necesito** Necesito editar información de la función asociada al módulo
- **Para** Para actualizar campos como el icono de acceso, determinar si su funcionalidad es lógica o de tipo render, así como su respectivo acceso

---

## Criterios de Aceptación

- [ ] En caso de que la función sea de tipo render, el formulario deberá desplegar 2 campos adicionales: `VISTA PRINCIPAL O SECUNDARIA` y `RUTA DE ACCESO`.
- [ ] Al elegir la ruta de acceso de la vista, los campos `MÓDULO`, `CONTROLADOR` y `FUNCIÓN` serán obligatorios.
- [ ] Se podrá cambiar el icono asignado a la vista del módulo.
- [ ] Al pulsar el botón "Guardar", se desplegará un modal de pre-confirmación antes de ejecutar los cambios en el sistema.
