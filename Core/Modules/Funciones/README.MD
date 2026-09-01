# Modulo Funciones

---

## Menú principal

- [Modulo Funciones](#modulo-funciones)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
  - [Todos especificos](#todos-especificos)

## Descripción

Modulo que consiste en parametrizar y gestionar las funciones del sistema que se encuentran en los controladores del sistema, con el fin de validar la seguridad y acceso a ellas dependiendo del rol asociado.

## Arquitectura Interna (Pre-documentación)

- **Constantes:** `Const/FuncionesConst.php` -> Constantes globales del modulo, contiene titulos, variables, mensajes, entre otros.
- **Controlador Principal:** - `Controller/FuncionesAsociadasController.php` -> Controlador de funciones asociadas al rol, aca se manipula la vista y las acciones de todas las acciones que se relacionan con el rol
  - `Controller/FuncionesController.php` -> Controlador que gestiona el listado de las funciones establecidas en el sistema
  - `Controller/FuncionesMainController.php` -> Controlador principal que sirve como punto de entrada a las funcionalidades internas del Módulo **Funciones** entre ellas se encuentran: **Funciones Asociadas Al Rol**, **Listado de funciones**, entre otros.
- **Estilos:** `Css/FuncionesIndexView.css` -> Contiene las reglas CSS específicas para el diseño y maquetación de la vista principal del modulo
  - `Css/RolesFunciones.css` -> css perteneciente a la vista **Funciones Asociadas Al Rol** (`views/FuncionesAsociadas.php`)
- **JavaScript Principal:** `Js/RolesFunciones.js` -> Lógica frontend, captura de eventos, interacción con la API interna y manipulación del DOM, paginacion entre otros de la vista **Funciones Asociadas Al Rol**
- **Selectores DOM:** `Js/Selectors.js` -> Selectores para exportar y re utilizar en el archivo `Areas.js`
- **Modelo:** `Model/AreasModel.php` -> Encargado del acceso a datos, consultas SQL y operaciones CRUD sobre las tablas del módulo de Áreas.
- **SQL (Migraciones):** `Mysql/alters.sql` -> Scrips de modificación de la tabla areas.sql
- **SQL (Estructura):** `Mysql/tables.sql` -> Definición base y scripts de creación de tablas (`CREATE TABLE`) requeridas por el módulo.
- **Documentación:** `README.MD` -> Documentación técnica general y especificaciones operativas del módulo.
- **Vistas Clave:**
  - `Views/areaView.php` -> Vista y panel principal para la gestión e interfaz del usuario.
  - `Views/tableViewArea.php` -> Componente de tipo tabla que se incluye en el archivo Views/areaView.php

## Dependencias de Base de Datos

- Tabla principal: `funciones`
- Tablas asociadas: `roles_funciones`, `roles`, `modulos`

---

## Todos especificos

- [ ] En vista **Funciones asociadas módulo - Funciones** IMPLEMENTAR UN CAMPO ADICIONAL EN LA TABLA QUE ME PERMITA GUARDAR LOS CONTROLADORES, ESTO PORQUE CUANDO HAY UN MODULO CON VARIOS CONTROLADORES INTERNOS NO SE A QUE CONTROLADOR HACE REFERENCIA.
- [X] Mover los archivos que estaban en el modulo de **ROLES** al modulo que pertenecen que son **Funciones**
- [ ] Implementar funcionalidad de log.
- [ ] Implementar creacion de funciones directas, es decir, crear la funcionalidad y asociarla al modulo desde el modulo de **FUNCIONES**
