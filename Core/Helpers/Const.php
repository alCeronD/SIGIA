<?php

//others
define('BASE_URL', __DIR__);
define('LIMIT', 7);
define('CONTENT_TYPE','Content-Type: application/json; charset=utf-8');

//Name Modules
define('CR_CONFIG_MODULES','ConfigModules');


// Words
define('CR_CONTROLLER','Controller');
define('CR_USER', 'usuario');
define('CR_LOGIN', 'login');
define('CR_INDEX', 'index');
define('CR_ROL_ID', 'rol_id');
define('CR_LA_FUNCION', 'Función');
define('CR_NO_EXISTE', 'No existe');
define('CR_MODULO', 'modulo');
define('CR_DASHBOARD', 'dashboard');


// Urls
define('CR_ROUTE_PERMISOS_CONTROLLER','/../Modules/Permisos/Controller/PermisosController.php');
define('CR_ROUTE_SOLICITUD_PRESTAMOS_CONTROLLER','/Modules/SolicitudPrestamos/controller/SolicitudPrestamosController.php');
define('CR_ROUTE_CONN', '/../Config/Conn.php');
define('CR_ROUTE_CONST','/Helpers/Const.php');
define('CR_ROUTE_SESSION','/Helpers/Session.php');
define('CR_ROUTE_VALIDATE_PERMISOS', '/Helpers/validatePermisos.php');
define('CR_ROUTE_USUARIOS_MODEL', '/Modules/Usuarios/model/usuariosModel.php');
define('CR_ROUTE_CONFIG_MODULES_MODEL', '/Modules/ConfigModules/model/ConfigModulesModel.php');
define('CR_ROUTE_CONFIG_MODULES_CONTROLLER', '/Modules/ConfigModules/controller/ConfigModulesController.php');
define('CR_ROUT_SOLICITUD_PRESTAMOS_MODEL', '/../model/solicitudPrestamosModel.php');
define('CR_ROUTE_SERVICES_RESERVA','/Modules/ReservaPrestamos/services/ServicesReservas.php');
define('CR_ROUTE_SERVICES_SOLICITUD','/Modules/SolicitudPrestamos/services/ServicesSolicitudPrestamos.php');
define('CR_ROUTE_HEADER', '../public/partials/header.php');
define('CR_ROUTE_FOOTER', '../public/partials/footer.php');


//Files
define('CR_FILE_CONST', 'Const.php');
define('CR_FILE_SESSION', 'Session.php');
define('CR_FILE_SCAN','ScanFiles.php');
define('CR_FILE_VALIDATE_PERMISOS','validatePermisos.php');
define('CR_FILE_RESPONSE', 'Response.php');


// Msg (message)
define('MSG_ERROR_SIN_PERMISOS','No tienes permisos para acceder');
?>