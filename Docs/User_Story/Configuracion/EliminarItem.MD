# Historia de Usuario: Eliminar Ítem

| Campo                        | Detalle                                            |
| :--------------------------- | :------------------------------------------------- |
| **ID Historia de Usuario**   | `HUConfig-07`                                      |
| **Nombre**                   | Eliminar Ítem                                      |
| **Responsables**             | Alejandro Cerón Delgado / Brahiam Alexander Llanos |
| **Prioridad**                | ALTA (1)                                           |
| **Requerimientos asociados** | `RF-CONFIG-07`                                     |
| **Elaborado por**            | Alejandro Cerón Delgado                            |

---

## Descripción

* **Como** usuario administrador / super administrador
* **Necesito** eliminar registros que ya no sean de utilidad en los módulos
* **Para** mantener limpia y al día la base de datos del sistema

---

## Criterios de Aceptación

* [ ] Solo el usuario con rol administrador / super administrador puede eliminar los registros.
* [ ] Se debe previsualizar un mensaje de preconfirmación antes de ejecutar la acción.
* [ ] Mostrar un mensaje de éxito en caso de que el registro haya sido eliminado correctamente.
* [ ] Esta funcionalidad/proceso aplica a los siguientes módulos: marcas, áreas, tipos de documento, categorías y roles.
