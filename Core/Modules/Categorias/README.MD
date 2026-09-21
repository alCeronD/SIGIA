# Categorias

---

## Menú principal

- [Categorias](#categorias)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna](#arquitectura-interna)
  - [Dependencias de la base de datos](#dependencias-de-la-base-de-datos)
  - [Requerimientos](#requerimientos)
  - [Diagramas UML](#diagramas-uml)
    - [Caso de uso](#caso-de-uso)
    - [Casos de uso Extendido](#casos-de-uso-extendido)
    - [Diagramas de secuencia](#diagramas-de-secuencia)
    - [Diagramas de actividades](#diagramas-de-actividades)
  - [TODOS](#todos)

## Descripción

Este modulo consiste en la gestión de las categorias de los elementos registrados que operan en la central didactica, este módulo presenta una ayuda para la clasificación de elementos, esta asociado con el módulo de  [**Elementos**](/Core/Modules/Elementos/README.MD) en la cual se consume su información desde la capa de servicios en el archivo **ServicesCategorias.php** que conecta la información entre el controlador del módulo **Categorias** y el módulo **Elementos**.

## Arquitectura Interna

Estructura del módulo:

```bash
├── Const
│   └── CategoriasConst.php # Archivo con variables constantes asociadas al modulo
├── Controller
│   └── CategoriasController.php # Controlador del modulo
├── Css
│   └── Categorias.css # Estilos css
├── Js
│   ├── Categorias.js # Archivo principal para gestionar las peticiones, y paginación
│   └── SelectorsCategorias.js # Selectores para exportar que se usan en `Categorias.js`
├── Model
│   └── CategoriasModel.php # Tabla categorias
├── Mysql
│   ├── alters.sql # Script principal del módulo
│   └── tables.sql # Alters del módulo
├── README.MD
├── Services
│   └── ServicesCategorias.php # Capa de servicio para conectar con el módulo categorias y otros módulos como ELEMENTOS
└── Views
    └── CategoriasView.php # Archivo html con la vista del módulo
```

## Dependencias de la base de datos

- Tabla principal: - `Categorias`
- Tablas relacionadas: -`Elementos` (elm_categoria)

## Requerimientos

[Requerimientos](/Docs/Requirements/Configuracion.MD)

## Diagramas UML

### Caso de uso

![Caso de uso](/Docs/Uml/CasoUso_Extend/Configuracion//CasoUsoConfiguracion.jpg)

### Casos de uso Extendido

[Caso de uso extendido](/Docs/Uml/CasoUso_Extend/Configuracion/Extend_Configuracion.MD)

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

## TODOS

- [X] Implementar tree del modulo
- [X] Ajustar Controlador
  - [X] Implementar getData desde la capa servicio
  - [X] Implementar store desde el controlador
  - [X] Implementar save
  - [X] Implementar delete
  - [X] Implementar changeStatus
- [X] Implementar css responsive
- [X] Implementar paginacion y renderizacion de datos desde la clase render en el frontendt
- [x] add funciones de acciones en el modulo (eliminar, crear, changeStatus,edit).
- [X] Implementar capa de services para consumir en otro modulo
- [X] Fix: arreglar scanfiles y router cuando no hayan archivos css o javascript que renderizar, marcar el error y mostrar la plantilla de error de manera correcta
