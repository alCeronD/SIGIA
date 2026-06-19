# Modulo roles

---

En este modulo se asignan los permisos que tiene el rol.
Hay muchas cosas por las cuales mejorar, por ende, se empieza con la implementación de la clase crud a la feature inner joins para las respectivas consultas.
se realiza la re factorización de las función select del modelo.

---

## TODOS

- [X] CREAR EL modelo de la tabla roles_funciones para su uso
- [ ] Extraer las funciones basadas al rol de la tabla roles_funciones y despues enviar a eliminar.
- [ ] Crear capa servicio para conectar los 2 controladores entre el de roles y roles_funciones
- [ ] si voy a borrar un rol, aplicar un delete a los registros relacionados con la tabla roles_funciones, crear la consulta y eliminar para despues borrar el registro del rol, para esto debo de crear la consulta inner join en la clase CRUD.PHP
