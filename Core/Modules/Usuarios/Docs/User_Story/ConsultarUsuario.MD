# Historia de Usuario: Consultar Usuario

| Campo                        | Detalle                 |
| :--------------------------- | :---------------------- |
| **ID Historia de Usuario**   | `HU-U02`                |
| **Nombre**                   | Consultar Usuario       |
| **Responsables**             | Alejandro Ceron Delgado |
| **Prioridad**                | ALTA (1)                |
| **Requerimientos asociados** | `RFU-2`                 |
| **Elaborado por**            | Alejandro Ceron Delgado |

## Descripción

* **Como** Como Almacenista/administrador
* **Necesito** Necesito consultar los usuarios que se encuentren alojados en la base de datos
* **Para** Para poder interactuar con sus acciones y procesos como ver información del usuario, activar, inactivar usuario y modificar información del usuario, asignar permiso al rol usuario

## Criterios de Aceptación

* [X] El sistema debe visualizar el listado de los usuarios a través de una tabla organizada de la siguiente manera:
  * Nro identificación
  * Nombre completo
  * Estado
  * Botones de acciones
* [X] El sistema me debe permitir mediante un formulario poder buscar las posibles coincidencias de los usuarios registrados en la base de datos, los criterios a buscar los usuarios es el siguiente:
  * [x] Nro de identificación
  * [x] Nombres
  * [x] Estado (Activo o inactivo).
  * [x] Cada fila debe de tener los siguientes botones de acciones
    * Editar información
    * Ver información
    * Activar o inactivar según su estado.
    * Asignar rol
