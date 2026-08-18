# Modulo Login

## Menú principal

- [Modulo Login](#modulo-login)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
    - [TODOS ESPECÍFICOS](#todos-específicos)

## Descripción

Modulo de inicio sesión que nos permite validar la existencia de nuestro usuario antes de realizar alguna acción en el sistema, nos permite ingresar y salir del sistema.

## Arquitectura Interna (Pre-documentación)

- **Constantes:** `Const/LoginConst.php` -> Constantes globales del modulo, contiene titulos, variables, mensajes, entre otros.
- **Controlador Principal:** `Controller/LoginController.php` -> Gestiona la peticion de renderizado y pre acceso al sistema
- **Estilos:** `Css/Login.css` -> Hoja de estilos principal del modulo de login.
- **JavaScript Principal:** `Js/Login.js` -> Lógica frontend, validacion de datos de los campos a diligenciar y envio de datos al backend para validar la existencia del usuario.
- **Modelo:** `Model/N/A.php` ->
- **SQL (Migraciones):** `Mysql/alters.sql` -> Scrips de modificación de la tabla areas.sql
- **SQL (Estructura):** `Mysql/tables.sql` -> Definición base y scripts de creación de tablas (`CREATE TABLE`) requeridas por el módulo.
- **Documentación:** `README.MD` -> Documentación técnica general y especificaciones operativas del módulo.
- **Vistas Clave:**
  - `Views/loginView.php` -> Template principal para renderizar la vista de inicio de sesion

## Dependencias de Base de Datos

- Tabla principal: `usuarios`
- Tabla secundaria: `roles`

---

### TODOS ESPECÍFICOS

- [ ] Ajustar controlador del login para dar el acceso mediante los modelos
- [ ] usar la funcionalidad window.location.replace(url) para asi poder re direccionar de manera correcta y evitar el uso del boton de atras, eso permite que el historial se elimine.
- [ ] Al cerrar la sesion re direccionar y eliminar los datos de localstorage usando clear
