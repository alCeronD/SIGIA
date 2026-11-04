# Requerimientos Modulo Roles

---

- [Requerimientos Modulo Roles](#requerimientos-modulo-roles)
  - [Introdución](#introdución)
    - [Matriz de Resumen de Requerimientos Funcionales (Módulo de Roles)](#matriz-de-resumen-de-requerimientos-funcionales-módulo-de-roles)
    - [RFR-01: Crear Rol](#rfr-01-crear-rol)
    - [RFR-02: Habilitar / Inhabilitar Rol](#rfr-02-habilitar--inhabilitar-rol)
    - [RFR-03: Editar Rol](#rfr-03-editar-rol)
    - [RFR-04: Eliminar Rol](#rfr-04-eliminar-rol)

## Introdución

En este documento se encuentran los requerimientos establecidos para el desarollo del modulo de usuarios.

### Matriz de Resumen de Requerimientos Funcionales (Módulo de Roles)

| Código     | Nombre del Requerimiento    | Actor(es) Principal(es)             | Prioridad / Tipo           | Descripción Breve                                                                                                                   |
| :--------- | :-------------------------- | :---------------------------------- | :------------------------- | :---------------------------------------------------------------------------------------------------------------------------------- |
| **RFR-01** | Crear Rol                   | Super administrador / Administrador | Alta / Esencial (Deseable) | Permite crear un rol mediante un formulario ingresando nombre, descripción y estableciendo el estado como ACTIVO por defecto.       |
| **RFR-02** | Habilitar / Inhabilitar Rol | Super administrador / Administrador | Media / Deseado            | Permite cambiar el estado operativo de un rol entre activo e inactivo para controlar su acceso en el sistema.                       |
| **RFR-03** | Editar Rol                  | Super administrador / Administrador | Alta / Esencial (Deseable) | Permite modificar la información existente de un rol (nombre y descripción) validando restricciones de caracteres.                  |
| **RFR-04** | Eliminar Rol                | Super administrador                 | Alta / Esencial (Deseable) | Permite eliminar permanentemente un rol del sistema mediante pre-confirmación y validando que no posea funciones asociadas.         |
| **RFR-05** | Asignar Permisos            | Administrador / Super administrador | Media / Deseado            | Permite asignar o revocar permisos de acceso a un rol mediante un modal interactivo con módulos y opciones de selección (checkbox). |

### RFR-01: Crear Rol

- **Descripción:**
- El sistema debe permitir al usuario con rol **Super administrador / Administrador** crear un rol en el sistema mediante un formulario con los siguientes campos:
  - Nombre
  - Descripción del rol
  - Estado (este debe establecerse como **ACTIVO** de manera automática).
- Solo los usuarios con rol **Super administrador / Administrador** pueden acceder a esta funcionalidad.
- El campo nombre no debe estar vacío.
- No se aceptan duplicados en el campo nombre, en caso de que ya esté registrado el rol en la base de datos.
- No se permiten caracteres vacíos en aquellos campos marcados como obligatorios mediante el carácter asterisco (*).
- El límite de caracteres del campo nombre debe ser **25 caracteres**.
- El límite de caracteres del campo descripción debe ser **100 caracteres**.

- **Excepciones:**
- Si el campo nombre se encuentra vacío, el sistema debe indicar que es un campo obligatorio.
- Si se intenta registrar un nombre de rol que ya existe en la base de datos, el sistema debe alertar sobre el duplicado.
- Si se superan los límites de caracteres permitidos en los campos correspondientes, el sistema debe bloquear la acción y notificar al usuario.

- **Criterios de aceptación:** Una vez validados los datos, el sistema debe crear el rol y mostrar un mensaje de alerta indicando que el rol ha sido creado exitosamente. De manera inmediata, el registro debe visualizarse en la tabla pertinente.

---

### RFR-02: Habilitar / Inhabilitar Rol

- **Descripción:**
- El sistema debe permitir al usuario con rol **Super administrador / Administrador** cambiar el estado de un rol del sistema con el fin de validar el acceso a las funcionalidades del sistema.
- Si el estado actual es **activo**, el sistema debe cambiar el estado del rol a **inactivo**, y viceversa si el estado actual es inactivo.
- Solo los usuarios con rol **Super administrador / Administrador** pueden realizar esta acción.

- **Excepciones:**
- El sistema debe mostrar una alerta en caso de que el rol no esté registrado en la base de datos y se quiera ejecutar esta acción.
- Si ocurre un error de procesamiento o conexión, el sistema debe notificar al usuario sobre el fallo en la actualización del estado.

- **Criterios de aceptación:** Una vez validada la acción a ejecutar, el sistema debe realizar el cambio de estado en la base de datos, mostrar un mensaje de alerta indicando el cambio de estado exitoso y recargar el listado de registros.

---

### RFR-03: Editar Rol

- **Descripción:**
- El sistema debe permitir al usuario con rol **Super administrador / Administrador** cambiar la información registrada del rol mediante un formulario con los siguientes campos:
  - Nombre
  - Descripción
  - Botón de envío (para guardar la información en la base de datos).
- Solo los usuarios con rol **Super administrador / Administrador** pueden efectuar esta acción.
- Los campos establecidos con asteriscos son obligatorios.
- El campo nombre no debe estar vacío.
- En el campo nombre **no se permiten caracteres especiales ni caracteres numéricos**.

- **Excepciones:**
- Si el campo nombre se deja vacío o contiene caracteres prohibidos (números o especiales), el sistema debe impedir el envío y mostrar un mensaje de error.
- En caso de error del sistema al haber ejecutado el proceso, el sistema debe mostrar un mensaje de alerta indicando el error o la excepción establecida.

- **Criterios de aceptación:** Una vez ejecutado el proceso correctamente, el sistema debe mostrar una alerta al usuario indicando que la información ha sido actualizada.

---

### RFR-04: Eliminar Rol

- **Descripción:**
- El sistema debe permitir al usuario con rol **Super administrador** eliminar un rol mediante un botón de acción asociado al registro correspondiente. Para efectuar este proceso, el superadministrador deberá pulsar el botón de acción y pre-confirmar la acción previamente realizada.
- Solo el usuario **Super administrador** puede ejecutar dicha acción.
- El rol que tenga funciones asociadas deberá primero eliminar dichas funciones asociadas en el módulo de funciones.

- **Excepciones:**
- Al momento de ejecutar la acción, si el usuario cancela el proceso en la ventana de confirmación, el rol no deberá ser eliminado.
- Si el rol cuenta con dependencias o funciones activas sin depurar, el sistema impedirá la eliminación y arrojará una alerta.
- Si ocurre un error técnico en el servidor durante la operación, se notificará al usuario mediante una alerta.

- **Criterios de aceptación:** Una vez ejecutada la acción validando las excepciones previas, el sistema realizará la eliminación del rol, informará mediante una alerta al usuario que se ha eliminado correctamente, recargará el recurso
