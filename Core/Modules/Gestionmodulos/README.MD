# Gestión de Módulos

## Menú principal

- [Gestión de Módulos](#gestión-de-módulos)
  - [Menú principal](#menú-principal)
  - [Introduccion](#introduccion)
  - [Arquitectura interna del modulo](#arquitectura-interna-del-modulo)
  - [Dependencias de bases de datos](#dependencias-de-bases-de-datos)
  - [Documentación del sistema](#documentación-del-sistema)
    - [Requerimientos](#requerimientos)
    - [Historias de usuario](#historias-de-usuario)
  - [Diagramas UML](#diagramas-uml)
    - [Caso de uso](#caso-de-uso)
    - [Caso de extendido](#caso-de-extendido)
    - [Diagramas de secuencia](#diagramas-de-secuencia)
    - [Diagrama de actividades](#diagrama-de-actividades)
    - [Diagrama de estados](#diagrama-de-estados)
  - [TODOS especificos del modulo](#todos-especificos-del-modulo)

---

## Introduccion

Este modulo contiene todo lo relacionado a las funciones que se asocian al modulo como tambien la personalizacion de su icono y ruta de acceso siguiento los parametros de enrutamiento tales como: modulo, controlador, funcion en el cual nos permite pre configurar las rutas de acceso de los modulos

## Arquitectura interna del modulo

```bash
.
├── Const
│   └── GestionModulosConst.php
├── Controller
│   └── GestionmodulosController.php
├── Css
│   ├── FunctionsAssoc.css
│   └── Modulos.css
├── Docs
├── Js
│   ├── FunctionsAssoc.js
│   ├── Modulos.js
│   ├── Selectors-FunctionsAssoc.js
│   └── Selectors-Modulos.js
├── Model
│   ├── ModulosModel.php
│   └── TipoFuncionModel.php
├── README.MD
├── Services
│   └── ServicesGestionModulos.php
├── Sql
│   ├── Alters.sql
│   └── Modulos.sql
└── Views
    ├── FunctionsAssoc.php
    └── ModulosView.php

```

## Dependencias de bases de datos

- **Tabla principal:** `modulos`
- **Tablas relacionadas:** `funciones`
- **Relaciones:**
  - `id_modulo`: Llave foranea que hace parte de la tabla `funciones`.

---

## Documentación del sistema

### Requerimientos

[VER README](./Docs/Requirements.MD)

### Historias de usuario

| Nombre historia                            |                         Documentación                          |
| :----------------------------------------- | :------------------------------------------------------------: |
| **Crear módulo**                           |         [Ver README](./Docs/User_Story/CrearModulo.MD)         |
| **Modificar módulo**                       |       [Ver README](./Docs/User_Story/ModificarModulo.MD)       |
| **Habilitar/Inhabilitar módulo**           | [Ver README](./Docs/User_Story/Habilitar_InhabilitarModulo.MD) |
| **Eliminar modulo**                        |       [Ver README](./Docs/User_Story/EliminarModulo.MD)        |
| **Asociar funciones al módulo**            |      [Ver README](./Docs/User_Story/AsociarFunciones.MD)       |
| **Editar funciones asociadas al módulo**   |  [Ver README](./Docs/User_Story/EditarFuncionesAsociadas.MD)   |
| **Eliminar funciones asociadas al módulo** | [Ver README](./Docs/User_Story/EliminarFuncionesAsociadas.MD)  |

---

## Diagramas UML

### Caso de uso

![Caso de uso](./Docs/Uml/CasodeUso_Extend/gestionModulosCasoDeUso.jpg)

### Caso de extendido

[Ver readme](./Docs/Uml/CasodeUso_Extend/Extend_gestionmodulos.MD)

### Diagramas de secuencia

**Crear modulo**
![Crear modulo](./Docs/Uml/Secuencia/)

**Inhabilitar-habilitar modulo**
![Inhabilitar-habilitar modulo](./Docs/Uml/Secuencia/)

**ELiminar modulo**
![ELiminar modulo](./Docs/Uml/Secuencia/)

**Editar modulo**
![Editar modulo](./Docs/Uml/Secuencia/)

**Asociar Funciones**
![Asociar Funciones](./Docs/Uml/Secuencia/)

**Editar funciones asociadas**
![Editar funciones asociadas](./Docs/Uml/Secuencia/)

**Eliminar funciones asociadas**
![Eliminar funciones asociadas](./Docs/Uml/Secuencia/)

### Diagrama de actividades

**Crear modulo**
![Crear modulo](./Docs/Uml/Actividades/)

**Inhabilitar-habilitar modulo**
![Inhabilitar-habilitar modulo](./Docs/Uml/Actividades/)

**ELiminar modulo**
![ELiminar modulo](./Docs/Uml/Actividades/)

**Editar modulo**
![Editar modulo](./Docs/Uml/Actividades/)

**Asociar Funciones**
![Asociar Funciones](./Docs/Uml/Actividades/)

**Editar funciones asociadas**
![Editar funciones asociadas](./Docs/Uml/Actividades/)

**Eliminar funciones asociadas**
![Eliminar funciones asociadas](./Docs/Uml/Actividades/)

### Diagrama de estados

**Crear modulo**
![Crear modulo](./Docs/Uml/Estado/)

**Inhabilitar-habilitar modulo**
![Inhabilitar-habilitar modulo](./Docs/Uml/Estado/)

**ELiminar modulo**
![ELiminar modulo](./Docs/Uml/Estado/)

**Editar modulo**
![Editar modulo](./Docs/Uml/Estado/)

**Asociar Funciones**
![Asociar Funciones](./Docs/Uml/Estado/)

**Editar funciones asociadas**
![Editar funciones asociadas](./Docs/Uml/Estado/)

**Eliminar funciones asociadas**
![Eliminar funciones asociadas](./Docs/Uml/Estado/)

## TODOS especificos del modulo

- [X] Implementar caracteristica de guardado de RUTA DE ACCESO - ESTO CONSISTE EN GUARDAR LOS VALORES DE LA RUTA DEL PARA ACCEDER A LA VISTA, ES DECIR, MEDIANTE MODELO, VISTA, CONTROLADOR, es decir,guardar las rutas de acceso, esto para poder renderizar de manera dinamica las vistas y acceso al modulo
- [ ] Implementar mejora de icono - al cambiar el icono hacer una preview del icono de como se observaria, buscar como hacerlo.
- [X]CREAR EL MODULO LLAMADO Gestion de modulos QUE ME PERMITE CREAR LOS MODULOS Y LAS FUNCIONES ASOCIADAS AL CONTROLADOR, ESTO SI O SI DEBE DE TENER SOLAMENTE PERMISOS EL ADMINISTRADOR.
- [X]el modulo debe tener logica de negocio que me permita antes de crear la funcion al controlador, validar que la funcion exista.
- [X]crear logica de negocio que me permita visualizar si la funcion existe o no en el archivo del controlador
- [X] Implementar feature create, updated, changeStatus, verDetalle del modulo.
- [X] **Feature update** actualizar los nombres de los modulos, el error esta en si escribo GeneralCrud me lo valida, si escribo Generalcrud no.
- [X] Implementar **funciones asociadas**
  - [X] **funciones asociadas** select - implementar vista de las funciones asociadas al modulo
  - [X] **funciones asociadas** insert - implementar modal para crear nueva funcionalidad de crear funcion, solo se deben de registrar las funcionalidades publicas.
  - [X] **funciones asociadas** update - implementar edicion del nombre de la base de datos
  - [X] **Funciones asociadas** Delete - implementar eliminar la funcionalidad.
- [X] Implementar validador de estado del MODULO EN `Router.php` esto para validar si el modulo se encuentra disponible para su respectivo uso.
