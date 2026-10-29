# Requerimientos Modulo Gestion Modulos

---

## Menu principal

- [Requerimientos Modulo Gestion Modulos](#requerimientos-modulo-gestion-modulos)
  - [Menu principal](#menu-principal)
  - [1 Matriz Resumen de Requerimientos](#1-matriz-resumen-de-requerimientos)
  - [2 Detalle de los requerimientos](#2-detalle-de-los-requerimientos)
    - [RFR-01: Crear Módulo](#rfr-01-crear-módulo)
    - [RFR-02: Habilitar / Inhabilitar Módulo](#rfr-02-habilitar--inhabilitar-módulo)
    - [RFR-03: Editar Módulo](#rfr-03-editar-módulo)
    - [RFR-04: Eliminar Módulo](#rfr-04-eliminar-módulo)
    - [RFR-05: Editar Funciones Asociadas](#rfr-05-editar-funciones-asociadas)
    - [RFR-06: Eliminar Funciones Asociadas](#rfr-06-eliminar-funciones-asociadas)

## 1 Matriz Resumen de Requerimientos

| Código     | Requerimiento                  | Prioridad | Roles Permitidos    |
| :--------- | :----------------------------- | :-------: | :------------------ |
| **RFR-01** | Crear Módulo                   |   Alta    | Super Administrador |
| **RFR-02** | Habilitar / Inhabilitar Módulo |   Media   | Super Administrador |
| **RFR-03** | Editar Módulo                  |   Alta    | Super Administrador |
| **RFR-04** | Eliminar Módulo                |   Media   | Super Administrador |
| **RFR-05** | Editar Funciones Asociadas     |   Media   | Super Administrador |
| **RFR-06** | Eliminar Funciones Asociadas   |   Media   | Super Administrador |

---

## 2 Detalle de los requerimientos

### RFR-01: Crear Módulo

- **Descripción:** El sistema debe permitir al usuario con rol SUPER ADMINISTRADOR la creación de un nuevo módulo en el sistema mediante un formulario con los siguientes campos: `nombreModulo`, `icono`, `descripcion` del módulo. Esto con el fin de habilitar el módulo en el sistema para los roles requeridos.
- **Excepciones:**
  - Solo los usuarios con el rol SUPER ADMINISTRADOR pueden acceder al módulo.
  - El nombre del módulo por registrar a la base de datos debe ser igual al nombre del directorio físico.
  - Los campos obligatorios marcados con asterisco (*) son obligatorios.
  - Los campos nombre módulo y descripción deben tener un límite de caracteres; en caso de que el usuario rebase el número de caracteres permitido, el sistema debe visualizar una alerta indicando que el campo sobrepasó el límite.
  - Se debe validar que el módulo ya exista en el directorio físico del sistema **previo** a crear el módulo en la base de datos.
  - Para el campo nombre del módulo, el tipo de dato permitido es texto; no se permiten caracteres especiales.
- **Criterios de aceptación:** Cuando el usuario presione el botón "guardar", el sistema deberá mostrar un mensaje indicando la creación exitosa del módulo, habiendo realizado previamente las validaciones establecidas.

---

### RFR-02: Habilitar / Inhabilitar Módulo

- **Descripción:** El sistema debe permitir al usuario con rol SUPER ADMINISTRADOR la opción de inhabilitar o habilitar el estado del módulo para así permitir o restringir el respectivo acceso.
- **Excepciones:**
  - Solo el usuario con rol SUPER ADMINISTRADOR podrá acceder a esta característica.
  - Cuando el estado del módulo sea "Habilitado", este estará disponible para el usuario que tenga acceso; caso contrario con el estado "Inhabilitado".
  - El cambio de estado se realizará mediante un botón interactivo que permite alternar el estado del módulo.
- **Criterios de aceptación:** Una vez el usuario pulse el botón de cambiar estado, el sistema deberá realizar la modificación en la base de datos y devolver un mensaje al usuario confirmando el nuevo estado aplicado.

---

### RFR-03: Editar Módulo

- **Descripción:** El sistema debe permitir al usuario con rol SUPER ADMINISTRADOR la opción de editar la información de un módulo mediante un formulario, con el fin de mantener actualizada su información respectiva.
- **Excepciones:**
  - Los campos marcados con asteriscos (*) son obligatorios.
  - El nombre del módulo a cambiar debe ser igual al nombre del módulo en el directorio físico.
  - Solo se permiten datos de tipo texto; no se permiten números ni caracteres especiales.
  - Si el usuario desea cambiar el nombre del módulo, se deberá validar que el nuevo nombre se encuentre en el sistema de manera física y sin duplicados.
- **Criterios de aceptación:** Una vez el usuario pulse el botón de enviado, el sistema deberá mostrar un mensaje indicando la actualización exitosa de la información y reflejar el cambio en la pantalla.

---

### RFR-04: Eliminar Módulo

- **Descripción:** El sistema debe permitir al usuario con el rol SUPER ADMINISTRADOR eliminar un módulo de la base de datos mediante un botón de acción, con el fin de retirar aquellos módulos a los que ya no se requiere acceso.
- **Excepciones:**
  - El sistema deberá eliminar el módulo de la base de datos, pero **no** se eliminará desde el directorio físico.
  - En caso de eliminar el módulo, deberán eliminarse todas las funcionalidades asociadas a este.
  - En caso de haber eliminado el módulo y que su vista de acceso siga visible, al intentar acceder a él, el sistema debe mostrar el siguiente mensaje: *"El módulo no está disponible para su uso"*.
- **Criterios de aceptación:** Una vez el usuario presione el botón de aceptar, el sistema deberá eliminar el registro y mostrar un mensaje de alerta indicando que el módulo ha sido eliminado correctamente.

---

### RFR-05: Editar Funciones Asociadas

- **Descripción:** El sistema, en el módulo GESTIÓN MÓDULOS, debe permitir al usuario con rol SUPER ADMINISTRADOR editar funcionalidades asociadas a un módulo en caso de ser necesario.
- **Excepciones:**
  - La funcionalidad a editar debe estar previamente registrada en la base de datos.
  - La funcionalidad debe existir de manera física en caso de estar asociada a una clase.
  - Los campos con asteriscos (*) son obligatorios.
  - En caso de que el usuario no digite o seleccione un campo obligatorio, el sistema deberá mostrar un mensaje indicando que: *"El campo [nombre del campo del formulario] es obligatorio"*.
- **Criterios de aceptación:** Una vez el usuario pulse el botón enviar, el sistema debe mostrar un mensaje indicando que la función ha sido editada exitosamente.

---

### RFR-06: Eliminar Funciones Asociadas

- **Descripción:** El sistema, en el módulo GESTIÓN MÓDULOS, debe permitir al usuario con rol SUPER ADMINISTRADOR eliminar funcionalidades asociadas al módulo que se encuentren en la base de datos mediante un botón de acción.
- **Excepciones:**
  - Una vez el usuario presione el botón de eliminar funcionalidad, se deberá mostrar un mensaje de preconfirmación indicándole si está seguro de eliminar dicho registro.
- **Criterios de aceptación:** Una vez el usuario presione el botón de aceptar en la confirmación, el sistema deberá eliminar el registro y mostrar un mensaje indicando que la funcionalidad ha sido eliminada correctamente.
