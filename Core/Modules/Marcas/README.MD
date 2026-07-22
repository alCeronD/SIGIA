# Módulo Marcas

---

## Menú principal

- [Módulo Marcas](#módulo-marcas)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
    - [TODOS ESPECÍFICOS](#todos-específicos)

## Descripción

Módulo auxiliar o de apoyo encargado de gestionar el catálogo de marcas disponibles en el sistema, permitiendo su registro, actualización, estado e integración con otros módulos operativos.

## Arquitectura Interna (Pre-documentación)

- **Constantes:** `Const/MarcasConst.php` -> Constantes globales del módulo, contiene títulos, variables, mensajes, entre otros.
- **Controlador Principal:** `Controller/MarcasController.php` -> Gestiona las peticiones, el renderizado de las vistas y las respuestas json de las respectivas peticiones.
- **Estilos:** `Css/Marcas.css` -> Contiene las reglas CSS específicas para el diseño y maquetación de las vistas del módulo de Marcas.
- **JavaScript Principal:** `Js/Marcas.js` -> Lógica frontend, captura de eventos, interacción con la API interna y manipulación del DOM y paginación.
- **Modelo:** `Model/MarcasModel.php` -> Encargado del acceso a datos, consultas SQL y operaciones CRUD sobre las tablas del módulo de Marcas.
- **SQL (Estructura y Migraciones):** `Mysql/Marcas.sql` -> Definición base, creación de tablas (`CREATE TABLE`) y scripts de modificación requeridos por el módulo.
- **Documentación:** `README.MD` -> Documentación técnica general y especificaciones operativas del módulo.
- **Vistas Clave:**
  - `Views/marcaView.php` -> Vista y panel principal para la gestión e interfaz del usuario.
  - `Views/tableMarcaView.php` -> Componente de tipo tabla que se incluye en el archivo Views/marcaView.php.

## Dependencias de Base de Datos

- Tabla principal: `marcas`

### TODOS ESPECÍFICOS

- **Módulo marcas**
- [X] Implementar la interfaz CRUD en el controlador.
- [X] Implementar paginación desde el frontend y backend.
- [X] Configurar eventos de actualización, inhabilitación y eliminación en la tabla dinámica.
- [X] Ajustar la respuesta visual y actualización del DOM tras crear o editar una marca.
- [X] Implementar rastro de miga (breadcrumb) en la vista principal.
- [X] Crear instancia de clase para renderizar datos
- [X] implementar interfaz de crud al controlador
- [ ] Ajustar diseño responsive y estilos generales.
