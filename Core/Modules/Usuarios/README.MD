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

- **Controlador Principal:** `Controller/UsuariosController.php` -> Maneja la vista principal del módulo y las peticiones HTTP junto a las respectivas respuestas de las transacciones (altas, bajas, modificaciones y consultas de usuarios).
- **Modelos:** `Model/UsuariosModel.php` -> Modelo que interactúa directamente con la base de datos (tabla `usuarios`) para la respectiva manipulación, validación y persistencia de los datos del usuario.
- **Constantes:** `Const/UsuariosConst.php` -> Define los valores fijos, mensajes de error, titulos y variables reutilizables en todo el módulo de Usuarios.
- **Vistas Clave:**
  - `Views/usuariosIndex.php` -> Panel principal del módulo de usuarios (interfaz de acceso con las opciones adicionales del modulo.).
  - `Views/usuariosView.php` -> Vista que renderiza el listado general de los usuarios registrados en el sistema.
  - `Views/createUsuariosView.php` -> Interfaz con el formulario dedicado a la creación de nuevos usuarios.
  - `Views/updatePersonalData.php` -> Formulario específico para que un usuario pueda actualizar sus propios datos personales o de perfil.
  - `Views/auditoriasView.php` -> Vista de auditoría encargada de mostrar el historial de acciones y movimientos ejecutados por los usuarios.
- **Css:**
  - `Css/UsuariosIndex.css` -> Hoja de estilos correspondiente al panel principal (`usuariosIndex.php`).
  - `Css/UsuariosView.css` -> Archivo CSS perteneciente a la hoja de estilos de la vista de listado (`usuariosView.php`).
  - `Css/CreateUser.css` -> Estilos visuales aplicados al formulario de creación de usuarios (`createUsuariosView.php`).
  - `Css/updatePersonalData.css` -> Hoja de estilos para la interfaz de actualización de datos personales (`updatePersonalData.php`).
  - `Css/AuditoriasUsuarios.css` -> Archivo CSS que contiene la estructura visual y diseño de la tabla/vista de auditoría (`auditoriasView.php`).
- **Js:**
  - `Js/UsuariosIndex.js` -> Lógica en el cliente (validaciones, eventos o llamadas asíncronas) para el panel principal.
  - `Js/UsuariosView.js` -> Controla el comportamiento dinámico del listado de usuarios (filtros, paginación o modales de eliminación/bloqueo).
  - `Js/CreateUser.js` -> Gestiona la validación de campos en tiempo real y el envío vía AJAX del formulario de registro de nuevos usuarios.
  - `Js/updatePersonalData.js` -> Maneja la interactividad y verificación de datos del formulario de actualización de perfil.
  - `Js/AuditoriasUsuarios.js` -> Lógica para la carga dinámica, búsqueda o filtrado de los registros del historial de auditoría.

## Dependencias de Base de Datos

- **Tabla principal:** `usuarios`
- **Tablas asociadas:** `auditorias`, `estados_usuarios`, `tipo_documento`, `roles`
- **Conexión con otros módulos:** `roles`, `tip_documento`
- **Relaciones:**
  - `estados_usuarios (id)` -> Llave foránea en `usuarios` para controlar si el usuario está activo, inactivo o suspendido.
  - `tipos_documento (id)` -> Llave foránea en `usuarios` para asociar el tipo de identificación (C.C., T.I., C.E., etc.).
  - `roles (id)` -> Relación para definir los permisos y el nivel de acceso del usuario dentro de SIGIA.

### TODOS ESPECÍFICOS

- **Módulo Usuario**
- [X] Re factorizar estructura de carpetas inicial
- [X] Desarrollar historia de usuario # HU-U00
- [X] Desarrollar historia de usuario # HU-U01 (Registrar usuario).
- [ ] Desarrollar historia de usuario de paginacion de usuario y consulta con filtros.
- [ ] Re factorizar css
- [ ] Re factorizar js
- [X] Crear componente de paginacion en los registros de los usuarios.
- [ ] Re factorizar backend moviendo todo lo de modelo a las acciones del controlador
- [ ] Implementar y arreglar las funcionales ya definidas, estas funcionalidades estan en las historias de usuario de la documentación en el drive
- [ ] Limpiar los datos de los nuevos usuarios usando DIRTY DATA (PYTHON)
- [ ] Cargar datos limpios a la tabla de usuario
- [ ] Crear mockup e historia de usuario del feature: CARGA MASIVA DE REGISTROS.
- [ ] Investigar como implementar el anterior todo
- [ ] Crear historia de usuario de carga de datos mediante excel.
- [ ] Desarrollar feature de historia de usuario carga de datos.
