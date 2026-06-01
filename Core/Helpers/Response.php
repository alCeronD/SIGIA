<?php

/**
 * Clase para el envio de respuestas a las peticiones realizadas.
 */
class Response
{

    /**
     * Function para entregar respuesta cada vez que haya una nueva peticion.
     *
     * @param integer $codeResponse - Código de respuesta, sea 400, 200, etc
     * @param boolean $status - TRUE OR FALSE, dependiendo de la logica
     * @param string $message - mensaje de respuesta.
     * @param array $data - Arreglo que devuelve datos en caso de ser necesario.
     * @return void
     */
    public static function responseRequest(int $codeResponse = 1, bool $status = true, String $message = "", array $data = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => empty($data) ? [] : $data
        ];
        http_response_code($codeResponse);
        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }
}
