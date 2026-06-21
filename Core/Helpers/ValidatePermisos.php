<?php

/**
 * Archivo para validar cada función ejecutada en los archivos.
 * Necesito traer la sessión, el getUrl y el response del para enviar la respuesta al front.
 * en esta clase requiero los modelos de usuarios, modelos de roles
 * y los modelos de roles_funciones para validar que exista el usuario
 */
require_once __DIR__ . "/Session.php";
require_once __DIR__ . '/Response.php';
require_once __DIR__ . "/../Modules/Permisos/Controller/PermisosController.php";

function validatePermisos(String $modulo, String $funcion)
{

    $dataResponse = [
        'status' => false,
        'data' => [],
        'message' => "No tienes permisos para realizar esta acción."
    ];
    if (session_status() === PHP_SESSION_NONE || !isset($_SESSION['usuario'])) {
        if ($modulo === 'login') return;

        if (UtilsFunctions::ajaxGeneral()) {
            header("Content-Type: application/json");

            Response::fail("Sesión no iniciada o expirada.", $dataResponse);
        } else {
            echo json_encode(['error'], JSON_PRETTY_PRINT);
            header("Location: " . Router::createRoute('Login', 'Login', 'index', false, 'index'));
        }
        exit();
    }

    require_once __DIR__ . "/../Modules/Permisos/Controller/PermisosController.php";
    $objPermisos = new PermisosController();

    $idNombreModulo = $objPermisos->gidIdModulo($modulo);
    $idFuncion = $objPermisos->getIdFuncion($funcion, $modulo, $idNombreModulo);

    if (session_status() === PHP_SESSION_NONE || !isset($_SESSION['usuario'])) {
        if ($modulo === 'login') return;

        if (UtilsFunctions::ajaxGeneral()) {
            Response::fail("Sesión no iniciada o expirada.");
        } else {
            Redirect::fast(Router::createRoute('Login', 'Login', 'index', false, 'index'));
        }
        exit();
    }

    $rolId = $_SESSION['usuario']['rol_id'];
    $isValidate = $objPermisos->validateRolFuncion($rolId, $idFuncion);

    if (!$isValidate) {
        if (UtilsFunctions::ajaxGeneral()) {

            // TODO: re hacer el response de la función fail y success.
            http_response_code(403);
            echo json_encode($dataResponse, JSON_PRETTY_PRINT);
            exit();

            // fail("No tienes permisos para esta acción.",$dataResponse);
        } else {
            Rect::fast(Router::createRoute('Login', 'Login', 'index', false, 'index'));
        }
        exit();
    }
}
