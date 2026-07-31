# Módulo TipoDocumento

---

## Menú principal

- [Módulo TipoDocumento](#módulo-tipodocumento)
  - [Menú principal](#menú-principal)
  - [Descripción](#descripción)
  - [Arquitectura Interna (Pre-documentación)](#arquitectura-interna-pre-documentación)
  - [Dependencias de Base de Datos](#dependencias-de-base-de-datos)
    - [TODOS ESPECÍFICOS](#todos-específicos)

## Descripción

Módulo auxiliar encargado de gestionar los diferentes tipos de documentos de identificación u operativos disponibles en el sistema, permitiendo el uso de los registros asociados al modulo tipo de documento, su uso secundario es en el modulo **USUARIOS**.

## Arquitectura Interna (Pre-documentación)

- **Constantes:** `Const/TpConst.php` -> Constantes globales del módulo, contiene títulos, variables, mensajes, entre otros.
- **Controlador Principal:** `Controller/TipoDocumentoController.php` -> Gestiona las peticiones, el renderizado de las vistas y las respuestas json de las respectivas peticiones.
- **Estilos:** `Css/TipoDocumento.css` -> Contiene las reglas CSS específicas para el diseño y maquetación de las vistas del módulo de TipoDocumento.
- **JavaScript Principal:** `Js/TipoDocumento.js` -> Lógica frontend, captura de eventos, interacción con la API interna,manipulación del DOM y paginación.
- **Modelo:** `Model/TipoDocumentoModel.php` -> Encargado del acceso a datos, consultas SQL y operaciones CRUD sobre las tablas del módulo de TipoDocumento.
- **SQL (Estructura y Migraciones):** `Mysql/TipoDocumento.sql` -> Definición base, creación de tablas (`CREATE TABLE`) y scripts de modificación requeridos por el módulo.
- **Documentación:** `README.MD` -> Documentación técnica general y especificaciones operativas del módulo.
- **Servicios:** `Services/ServicesTipoDocumento.php` -> Contiene la lógica de negocio que nos permite re utilizar en otros controladores como por ejemplo en el controlador de usuario
- **Vistas Clave:**
  - `Views/tpDocumentoView.php` -> Vista y panel principal para la gestión e interfaz del usuario.
  - `Views/tableViewtpDocumento.php` -> Componente de tipo tabla que se incluye en el archivo Views/tpDocumentoView.php.

## Dependencias de Base de Datos

- Tabla principal: `tipo_documento`

### TODOS ESPECÍFICOS

- **Módulo TipoDocumento**
- [X] Implementar la interfaz CRUD en el controlador y conectar con la capa de servicios.
- [X] Implementar paginación desde el frontend y backend.
- [X] Implementar rastro de miga (breadcrumb) en la vista principal.
- [X] Crear clase js para renderizar y enviar datos.
- [X] Aplicar lo mismo que el modulo areas.
- [X] implementar interfaz de crud al controlador
- [ ] Ajustar diseño responsive y estilos generales.
