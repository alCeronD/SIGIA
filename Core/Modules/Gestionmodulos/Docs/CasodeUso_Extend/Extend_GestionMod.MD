# Caso de uso extendido: Módulo GestionModulos

- [Caso de uso extendido: Módulo GestionModulos](#caso-de-uso-extendido-módulo-gestionmodulos)
  - [Introducción](#introducción)
  - [Cod 20 - Activar/Inactivar módulo](#cod-20---activarinactivar-módulo)
    - [Curso normal de los eventos](#curso-normal-de-los-eventos)
    - [Cursos alternos](#cursos-alternos)
  - [Cod 21 - Eliminar módulo](#cod-21---eliminar-módulo)
    - [Curso normal de los eventos](#curso-normal-de-los-eventos-1)
    - [Cursos alternos](#cursos-alternos-1)
  - [Cod 22 - Asignar funciones asociadas](#cod-22---asignar-funciones-asociadas)
    - [Curso normal de los eventos](#curso-normal-de-los-eventos-2)
    - [Cursos alternos](#cursos-alternos-2)
  - [Cod 23 - Editar función asociada](#cod-23---editar-función-asociada)
    - [Curso normal de los eventos](#curso-normal-de-los-eventos-3)
    - [Cursos alternos](#cursos-alternos-3)
  - [Cod 24 - Eliminar funciones asociadas](#cod-24---eliminar-funciones-asociadas)
    - [Curso normal de los eventos Cod 24](#curso-normal-de-los-eventos-cod-24)
    - [Cursos alternos Cod 24](#cursos-alternos-cod-24)

## Introducción

Este documento tiene como finalidad explicar de manera detallada los casos de uso del módulo **GestionModulos** con el fin de detallar su accionar y sus comportamientos alternos.

---

## Cod 20 - Activar/Inactivar módulo

- **Descripción:** El sistema debe permitir al usuario con rol SUPER ADMINISTRADOR la opción de inhabilitar/habilitar el estado del módulo para así permitir o denegar el respectivo acceso.
- **Actor(es):** Super administrador
- **Tipo:** Secundario
- **Referencias cruzadas:** RFGM-02

### Curso normal de los eventos

| Acción del Actor                                                                      | Respuesta del Sistema                                                                                                                                                                                                                                    |
| :------------------------------------------------------------------------------------ | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1.** Este caso de uso comienza cuando el usuario indica “Activar/Inactivar módulo”. | **1.1** El sistema mostrará una ventana de preconfirmación con un mensaje personalizado dependiendo de la acción a realizar indicando si desea activar o inactivar el módulo.                                                                            |
| **2.** El usuario presiona “Cancelar”.                                                | **2.1** La ventana de preconfirmación se cierra y el proceso no se ejecuta.                                                                                                                                                                              |
| **3.** El usuario presiona “Aceptar”.                                                 | **3.1** El sistema verifica que los datos requeridos sean correctos.<br>**3.2** El sistema se conecta con la base de datos.<br>**3.3** El sistema verifica que el módulo a activar o inactivar exista.<br>**3.4** El sistema cambia el estado requerido. |

### Cursos alternos

- **3.1a** El sistema mostrará un mensaje: “Datos incompletos”.
- **3.1b** El sistema mostrará un mensaje: “Estado enviado incorrecto”.
- **3.2** El sistema mostrará un mensaje: “Ha ocurrido un error al conectarse a la base de datos”.
- **3.3** El sistema mostrará un mensaje: “El módulo no existe en la base de datos”.
- **3.4** El sistema mostrará un mensaje: “Ocurrió un error al actualizar el estado del módulo”.

---

## Cod 21 - Eliminar módulo

- **Descripción:** El sistema debe permitir al usuario con el rol SUPER ADMINISTRADOR eliminar un módulo de la base de datos mediante un botón de acción con el fin de eliminar aquellos módulos a los que no se requiere acceso.
- **Actor(es):** Super administrador
- **Tipo:** Primario
- **Referencias cruzadas:** RFGM-04

### Curso normal de los eventos

| Acción del Actor                                                             | Respuesta del Sistema                                                                                                                                                                                                                                                                                                               |
| :--------------------------------------------------------------------------- | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1.** Este caso de uso comienza cuando el usuario indica “Eliminar módulo”. | **1.1** El sistema muestra una alerta con el mensaje: “¿Está seguro que desea eliminar este módulo? No se permitirá el acceso al sistema y deberá reinstalarlo en la base de datos para recuperar su acceso”, y dos botones para “Cancelar” y “Eliminar”.                                                                           |
| **2.** El usuario indica “Cancelar”.                                         | **2.1** El sistema esconde la alerta y termina la acción.                                                                                                                                                                                                                                                                           |
| **3.** El usuario indica “Eliminar”.                                         | **3.1** El sistema se conecta con la base de datos.<br>**3.2** El sistema obtiene la ID del módulo que se desea eliminar.<br>**3.3** El sistema valida que la ID del módulo exista y el nombre del módulo corresponda al módulo instalado en el directorio físico del sistema.<br>**3.4** Se elimina el módulo de la base de datos. |

### Cursos alternos

* **3.1** El sistema mostrará un mensaje: “Ocurrió un error de conexión con el servidor”.
* **3.3a** El sistema mostrará un mensaje: “La ID del módulo es incorrecta”.
* **3.3b** El sistema mostrará un mensaje: “El módulo no corresponde al directorio físico del sistema”.
* **3.4** El sistema mostrará un mensaje: “Ocurrió un error al eliminar el módulo”.

---

## Cod 22 - Asignar funciones asociadas

- **Descripción:** El sistema en el módulo GESTION MODULOS debe permitir al usuario con rol SUPER ADMINISTRADOR vincular nuevas funcionalidades específicas a un módulo existente en la base de datos mediante un formulario de registro de funciones.
- **Actor(es):** Super administrador
- **Tipo:** Primario
- **Referencias cruzadas:** RFGM-05

### Curso normal de los eventos

| Acción del Actor                                                                                    | Respuesta del Sistema                                                                                                                                                                                                                                                                                                                                                                                                                                |
| :-------------------------------------------------------------------------------------------------- | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1.** Este caso de uso comienza cuando el usuario selecciona la opción “Asignar función asociada”. | **1.1** El sistema despliega el formulario para la creación y asignación de una nueva función, solicitando los datos obligatorios (nombre de la función, ruta/endpoint, descripción, estado).                                                                                                                                                                                                                                                        |
| **2.** El usuario diligencia el formulario y presiona el botón “Guardar”.                           | **2.1** El sistema valida que los campos requeridos estén completos y con el formato adecuado.<br>**2.2** El sistema se conecta con la base de datos.<br>**2.3** El sistema verifica que la función no se encuentre registrada previamente en el módulo.<br>**2.4** Se guarda la nueva función y se asocia al módulo correspondiente en la base de datos.<br>**2.5** El sistema muestra un mensaje de confirmación: “Función asignada exitosamente”. |

### Cursos alternos

- **2.1** El sistema mostrará un mensaje: “Por favor complete todos los campos requeridos”.
- **2.2** El sistema mostrará un mensaje: “Ocurrió un error de conexión con el servidor”.
- **2.3** El sistema mostrará un mensaje: “La función ya se encuentra registrada en este módulo”.
- **2.4** El sistema mostrará un mensaje: “Ocurrió un error al guardar la función en la base de datos”.

---

## Cod 23 - Editar función asociada

- **Descripción:** El sistema en el módulo GESTION MODULOS debe permitir al usuario con rol SUPER ADMINISTRADOR modificar la información o los parámetros de una funcionalidad existente asociada a un módulo.
- **Actor(es):** Super administrador
- **Tipo:** Primario
- **Referencias cruzadas:** RFGM-06

### Curso normal de los eventos

| Acción del Actor                                                                                                                | Respuesta del Sistema                                                                                                                                                                                                     |
| :------------------------------------------------------------------------------------------------------------------------------ | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **1.** Este caso de uso comienza cuando el usuario selecciona la opción “Editar función asociada” sobre un registro específico. | **1.1** El sistema se conecta con la base de datos y obtiene la información actual de la función.<br>**1.2** El sistema despliega un formulario precalculado con los datos actuales de la función seleccionada.           |
| **2.** El usuario modifica los campos deseados y presiona “Guardar cambios”.                                                    | **2.1** El sistema valida los datos ingresados.<br>**2.2** El sistema actualiza la información de la función en la base de datos.<br>**2.3** El sistema muestra un mensaje de éxito: “Función actualizada correctamente”. |

### Cursos alternos

- **1.1** El sistema mostrará un mensaje: “Error al cargar los datos de la función”.
- **2.1** El sistema mostrará un mensaje: “Los datos ingresados son inválidos o están incompletos”.
- **2.2a** El sistema mostrará un mensaje: “Ocurrió un error de conexión con el servidor”.
- **2.2b** El sistema mostrará un mensaje: “Ocurrió un error al actualizar la función en la base de datos”.

---

## Cod 24 - Eliminar funciones asociadas

- **Descripción:** El sistema en el módulo GESTION MODULOS debe permitir al usuario con rol SUPER ADMINISTRADOR eliminar funcionalidades asociadas al módulo que se encuentren en la base de datos mediante un botón de acción.
- **Actor(es):** Super administrador
- **Tipo:** Primario
- **Referencias cruzadas:** RFGM-07

### Curso normal de los eventos Cod 24

| Acción del Actor                                                                       | Respuesta del Sistema                                                                                                                                                              |
| :------------------------------------------------------------------------------------- | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1.** Este caso de uso comienza cuando el usuario indica “Eliminar función asociada”. | **1.1** El sistema muestra una alerta con el mensaje: “¿Está seguro que desea eliminar esta función?”, y dos botones para “Cancelar” y “Eliminar”.                                 |
| **2.** El usuario indica “Cancelar”.                                                   | **2.1** El sistema esconde la alerta y termina la acción.                                                                                                                          |
| **3.** El usuario indica “Eliminar”.                                                   | **3.1** El sistema se conecta con la base de datos.<br>**3.2** El sistema obtiene la ID de la función que se desea eliminar.<br>**3.3** Se elimina la función de la base de datos. |

### Cursos alternos Cod 24

- **3.1** El sistema mostrará un mensaje: “Ocurrió un error de conexión con el servidor”.
- **3.3** El sistema mostrará un mensaje: “Ocurrió un error al eliminar la función”.
