# Caso de uso extendido - Modulos de configuracion

---

- [Caso de uso extendido - Modulos de configuracion](#caso-de-uso-extendido---modulos-de-configuracion)
  - [Introducción](#introducción)
  - [Caso de Uso Extendido: Módulos de Configuración](#caso-de-uso-extendido-módulos-de-configuración)
  - [CU-01: Crear Item](#cu-01-crear-item)
    - [Curso Normal de los Eventos](#curso-normal-de-los-eventos)
    - [Cursos Alternos CU-01](#cursos-alternos-cu-01)
  - [CU-02: Modificar Item](#cu-02-modificar-item)
    - [Curso normal de los eventos CU-02](#curso-normal-de-los-eventos-cu-02)
    - [Cursos Alternos CU-02](#cursos-alternos-cu-02)
  - [CU-03: Eliminar Item](#cu-03-eliminar-item)
    - [Curso normal de los eventos CU-03](#curso-normal-de-los-eventos-cu-03)
    - [Cursos Alternos CU-03](#cursos-alternos-cu-03)
  - [CU-04: Habilitar/Inhabilitar Item](#cu-04-habilitarinhabilitar-item)
    - [Curso normal de los eventos CU-04](#curso-normal-de-los-eventos-cu-04)
    - [Cursos Alternos CU-04](#cursos-alternos-cu-04)

## Introducción

Este documento contiene informacion acerca de los diferentes **casos de uso extendidos** de los modulos de configuración del sistema, los módulos son los siguientes, estos casos de uso consisten en un específicar de manera más detallada los casos de uso de los módulos

- [Areas](/Core/Modules/Areas/README.MD)
- [Tipo de documento](/Core/Modules/Tipodocumento/README.MD)
- [Categorias](/Core/Modules/Categorias/README.MD)
- [Marcas](/Core/Modules/Marcas/README.MD)

Los módulos previamente mencionados trabajan de la misma forma por ende la documentación de casos de uso y caso de uso extendido es general

## Caso de Uso Extendido: Módulos de Configuración

---

## CU-01: Crear Item

| Atributo                 | Detalle                                                                                                                        |
| :----------------------- | :----------------------------------------------------------------------------------------------------------------------------- |
| **Código**               | **CU-01**                                                                                                                      |
| **Nombre**               | Crear Item                                                                                                                     |
| **Descripción**          | El sistema permitirá al usuario crear un nuevo elemento mediante el uso de un formulario con un campo para el nombre de estos. |
| **Actor(es)**            | Administrador/Super Administrador                                                                                              |
| **Tipo**                 | Primario                                                                                                                       |
| **Referencias Cruzadas** | N/A                                                                                                                            |

---

### Curso Normal de los Eventos

|  N°   | Acción del Actor                                                   | Respuesta del Sistema                                                                                                                                                                                         |
| :---: | :----------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **1** | Este caso de uso comienza cuando el usuario indica “Crear estado”. | **1.1** El sistema mostrará un formulario con un campo editable para el nombre del estado y un botón de “Crear”.                                                                                              |
| **2** | El usuario ingresa el nombre del nuevo estado.                     | **2.1** El sistema no permitirá que se ingresen números.<br>**2.2** El sistema valida que el nombre no sea un campo vacío.                                                                                    |
| **3** | El usuario indica “Crear”.                                         | **3.1** El sistema se conecta con la base de datos.<br>**3.2** El sistema verifica que no exista un estado creado con el mismo nombre que el nuevo.<br>**3.3** Se crea una nueva fila con los datos enviados. |

---

### Cursos Alternos CU-01

* **2.2a - Campo vacío:**
  * El sistema mostrará un mensaje de advertencia: *“Complete los campos”*.
* **3.1a - Error de conexión:**
  * El sistema mostrará un mensaje de error: *“Ocurrió un error de conexión con el servidor”*.
* **3.2a - Registro duplicado:**
  * El sistema mostrará un mensaje de advertencia: *“Actualmente existe un estado con ese nombre”*.
* **3.3a - Error en el registro:**
  * El sistema mostrará un mensaje de error: *“Ocurrió un error al crear el recurso"*.

---

## CU-02: Modificar Item

| Atributo                 | Detalle                                                                                                                                             |
| :----------------------- | :-------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Código**               | **CU-02**                                                                                                                                           |
| **Nombre**               | Crear Item                                                                                                                                          |
| **Descripción**          | El sistema permitirá modificar las items registrados en la base de datos mediante un formulario con campos especificados en los **REQUERIMIENTOS**. |
| **Actor(es)**            | Administrador/Super Administrador                                                                                                                   |
| **Tipo**                 | Primario                                                                                                                                            |
| **Referencias Cruzadas** | N/A                                                                                                                                                 |

---

### Curso normal de los eventos CU-02

|  N°   | Acción del Actor                                                        | Respuesta del Sistema                                                                                                                                                                                                                                                                          |
| :---: | :---------------------------------------------------------------------- | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Este caso de uso comienza cuando el usuario indica “Modificar elemento” | **1.1** 1.1 El sistema mostrara un formulario con un campo editable que tenga por defecto los registros actuales a modificar y un botón de “Modificar”.                                                                                                                                        |
| **2** | El usuario ingresa el nuevo nombre del modelo.                          | **2.1** El sistema no permitirá que se ingresen números.<br> **2.2** El sistema valida que el nombre no sea un campo vacío                                                                                                                                                                     |
| **3** | El usuario indica “Modificar”                                           | **3.1** El sistema se conecta con la base de datos. <br> **3.2** El sistema verifica que no exista un item con el mismo nombre que el nuevo. <br> **3.3** El sistema obtiene la id del registro que se desea modificar. <br> **3.4** El sistema cambia el nombre del registro por el ingresado |

---

### Cursos Alternos CU-02

2.2 **Campos vacios**: “Complete los campos”
3.1 **Error al conectar la base de datos**: “Ocurrió un error de conexión con el servidor”
3.2 **Registro duplicado**: “No se permiten duplicados”

---

## CU-03: Eliminar Item

| Atributo                 | Detalle                                                                                                                  |
| :----------------------- | :----------------------------------------------------------------------------------------------------------------------- |
| **Código**               | **CU-03**                                                                                                                |
| **Nombre**               | Eliminar Item                                                                                                            |
| **Descripción**          | El sistema permitirá al usuario eliminar el registro seleccionado mostrando una alerta para la confirmación de la acción |
| **Actor(es)**            | Administrador/Super Administrador                                                                                        |
| **Tipo**                 | Primario                                                                                                                 |
| **Referencias Cruzadas** | N/A                                                                                                                      |

---

### Curso normal de los eventos CU-03

|  N°   | Acción del Actor                                                        | Respuesta del Sistema                                                                                                                                                                                                                                             |
| :---: | :---------------------------------------------------------------------- | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Este caso de uso comienza cuando el usuario indica “Eliminar registro”. | 1.1 El sistema mostrara una alerta con el mensaje: “¿Esta seguro que desea eliminar este modelo?” y dos botones para “Cancelar” y “Eliminar”                                                                                                                      |
| **2** | 2. El usuario indica “Cancelar”                                         | 2.1 El sistema esconde la alerta y termina la acción                                                                                                                                                                                                              |
| **3** | 3. El usuario indica “Eliminar”                                         | 3.1 El sistema se conecta con la base de datos <br>      3.2 Se elimina el modelo de la base de datos                                                                                          <br> 3.3 El sistema obtiene la id del modelo que se desea eliminar |
|       |

---

### Cursos Alternos CU-03

3.1 El sistema mostrara un mensaje: “Ocurrió un error de conexión con el servidor”
3.3 El sistema mostrara un mensaje: “Ocurrió un error al eliminar el modelo”

---

## CU-04: Habilitar/Inhabilitar Item

| Atributo                 | Detalle                                                                                |
| :----------------------- | :------------------------------------------------------------------------------------- |
| **Código**               | **CU-03**                                                                              |
| **Nombre**               | Habilitar/Inhabilitar Item                                                             |
| **Descripción**          | El sistema permitirá al usuario cambiar el estado del item dependiendo de su necesidad |
| **Actor(es)**            | Administrador/Super Administrador                                                      |
| **Tipo**                 | Primario                                                                               |
| **Referencias Cruzadas** | N/A                                                                                    |

---

### Curso normal de los eventos CU-04

|  N°   | Acción del Actor                                                                     | Respuesta del Sistema                                                                                                                                                                                                                                                                        |
| :---: | :----------------------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Este caso de uso comienza cuando el usuario indica “Habilitar/Inhabilitar” registro. | 1.1 El sistema mostrara una alerta con el mensaje: “¿Está seguro de cambiar el estado del registro?”                                                                                                                                                                                         |
| **2** | 2. El usuario indica “Cancelar”                                                      | 2.1 El sistema esconde la alerta y termina la acción                                                                                                                                                                                                                                         |
| **3** | 3. El usuario indica “Eliminar”                                                      | 3.1 El sistema se conecta con la base de datos <br>      3.2 Se actualiza el estado del registro seleccionado                                                                                          <br> 3.3 El sistema muestra un mensaje personalizado dependiendo del estado a cambiar |
|       |

---

### Cursos Alternos CU-04

3.1 El sistema mostrara un mensaje: “Ocurrió un error de conexión con el servidor”
3.3 El sistema mostrara un mensaje: “Ocurrió un error al cambiar el estado del registro”
