# Requerimientos Modulo Usuario

---

- [Requerimientos Modulo Usuario](#requerimientos-modulo-usuario)
  - [Introdución](#introdución)
  - [1 Matriz Resumen de Requerimientos](#1-matriz-resumen-de-requerimientos)
  - [2 Detalle de los requerimientos](#2-detalle-de-los-requerimientos)
    - [RFU-01: Crear Usuario](#rfu-01-crear-usuario)
    - [RFU-02: Consultar Usuario](#rfu-02-consultar-usuario)
    - [RFU-03: Modificar Usuario](#rfu-03-modificar-usuario)
    - [RFU-04: Habilitar/Inhabilitar Usuario](#rfu-04-habilitarinhabilitar-usuario)
    - [RFU-05: Actualizar datos personales](#rfu-05-actualizar-datos-personales)
    - [RFU-06: Ver detalle del usuario](#rfu-06-ver-detalle-del-usuario)

## Introdución

En este documento se encuentran los requerimientos establecidos para el desarollo del modulo de usuarios.

## 1 Matriz Resumen de Requerimientos

| Código     | Requerimiento               | Prioridad | Roles Permitidos               |
| :--------- | :-------------------------- | :-------: | :----------------------------- |
| **RFU-01** | Crear usuario               |   Alta    | Admin, Super Admin             |
| **RFU-02** | Consultar Usuario           |   Media   | Admin,Super admin,Coordinador  |
| **RFU-03** | Modificar Usuario           |   Media   | Admin, Super admin             |
| **RFU-04** | Habilitar/Inactivar Usuario |   Media   | Admin, Super Admin,Coordinador |
| **RFU-05** | Actualizar datos personales |   Media   | Todos                          |
| **RFU-06** | Ver detalle del usuario     |   Media   | Admin, Super Admin             |

## 2 Detalle de los requerimientos

### RFU-01: Crear Usuario

- **Descripción:** "El sistema deberá mostrar un formulario para el registro del nuevo usuario, donde solicitará obligatoriamente los siguientes datos:
     -Tipo de documento
     -Numero de identificación.
     -Nombres
     -Apellidos .
     -Teléfono.
     -Dirección.
     -Correo electrónico.
     -Contraseña
     -Observacion
-Nota adicional:
     -Rol/roles.
Una vez digitados estos datos, el sistema debe almacenarlos tomando el número de identificación como código único del cliente, dicho esto, No puede haber duplicidad de este. Asociando a estado el valor de Activo.

Esta función solo podrá ser realizada por los usuarios con rol/roles:

- **Excepciones:**
  - Si al momento de registrar el usuario no todos los campos están diligenciados, el sistema debe retornar un mensaje alertando: "Es obligatorio diligenciar todos los campos".
  - Si al momento de registrar el usuario alguno de los campos exeptuando el correo, contraseña y dirección, contiene caracteres especiales, el sistema debe retornar un mensaje alertando: **"Por favor no usar caracter-especial en el campo nombre-campo".**
  - Si al momento de registrar el usuario, el campo **"número de identificación"** no es numerico, el sistema debe retornar un mensaje alertando: **"El campo número de identificación debe ser númerico"**.
  - Si al ingresar los datos se encuentra el numero de identificacion ya registrado, el sistema debe retornar un mensaje alertando: "Ya existe el numero de identificacion registrado en la base de datos".
  - Si al momento de registrar el usuario no se encuentra al menos un rol seleccionado, el sistema debe retornar un mensaje alertando: **"Seleccione un rol para el usuario"**.
  - Si al ingresar los datos se encuentra el correo electronico ya registrado, el sistema debe retornar un mensaje alertando: "Ya existe el correo electronico registrado en la base de datos".

- **Criterios de aceptación:** Si al momento de registrar el usuario, no se genera ninguna excepción, el sistema debe retornar un mensaje alertando "Usuario registrado exitosamente".

### RFU-02: Consultar Usuario

- **Descripción:**
  - El sistema deberá permitir consultar todos los usuarios ya registrados en el sistema trayendo los datos:
     -Numero de identificación.
     -Nombres
     -Apellidos.
     -Rol/roles.
     -Estado
     -Acciones
  - El sistema debe contar con un pequeño formulario que permita realizar busquedas especificas trayendo coincidencias de alguno de los datos del usuario, por ejemplo, buscar un numero de cedula puntual o las cedulas que contengan el número 9. De acuerdo a este ejemplo, los datos que pueden buscar coincidencias en el sistema son: Nombres,Numero documento,Estado.
  - Esta función solo podrá ser realizada por los usuarios con rol/roles: **Administrador/Super administrador**.

- **Excepciones:**
  - Si al momento de consultar el listado de usuarios sin un filtro especifico y no existe ningún dato, el sistema deberá generar una alerta: "No se encontraron resultados"
  - Si al momento de ingresar la información para una consulta especifica, los datos suministrados contienen caracteres especiales (",',{,[,etc), el sistema debe suprimir dicho caracter al momento de hacer uso de este en la consulta.
  - Si al momento de ingresar la información para una consulta especifica, los datos suministrados no coinciden con ninguno de los registros, el sistema deberá alertar: "No se encontraron resultados".

- **Criterios de aceptación:** Si al momento de ingresar la información para una consulta especifica los datos suministrados coinciden con uno o más registros, el sistema debe reflejar dichas coincidencias.

### RFU-03: Modificar Usuario

- **Descripción:**
  - El sistema deberá permitir modificar ciertos datos de un usuario según su Identificación, que son:
     -Tipo de documento
     -Nombres
     -Apellidos .
     -Teléfono.
     -Dirección.
     -Correo electrónico.
     -Contraseña
     -Rol/roles.
  - El sistema deberá registrar estos cambios, una vez se hayan reemplazado los datos anteriores por los nuevos.
  - Esta función solo podrá ser realizada por los usuarios con rol: **Administrador/Super administrador.**

- **Excepciones:**
  - El campo número de identificación no pude ser modificado dentro del sistema.
  - Si al momento de actualizar la información se encuentra uno o más de los datos vacíos, el sistema debe generar la alerta: "El nombre_campo es obligatorio".
  - Si al momento de actualizar la información se identifica que el nuevo correo pertenece a otra persona, el sistema debe generar la alerta: "El correo ya se encuentra en uso por otro usuario".

- **Criterios de aceptación:** Si al momento de modificar el usuario, no se genera ninguna excepción, el sistema debe retornar un mensaje alertando "El usuario ha sido actualizado".

### RFU-04: Habilitar/Inhabilitar Usuario

- **Descripción:**
  - El sistema debrá permitir alternar el estado de los usuarios del sistema ya sea en "activo" o "inactivo" mediante un botón que se visualiza en la consulta como "estado".
  - Esta función solo podrá ser realizada por los usuarios con rol/roles: **Super administrador**

- **Excepciones:** Si al momento de confirmar que estamos seguros de realizar el cambio, la respuesta sea que no. El sistema debe cancelar el proceso, En ambos casos de cancelación de proceso, el sistema lo dejara en la interfaz de consulta.

- **Criterios de aceptacion:** Si  no se genera ninguna excepción, el sistema debe retornar un mensaje alertando "Los cambios se realizaron exitosamente".

### RFU-05: Actualizar datos personales

- **Descripción:** El sistema deberá permitir actualizar los datos personales del usuario logueado en el sistema mediante un formulario con los siguientes campos: Nombre, apellido, teléfono, correo.

- **Excepciones:** - Los usuarios que no esten logueados en el sistema no podran realizar esta accion

- **Criterios de aceptacion:** Una vez enviada la información, el sistema debera indicar al usuario que sus datos han sido actualizados y por ende, será re direccionado al login.

### RFU-06: Ver detalle del usuario

- **Descripcion:** El sistema debe permitir al usuario con rol: **- Super administrador** visualizar la informacion personal del usuario mediante un boton de accion con el fin de obtener el historico de acciones realizadas en el sistema como tambien los prestamos que el usuario ha realizado y en caso de ser requerido, ver el PRESTAMO ACTUAL de los elementos, entre otros.

- **Excepciones:** En caso de no tener permisos de acceso de lectura a los modulos: elementos y/o reserva prestamos, prestamos, este debera solamente mostrar la información personal del usuario

- **Criterios de aceptacion:** Se debe visualizar la informacion detallada del usuario respetando la integridad del sistema.
