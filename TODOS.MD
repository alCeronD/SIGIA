# TODOS

Listado de TODOS generales y específicos por cada módulo.

## Menú principal

- [TODOS](#todos)
  - [Menú principal](#menú-principal)
  - [Generales](#generales)
  - [Específicos](#específicos)

## Generales

- [X] Arreglar palabras reservada para el levantamiento de la aplicación en linux
- [X] adicionar el tag de cuando se entrego el producto a la entidad de formación + tabla para visualizar la versión entregada.
- [X] Arreglar rutas para verificar el correcto funcionamiento del sistema como la re direccionamiento al login, o a otros módulos de acceso.
- [X] Implementar la validacion de los permisos en el executeFunction antes del renderizado
- [ ] **Documentación** = Colocar requisitos del sistema implementados y diagramas que se usaron para el proceso de creación del producto.
- [X] Cambiar estructura de carpetas del proyecto, cambiar la carpeta app a core y core debe de tener solo config, helpers, adicionar la carpeta mod que esa tendrá los módulos del proyecto
- [ ] Crear el núcleo del proyecto que me permita crear escalar el proyecto según la necesidad.
- [X] Re definir estructura de carpetas del proyecto para asi módularizar más los módulos.
- [X] Crear constantes a cada modulo y si se puede al core principal para evitar redundancia en mensajes de alerta y/o textos.
- [ ] Crear CRUD
  - [X] Crear consulta basica select
  - [X] Crear consulta insert basica
  - [X] Crear estructura paginada
  - [X] Crear update
  - [X] Crear delete
  - [X] Crear where
    - [ ] ajustar where para hacer consulta con mas opciones and y or
  - [ ] Crear join
  - [X] Crear count
- [X] Mover archivos css a cada modulo correspondiente.
- [X] Mover archivos javascript a cada modulo correspondiente
- [X] Ajustar la lectura de las rutas y permitir que se lean mas archivos css y javascript
- [ ] Investigar e implementar rastro de miga.
- [ ] Implementar variables ENV según el contexto, si es dev o producción, en caso de ser requerido.

## Específicos

- **Módulo areas**
- [ ] Implementar feature de eliminar item, está el de habilitar e inhabilitar pero no eliminar registro.
- [ ] Modificar color de fondo del modal
- [ ] Refactorizar el javascript y transformar todo de ajax a fetch
- [X] Implementar paginado en front y en back
- [ ] Implementar cantidad de registros, boton de preview y next + mostrar la pagina, ejemplo: se muestran 0 de n registros
- [ ] Implementar rastro de miga
- [X] Implementar backend de eventos de actualizar, inhabilitar y eliminar pero la asignacion de evento sera en la tabla
- [ ] Implementar frontend de acciones
