# TODOS

Listado de TODOS generales y específicos por cada módulo.

## Menú principal

- [TODOS](#todos)
  - [Menú principal](#menú-principal)
  - [Generales](#generales)
  - [Específicos](#específicos)
  - [Estado de Módulos](#estado-de-módulos)
  - [Archivos especificos](#archivos-especificos)
  - [Documentación](#documentación)

## Generales

- [X] Implementar variables ENV según el contexto, si es dev o producción, en caso de ser requerido.
- [X] Crear logica que me permita definir cuales son los archivos que vamos a implementar en la vista, aplica para los css y javascript, esto se puede ejecutar cuando accedamos a la vista.
- [X] Ajustar la vista al modulo de configuraciones.
- [X] Arreglar paginacion a los elementos cuando este se encuentra en una pagina x o cuando se **elimina** un registro en la ultima pagina.
- [X] Transformar en clase aquellas funcionalidades de javascript repetibles
- [X] Investigar e implementar rastro de miga.
  - [X] Ajustar rastro de miga, debemos crear la logica en un solo arreglo en el configController.
- [X] Crear los campos created_at y updated_at a todas las tablas.
- [ ] RE factorizar el menu del footer
- [X] Arreglar archivo README.MD de los modulos que ya han sido ajustados (areas, tipo documento, marcas, roles, usuarios).
- [ ] Buscar como implementar mensajes de error a errores genericos de codigo, no de sintaxis., ejemplo: del error

  ```php
  // este error se genero porque no adicione la funcionalidad get()
      # incorrecto
      $dataInsert = $this->fModel->insert($data)->prepareSql($dataPrepare);
      #correcto
      $dataInsert = $this->fModel->insert($data)->prepareSql($dataPrepare)->get();

  <br />
  <b>Fatal error</b>:  Uncaught Error: Cannot use object of type FuncionesModel as array in /var/www/html/SIGIA/Core/Modules/Gestionmodulos/Controller/FuncionesController.php:60
  Stack trace:
  #0 /var/www/html/SIGIA/Core/Helpers/Router.php(55): FuncionesController-&gt;store()
  #1 /var/www/html/SIGIA/Core/dashboard.php(13): Router::ExecuteFunction()
  #2 {main}
    thrown in <b>/var/www/html/SIGIA/Core/Modules/Gestionmodulos/Controller/FuncionesController.php</b> on line <b>60</b><br />
  ```

- [ ] Eliminar el parametro "PARAMETER" DE LA FUNCIONALIDAD ROUTER:URL
- [ ] Cuando me quede sin internet el sistema no funcionaba producto de la conexion a los material icons de google, buscar una forma de evitar que el sistema se detenga cuando no haya conectividad a internet y requerir de librerias externas mediante url, una opcion es ver si podemos descargar los iconos e implementarlos en el sistema como libreria, similiar a lo que ocurre con materialize css.
- [ ] BUSCAR UNA FORMA DE COMO GUARDAR DE MANERA ADECUADA TODOS LOS MODELOS QUE NO REQUEIREN CONTROLADORES, POR EJEMPLO EN UNA CARPETA LLAMADA MODELS DENTRO DE HELPERS.
- [ ] Clase render - al renderizar un dato en una tabla renderizar exclusivamente el valor que esta en el id de la tabla, asi haya espacios entre ellas, si voy a cambiar la posicion de la tabla, cambiar también en donde se ubican los registros y los tds.
- [ ] Crear funcionalidad para ejecutar la paginacion de manera dinamica, ya se hizo su renderizado, ahora se ejecutara su renderizado desde la clase propia.
- [ ] enviar los estilos y el javascript de footer.php a archivos independientes.
- [ ] ajustar renderizado de menu (ver si se puede implementar por peticiones) y no por variables de session(usar localstorage).
- [ ] Analizar posibles causas de lentitud de la aplicacion EN EL login a medida que pasa el tiempo.
- [ ] **Documentación** = Colocar requisitos del sistema implementados y diagramas que se usaron para el proceso de creación del producto.
- [X] Arreglar palabras reservada para el levantamiento de la aplicación en linux
- [X] adicionar el tag de cuando se entrego el producto a la entidad de formación + tabla para visualizar la versión entregada.
- [X] Arreglar rutas para verificar el correcto funcionamiento del sistema como la re direccionamiento al login, o a otros módulos de acceso.
- [X] Implementar la validacion de los permisos en el executeFunction antes del renderizado
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
- [ ] Ajustar la seccion de seguridad de sistema en el modulo de funciones, crear l nuevo modulo.
- [ ] En el modulo **ROLES** crear otra vista interna y otro controlador para definir plantilla por defecto del sistema.
- [ ] Crear modulo de **Funciones** Y ajustar lo de funcionesRoles, entre otros al mismo modulo de funciones.

## Específicos

## Estado de Módulos

| Módulo                 | Progreso | Documentación |                     Enlace a Detalles                     |
| :--------------------- | :------: | :-----------: | :-------------------------------------------------------: |
| **Areas**              |   90%    |  Completado   |       [Ver README](./Core/Modules/Areas/README.MD)        |
| **Tipo Documento**     |   90%    |  Completado   |   [Ver README](./Core/Modules/TipoDocumento/README.MD)    |
| **Marcas**             |   90%    |  Completado   |       [Ver README](./Core/Modules/Marcas/README.MD)       |
| **Roles**              |   90%    |  Completado   |       [Ver README](./Core/Modules/Roles/README.MD)        |
| **Permisos**           |   50%    |  Completado   |      [Ver README](./Core/Modules/Permisos/README.MD)      |
| **Usuarios**           |   70%    |  En Proceso   |      [Ver README](./Core/Modules/Usuarios/README.MD)      |
| **Categorias**         |    0%    |   Pendiente   |     [Ver README](./Core/Modules/Categorias/README.MD)     |
| **Dashboard**          |    0%    |   Pendiente   |     [Ver README](./Core/Modules/Dashboard/README.MD)      |
| **Elementos**          |    0%    |   Pendiente   |     [Ver README](./Core/Modules/Elementos/README.MD)      |
| **Login**              |   100%   |   Pendiente   |       [Ver README](./Core/Modules/Login/README.MD)        |
| **Reportes**           |    0%    |   Pendiente   |      [Ver README](./Core/Modules/Reportes/README.MD)      |
| **SolicitudPrestamos** |    0%    |   Pendiente   | [Ver README](./Core/Modules/SolicitudPrestamos/README.MD) |
| **ReservaPrestamos**   |    0%    |   Pendiente   |  [Ver README](./Core/Modules/ReservaPrestamos/README.MD)  |
| **Gestionmodulos**     |    0%    |   Pendiente   |   [Ver README](./Core/Modules/Gestionmodulos/README.MD)   |
| **GeneralCrud**        |    0%    |   Pendiente   |    [Ver README](./Core/Modules/GeneralCrud/README.MD)     |
| **Funciones**          |    0%    |   Pendiente   |     [Ver README](./Core/Modules/Funciones/README.MD)      |

## Archivos especificos

-**Clase Permisos model**

- [ ] ajustar la logica de toda la clase para usar solamente los modelos

-**Clase loginController**

- [X] Ajustar la logica de inicio de sesion implementando los modelos

-**Clase dashboardController**

- [ ] Todo lo relacionado a lo que este en el escritorio como las tareas automaticas, implementarlas en el dashboard controller.

- [ ] Exceptiones - cuando ocurre un fallo, reportar el fallo y visualizarlo en pantalla principal para visualizar el error y hacer seguimiento, hacer seguimiento dependiendo de si es local o produccion.

---

## Documentación

- [ ] Ajustar diagrama de clases
- [ ] Crear Y/o ajustar historias de usuario
- [ ] Adicionar requisitos requeridos para cada modulo.
- [ ] Referenciar toda la documentación mediante un link de google drive para observar la documentación del producto
- [X] Adicionar archivo README.MD A cada modulo para presentar que hace cada producto.
