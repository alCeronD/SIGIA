<?php

// clase para validar la respuesta de la base de datos en caso de errores y devolver un mensaje personalizado para el usuario.

class DatabaseHandler
{
  public static array $databaseErrors = [
    'sqlState' => [
      23000 => 'Violación de restricción de integridad (Llaves duplicadas, nulos o foráneas)',
      22001 => 'Datos de cadena o binarios truncados (El texto enviado es demasiado largo)',
      '42S02' => 'La tabla base o vista especificada no fue encontrada en el sistema',
      '42S22' => 'Columna no encontrada en la tabla especificada',
      '08S01' => 'Fallo en el enlace de comunicación (Pérdida de conexión con el servidor de BD)'
    ],
    'codeError' => [
      1062 => 'Registro duplicado en índice UNIQUE o PRIMARY KEY',
      1451 => 'Restricción de borrado: El registro tiene filas hijas vinculadas (Foreign Key)',
      1452 => 'Restricción de inserción/actualización: La llave foránea no existe en la tabla padre',
      1048 => 'Columna obligatoria recibió un valor NULL',
      1406 => 'El valor de la columna es demasiado largo para el tipo de dato'
    ]
  ]; //
  public static array $mensajesCodeErrors = [
    'codeError' => [
      1062 => [
        'status_http' => HttpStatus::CONFLICT, // 409
        'message'     => 'No se pudo guardar: Ya existe un registro en el sistema con estos datos únicos.'
      ],
      1451 => [
        'status_http' => HttpStatus::UNPROCESSABLE_ENTITY, // 422
        'message'     => 'No es posible eliminar este registro porque tiene información importante relacionada en el sistema.'
      ],
      1452 => [
        'status_http' => HttpStatus::BAD_REQUEST, // 400
        'message'     => 'Error de vinculación: Los datos de referencia seleccionados ya no existen o no son válidos.'
      ],
      1048 => [
        'status_http' => HttpStatus::BAD_REQUEST, // 400
        'message'     => 'Error de formulario: Hay campos obligatorios que no han sido completados en la solicitud.'
      ],
      1406 => [
        'status_http' => HttpStatus::BAD_REQUEST, // 400
        'message'     => 'Error de capacidad: Uno de los campos supera el límite máximo de caracteres permitido.'
      ]
    ]
  ]; //

  public static function validateResponse(array $response)
  {
    $sqlState = (string) $response['sqlState'];
    $codeError = $response['codeError'];

    // validamos si en los arreglos de la clase existe la clave para retornar el mensaje personalizado.
    if (isset(self::$mensajesCodeErrors['codeError'][$codeError])) {
      return [
        'message'      => self::$mensajesCodeErrors['codeError'][$codeError]['message'],
        'codeResponse' => self::$mensajesCodeErrors['codeError'][$codeError]['status_http']
      ];
    }

    if (isset(self::$databaseErrors['sqlState'][$sqlState])) {
      return [
        'message'      => 'Error de base de datos: ' . self::$databaseErrors['sqlState'][$sqlState],
        'codeResponse' => HttpStatus::INTERNAL_SERVER_ERROR // 500
      ];
    }

    return [
      'message'      => 'Ocurrió un error inesperado al procesar la solicitud en el servidor.',
      'codeResponse' => HttpStatus::INTERNAL_SERVER_ERROR // 500
    ];
  }
}
