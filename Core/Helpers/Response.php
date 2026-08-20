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
        header(CONTENT_TYPE);
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => empty($data) ? [] : $data
        ];
        http_response_code($codeResponse);
        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Function para responder al usuario usando template en vez de una peticion
     *
     * @param integer $codeResponse
     * @param string $message
     * @param array $data
     * @return void
     */
    public static function responseTemplate(int $codeResponse = 1, String $message = "", array $data = [])
    {
        try {
            $nameTemplate = "{$codeResponse}.php";
            $routeRemplate = realpath(BASE_URL . "/../../public/templates/$nameTemplate");
            $previewRoute = $data['previewRoute'];
            if (!file_exists($routeRemplate)) throw new Exception($message, 404);
            $codeResponseTemplate = $codeResponse;
            $messageToTemplate = $message;
            include_once $routeRemplate;
        } catch (\Exception $th) {
            // fallback en caso de que no se encuentre el codigo de respuesta.
            include_once realpath(BASE_URL . "/../../public/templates/404.php");
        }
    }
}
