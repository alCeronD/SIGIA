# Módulo Roles

---

## Menú principal

- [Módulo Roles](#módulo-roles)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
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
- Tabla de relación: `roles_funciones`
- Tabla de catálogo: `funciones`
- Tabla externa: `modulos`

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
- [ ] Implementar feature de all check en el modulo para asociar todos los permisos que pertenecen al modulo
- [ ] Ajustar css
- [ ] Revisar el limite de caracteres en el input de registrar rol, debido a que este me marca un limite, error en la lógica de negocio.
- [ ] En el modulo **ROLES** crear otra vista interna y otro controlador para definir plantilla por defecto del sistema.
