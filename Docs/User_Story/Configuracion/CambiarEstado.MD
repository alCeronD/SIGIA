# Historia de Usuario: Cambiar estado

| Campo                        | Detalle                                            |
| :--------------------------- | :------------------------------------------------- |
| **ID Historia de Usuario**   | `HU-CONFIG7`                                       |
| **Nombre**                   | Cambiar Estado                                     |
| **Responsables**             | Alejandro Ceron Delgado / Brahiam Alexander Llanos |
| **Prioridad**                | ALTA (1)                                           |
| **Requerimientos asociados** | `RF-CONFIG-07`, `RF-CONFIG-08`                     |
| **Elaborado por**            | Alejandro Ceron Delgado                            |

---

## Descripción

* **Como** usuario administrador / super administrador
* **Necesito** poder cambiar el estado de un ítem (activo o inactivo)
* **Para** que este ítem no tenga acceso a los módulos como elementos, préstamos, usuarios, entre otros.

---

## Criterios de Aceptación

* [ ] El ítem debe estar registrado en la base de datos.
* [ ] No se permiten caracteres especiales.
* [ ] Esta funcionalidad/proceso aplica a los siguientes módulos: marcas, áreas, tipos de documento, categorías y roles.
* [ ] Cuando su estatus cambie a **inactivo**, este no debe estar visible para su utilidad.
* [ ] Cuando el estatus cambie a **activo**, este ítem deberá estar visible para su utilidad en los respectivos módulos de usuarios, reservas, entre otros.
