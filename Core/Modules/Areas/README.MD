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

- **Constantes:** `Const/AreasConst.php` -> Constantes globales del modulo, contiene titulos, variables, mensajes, entre otros.
- **Controlador Principal:** `Controller/AreasController.php` -> Gestiona las peticiones, el renderizado de las vistas y las respuestas json de las respectivas peticiones.
- **Estilos:** `Css/Areas.css` -> Contiene las reglas CSS específicas para el diseño y maquetación de las vistas del módulo de Áreas.
- **JavaScript Principal:** `Js/Areas.js` -> Lógica frontend, captura de eventos, interacción con la API interna y manipulación del DOM y paginacón
- **Selectores DOM:** `Js/Selectors.js` -> Selectores para exportar y re utilizar en el archivo `Areas.js`
- **Modelo:** `Model/AreasModel.php` -> Encargado del acceso a datos, consultas SQL y operaciones CRUD sobre las tablas del módulo de Áreas.
- **SQL (Migraciones):** `Mysql/alters.sql` -> Scrips de modificación de la tabla areas.sql
- **SQL (Estructura):** `Mysql/tables.sql` -> Definición base y scripts de creación de tablas (`CREATE TABLE`) requeridas por el módulo.
- **Documentación:** `README.MD` -> Documentación técnica general y especificaciones operativas del módulo.
- **Vistas Clave:**
  - `Views/areaView.php` -> Vista y panel principal para la gestión e interfaz del usuario.
  - `Views/tableViewArea.php` -> Componente de tipo tabla que se incluye en el archivo Views/areaView.php

## Dependencias de Base de Datos

- Tabla principal: `areas`

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
- [X] Implementar rastro de miga
- [X] implementar interfaz de crud al controlador
- [ ] Modificar css responsive, implementar estilos a botones preview y next.
- [ ] Arreglar bug de paginación al seleccionar una pagina en concreto y presionar el botón preview y next
- [ ] PROBAR EL ARRAY ACCESS CON EL MODULO DE AREAS, E IMPLEMENTAR ERRORES DE EXCEPTIONES PROPIOS AL QUERY BUILDER

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
