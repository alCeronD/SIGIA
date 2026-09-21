# Proyecto SIGIA

---

- **SISTEMA DE INFORMACIÓN DE GESTIÓN ADMINISTRATIVA**

## Menú Principal

- [Proyecto SIGIA](#proyecto-sigia)
  - [Menú Principal](#menú-principal)
    - [Introducción](#introducción)
    - [Objetivo](#objetivo)
      - [**Objetivo general**](#objetivo-general)
      - [**Objetivos específicos**](#objetivos-específicos)
    - [Previsualización](#previsualización)
    - [Módulos del sistema](#módulos-del-sistema)
    - [Diagramas Generales](#diagramas-generales)
    - [Requerimientos de instalación](#requerimientos-de-instalación)
      - [Instalación del proyecto](#instalación-del-proyecto)

### Introducción

- Una de las problemáticas planteadas en la central didáctica es la no parametrización de elementos que entran en circulación para las respectivas operaciones de formación como inducciones, eventos académicos, entre otros, para ello. Se plantea una solución conocida como SIGIA que consiste en dar control y flujo de aquellos elementos que se encuentran fuera de almacén para así parametrizar todos los insumos activos o inactivos de la central didáctica.

### Objetivo

#### **Objetivo general**

- Proporcionar a los usuarios una guía clara, estructurada y accesible para el uso adecuado del Sistema Interactivo de Gestión de Inventario – SIGIA, facilitando la navegación, comprensión de funcionalidades, y ejecución de procesos de control y gestión de inventario de acuerdo con el rol asignado

#### **Objetivos específicos**

- Describir los requisitos técnicos y de acceso necesarios para utilizar correctamente el sistema SIGIA.
Orientar al usuario en el proceso de autenticación mediante el formulario de inicio de sesión, detallando los controles y validaciones del sistema.
- Explicar las funcionalidades principales disponibles según el rol del usuario (Instructor, Coordinador o Administrador).
- Guiar al usuario en la navegación del sistema mediante ejemplos visuales que muestren el uso práctico de las diferentes secciones.
- Facilitar la comprensión del flujo operativo del sistema, desde el registro de movimientos hasta la consulta y generación de reportes.

### Previsualización

Capturas del proyecto

### Módulos del sistema

| Módulo                 | Progreso | Documentación |                     Enlace a Detalles                     |
| :--------------------- | :------: | :-----------: | :-------------------------------------------------------: |
| **Areas**              |   100%   |  Completado   |       [Ver README](./Core/Modules/Areas/README.MD)        |
| **Tipo Documento**     |   100%   |  Completado   |   [Ver README](./Core/Modules/TipoDocumento/README.MD)    |
| **Marcas**             |   100%   |  Completado   |       [Ver README](./Core/Modules/Marcas/README.MD)       |
| **Categorias**         |   100%   |  Completado   |     [Ver README](./Core/Modules/Categorias/README.MD)     |
| **Usuarios**           |   70%    |  En Proceso   |      [Ver README](./Core/Modules/Usuarios/README.MD)      |
| **Login**              |   100%   |  En Proceso   |       [Ver README](./Core/Modules/Login/README.MD)        |
| **Roles**              |   90%    |   Pendiente   |       [Ver README](./Core/Modules/Roles/README.MD)        |
| **Permisos**           |   90%    |   Pendiente   |      [Ver README](./Core/Modules/Permisos/README.MD)      |
| **Dashboard**          |    0%    |   Pendiente   |     [Ver README](./Core/Modules/Dashboard/README.MD)      |
| **Elementos**          |    0%    |   Pendiente   |     [Ver README](./Core/Modules/Elementos/README.MD)      |
| **Reportes**           |    0%    |   Pendiente   |      [Ver README](./Core/Modules/Reportes/README.MD)      |
| **SolicitudPrestamos** |    0%    |   Pendiente   | [Ver README](./Core/Modules/SolicitudPrestamos/README.MD) |
| **ReservaPrestamos**   |    0%    |   Pendiente   |  [Ver README](./Core/Modules/ReservaPrestamos/README.MD)  |
| **Gestionmodulos**     |   90%    |   Pendiente   |   [Ver README](./Core/Modules/Gestionmodulos/README.MD)   |
| **GeneralCrud**        |    0%    |      N/A      |    [Ver README](./Core/Modules/GeneralCrud/README.MD)     |
| **Funciones**          |   90%    |   Pendiente   |     [Ver README](./Core/Modules/Funciones/README.MD)      |
| **Estados**            |    0%    |      N/A      |               [Ver README](./Core/Modules/)               |

### Diagramas Generales

**DIAGRAMA DE CLASES**

**DIAGRAMA DE DESPLIEGUE**

### Requerimientos de instalación

- Apache
- MariaBD
- PHP

```bash
sudo systemctl start httpd
sudo systemctl start mysql
```

#### Instalación del proyecto

```bash

```
