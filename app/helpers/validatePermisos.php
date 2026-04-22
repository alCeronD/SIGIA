<?php

/**
 * Archivo para validar cada función ejecutada en los archivos.
 * Necesito traer la sessión, el getUrl y el response del para enviar la respuesta al front.
 */
require_once __DIR__ . "/session.php";
require_once __DIR__ . "/getUrl.php";
require_once __DIR__ . '/response.php';
require_once __DIR__ . "/../modules/Permisos/Controller/PermisosController.php";

function validatePermisos(String $modulo, String $funcion)
{

    $dataResponse = [
        'status' => false,
        'data' => [],
        'message' => "No tienes permisos para realizar esta acción."
    ];
    if (session_status() === PHP_SESSION_NONE || !isset($_SESSION['usuario'])) {
        if ($modulo === 'login') return;

        if (isAjaxRequest()) {
            header("Content-Type: application/json");

            fail("Sesión no iniciada o expirada.", $dataResponse);
        } else {
            echo json_encode(['error'], JSON_PRETTY_PRINT);
            header("Location: " . getUrl('login', 'login', 'index', false, 'index'));
        }
        exit();
    }

    require_once __DIR__ . "/../modules/Permisos/Controller/PermisosController.php";
    $objPermisos = new PermisosController();

    $idNombreModulo = $objPermisos->gidIdModulo($modulo);
    $idFuncion = $objPermisos->getIdFuncion($funcion, $modulo, $idNombreModulo);

    if (session_status() === PHP_SESSION_NONE || !isset($_SESSION['usuario'])) {
        if ($modulo === 'login') return;

        if (isAjaxRequest()) {
            fail("Sesión no iniciada o expirada.");
        } else {
            redirect(getUrl('login', 'login', 'index', false, 'index'));
        }
        exit();
    }

    $rolId = $_SESSION['usuario']['rol_id'];
    $isValidate = $objPermisos->validateRolFuncion($rolId, $idFuncion);

    if (!$isValidate) {
        if (isAjaxRequest()) {

            // TODO: re hacer el response de la función fail y success.
            http_response_code(403);
            echo json_encode($dataResponse,JSON_PRETTY_PRINT);
            exit();

            // fail("No tienes permisos para esta acción.",$dataResponse);
        } else {
            redirect(getUrl('login', 'login', 'index', false, 'index'));
        }
        exit();
    }
}

/**
 * Summary of isAjaxRequest - Sirve para validar que la petición enviada es mediante HTTP fue enviada por ajax.
 * Esto verifica que la cabecera enviada es una cabecera de una librería de javascript, en este caso, ajax o fetch.
 * Se transforma a minusculas porque es el estandar usado de XMLHttpRequest usado con fetch de javascript o ajax.
 * @return bool
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}
