# Caso de uso extendido - Modulo Usuarios

---

- [Caso de uso extendido - Modulo Usuarios](#caso-de-uso-extendido---modulo-usuarios)
  - [Introducción](#introducción)
  - [CU-01: Crear Usuario](#cu-01-crear-usuario)
    - [Curso normal de los eventos CU-01](#curso-normal-de-los-eventos-cu-01)
    - [Cursos alternos CU-O1](#cursos-alternos-cu-o1)
  - [CU-02: Editar usuario](#cu-02-editar-usuario)
    - [Curso normal de los eventos CU-02](#curso-normal-de-los-eventos-cu-02)
    - [Cursos alternos CU-02](#cursos-alternos-cu-02)
    - [Criterios de aceptacion CU-02](#criterios-de-aceptacion-cu-02)
  - [CU-03: Deshabilitar/Habilitar Usuario](#cu-03-deshabilitarhabilitar-usuario)
    - [Curso normal de los eventos CU-03](#curso-normal-de-los-eventos-cu-03)
    - [Cursos alternos CU-03](#cursos-alternos-cu-03)
    - [Criterios de aceptacion CU-03](#criterios-de-aceptacion-cu-03)
  - [CU-04: Consultar Usuario](#cu-04-consultar-usuario)
    - [Curso normal de los eventos CU-04](#curso-normal-de-los-eventos-cu-04)
    - [Cursos alternos CU-04](#cursos-alternos-cu-04)
    - [Criterios de aceptacion CU-4](#criterios-de-aceptacion-cu-4)

## Introducción

Este documento tiene como finalidad establecer una especificación a los casos de usos definidos por el módulo, es una forma de comprender cual es el comportamiento que debe de tener el módulo bajo diversos escenarios.

---

## CU-01: Crear Usuario

| Atributo        | Detalle                                                                                                                                                                                                                                                                 |
| :-------------- | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Código**      | **CU-01**                                                                                                                                                                                                                                                               |
| **Nombre**      | Crear Usuario                                                                                                                                                                                                                                                           |
| **Descripción** | Permite al Administrador o super administrador crear un usuario a través de un formulario que contiene campos obligatorios como número de documento, tipo de documento, nombres, apellidos, teléfono, correo electrónico, contraseña, confirmación de contraseña y rol. |

 |
| **Actor(es)**            | Administrador/Super Administrador                                                                                              |
| **Tipo**                 | Primario                                                                                                                       |
| **Referencias Cruzadas** | N/A                                                                                                                            |

---

### Curso normal de los eventos CU-01

|  N°   | Acción del Actor                                 | Respuesta del Sistema                                                                                                                                                                                                                                                                                                                                                                       |
| :---: | :----------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **1** | 1. - Accede al formulario de creación de usuario | **1.1** Este contara con un mensaje al inicio que dira "Los campos marcados con asterisco (`*`) son obligatorios". El formulario tendra los siguientes campos:  <br> -Número de documento (Numérico)* <br>  -Tipo de documento* <Lista desplegable> -Nombres* <br> -Apellidos* <br> -Teléfono* -Correo electrónico* <br> -Contraseña* <br> -Confirmar contraseña* -Rol* <Lista desplegable> |
| **2** | 2. Presiona el botón para guardar                | **2.1** Presiona el botón para guardar                                                                                                                                                                                                                                                                                                                                                      |

### Cursos alternos CU-O1

- **1.1a - Campo vacío:**
  - Muestra mensaje de validación
- **1.2a - Documento ya registrado:**
  - Muestra alerta de duplicado
- **1.3a - Correo ya registrado:**
  - Muestra alerta de duplicado
- **1.4a - Contraseñas no coinciden:**
  - Muestra error de validación*.
- **2.4** -  **Error en servidor**
  - Muestra notificación explicando el fallo

## CU-02: Editar usuario

| Atributo        | Detalle                                                     |
| :-------------- | :---------------------------------------------------------- |
| **Código**      | **CU-02**                                                   |
| **Nombre**      | Editar Usuario                                              |
| **Descripción** | Permite editar los datos de un usuario desde un formulario. |

| **Actor(es)**            | Administrador/Super Administrador                                                                                              |
| **Tipo**                 | Primario                                                                                                                       |
| **Referencias Cruzadas** | N/A                                                                                                                            |

### Curso normal de los eventos CU-02

|  N°   | Acción del Actor                     | Respuesta del Sistema                                                        |
| :---: | :----------------------------------- | :--------------------------------------------------------------------------- |
| **1** | 1. - Accede al formulario de edición | **1.1** Carga datos del usuario. <br> **1.2** Modifica los campos requeridos |
| **2** | 2. Presiona el botón para guardar    | **2.1** Actualiza datos y redirige a listado                                 |

### Cursos alternos CU-02

- **1.1a - Campo vacío:**
  - Muestra mensaje de validación
- **1.2a - Documento ya registrado:**
  - Muestra alerta de duplicado
- **1.3a - Correo ya registrado:**
  - Muestra alerta de duplicado
- **2.4** -  **Error en servidor**
  - Muestra notificación explicando el fallo

### Criterios de aceptacion CU-02

- No se puede editar documento
- Datos se cargan por defecto
- Se muestra éxito y se redirige a lista de usuarios

## CU-03: Deshabilitar/Habilitar Usuario

| Atributo        | Detalle                                                                                                                                                 |
| :-------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Código**      | **CU-02**                                                                                                                                               |
| **Nombre**      | Habiliar/Deshabilitar Usuario                                                                                                                           |
| **Descripción** | Deberá mostrar una alerta para confirmar si se desea deshabilitar al usuario (cambiar su estado a 'inactivo'). Incluye botones de confirmar o cancelar. |

| **Actor(es)**            | Administrador/Super Administrador/Coordinador                                                                                              |
| **Tipo**                 | Primario                                                                                                                       |
| **Referencias Cruzadas** | RFU-4                                                                                                                            |

### Curso normal de los eventos CU-03

|  N°   | Acción del Actor              | Respuesta del Sistema                     |
| :---: | :---------------------------- | :---------------------------------------- |
| **1** | 1. Clic en botón deshabilitar | **1.1** 1.1 Cambia estado y muestra éxito |
| **2** | 2. Confirma                   | **2.1** Carga y muestra datos completos   |
| **3** | 2. Cancelar                   | **3.1** Cierra alerta sin cambios         |

### Cursos alternos CU-03

- **Fallo del sistema - 1.1**
  - Notificación explicando el error

### Criterios de aceptacion CU-03

- Estado cambia correctamente a inactivo
- Se puede cancelar sin aplicar cambios
- Se muestra notificación según resultado

## CU-04: Consultar Usuario

| Atributo        | Detalle                                                                                                                                                                                    |
| :-------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Código**      | **CU-04**                                                                                                                                                                                  |
| **Nombre**      | Consultar Usuario                                                                                                                                                                          |
| **Descripción** | Deberá Permitir ver los datos detallados de un usuario seleccionado desde una barra de búsqueda por nombre o documento. Los datos se actualizan automáticamente al seleccionar un usuario. |

| **Actor(es)**            | Administrador/Super Administrador/Coordinador                                                                                              |
| **Tipo**                 | Primario                                                                                                                       |
| **Referencias Cruzadas** | RFU-2                                                                                                                           |

### Curso normal de los eventos CU-04

|  N°   | Acción del Actor         | Respuesta del Sistema                                                                     |
| :---: | :----------------------- | :---------------------------------------------------------------------------------------- |
| **1** | 1. Usa barra de búsqueda | **1.1** Muestra coincidencias. <br> **1.2** Carga los datos con coincidencias encontradas |

### Cursos alternos CU-04

- Ningún usuario seleccionado → Muestra mensaje: 'No se encontró ningún usuario'

### Criterios de aceptacion CU-4

- Datos cambian según selección
- Búsqueda filtra todos los usuarios correctamente
