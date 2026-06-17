<?php

/**
 * Clase que contiene variables constantes con los diferentes códigos de respuestas.
 */
class HttpStatus
{
  const OK = 200; //
  const CREATED = 201; // el recurso se creo correctamente.
  const NO_CONTENT = 204; // se ejecuto correctamente la ejecucion pero este no devuelve ningun cuerpo.
  const BAD_REQUEST = 400;
  const UNAUTHORIZED = 401; // no cuenta con las credenciales validas para ejecutar el proceso
  const FORBIDDEN = 403; //Podemos usarlo para validar los permisos del usuario cuando este no tenga acceso.
  const NOT_FOUNT = 404; // no se encuentra el recurso
  const METHOD_NOT_ALLOWED = 405; // metodo no permitido
  const INTERNAL_SERVER_ERROR = 500;
  const UNPROCESSABLE_ENTITY = 422; // LA SINTAXIS ES CORRECTA PERO LAS INSTRUCCIONES NO SE PUEDEN EJECUTAR.
}
