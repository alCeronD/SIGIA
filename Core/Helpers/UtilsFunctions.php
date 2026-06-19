<?php

/**
 * Clase general para instanciar funciones basicas requeridas, se usa en forma static porque usamos autoload para validarla.
 */
class UtilsFunctions
{
    public static function ajaxGeneral()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public static function dd($var)
    {
        echo "<pre>";
        print_r($var);
        die();
    }

    public static function getNameModule()
    {
        return $_GET['modulo'] ?? null;
    }

    public static function getModulesNames()
    {
        return [
            'Categorias',
            'Areas',
            'ConfigModules',
            'Dashboard',
            'Elementos',
            'GeneralCrud',
            'Login',
            'Permisos',
            'Reportes',
            'ReservaPrestamos',
            'Roles',
            'Usuarios',
            'SolicitudPrestamos',
            'TipoDocumento',
            'Marcas'
        ];
    }

    /**
     * returnGetDecode - function para devolver la información en arreglo asociativo que se recibe por petición
     *
     * @return array
     */
    public static function returnGetDecode()
    {
        if (ob_get_length()) ob_clean();
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        return $data;
    }

    /**
     * Function para ejecutar la estructura de paginado y re utilizar su logica en otros controllers
     *
     * @param integer $cantidadRegistros - el Count de la tabla
     * @param integer $limit - si el usuario quiere ver 7 o 10, 20, 100 registros
     * @param integer $paginaActual - pagina reciente que el usuario visualiza
     * @return array
     */
    public static function executePaginate(int $cantidadRegistros = 1, int $limit = LIMIT, int $paginaActual = 1)
    {
        # colocamos la division con el max para evitar que no se divida con 0, en caso de que le pasemos 0 en la variable cantidad de registros
        $totalPaginas = (int) ceil($cantidadRegistros / max(1, $limit));
        if ($paginaActual > $totalPaginas) {
            $paginaActual = (int) $totalPaginas;
        }
        if ($paginaActual < 1) {
            $paginaActual = (int) 1;
        }
        $offset = ($paginaActual - 1) * $limit;
        return [
            'offset' => $offset,
            'totalPaginas' => $totalPaginas
        ];
    }
    public static function validateContentString(String $valor, String $key)
    {
        return str_contains(strtoupper($valor), $key);
    }

    /**
     * Function para validar los campos y determinar cuales son obligatorios y cuales no.
     *
     * @param array $campos - arreglo con datos a comparar
     * @param array $mapCapos - arreglo clave valor en donde la clave debe ser la misma que la clave del parametro campos y su valor debe ser un nombre amigable para el usuario - ['ar_nombre' => 'nombre departamento']
     * @return void
     */
    public static function validateCampos(array $campos = [], array $mapCapos = [])
    {
        foreach ($campos as $key => $value) {
            if (key_exists($key, $mapCapos) && empty($value)) {
                $message = "El campo {$mapCapos[$key]} debe ser obligatorio";
                Response::responseRequest(HttpStatus::BAD_REQUEST, false, $message, []);
                return;
            }
            break;
        }
    }

    /**
     * Function para recorrer los datos y eliminar todos los espacios al inicio y al final de cada valor.
     *
     * @param array $datos
     * @return array
     */
    public static function deleteSpace(array $datos = []): array
    {
        foreach ($datos as $key => $value) {
            $datos[$key] = trim($value);
        }

        return $datos;
    }
}
