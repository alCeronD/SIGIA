# Areas

---

## Menú principal

- [Areas](#areas)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
  - [Requerimientos](#requerimientos)
  - [Diagramas UML](#diagramas-uml)
    - [Caso de uso](#caso-de-uso)
    - [Casos de uso Extendido](#casos-de-uso-extendido)
    - [Diagramas de secuencia](#diagramas-de-secuencia)
    - [Diagramas de actividades](#diagramas-de-actividades)
  - [TODOS](#todos)

## Descripción

Este modulo consiste en la gestión de los departamentos que se encuentran en operación en la central didactica, se implementa este módulo con el fin de gestionar la parametrización y acceso a otros módulos como lo son [**Elementos**](/Core/Modules/Elementos/README.MD), [**Reserva Prestamos**](/Core/Modules/Reservaprestamos/README.MD), entre otros.

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

## Requerimientos

[VER README](/Docs/Requirements/Configuracion.MD)

## Diagramas UML

### Caso de uso

!['Caso de uso'](/Docs/Uml/CasoUso_Extend/Configuracion//CasoUsoConfiguracion.jpg)

### Casos de uso Extendido

!['Caso de uso extendido'](/Docs/Uml/CasoUso_Extend/Configuracion/Extend_Configuracion.MD)

### Diagramas de secuencia

- **Crear item**

!['CrearItem'](/Docs/Uml/Secuencia/Configuracion/registrarItem.jpg)

- **Cambiar estado**

!['Cambiar Estado'](/Docs/Uml/Secuencia/Configuracion/cambiarEstado.svg)

- **Editar item**

!['EditarItem'](/Docs/Uml/Secuencia/Configuracion/editarItem.svg)

- **Eliminar Item**

!['EliminarItem](/Docs/Uml/Secuencia/Configuracion/eliminarItem.jpg)

### Diagramas de actividades

- **Registrar item**

!['RegistrarItem'](/Docs/Uml/Actividades/Configuracion/RegistrarItem.svg)

- **Editar item**

!['EditarItem'](/Docs/Uml/Actividades/Configuracion/EditarItem.svg)

- **Eliminar Item**

!['EliminarItem'](/Docs/Uml/Actividades/Configuracion/EliminarItem.jpg)

- **Cambiar estado**

!['CambiarEstado'](/Docs/Uml/Actividades/Configuracion/CambiarEstado.svg)

## TODOS

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
- [X] Modificar css responsive, implementar estilos a botones preview y next.
- [ ] Arreglar bug de paginación al seleccionar una pagina en concreto y presionar el botón preview y next
