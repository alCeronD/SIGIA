<?php

/**
 * Archivo para validar cada función ejecutada en los archivos.
 * Necesito traer la sessión, el getUrl y el response del para enviar la respuesta al front.
 */
require_once __DIR__ . "/session.php";
require_once __DIR__ . "/getUrl.php";
require_once __DIR__ . '/response.php';
require_once __DIR__ . "/../Modules/Permisos/Controller/PermisosController.php";

function validatePermisos(String $modulo, String $funcion)
{

    require_once __DIR__ . "/../Modules/Permisos/Controller/PermisosController.php";
    $objPermisos = new PermisosController();
    /**
     * Primera consulta
     * traer el id del modulo usando el nombre del modulo.
     */
    $idNombreModulo = $objPermisos->gidIdModulo($modulo);
    /**
     * Segunda consulta
     * traer el id de la función basadao en su nombre de la función y ID De la función.
     */

    $idFuncion = $objPermisos->getIdFuncion($funcion, $modulo, $idNombreModulo);
    /**
     * Tercera consulta
     * Validar que el ROL DEL USUARIO TENGA EL PERMISO ADECUADO PARA ACCEDER A ESA FUNCIÓN, BASADO EN ESA FUNCIÓN PODEMOS USAR EL MODULO.
     */
    if (session_status() === PHP_SESSION_NONE) {
        $rolId = 0;
        $isValidate = false;
        if ($modulo === 'login') {
            $rolId = 0;
            $isValidate = true;
        } else {
            redirect(getUrl('login', 'login', 'index', false, 'index'));
            exit();
        }
    } else {
        // TODO, con esto en caso de que el rol no este asociado a la función, re dirigir a lógin.
        $rolId = $_SESSION['usuario']['rol_id'];
        $isValidate = $objPermisos->validateRolFuncion($rolId, $idFuncion);
    }

    if (!$isValidate) {
        $result = [
            'status' => false,
            'message' => "No tienes permisos para realizar esta operación",
            'data' => []
        ];
        fail('No tienes permisos para realizar esta acción', $result);
        return;
    }
}
