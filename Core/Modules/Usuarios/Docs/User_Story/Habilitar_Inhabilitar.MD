# Historia de Usuario: Habilitar/Inhabilitar

| Campo                        | Detalle                       |
| :--------------------------- | :---------------------------- |
| **ID Historia de Usuario**   | `HU-U02`                      |
| **Nombre**                   | Habilitar/Inhabilitar Usuario |
| **Responsables**             | Alejandro Ceron Delgado       |
| **Prioridad**                | ALTA (1)                      |
| **Requerimientos asociados** | `RFU-4`                       |
| **Elaborado por**            | Alejandro Ceron Delgado       |

## Descripción

* **Como** Como Almacenista/administrador
* **Necesito** Necesito cambiar el estado del usuario a activo o inactivo dependiendo de su estado actual
* **Para** Para así mantener un control de los usuarios que pueden realizar reservas inmediatas o previas en el aplicativo.

## Criterios de Aceptación

* [ ]El sistema debe visualizar un botón de acción perteneciente a cada usuario que me permita inhabilitar o activar el usuario dependiendo del estado actual.
* [ ]El botón debe reflejar el estado opuesto al usuario, si está Activo, el botón debe decir “Inactivo”, si el usuario esta Inactivo, el botón debe decir “Activar”, o en su defecto iconos representativos que representen el estado a cambiar del usuario.
* [ ]El sistema debe mostrar un mensaje de confirmación con el siguiente texto: “¿Esta seguro de cambiar el estado del usuario?”
* [ ]El sistema debe mostrar un mensaje de éxito en caso de que el usuario haya elegido continuar con el proceso, en caso contrario, el proceso debe ser cancelado, Luego de mostrar el mensaje de éxito, los cambios deben ser reflejados en la tabla y el botón de acción debe de cambiar su texto según el estado actual del registro.
