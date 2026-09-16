# Módulo Marcas

---

## Menú principal

- [Módulo Marcas](#módulo-marcas)
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
    - [TODOS ESPECÍFICOS](#todos-específicos)

## Descripción

Módulo auxiliar encargado de gestionar el catálogo de marcas disponibles en el sistema, permitiendo su registro, actualización, estado e integración con otros módulos operativos.

## Arquitectura Interna (Pre-documentación)

- **Constantes:** `Const/MarcasConst.php` -> Constantes globales del módulo, contiene títulos, variables, mensajes, entre otros.
- **Controlador Principal:** `Controller/MarcasController.php` -> Gestiona las peticiones, el renderizado de las vistas y las respuestas json de las respectivas peticiones.
- **Estilos:** `Css/Marcas.css` -> Contiene las reglas CSS específicas para el diseño y maquetación de las vistas del módulo de Marcas.
- **JavaScript Principal:** `Js/Marcas.js` -> Lógica frontend, captura de eventos, interacción con el consumo interno de datos, manipulación del DOM y paginación.
- **Modelo:** `Model/MarcasModel.php` -> Encargado del acceso a datos, consultas SQL y operaciones CRUD sobre las tablas del módulo de Marcas.
- **SQL (Estructura y Migraciones):** `Mysql/Marcas.sql` -> Definición base, creación de tablas (`CREATE TABLE`) y scripts de modificación requeridos por el módulo.
  - `Mysql/Alters.sql` -> sql con todos los alters modificados después de haber creado el módulo
- **Documentación:** `README.MD` -> Documentación del módulo
- **Vistas Clave:**
  - `Views/marcaView.php` -> Vista y panel principal para la gestión e interfaz del usuario.
  - `Views/tableMarcaView.php` -> Componente de tipo tabla que se incluye en el archivo `Views/marcaView.php`.

## Dependencias de Base de Datos

- Tabla principal: `marcas`

## Requerimientos

[Ver README.md](/Docs/Requirements/Configuracion.MD)

## Diagramas UML

### Caso de uso

![Caso de uso](/Docs/Uml/CasoUso_Extend/Configuracion//CasoUsoConfiguracion.jpg)

### Casos de uso Extendido

[VER README.md](/Docs/Uml/CasoUso_Extend/Configuracion/Extend_Configuracion.MD)

### Diagramas de secuencia

- **Crear item**

!['CrearItem'](/Docs/Uml/Secuencia/Configuracion/registrarItem.jpg)

- **Cambiar estado**

!['Cambiar Estado'](/Docs/Uml/Secuencia/Configuracion/cambiarEstado.svg)

- **Editar item**

!['EditarItem'](/Docs/Uml/Secuencia/Configuracion/editarItem.jpg)

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

### TODOS ESPECÍFICOS

- **Módulo marcas**
- [X] Implementar la interfaz CRUD en el controlador.
- [X] Implementar paginación desde el frontend y backend.
- [X] Configurar eventos de actualización, inhabilitación y eliminación en la tabla dinámica.
- [X] Ajustar la respuesta visual y actualización del DOM tras crear o editar una marca.
- [X] Implementar rastro de miga (breadcrumb) en la vista principal.
- [X] Crear instancia de clase para renderizar datos
- [X] implementar interfaz de crud al controlador
- [X] Add alters de los campos created_at, updated_at
- [ ] Ajustar diseño responsive y estilos generales.
