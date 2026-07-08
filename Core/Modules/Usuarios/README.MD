# Modulo Usuarios

---

## Menú principal

- [Modulo Usuarios](#modulo-usuarios)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
    - [TODOS ESPECÍFICOS](#todos-específicos)

## Descripción

El modulo de usuarios tiene como finalidad la respectiva gestión de los datos de los usuarios registrados en la plataforma sigia con el fin de mantener al día la información del usuario que realiza procesos como solicitudes de prestamos, gestión de elementos, entre otros.

## Arquitectura Interna (Pre-documentación)

- **Controlador Principal:** `Controller/UsuariosController.php` -> Maneja la vista principal del modulo y las peticiones http junto a las respectivas respuestas de las transacciones.
- **Modelos:** `Model/UsuariosModel.php` -> modelo que interactua con la tabla usuarios para la respectiva manipulación de datos del usuario.
- **Vistas Clave:** - `views/usuariosIndex.php` -> Panel principal del usuario.
  - `views/usuariosView.php` -> vista con el listado de los usuarios
  - `views/auditoriasView.php` -> vista de auditoria del usuario que ejecuto las acciones
- **Css** - `Css/Usuarios.css` -> archivo css perteneciente a la hoja de estilos de la vista `views/usuariosView.php`
  - `Css/AuditoriasUsuarios.css` -> archivo css que tiene la hoja de estilos de la auditoria del usuario

## Dependencias de Base de Datos

- Tabla principal: `usuarios`
- Relaciones: `estados_usuarios (id)`, `tipos_documento (id)`

### TODOS ESPECÍFICOS

- **Módulo Usuario**
- [ ] Re factorizar estructura de carpetas inicial
- [ ] Crear vista principal con su rastro de miga
- [ ] Crear vista de usuarios con rastro de miga
- [ ] Crear vista de auditorias del usuario con el rastro de miga
- [ ] Re factorizar css
- [ ] Re factorizar js
- [ ] Crear componente de paginacion en los registros de los usuarios.
- [ ] Re factorizar backend moviendo todo lo de modelo a las acciones del controlador
- [ ] Implementar y arreglar las funcionales ya definidas, estas funcionalidades estan en las historias de usuario de la documentación en el drive
- [ ] Limpiar los datos de los nuevos usuarios usando DIRTY DATA (PYTHON)
- [ ] Cargar datos limpios a la tabla de usuario
