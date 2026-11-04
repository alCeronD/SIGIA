# Requerimientos Modulo Funciones

## Introduccion

Este documento contiene el listado de los requerimientos del modulo **funciones**

- [Requerimientos Modulo Funciones](#requerimientos-modulo-funciones)
  - [Introduccion](#introduccion)
  - [1 Matriz Resumen de Requerimientos](#1-matriz-resumen-de-requerimientos)
  - [2 Detalle de los requerimientos](#2-detalle-de-los-requerimientos)
    - [RFF-02: Eliminar función](#rff-02-eliminar-función)

## 1 Matriz Resumen de Requerimientos

| Código     | Requerimiento    |   Prioridad   | Roles Permitidos    |
| :--------- | :--------------- | :-----------: | :------------------ |
| **RFF-02** | Eliminar función | Alta/Esencial | Super Administrador |

---

## 2 Detalle de los requerimientos

### RFF-02: Eliminar función

- **Descripción:** El sistema debe permitir al usuario con rol super administrador eliminar la función asociada al rol mediante un botón de acción asociado que será renderizado junto a una tabla con los siguientes campos:
  - ID (primario)
  - Id función
  - Nombre de la función
  - Módulo asociado
  - Acción (botón eliminar)

  Mediante el botón de acción eliminar se deberá realizar el proceso, el sistema deberá mostrar un modal de pre-confirmación.

  Esta función solo podrá ser realizada por los usuarios con rol/roles:
  - **Super Administrador**

- **Excepciones:**
  - Si el usuario no cuenta con el rol **Super Administrador**, el sistema no debe permitir el acceso ni la ejecución de la acción.

- **Criterios de aceptación:** Una vez ejecutada la acción, el sistema debe mostrar un mensaje de alerta indicando al usuario que la funcionalidad asociada al rol ha sido eliminada con éxito.

---
