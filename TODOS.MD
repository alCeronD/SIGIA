# TODOS

Listado de TODOS generales y específicos por cada módulo.

## Menú principal

- [TODOS](#todos)
  - [Menú principal](#menú-principal)
  - [Generales](#generales)
  - [Específicos](#específicos)
  - [📦 Estado de Módulos](#-estado-de-módulos)
  - [Archivos especificos](#archivos-especificos)
  - [Documentación](#documentación)

## Generales

- [X] Implementar variables ENV según el contexto, si es dev o producción, en caso de ser requerido.
- [ ] Crear logica que me permita definir cuales son los archivos que vamos a implementar en la vista, aplica para los css y javascript, esto se puede ejecutar cuando accedamos a la vista.
- [ ] Arreglar paginacion a los elementos cuando este se encuentra en una pagina x o cuando se **elimina** un registro en la ultima pagina.
- [ ] Transformar en clase aquellas funcionalidades de javascript repetibles
- [ ] Investigar e implementar rastro de miga.
- [X] Arreglar palabras reservada para el levantamiento de la aplicación en linux
- [X] adicionar el tag de cuando se entrego el producto a la entidad de formación + tabla para visualizar la versión entregada.
- [X] Arreglar rutas para verificar el correcto funcionamiento del sistema como la re direccionamiento al login, o a otros módulos de acceso.
- [X] Implementar la validacion de los permisos en el executeFunction antes del renderizado
- [ ] **Documentación** = Colocar requisitos del sistema implementados y diagramas que se usaron para el proceso de creación del producto.
- [X] Cambiar estructura de carpetas del proyecto, cambiar la carpeta app a core y core debe de tener solo config, helpers, adicionar la carpeta mod que esa tendrá los módulos del proyecto
- [X] Crear el núcleo del proyecto que me permita crear escalar el proyecto según la necesidad.
- [X] Re definir estructura de carpetas del proyecto para asi módularizar más los módulos.
- [X] Crear constantes a cada modulo y si se puede al core principal para evitar redundancia en mensajes de alerta y/o textos.
- [X] Crear CRUD
  - [X] Crear consulta basica select
  - [X] Crear consulta insert basica
  - [X] Crear estructura paginada
  - [X] Crear update
  - [X] Crear delete
  - [X] Crear where
    - [X] ajustar where para hacer consulta con mas opciones and y or
    - [X] Crear whereIn
  - [X] Crear joins
    - [X] Inner join
    - [X] Left Join
    - [X] Right Join
  - [X] Crear count
- [X] Mover archivos css a cada modulo correspondiente.
- [X] Mover archivos javascript a cada modulo correspondiente
- [X] Ajustar la lectura de las rutas y permitir que se lean mas archivos css y javascript
- [X] Transformar en una clase el renderizado de datos
- [X] EN PHP, AJUSTAR EL RESPONSE DE LA CLASE EN DONDE la funcion se puede transformar en una sola.

## Específicos

## 📦 Estado de Módulos

| Módulo               | Progreso | Documentación | Enlace a Detalles                                       |
| :------------------- | :------: | :-----------: | :------------------------------------------------------ |
| **Areas**            |   90%    | 🔄 En proceso  | [Ver README](./Core/Modules/Areas/README.MD)            |
| **Categorias**       |   80%    |  ❌ Pendiente  | [Ver README](./Core/Modules/Categorias/README.MD)       |
| **ReservaPrestamos** |   40%    |    Diseño     | [Ver README](./Core/Modules/ReservaPrestamos/README.MD) |
| **Modulos**          |    0%    |  ❌ Pendiente  | [Ver README](./Core/Modules/Modulos/README.MD)          |

## Archivos especificos

-**Clase Permisos model**

- [ ] ajustar la logica de toda la clase para usar solamente los modelos

-**Clase loginController**

- [ ] Ajustar la logica de inicio de sesion implementando los modales

-**Clase dashboardController**

- [ ] Todo lo relacionado a lo que este en el escritorio como las tareas automaticas, implementarlas en el dashboard controller.

---

## Documentación

- [ ] Ajustar diagrama de clases
- [ ] Crear historias de usuario
- [ ] Adicionar requisitos requeridos para el documento.
- [ ] Referenciar toda la documentación mediante un link de google drive para observar la documentación del producto
- [ ] Adicionar archivo README.MD A cada modulo para presentar que hace cada producto.
