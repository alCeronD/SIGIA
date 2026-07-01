# Modulo Areas

---

## Menú principal

- [Modulo Areas](#modulo-areas)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
    - [TODOS ESPECÍFICOS](#todos-específicos)

## Descripción

Breve explicación de qué necesidad de negocio resuelve este módulo en el sistema (ej: "Gestiona el flujo de solicitudes, aprobación y salida de bienes consumibles y devolutivos...").

## Arquitectura Interna (Pre-documentación)

- **Controlador Principal:** `Controller/ReservaPrestamosController.php` -> Maneja las peticiones HTTP y respuestas JSON para fetch.
- **Servicios:** `Services/ServicesReservas.php` -> Contiene la lógica de negocio pesada extraída del controlador.
- **Modelos:** `Model/ReservaModel.php` -> Interactúa con las tablas `reservas` y `detalle_reservas`.
- **Vistas Clave:** - `views/reservaView.php` -> Panel principal del usuario.
  - `views/modalAddReserva.php` -> Formulario dinámico de inserción.

## Dependencias de Base de Datos

- Tabla principal: `reservas`
- Relaciones: `usuarios (id)`, `elementos (id)`

### TODOS ESPECÍFICOS

- **Módulo areas**
- [X] Implementar feature de eliminar item, está el de habilitar e inhabilitar pero no eliminar registro.
- [X] Modificar color de fondo del modal
- [X] Refactorizar el javascript y transformar todo de ajax a fetch
- [X] Implementar paginado en front y en back
- [X] Implementar backend de eventos de actualizar, inhabilitar y eliminar pero la asignacion de evento sera en la tabla
- [X] Implementar post de crear area
- [X] Implementar frontend de acciones
- [X] Ajustar renderizado de datos cuando edito o creo un nuevo elemento.
- [X] Implementar cantidad de registros, boton de preview y next + mostrar la pagina, ejemplo: se muestran 0 de n registros
- [ ] Implementar rastro de miga
- [X] implementar interfaz de crud al controlador