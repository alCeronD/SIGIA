# Historia de Usuario: Modificar Usuario

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-U03`                |
| **Nombre**                   | Modificar Usuario       |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | ALTA (1)                |
| **Requerimientos asociados** | `RFU-4`                 |
| **Elaborado por**            | Alejandro Ceron Delgado |

## Descripción

* **Como** Como Almacenista/administrador
* **Necesito** Necesito modificar información de los usuarios alojados en la base de datos
* **Para** Para tener la información de contacto del usuario lo más actualizada posible con el fin de realizar un seguimiento a los elementos a los cuales el usuario tiene en su poder.

## Criterios de Aceptación

* [ ] El sistema debe visualizar un formulario dentro de una ventana de tipo modal que me permita modificar la siguiente información:
  * Nombre completo
  * Teléfono
  * Dirección
  * Nota adicional
* [ ] El sistema debe visualizar un botón representado por un icono de caset el accionar de guardar cambios.
* [ ] El nro de identificación debe ser visible para el usuario, sin embargo, no debe ser modificado.
* [ ] La nota adicional debe tener un límite de caracteres de 100 caracteres
* [ ] Una vez que se haya modificado la información, el sistema debe mostrar un mensaje de alerta indicando el proceso exitoso, seguido de esto, cerrar el modal y actualizar la tabla.
