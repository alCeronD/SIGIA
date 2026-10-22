# Módulo Roles

---

## Menú principal

- [Módulo Roles](#módulo-roles)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
  - [Documentación del sistema](#documentación-del-sistema)
    - [Requerimientos](#requerimientos)
    - [Historias de usuario](#historias-de-usuario)
  - [Diagramas UML](#diagramas-uml)
    - [Caso de uso extendido](#caso-de-uso-extendido)
    - [Diagramas de secuencia](#diagramas-de-secuencia)
    - [Diagramas de actividades](#diagramas-de-actividades)
    - [Diagrama de estados](#diagrama-de-estados)
    - [TODOS ESPECÍFICOS](#todos-específicos)

## Descripción

Gestiona los roles de usuario dentro del sistema, permitiendo su creación, edición, habilitación/inhabilitación y la administración detallada de permisos mediante la asignación y eliminación de funciones asociadas a cada rol.

## Arquitectura Interna (Pre-documentación)

- **Constantes:** `Const/RolesConst.php` -> Constantes globales del módulo, contiene títulos, variables, mensajes de respuesta, entre otros.
- **Controlador Principal (Roles):** `Controller/RolesController.php` -> Gestiona las peticiones HTTP, renderizado de vistas base y respuestas JSON para operaciones CRUD sobre los roles
- **Controlador de Funciones:** `Controller/FuncionesController.php` -> Atiende las peticiones y gestión de los endpoints relativos a funciones del sistema.
- **Controlador de Asignaciones:** `Controller/RolesFuncionesController.php` -> Administra el flujo de asignación y desvinculación (eliminación) de las funciones vinculadas a los roles.
- **Estilos:**
  - `Css/Roles.css` -> Reglas de estilo para los formularios y vistas generales de roles.
  - `Css/RolesFunciones.css` -> Estilos específicos para la interfaz de asignación de funciones y gestión de permisos.
  - `Css/RolesIndex.css` -> Estilos para la maquetación del panel/listado principal de roles.
- **JavaScript Principal:**
  - `Js/Roles.js` -> Lógica frontend para la gestión de roles (creación, edición, cambio de estado y eliminación) y manipulación del DOM.
  - `Js/RolesFunciones.js` -> Todo lo relacionado a la paginación, y peticiones transaccionales de la vista rolesFunciones
  - `Js/SelectorsRoles.js` -> Selectores DOM reutilizables en los scripts del módulo.
- **Modelos:**
  - `Model/RolesModel.php` -> Acceso a datos y consultas SQL para la tabla base de roles.
  - `Model/FuncionesModel.php` -> Acceso a datos para la consulta y gestión de las funciones registradas del sistema
  - `Model/RolesFuncionesModel.php` -> Consultas SQL y gestión de la relación entre roles y sus funciones/permisos asignados.
- **Servicios:**
  - `Services/ServicesRoles.php` -> Lógica de negocio para acoplar la información re utilizable de los roles.
  - `Services/ServicesFunciones.php` -> Lógica de negocio para re utilizar las funcionalidades asociadas al sistema.
- **Documentación:** `README.MD` -> Documentación técnica general y especificaciones operativas del módulo.
- **Vistas Clave:**
  - `views/rolesIndex.php` -> Vista principal del modulo de roles
  - `views/rolesViews.php` -> Vista que muestra el listado de los roles registrados en el sistema.
  - `views/rolesRegistrar.php` -> Formulario o interfaz para el registro de nuevos roles.
  - `views/rolesFunciones.php` -> Vista para visualizar las FUNCIONES QUE ESTÁN ASOCIADAS a un rol específico.
  - `views/modalAsing.php` -> Ventana modal para la asignación y desvinculación de funciones a un rol.
  - `views/modalEditar.php` -> Ventana modal para la edición de la información básica del rol.

## Dependencias de Base de Datos

- Tabla principal: `roles`
- Tablas asociadas: `roles_funciones`,`funciones`,`modulos`
- **Conexión con otros módulos**: N/A
- **Relaciones:**:  `rlp_id_rl` -> llave foranea de la tabla `roles_funciones` que se relaciona con la tabla `roles`

```bash
# Estructura interna del modulo

.
├── Const
│   └── RolesConst.php # Archivo con constantes del sistema
├── Controller
│   └── RolesController.php # Controlador principal del sistema
├── Css
│   ├── Roles.css # Css de la vista de los roles
│   └── RolesIndex.css # Css de la vista principal del modulo
├── Docs
│   ├── Requirements.MD # Requerimientos del sistema
│   ├── Uml # Folder con todas las imagenes de los diagramas creados del modulo
│   │   ├── Actividades
│   │   │   ├── activarInhabilitarRol.jpg
│   │   │   ├── asignarPermisos.jpg
│   │   │   ├── crearRol.jpg
│   │   │   ├── editarRol.jpg
│   │   │   └── eliminarRol.jpg
│   │   ├── Casodeuso_Extend
│   │   │   ├── Extend_Roles.MD
│   │   │   └── moduloRoles.jpg
│   │   ├── Estados
│   │   │   ├── asignarPermisos.jpg
│   │   │   ├── crearRol.jpg
│   │   │   ├── editarRol.jpg
│   │   │   └── eliminarRol.jpg
│   │   └── Secuencia
│   │       ├── activarInactivarRol.jpg
│   │       ├── asignarPermisos.jpg
│   │       ├── crearRol.jpg
│   │       ├── editarRol.jpg
│   │       └── eliminarRol.jpg
│   └── User_Story # Historias de usuario
│       ├── AsignarPermisos.MD
│       ├── CrearRol.md
│       ├── EliminarRol.md
│       ├── Habilitar_InhabilitarRol.MD
│       └── ModificarRol.md
├── Js
│   ├── Roles.js # Archivo principal del listado de roles
│   └── SelectorsRoles.js # selectores de Roles.js
├── Model
│   ├── RolesFuncionesModel.php # tabla roles_funciones
│   └── RolesModel.php # tabla roles
├── Mysql
│   ├── AltersRolesFunciones.sql # alters de todos los ajustes implementados de la tabla roles_funciones
│   ├── AltersRoles.sql # alters de todos los ajustes implementados de la tabla roles
│   ├── RolesFunciones.sql # script principal de la tabla roles_funciones
│   └── Roles.sql # script principal de la tabla roles
├── README.MD
├── Services # capa servicio para re utilizar y solicitar datos en otros modulos en caso de ser rquerido, uno es de las funciones y otro de los ROLES.
│   ├── ServicesFunciones.php
│   └── ServicesRoles.php
└── views # componentes de vistas del modulo
    ├── modalAsing.php
    ├── modalEditar.php
    ├── rolesFunciones.php
    ├── rolesMainView.php
    └── rolesViews.php

```

## Documentación del sistema

### Requerimientos

[Ver README](./Docs/Requirements.MD)

### Historias de usuario

| Nombre historia               |                        Documentación                        |
| :---------------------------- | :---------------------------------------------------------: |
| **Crear rol**                 |         [Ver README](./Docs/User_Story/CrearRol.md)         |
| **Modificar rol**             |       [Ver README](./Docs/User_Story/ModificarRol.md)       |
| **Habilitar/Inhabilitar rol** | [Ver README](./Docs/User_Story/Habilitar_InhabilitarRol.MD) |
| **Eliminar rol**              |       [Ver README](./Docs/User_Story/EliminarRol.md)        |
| **asignar Permisos rol**      |     [Ver README](./Docs/User_Story/AsignarPermisos.MD)      |

## Diagramas UML

![Caso de uso](./Docs/Uml/Casodeuso_Extend/moduloRoles.jpg)

### Caso de uso extendido

[Ver readme](./Docs/Uml/Casodeuso_Extend/Extend_Roles.MD)

### Diagramas de secuencia

**Crear rol**
![Crear rol](./Docs/Uml/Secuencia/crearRol.jpg)

**Editar rol**
![Editar rol](./Docs/Uml/Secuencia/editarRol.jpg)
**Habilitar - inhabilitar rol**
![Habilitar - inhabilitar rol](./Docs/Uml/Secuencia/activarInactivarRol.jpg)
**Eliminar rol**
![Eliminar rol](./Docs/Uml/Secuencia/eliminarRol.jpg)
**asignar permisos**
![asignar permisos](./Docs/Uml/Secuencia/asignarPermisos.jpg)

### Diagramas de actividades

**Crear rol**
![Crear rol](./Docs/Uml/Actividades/crearRol.jpg)
**Editar rol**
![Editar rol](./Docs/Uml/Actividades/editarRol.jpg)
**Habilitar - inhabilitar rol**
![Habilitar - inhabilitar rol](./Docs/Uml/Actividades/activarInhabilitarRol.jpg)
**Eliminar rol**
![Eliminar rol](./Docs/Uml/Actividades/eliminarRol.jpg)
**asignar permisos**
![asignar permisos](./Docs/Uml/Actividades/asignarPermisos.jpg)

### Diagrama de estados

**Crear rol**
![CREAR ROL](./Docs/Uml/Estados/crearRol.jpg)
**Editar rol**
![EDITAR ROL](./Docs/Uml/Estados/editarRol.jpg)
**Eliminar rol**
![Eliminar rol](./Docs/Uml/Estados/eliminarRol.jpg)
**asignar permisos**
![asignar permisos](./Docs/Uml/Estados/asignarPermisos.jpg)

### TODOS ESPECÍFICOS

- [X] CREAR EL modelo de la tabla roles_funciones para su uso
- [X] Extraer las funciones basadas al rol de la tabla roles_funciones y despues enviar a eliminar.
- [X] Crear capa servicio para conectar los 2 controladores entre el de roles y roles_funciones
- [X] si voy a borrar un rol, aplicar un delete a los registros relacionados con la tabla roles_funciones, crear la consulta y eliminar para despues borrar el registro del rol, para esto debo de crear la consulta inner join en la clase CRUD.PHP
- [X] Crear una vista tipo dashboard adicional que me permita seleccionar el accionar de ver las funciones asociadas al rol y la gestion de roles
- [X] Crear el controlador roles_funciones para poder ver las funciones asociadas a los roles y asi poder eliminarlos.
- [X] Crear clase servicio para el consumo de los roles
- [X] Ajustar proceso de asociacion de permisos - validar si las funciones por asociar ya existen en la base de datos.
- [X] Ajustar proceso de definir si se ejecutan 3 pasos o 4, en donde uno de esos pasos puede ser solamente de eliminar los permisos porque vamos a quitarlos o en su defecto, solo guardar permisos.
- [X] Implementar rastro de miga
- [X] Ajustar css
  - [x] vista principal
  - [X] listado de roles
- [X] Revisar el limite de caracteres en el input de registrar rol, debido a que este me marca un limite, error en la lógica de negocio.
- [X] EN LA FUNCION delete implementar logica de negocio para validar el rol a borrar, no se puede borrar el rol que tenga las palabras reservadas ADMINISTRADOR O SUPER ADMINISTRADOR.
- [x] A la funcion SET PERMISOS IMPLEMENTAR VALIDADOR DE DATOS, EN CASO DE QUE LOS DATOS ENVIADOS SEAN INCORRECTOS.
- [x] Al crear los permisos y aplicar el commit, verificar el menu, que id estamos enviando, si el id del usuario logueado o de otro usuario
- [X] Implementar documentacion
  - [x] requerimientos
  - [X] Historias de usuario
  - [x] Uml
    - [x] caso de uso
    - [x] caso de uso extendido
    - [x] secuencia
    - [x] actividad
    - [x] estado
- [ ] Crear logs personalizados del modulo
