# Módulo Configuracion

---

## Introdución

En este documento encontraras los **Requerimientos funcionales** que aplican a los módulos: **Areas, tipo de documento, marcas, categoria**.

- [Módulo Configuracion](#módulo-configuracion)
  - [Introdución](#introdución)
  - [1 Matriz Resumen de Requerimientos](#1-matriz-resumen-de-requerimientos)
  - [2 Detalle de los requerimientos](#2-detalle-de-los-requerimientos)
    - [RF-CONFIG-01: Registrar Ítem](#rf-config-01-registrar-ítem)
    - [RF-CONFIG-02: Modificar Ítem](#rf-config-02-modificar-ítem)
    - [RF-CONFIG-03: Eliminar Ítem](#rf-config-03-eliminar-ítem)
    - [RF-CONFIG-04: Inactivar Item](#rf-config-04-inactivar-item)
    - [RF-CONFIG-05: Activar Item](#rf-config-05-activar-item)

## 1 Matriz Resumen de Requerimientos

| Código           | Requerimiento  | Prioridad | Roles Permitidos   | Módulos Aplicables                         |
| :--------------- | :------------- | :-------: | :----------------- | :----------------------------------------- |
| **RF-CONFIG-01** | Registrar Ítem |   Alta    | Administrador      | Marcas, Áreas, Tipo Doc, Categorías        |
| **RF-CONFIG-02** | Modificar Ítem |   Media   | Administrador      | Marcas, Áreas, Tipo Doc, Categorías        |
| **RF-CONFIG-03** | Eliminar Ítem  |   Baja    | Admin, Super Admin | Marcas, Áreas, Tipo Doc, Categorías        |
| **RF-CONFIG-04** | Inactivar Ítem |   Media   | Admin, Super Admin | Marcas, Áreas, Tipo Doc, Categorías        |
| **RF-CONFIG-05** | Activar Ítem   |   Media   | Admin, Super Admin | Marcas, Áreas, Tipo Doc, Categorías, Roles |

## 2 Detalle de los requerimientos

### RF-CONFIG-01: Registrar Ítem

- **Descripción:** El sistema deberá permitir al rol de administrador crear registros en los siguientes módulos: marcas, áreas, tipos de documento, categorías mediante un formulario.
- **Excepciones:**
  - Se verificarán los datos del formulario para evitar registros duplicados.
  - El campo descripción no es obligatorio.
  - El campo nombre es obligatorio
  - Este proceso es aplicable a los módulos: marcas, Áreas, Tipo de documento, categorías.
- **Criterios de aceptación:** Los administradores pueden crear nuevos registros usando los campos establecidos para cada módulo correspondiente.

### RF-CONFIG-02: Modificar Ítem

- **Descripción:** El sistema deberá permitir al rol de administrador modificar información de los registros en los módulos de configuración
- **Excepciones:**
  - Se verificaran los datos del formulario para evitar los registros duplicados.
  - El campo nombre debe ser obligatorio.
  - Este proceso es aplicable a los modulos: marcas, areas, tipo de documentos, categorias
- **Criterios de aceptación** Los administradores pueden modificar una marca en el sistema mediante el uso de el formulario con los campos establecidos

### RF-CONFIG-03: Eliminar Ítem

- **Descripción:** El sistema deberá permitir al rol de administrador/super administrador eliminar registros en los módulos de parámetros del sistema.
- **Excepciones:**
  - Se mostrará una alerta para confirmar la acción de eliminación del registro.
  - Este requerimiento aplica a los siguientes módulos Aplica a: marcas, áreas, tipos de documento, categorías
- **Criterios de aceptación** Los administradores pueden eliminar registros específicos de los módulos mencionados.

### RF-CONFIG-04: Inactivar Item

- **Descripción:** El sistema deberá permitir al rol de ADMINISTRADOR Y/O SUPER ADMINISTRADOR inactivar items en los módulos de parámetros para evitar su uso sin eliminarlos.
- **Excepciones:**
  - Se mostrará una alerta para confirmar la inactivación del item.
  - En caso de que el usuario presione el botón "cancelar" el proceso no debe ejecutarse.
  - Aplica a los siguientes módulos: marcas, áreas, tipos de documento, categorías
- **Criterios de aceptación** Los administradores y/o super administradores pueden inhabilitar temporalmente item que no deben ser utilizados.

### RF-CONFIG-05: Activar Item

- **Descripción:** El sistema deberá permitir al rol de ADMINISTRADOR Y/O SUPER ADMINISTRADOR reactivar items previamente inactivos en los módulos de parámetros.
- **Excepciones:**
  - Se mostrará una alerta para confirmar la acción de activación del registro.
  - Aplica a los siguientes módulos: marcas, áreas, tipos de documento, categorías y roles.
- **Criterios de aceptación:** Los ADMINISTRADORES y/o SUPER ADMINISTRADORES pueden volver a habilitar item para que puedan ser utilizados nuevamente.
