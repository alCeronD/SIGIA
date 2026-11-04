# Caso de uso extendido - Inicio de sesión

---

- [Caso de uso extendido - Inicio de sesión](#caso-de-uso-extendido---inicio-de-sesión)
  - [Introducción](#introducción)
  - [CU-01: Iniciar sesión](#cu-01-iniciar-sesión)
    - [Curso Normal de los Eventos](#curso-normal-de-los-eventos)
    - [Cursos Alternos CU-01](#cursos-alternos-cu-01)
  - [CU-02: Cerrar Sesión](#cu-02-cerrar-sesión)
    - [Curso normal de los eventos CU-02](#curso-normal-de-los-eventos-cu-02)
    - [Cursos Alternos CU-02](#cursos-alternos-cu-02)

## Introducción

Este documento contiene informacion acerca de los diferentes **casos de uso extendidos** del módulo de login, es una especificación más detallada acerca de las acciones establecidas en el caso de uso presentado en UML en el archivo [README.MD](/Core/Modules/Login/README.MD)

---

## CU-01: Iniciar sesión

| Atributo                 | Detalle                                                                                                                              |
| :----------------------- | :----------------------------------------------------------------------------------------------------------------------------------- |
| **Código**               | **CU-01**                                                                                                                            |
| **Nombre**               | Crear Item                                                                                                                           |
| **Descripción**          | El sistema permitirá iniciar sesión mediante un formulario con dos campos para número de identificación y contraseña respectivamente |
| **Actor(es)**            | Usuarios                                                                                                                             |
| **Tipo**                 | Primario                                                                                                                             |
| **Referencias Cruzadas** | RFA-01, RFA-02                                                                                                                       |

---

### Curso Normal de los Eventos

|  N°   | Acción del Actor                                                | Respuesta del Sistema                                                                                                                                                                                                                                                                                                                                                                                                                               |
| :---: | :-------------------------------------------------------------- | :-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Este caso de uso comienza cuando el usuario ingresa al sistema. | **1.1** El sistema muestra el Formulario de Inicio de sesión con los campos de cédula, contraseña y un botón para visualizar y/o ocultar la contraseña, representado con un icono de ojo.                                                                                                                                                                                                                                                           |
| **2** | El usuario ingresa el número de identificación.                 | **2.1** El sistema valida que el número de identificación no sea un campo vacío.<br>**2.2** El sistema solo permitirá ingresar números. <br> **2.3** El sistema valida que el número de identificación contenga al menos 7 caracteres. <br> El sistema valida que el número de identificación contenga máximo 10 caracteres.                                                                                                                        |
| **3** | ingresar la contraseña.                                         | **3.1** El sistema valida que la contraseña no sea un campo vacío.                                                                                                                                                                                                                                                                                                                                                                                  |
| **4** | El usuario hace clic en el icono de “visualizar contraseña”     | **4.1** En caso de que la contraseña este oculta (*) mostrara la contraseña (caracteres alfanuméricos).<br> **4.2** En caso de que la contraseña este revelada (caracteres alfanuméricos) la ocultara usando asteriscos                                                                                                                                                                                                                             |
| **5** | El usuario indica “Iniciar sesión”                              | **5.1** El sistema se conecta con la base de datos. <br> **5.2** El sistema valida que el número de identificación se encuentre registrado en la lista de usuarios activos de la base de datos. <br> **5.3** Valida la concordancia entre el número de identificación de usuario y la contraseña insertada. <br> **5.5** Despliega la pantalla de bienvenida al sistema con el menú de opciones que tiene asociadas el rol del usuario autenticado. |

---

### Cursos Alternos CU-01

- **2.1** El sistema mostrara un mensaje: “Complete todos los campos”.
- **2.3** El sistema mostrara un mensaje: “El número de identificación es demasiado corto. Su longitud debe ser al menos de 7 caracteres”.
- **2.4** El sistema mostrara un mensaje: “El número de identificación es demasiado largo. Su longitud debe ser máximo de 10 caracteres”.
- **3.1** El sistema mostrara un mensaje: “Complete todos los campos”.
- **5.1** El sistema mostrara un mensaje: “Ocurrió un problema de conexión con el servidor”
- **5.2** El sistema mostrara un mensaje: “No existe este usuario o es posible que esté inactivado”
- **5.3** El sistema mostrara un mensaje: “Contraseña incorrecta”.

---

## CU-02: Cerrar Sesión

| Atributo                 | Detalle                                                                             |
| :----------------------- | :---------------------------------------------------------------------------------- |
| **Código**               | **CU-02**                                                                           |
| **Nombre**               | Crear Item                                                                          |
| **Descripción**          | El sistema permitirá finalizar la sesión activa del usuario con confirmación previa |
| **Actor(es)**            | Usuarios                                                                            |
| **Tipo**                 | Primario                                                                            |
| **Referencias Cruzadas** | RFA-06                                                                              |

---

### Curso normal de los eventos CU-02

|  N°   | Acción del Actor                              | Respuesta del Sistema                                                                                                                    |
| :---: | :-------------------------------------------- | :--------------------------------------------------------------------------------------------------------------------------------------- |
| **1** | Usuario presiona el botón  de acción “Salir”. | **1.1** Muestra diálogo de confirmación: "Cerrar Sesión” - Mensaje: "¿Está seguro de cerrar sesión?" - Botones: "Cancelar" y "Confirmar" |
| **2** | 2. Confirma acción.                           | **2.1** Confirmar: Al hacer clic se cerrara la sesión del usuario y lo redirigirá al inicio de sesión.                                   |

---

### Cursos Alternos CU-02

1.1 Usuario cancela: “Cierra diálogo sin cambios”.
