# Caso de uso extendido - Modulo Roles

---

- [Caso de uso extendido - Modulo Roles](#caso-de-uso-extendido---modulo-roles)
  - [Introducción](#introducción)
  - [CU-02: Crear Rol](#cu-02-crear-rol)
    - [Curso normal de los eventos CU-02](#curso-normal-de-los-eventos-cu-02)
    - [Cursos alternos CU-02](#cursos-alternos-cu-02)
  - [CU-03: Editar Rol](#cu-03-editar-rol)
    - [Curso normal de los eventos CU-03](#curso-normal-de-los-eventos-cu-03)
    - [Cursos alternos CU-03](#cursos-alternos-cu-03)
  - [CU-04: Eliminar Rol](#cu-04-eliminar-rol)
    - [Curso normal de los eventos CU-04](#curso-normal-de-los-eventos-cu-04)
    - [Cursos alternos CU-04](#cursos-alternos-cu-04)
  - [CU-05: Activar / Inactivar Rol](#cu-05-activar--inactivar-rol)
    - [Curso normal de los eventos CU-05](#curso-normal-de-los-eventos-cu-05)
    - [Cursos alternos CU-05](#cursos-alternos-cu-05)
  - [CU-06: Asignar Permisos](#cu-06-asignar-permisos)
    - [Curso normal de los eventos CU-06](#curso-normal-de-los-eventos-cu-06)
    - [Cursos alternos CU-06](#cursos-alternos-cu-06)

## Introducción

Este documento tiene como finalidad establecer una especificación a los casos de usos definidos por el módulo, es una forma de comprender cual es el comportamiento que debe de tener el módulo bajo diversos escenarios.

---

## CU-02: Crear Rol

| Atributo                 | Detalle                                                                                                                                                      |
| :----------------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Código**               | **CU-02**                                                                                                                                                    |
| **Nombre**               | Crear Rol                                                                                                                                                    |
| **Descripción**          | El sistema permitirá crear roles de usuario, mediante un formulario con un campo para el nombre y una lista de los permisos que se le otorgarán a dicho rol. |
| **Actor(es)**            | Administrador / Super Administrador                                                                                                                          |
| **Tipo**                 | Primario                                                                                                                                                     |
| **Referencias Cruzadas** | RFR-01                                                                                                                                                       |

---

### Curso normal de los eventos CU-02

|  N°   | Acción del Actor                     | Respuesta del Sistema                                                                                                                                                                                                                                                                                                                           |
| :---: | :----------------------------------- | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Indica “Crear rol”                   | **1.1** Muestra un formulario con un campo para el nombre del rol, descripción del rol y un botón de “Crear”.                                                                                                                                                                                                                                   |
| **2** | Ingresa el nombre del nuevo rol      | **2.1** Valida que no se ingresen números.<br>**2.2** Valida que el nombre no sea un campo vacío.                                                                                                                                                                                                                                               |
| **3** | Ingresa la descripción del nuevo rol | **3.1** N/A                                                                                                                                                                                                                                                                                                                                     |
| **4** | Indica “Crear”                       | **4.1** Verifica que los campos obligatorios hayan sido diligenciados.<br>**4.2** Se conecta con la base de datos.<br>**4.3** Verifica que no exista un rol creado con el mismo nombre del rol a registrar.<br>**4.4** Crea una nueva fila en la base de datos con los datos enviados.<br>**4.5** Muestra un mensaje de éxito al usuario final. |

### Cursos alternos CU-02

* **2.1a - Números en el nombre:**
  * Muestra mensaje: *"No se permiten números en este campo"*.
* **2.2a - Campo vacío:**
  * Muestra mensaje: *"El campo nombre no debe estar vacío"*.
* **3.1a - Campos obligatorios incompletos:**
  * Muestra mensaje: *"El campo [NOMBRE DEL CAMPO] debe ser obligatorio"*.
* **4.2a - Error de conexión:**
  * Muestra mensaje: *"Ha ocurrido un error al conectarse a la base de datos"*.
* **4.3a - Rol duplicado:**
  * Muestra mensaje: *"No se permiten duplicados"*.
* **4.5a - Error al ejecutar el proceso:**
  * Muestra mensaje: *"Ocurrió un error al ejecutar el proceso"*.

---

## CU-03: Editar Rol

| Atributo                 | Detalle                                                                                                                    |
| :----------------------- | :------------------------------------------------------------------------------------------------------------------------- |
| **Código**               | **CU-03**                                                                                                                  |
| **Nombre**               | Modificar Roles de Usuario                                                                                                 |
| **Descripción**          | El sistema permitirá modificar roles de usuario, mediante un formulario con un campo para el nombre y descripción del rol. |
| **Actor(es)**            | Administrador / Super Administrador                                                                                        |
| **Tipo**                 | Primario                                                                                                                   |
| **Referencias Cruzadas** | RFT-10                                                                                                                     |

---

### Curso normal de los eventos CU-03

|  N°   | Acción del Actor                  | Respuesta del Sistema                                                                                                                                                                                                                                                                                                              |
| :---: | :-------------------------------- | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Indica “Modificar rol”            | **1.1** Muestra un formulario con un campo editable para el nombre del rol que tenga por defecto el nombre actual del rol, junto con la descripción del rol.                                                                                                                                                                       |
| **2** | Ingresa el nuevo nombre del rol   | **2.1** Valida que no se ingresen números.<br>**2.2** Valida que el nombre no sea un campo vacío.                                                                                                                                                                                                                                  |
| **3** | Ingresa nueva descripción del rol | **3.1** Registra la nueva descripción.<br>**3.2** Valida que no se permitan caracteres especiales.                                                                                                                                                                                                                                 |
| **4** | Indica “Guardar”                  | **4.1** Verifica que los campos obligatorios hayan sido diligenciados.<br>**4.2** Se conecta con la base de datos.<br>**4.3** Verifica que no exista un rol creado con el mismo nombre que el nuevo rol.<br>**4.4** Obtiene el ID del rol que se desea modificar.<br>**4.5** Cambia el nombre y los campos adicionales requeridos. |

### Cursos alternos CU-03

* **2.2a - Campo vacío:**
  * Muestra mensaje: *"El campo nombre no debe estar vacío"*.
* **2.2b - Caracteres numéricos en el nombre:**
  * Muestra mensaje: *"El campo nombre no permite caracteres numéricos"*.
* **4.1a - Datos incompletos:**
  * Muestra mensaje: *"Datos incompletos"*.
* **4.2a - Error de conexión:**
  * Muestra mensaje: *"Ocurrió un error de conexión con el servidor"*.
* **4.3a - Nombre de rol existente:**
  * Muestra mensaje: *"Actualmente existe un rol con ese nombre"*.
* **4.5a - Error al modificar:**
  * Muestra mensaje: *"Ocurrió un error al modificar el rol"*.

---

## CU-04: Eliminar Rol

| Atributo                 | Detalle                                                                                                                |
| :----------------------- | :--------------------------------------------------------------------------------------------------------------------- |
| **Código**               | **CU-04**                                                                                                              |
| **Nombre**               | Eliminar Roles de Usuario                                                                                              |
| **Descripción**          | El sistema permitirá al usuario eliminar los roles de usuario, mostrando una alerta para la confirmación de la acción. |
| **Actor(es)**            | Super Administrador                                                                                                    |
| **Tipo**                 | Primario                                                                                                               |
| **Referencias Cruzadas** | RFR-04                                                                                                                 |

---

### Curso normal de los eventos CU-04

|  N°   | Acción del Actor      | Respuesta del Sistema                                                                                                                           |
| :---: | :-------------------- | :---------------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Indica “Eliminar rol” | **1.1** Muestra una alerta con el mensaje: “¿Está seguro que desea eliminar este rol?”, y dos botones para “Cancelar” y “Eliminar”.             |
| **2** | Indica “Cancelar”     | **2.1** Esconde la alerta y termina la acción.                                                                                                  |
| **3** | Indica “Eliminar”     | **3.1** Se conecta con la base de datos.<br>**3.2** Obtiene el ID del rol que se desea eliminar.<br>**3.3** Elimina el rol de la base de datos. |

### Cursos alternos CU-04

* **3.1a - Error de conexión:**
  * Muestra mensaje: *"Ocurrió un error de conexión con el servidor"*.
* **3.3a - Error al eliminar:**
  * Muestra mensaje: *"Ocurrió un error al eliminar el rol"*.

---

## CU-05: Activar / Inactivar Rol

| Atributo                 | Detalle                                                                                                                                                                            |
| :----------------------- | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Código**               | **CU-05**                                                                                                                                                                          |
| **Nombre**               | Activar Inactivar Roles de Usuario                                                                                                                                                 |
| **Descripción**          | El sistema permitirá cambiar el estado del rol de usuario, mediante un botón de acción con el fin de visualizar un mensaje de pre-confirmación para realizar el cambio o cancelar. |
| **Actor(es)**            | Administrador / Super Administrador                                                                                                                                                |
| **Tipo**                 | Secundario                                                                                                                                                                         |
| **Referencias Cruzadas** | RFR-02                                                                                                                                                                             |

---

### Curso normal de los eventos CU-05

|  N°   | Acción del Actor               | Respuesta del Sistema                                                                                                                                                                                     |
| :---: | :----------------------------- | :-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Indica “Activar/Inactivar rol” | **1.1** Muestra una ventana de pre-confirmación con un mensaje personalizado dependiendo de la acción a realizar, indicando si desea activar o inactivar el rol.                                          |
| **2** | Presiona “Cancelar”            | **2.1** La ventana de pre-confirmación se cierra y el proceso no se ejecuta.                                                                                                                              |
| **3** | Presiona “Aceptar”             | **3.1** Verifica que los datos requeridos sean correctos.<br>**3.2** Se conecta con la base de datos.<br>**3.3** Verifica que el rol a activar o inactivar exista.<br>**3.4** Cambia el estado requerido. |

### Cursos alternos CU-05

* **3.1a - Datos incompletos:**
  * Muestra mensaje: *"Datos incompletos"*.
* **3.1b - Estado enviado incorrecto:**
  * Muestra mensaje: *"Estado enviado incorrecto"*.
* **3.3a - Error de conexión:**
  * Muestra mensaje: *"Ha ocurrido un error al conectarse a la base de datos"*.
* **3.4a - Error al actualizar el estado:**
  * Muestra mensaje: *"Ocurrió un error al actualizar el estado del rol"*.

---

## CU-06: Asignar Permisos

| Atributo                 | Detalle                                                                                                                                                                               |
| :----------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Código**               | **CU-06**                                                                                                                                                                             |
| **Nombre**               | Asignar Roles de Usuario (Permisos)                                                                                                                                                   |
| **Descripción**          | El sistema permitirá asignar permisos de acceso al rol mediante una ventana modal compuesta por el nombre del módulo y las funcionalidades propias para marcar o desmarcar el acceso. |
| **Actor(es)**            | Administrador / Super Administrador                                                                                                                                                   |
| **Tipo**                 | Primario                                                                                                                                                                              |
| **Referencias Cruzadas** | RFR-05                                                                                                                                                                                |

---

### Curso normal de los eventos CU-06

|  N°   | Acción del Actor              | Respuesta del Sistema                                                                                                                                                                                                                                         |
| :---: | :---------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **1** | Indica “Asignar permisos rol” | **1.1** Despliega una ventana de tipo modal con diferentes cajas de información (cada una constituida por el módulo y las acciones del rol), además de un botón de “Crear/Guardar”.                                                                           |
| **2** | Pulsa el botón “Guardar”      | **2.1** Despliega una ventana de pre-confirmación de acción.                                                                                                                                                                                                  |
| **3** | Selecciona “Cancelar”         | **3.1** Cierra la ventana de pre-confirmación cancelando el proceso de guardado.                                                                                                                                                                              |
| **4** | Indica “Guardar” (Confirmar)  | **4.1** Verifica que haya como mínimo un permiso marcado.<br>**4.2** Valida que los datos enviados sean correctos.<br>**4.3** Se conecta con la base de datos.<br>**4.4** Aplica la lógica de negocio requerida.<br>**4.5** Establece los permisos de acceso. |

### Cursos alternos CU-06

* **4.1a - Sin permisos:**
  * Muestra mensaje: *"No hay permisos asignados"*.
* **4.2a - Datos incorrectos:**
  * Muestra mensaje: *"Datos enviados incorrectamente"*.
* **4.3a - Error de conexión:**
  * Muestra mensaje: *"Ocurrió un error de conexión con el servidor"*.
* **4.5a - Error al modificar:**
  * Muestra mensaje: *"Ocurrió un error al modificar el rol"*.
